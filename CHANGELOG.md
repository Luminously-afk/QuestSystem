# IT Engagement System — Feature Changelog

**Date:** May 18, 2026

---

## Summary

Nine new features were requested. Two already existed in the codebase (Submission Resubmission and Point Transaction History). The remaining seven were fully implemented across the database, models, controllers, and views.

---

## Features Added

### 1. Quest Categories (Expanded)

**Before:** 3 categories — Curricular, Extra-Curricular, Co-Curricular  
**After:** 6 categories — **Academic, Event, Volunteer, Competition, Attendance, Bonus**

- Updated the category `<select>` dropdown in both the **Create Quest** and **Edit Quest** modals (admin side).
- Updated the category filter dropdown on the admin quest management table.
- Student quest board displays the new categories.
- No database schema change was needed since `category` was already `VARCHAR(50)`.

**Files changed:**
- `app/views/admin/quests/index.php` — category options and filter dropdown
- `app/controllers/AdminController.php` — removed old `categoryLabels` mapping

---

### 2. Quest Difficulty / Point Logic

Added a **difficulty tier** to every quest: `Easy`, `Medium`, or `Hard`.

- **Database:** Added `difficulty ENUM('easy','medium','hard') NOT NULL DEFAULT 'medium'` column to the `quests` table.
- Difficulty is color-coded in quest tables:
  - 🟢 **Easy** — green text
  - 🟡 **Medium** — amber text
  - 🔴 **Hard** — red text
- Admin can set difficulty when creating or editing a quest.
- Students see the difficulty badge on the quest board.

**Files changed:**
- `database.sql` — schema update
- `migrations/2026_05_18_feature_additions.sql` — ALTER TABLE
- `app/models/Quest.php` — `create()` and `update()` include `difficulty`
- `app/controllers/AdminController.php` — handles `difficulty` field
- `app/views/admin/quests/index.php` — difficulty column + form fields
- `app/views/student/quests/index.php` — difficulty column in quest board

---

### 3. Quest Limit (Max Slots)

Allows admins to set a **maximum number of students** who can accept a quest.

- **Database:** Added `max_slots INT NULL DEFAULT NULL` column to `quests`. `NULL` means unlimited.
- When a student tries to accept a full quest, they are redirected with a `slots_full` error.
- Quest tables display slot usage as `X/Y` (e.g., `15/20`) or `∞` for unlimited.

**Files changed:**
- `database.sql` — schema update
- `migrations/2026_05_18_feature_additions.sql` — ALTER TABLE
- `app/models/Quest.php` — `create()`, `update()`, `getAcceptanceCount()`, `getVisibleForStudent()` includes `acceptance_count`
- `app/controllers/AdminController.php` — handles `max_slots` field
- `app/controllers/StudentController.php` — slot limit check in `acceptQuest()`
- `app/views/admin/quests/index.php` — slots column + form fields
- `app/views/student/quests/index.php` — slots column + error message

---

### 4. Submission Resubmission *(Already Existed)*

Students can already resubmit rejected quest submissions. The existing `Submission::resubmit()` method handles this, and the student quest view shows a **RESUBMIT** button for rejected submissions with the admin's rejection remarks visible.

**No changes were needed.**

---

### 5. Point Transaction History *(Already Existed)*

A full point transaction history is already served by the `vw_user_point_history` SQL view, which aggregates:
- Points earned from approved quests
- Points deducted from penalties
- Points spent on approved reward redemptions
- Points manually added by admins

Accessible at `/student/history`. Also now shown on the new **Student Profile** page (see Feature 8).

**No changes were needed.**

---

### 6. Leaderboard — 3 Tabs

**Before:** 2 leaderboard columns — Top by XP, Top by Quests  
**After:** 3 leaderboard columns — **Top by XP, Top by Quests, Top by Event Participation**

- The "Top by Events" tab counts approved submissions for quests with `category = 'Event'`.
- Updated the `vw_student_leaderboard` SQL view to include `event_participations` via a `SUM(CASE WHEN q.category = 'Event' ...)` expression.
- Layout changed from `lg:grid-cols-2` to `lg:grid-cols-3`.
- Applied to both **admin** and **student** leaderboard views.

**Files changed:**
- `database.sql` — leaderboard view updated
- `migrations/2026_05_18_feature_additions.sql` — DROP/CREATE VIEW
- `app/models/User.php` — `getLeaderboard('events')` sorting option added
- `app/controllers/AdminController.php` — passes `leaderboard_events`
- `app/controllers/StudentController.php` — passes `leaderboard_events`
- `app/views/admin/leaderboard.php` — 3rd column added
- `app/views/student/leaderboard.php` — 3rd column added

---

### 7. QR Code Expiry

QR tokens now **automatically expire** based on the quest deadline.

- When `QuestQrToken::createOrGetActiveToken()` generates a token, it looks up the quest's `deadline` and sets it as the token's `expires_at` value.
- Added `expireStaleTokens()` method for bulk cleanup of expired tokens.
- The existing admin QR scan flow already checks `expires_at` before accepting a scan.

**Files changed:**
- `app/models/QuestQrToken.php` — auto-sets `expires_at`, added `expireStaleTokens()`

---

### 8. Student Profile / Progress Page

A new **centralized profile page** at `/student/profile` showing:

| Stat | Description |
|------|-------------|
| Total XP | Current point balance |
| Completed | Number of approved quest submissions |
| In Progress | Active accepted quests |
| Pending | Submissions awaiting review |
| Redeemed | Approved reward redemptions |
| Penalties | Total penalty count |

Below the stats grid, the page displays the **full point transaction history** with color-coded type badges (Earned, Manual, Penalty, Spent).

- Added `User::getFullStudentProfile()` model method.
- Added `StudentController::profile()` controller method.
- Added sidebar navigation links for **Point History** and **My Profile**.
- Added **MY PROFILE** button on the student dashboard.

**Files changed:**
- `app/models/User.php` — `getFullStudentProfile()`
- `app/controllers/StudentController.php` — `profile()` method
- `app/views/student/profile.php` — **new file**
- `app/views/student/index.php` — added profile link, fixed broken HTML tag
- `app/views/layouts/header.php` — sidebar nav links

---

### 9. Reward Stock / Quantity

Admins can now track **inventory** for rewards.

- **Database:** Added `stock INT NULL DEFAULT NULL` column to `rewards`. `NULL` means unlimited.
- Admin can set stock when creating or editing a reward.
- Stock is displayed in reward tables as a number or `∞` (unlimited).
- When stock reaches 0, it shows in **red** text.
- On redemption approval, stock is **decremented** inside a database transaction.
- If stock is exhausted, approval is blocked with an `out_of_stock` error.
- Students see stock in the rewards shop and cannot request out-of-stock rewards.

**Files changed:**
- `database.sql` — schema update
- `migrations/2026_05_18_feature_additions.sql` — ALTER TABLE
- `app/models/Reward.php` — `create()`, `update()`, `getAvailable()` with `claimed_count`, `decrementStock()`, `hasStock()`
- `app/models/Redemption.php` — stock check and decrement in `review()` method
- `app/controllers/AdminController.php` — handles `stock` field in reward CRUD
- `app/views/admin/rewards/index.php` — stock column + form fields
- `app/views/student/rewards/index.php` — stock column, `out_of_stock` error, stock availability check

---

## Migration

A migration file was created at `migrations/2026_05_18_feature_additions.sql` and has been executed.

```sql
ALTER TABLE quests ADD COLUMN difficulty ENUM('easy','medium','hard') NOT NULL DEFAULT 'medium';
ALTER TABLE quests ADD COLUMN max_slots INT NULL DEFAULT NULL;
ALTER TABLE rewards ADD COLUMN stock INT NULL DEFAULT NULL;
-- Updated vw_student_leaderboard view with event_participations column
```

---

## Files Created

| File | Purpose |
|------|---------|
| `migrations/2026_05_18_feature_additions.sql` | Database migration script |
| `app/views/student/profile.php` | Student profile / progress page |

## Files Modified

| File | Changes |
|------|---------|
| `database.sql` | Added `difficulty`, `max_slots` to quests; `stock` to rewards; updated leaderboard view |
| `app/models/Quest.php` | Full rewrite with difficulty, max_slots, acceptance count |
| `app/models/Reward.php` | Full rewrite with stock support |
| `app/models/User.php` | Events leaderboard + `getFullStudentProfile()` |
| `app/models/Redemption.php` | Stock check/decrement on approval |
| `app/models/QuestQrToken.php` | Auto-expiry from quest deadline |
| `app/controllers/AdminController.php` | All new fields in quest/reward CRUD, events leaderboard |
| `app/controllers/StudentController.php` | Profile page, slot limit check, events leaderboard |
| `app/views/admin/quests/index.php` | 6 categories, difficulty, slots in table and modals |
| `app/views/admin/rewards/index.php` | Stock column and form fields |
| `app/views/admin/leaderboard.php` | 3-column layout with events tab |
| `app/views/student/quests/index.php` | Difficulty, slots columns, slots_full error |
| `app/views/student/rewards/index.php` | Stock column, out_of_stock error |
| `app/views/student/leaderboard.php` | 3-column layout with events tab |
| `app/views/student/index.php` | Profile link, fixed broken HTML |
| `app/views/layouts/header.php` | Sidebar nav: Point History + My Profile links |
