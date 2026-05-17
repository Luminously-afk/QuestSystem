# Project Feature Summary

## Overview
- Web-based IT quest engagement system with admin and student roles.
- PHP MVC structure with MySQL storage.
- Docker support plus SQL schema and migrations.

## Authentication and Accounts
- Role-based login (admin/student) with account activation status checks.
- First-login password change enforcement for temporary passwords.
- Admin can create student accounts with auto-generated temporary passwords.
- Admin can reset or reroll temporary passwords for students.

## Quest Management (Admin)
- Create, edit, and delete quests.
- Quest scope by year level (all, single year, multi-year).
- Quest status (active/inactive) and deadline handling.
- Proof types supported: text, image, image + text, multiple images, none, QR code.
- Quest list search and filters (status, category, text search).

## Quest Board (Student)
- Students see quests filtered by eligibility and year level.
- Inactive or expired quests remain visible but are disabled.
- Accepting quests is blocked when not available.

## Submissions and Reviews
- Students can submit proof for non-QR quests.
- Proof upload supports images and mixed proof types.
- Admin reviews submissions and approves or rejects with remarks.
- Approved submissions add points automatically.

## QR Proof Flow
- QR proof quests generate a unique token on acceptance.
- Student can view a QR code tied to the quest.
- Admin can scan via camera, upload a QR image, or paste the token.
- QR redemption approves the quest and marks the token as redeemed.
- Redeemed tokens become invalid for future scans.

## Rewards and Redemptions
- Admin manages rewards (available/unavailable).
- Students can request rewards based on available points.
- Admin reviews redemptions and approves or rejects.

## Points, Penalties, and Leaderboards
- Points earned from approved quests and spent on redemptions.
- Admin can manually add points to a student.
- Admin can deduct points using penalties with reasons.
- Student and admin leaderboards by points or quest count.

## Audit Logs and Admin Dashboard
- Audit logs record key admin actions.
- Admin dashboard shows summary stats (quests, submissions, redemptions, students, penalties).

## Data and Security Notes
- Role-based DB users for admin vs student operations.
- File uploads stored under public/uploads/submissions.
- Tokens, submissions, and acceptances are validated server-side.
