# IT Engagement System - Database Design Documentation

This document explains the database design principles, schema creation, ACID properties, CRUD operations, Views, and Procedures implemented in the IT Engagement System (Quest System).

## 1. Database Design & Schema Creation

The database (`it_quest`) uses a normalized relational model to reduce redundancy and maintain data integrity.

### Entities (Tables) & Attributes
*   **users**: Stores both students and admins. Attributes include `user_id` (PK), `full_name`, `student_id`, `email`, `role`, `total_points`.
*   **quests**: Available quests. Attributes include `quest_id` (PK), `title`, `description`, `points`, `deadline`.
*   **quest_submissions**: Submissions by students. Attributes include `submission_id` (PK), `user_id` (FK), `quest_id` (FK), `proof_type`, `status`.
*   **rewards**: Available rewards in the shop. Attributes include `reward_id` (PK), `reward_name`, `required_points`.
*   **reward_redemptions**: Redemptions claimed by students. Attributes include `redemption_id` (PK), `user_id` (FK), `reward_id` (FK), `status`.
*   **penalties**: Penalties assigned to students by admins. Attributes include `penalty_id` (PK), `user_id` (FK), `points_deducted`, `reason`.
*   **audit_logs**: Tracks admin actions. Attributes include `log_id` (PK), `admin_id` (FK), `action`, `description`.

### Relationships (Primary & Foreign Keys)
*   **One-to-Many**: A `user` (student) can have many `quest_submissions`, `reward_redemptions`, and `penalties`.
    *   *Foreign Key constraint*: `user_id` in `quest_submissions` references `users(user_id) ON DELETE CASCADE`.
*   **One-to-Many**: A `quest` can have many `quest_submissions`.
    *   *Foreign Key constraint*: `quest_id` in `quest_submissions` references `quests(quest_id) ON DELETE CASCADE`.
*   **One-to-Many**: A `reward` can be redeemed multiple times (`reward_redemptions`).

### Constraints
*   **NOT NULL**: Ensures essential fields like `full_name`, `email`, and `points` cannot be empty.
*   **UNIQUE**: The `email` and `student_id` fields have unique constraints to prevent duplicate accounts.
*   **CHECK**: Ensures logical validity, e.g., `CONSTRAINT chk_total_points CHECK (total_points >= 0)` prevents negative points, and `chk_quest_points CHECK (points > 0)` ensures quests give positive rewards.

---

## 2. ACID Properties Demonstration

The database is built on MySQL (InnoDB engine) which natively supports ACID properties. We demonstrate this directly through our stored procedure `sp_deduct_penalty`.

*   **Atomicity (All-or-nothing)**: Inside `sp_deduct_penalty`, we wrap three operations (insert penalty, deduct user points, log admin action) in a `START TRANSACTION` block. If any step fails (e.g., deducting points fails due to the `CHECK` constraint), the `SQLEXCEPTION` handler triggers a `ROLLBACK`, undoing all previous steps in that block.
*   **Consistency**: The database goes from one valid state to another. For example, the `chk_total_points CHECK (total_points >= 0)` constraint guarantees that a penalty cannot be issued if it drops the student's points below zero. The transaction enforces this rule.
*   **Isolation**: If two admins attempt to issue penalties or approve quests for the same student concurrently, InnoDB uses row-level locking. The first transaction locks the `users` row to deduct points; the second transaction must wait until the first `COMMIT`s, preventing race conditions or corrupted point totals.
*   **Durability**: Once the `COMMIT` command executes in `sp_deduct_penalty` (or during any standard INSERT/UPDATE), the changes to the user's points and the penalty record are permanently written to the disk, surviving any potential XAMPP/MySQL crash.

---

## 3. SQL CRUD Operations

Here are examples of CRUD (Create, Read, Update, Delete) operations used within the system's PHP Data Access Object models:

**Create (Insert)**
```sql
INSERT INTO quests (title, description, category, points, deadline, created_by)
VALUES ('Network Setup', 'Fix router in Lab 2', 'Hardware', 500, '2026-12-31 23:59:59', 1);
```

**Read (Select)**
```sql
SELECT title, points, deadline FROM quests WHERE status = 'active' ORDER BY deadline ASC;
```

**Update**
```sql
UPDATE quest_submissions 
SET status = 'approved', remarks = 'Great job!', reviewed_by = 1, reviewed_at = CURRENT_TIMESTAMP
WHERE submission_id = 45;
```

**Delete**
```sql
DELETE FROM rewards WHERE reward_id = 12 AND status = 'unavailable';
```

---

## 4. Database Users & Privileges (Security & Least Privilege)

To adhere strictly to the principle of Least Privilege, we create two separate MySQL database users with fine-grained table-level permissions based on the application roles:

**1. Student App User (`quest_student_user`)**
```sql
CREATE USER IF NOT EXISTS 'quest_student_user'@'localhost' IDENTIFIED BY 'student_secure_pass123';
-- Grants only SELECT, INSERT, UPDATE on specific tables needed by students
GRANT SELECT ON it_quest.quests TO 'quest_student_user'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON it_quest.quest_submissions TO 'quest_student_user'@'localhost';
-- ... (other restrictive grants)
```
*   **Privileges**: Limited `SELECT`, `INSERT`, `UPDATE`, `DELETE` mapped strictly to tables students interact with (e.g., they cannot `DELETE` quests, only read them).
*   **Explanation**: When a student logs into the PHP app, the system connects using this user. Even if a SQL injection vulnerability exists in a student form, the attacker cannot drop tables, modify other users' points, or delete quests because the MySQL user lacks those privileges.

**2. Admin App User (`quest_admin_user`)**
```sql
CREATE USER IF NOT EXISTS 'quest_admin_user'@'localhost' IDENTIFIED BY 'admin_secure_pass123';
GRANT SELECT, INSERT, UPDATE, DELETE ON it_quest.* TO 'quest_admin_user'@'localhost';
GRANT EXECUTE ON PROCEDURE it_quest.sp_deduct_penalty TO 'quest_admin_user'@'localhost';
```
*   **Privileges**: Full CRUD operations on application tables and `EXECUTE` for stored procedures.
*   **Explanation**: When an admin logs in, the PHP app connects using this user, allowing them to manage quests, approve submissions, and execute the penalty stored procedure.

---

## 5. SQL Views

A View is a virtual table based on the result-set of a SQL statement. We created `vw_student_leaderboard` to simplify complex aggregations.

**Creation:**
```sql
CREATE OR REPLACE VIEW vw_student_leaderboard AS
SELECT 
    u.user_id, 
    u.full_name, 
    u.student_id,
    u.year_level,
    u.total_points,
    COUNT(s.submission_id) AS completed_quests
FROM users u
LEFT JOIN quest_submissions s ON u.user_id = s.user_id AND s.status = 'approved'
WHERE u.role = 'student' AND u.is_active = 1
GROUP BY u.user_id
ORDER BY u.total_points DESC, completed_quests DESC;
```
**Application/Use Case:** 
Instead of writing complex `LEFT JOIN` and `GROUP BY` queries in the PHP application every time the leaderboard page loads, the application can simply run `SELECT * FROM vw_student_leaderboard LIMIT 10;`. This improves code readability and reusability.

---

## 5. SQL Stored Procedures

A Stored Procedure is prepared SQL code that you can save and reuse. We created `sp_deduct_penalty` to handle the complex workflow of penalizing a student.

**Application/Use Case:**
When an Admin issues a penalty for a policy violation, the system must record the penalty, update the user's total points, and create an audit log. Executing this via a single Stored Procedure call `CALL sp_deduct_penalty(15, 100, 'Late hardware return', 1);` reduces network round-trips between PHP and MySQL, centralizes business logic, and ensures strict transactional integrity (ACID Atomicity).

---

## 7. System Use Case Relevance

The **IT Engagement (Quest) System** relies heavily on this database design to function correctly:
*   **Gamification tracking**: The `total_points` must be absolutely accurate. The use of **Database Triggers** (`after_submission_approval`, `after_redemption_approval`) ensures points are automatically and atomically calculated whenever a quest is completed or a reward is claimed, offloading business logic from the PHP code to the database layer.
*   **Security & Accountability**: The strict Foreign Key constraints (`ON DELETE CASCADE` and `ON DELETE SET NULL`) ensure that if a quest is deleted, all its submissions disappear, but if an admin is deleted, their review logs remain intact (`SET NULL`).
*   **Reliability**: The relational design ensures the IT department can reliably track which students engaged with which quests over the entire academic year without data anomalies.
