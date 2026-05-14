CREATE DATABASE IF NOT EXISTS it_quest;
USE it_quest;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    student_id VARCHAR(20) UNIQUE,
    year_level TINYINT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL DEFAULT 'student',
    must_change_password TINYINT(1) NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    password_changed_at TIMESTAMP NULL DEFAULT NULL,
    total_points INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_total_points CHECK (total_points >= 0),
    CONSTRAINT chk_student_id_required CHECK (role <> 'student' OR student_id IS NOT NULL),
    CONSTRAINT chk_student_id_format CHECK (student_id IS NULL OR student_id REGEXP '^[A-Za-z0-9]{4}-[0-9]{4}$')
);

CREATE TABLE quests (
    quest_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    scope_type ENUM('all', 'year', 'multi') NOT NULL DEFAULT 'all',
    scope_years VARCHAR(20) NULL,
    proof_type ENUM('text', 'image', 'image_text', 'multi_image', 'none') NOT NULL DEFAULT 'text',
    points INT NOT NULL,
    deadline DATETIME NOT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE RESTRICT,
    CONSTRAINT chk_quest_points CHECK (points > 0)
);

CREATE TABLE quest_submissions (
    submission_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    quest_id INT NOT NULL,
    proof_type ENUM('text', 'image', 'image_text', 'multi_image', 'none') NOT NULL DEFAULT 'text',
    proof_text TEXT NULL,
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    remarks TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_by INT,
    reviewed_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (quest_id) REFERENCES quests(quest_id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE rewards (
    reward_id INT AUTO_INCREMENT PRIMARY KEY,
    reward_name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    required_points INT NOT NULL,
    status ENUM('available', 'unavailable') NOT NULL DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_required_points CHECK (required_points > 0)
);

CREATE TABLE reward_redemptions (
    redemption_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reward_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    remarks TEXT,
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_by INT,
    reviewed_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (reward_id) REFERENCES rewards(reward_id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE audit_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT,
    action VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE quest_acceptances (
    acceptance_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    quest_id INT NOT NULL,
    status ENUM('accepted', 'completed', 'cancelled') NOT NULL DEFAULT 'accepted',
    accepted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY uniq_acceptance (user_id, quest_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (quest_id) REFERENCES quests(quest_id) ON DELETE CASCADE
);

CREATE TABLE quest_submission_files (
    file_id INT AUTO_INCREMENT PRIMARY KEY,
    submission_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (submission_id) REFERENCES quest_submissions(submission_id) ON DELETE CASCADE
);

CREATE TABLE penalties (
    penalty_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    points_deducted INT NOT NULL,
    reason TEXT NOT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL,
    CONSTRAINT chk_penalty_points CHECK (points_deducted > 0)
);

CREATE TABLE manual_points (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    points_added INT NOT NULL,
    reason TEXT NOT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL
);

-- Triggers for points calculation
DELIMITER //

CREATE TRIGGER after_submission_approval
AFTER UPDATE ON quest_submissions
FOR EACH ROW
BEGIN
    IF NEW.status = 'approved' AND OLD.status != 'approved' THEN
        UPDATE users 
        SET total_points = total_points + (SELECT points FROM quests WHERE quest_id = NEW.quest_id)
        WHERE user_id = NEW.user_id;
    END IF;
END;
//

CREATE TRIGGER after_redemption_approval
AFTER UPDATE ON reward_redemptions
FOR EACH ROW
BEGIN
    IF NEW.status = 'approved' AND OLD.status != 'approved' THEN
        UPDATE users 
        SET total_points = total_points - (SELECT required_points FROM rewards WHERE reward_id = NEW.reward_id)
        WHERE user_id = NEW.user_id;
    END IF;
END;
//

DELIMITER ;

-- --------------------------------------------------------
-- SQL VIEW
-- --------------------------------------------------------
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

CREATE OR REPLACE VIEW vw_user_point_history AS
SELECT 
    s.user_id,
    q.points AS points_change,
    'Earned' AS type,
    CONCAT('Quest Completed: ', q.title) AS reason,
    s.reviewed_at AS transaction_date
FROM quest_submissions s
JOIN quests q ON s.quest_id = q.quest_id
WHERE s.status = 'approved'
UNION ALL
SELECT 
    r.user_id,
    -rew.required_points AS points_change,
    'Deducted' AS type,
    CONCAT('Reward Claimed: ', rew.reward_name) AS reason,
    r.reviewed_at AS transaction_date
FROM reward_redemptions r
JOIN rewards rew ON r.reward_id = rew.reward_id
WHERE r.status = 'approved'
UNION ALL
SELECT 
    p.user_id,
    -p.points_deducted AS points_change,
    'Penalty' AS type,
    CONCAT('Penalty: ', p.reason) AS reason,
    p.created_at AS transaction_date
FROM penalties p
UNION ALL
SELECT 
    m.user_id,
    m.points_added AS points_change,
    'Manual' AS type,
    CONCAT('Admin Adjustment: ', m.reason) AS reason,
    m.created_at AS transaction_date
FROM manual_points m;

-- --------------------------------------------------------
-- SQL STORED PROCEDURE WITH TRANSACTION (ACID)
-- --------------------------------------------------------
DELIMITER //

CREATE PROCEDURE sp_deduct_penalty(
    IN p_user_id INT,
    IN p_points_deducted INT,
    IN p_reason TEXT,
    IN p_admin_id INT
)
BEGIN
    DECLARE v_student_name VARCHAR(100);
    DECLARE v_student_id_str VARCHAR(20);
    
    -- Declare variables for transaction exception handling
    DECLARE exit handler for sqlexception
    BEGIN
        -- Rollback if there is any error (Atomicity)
        ROLLBACK;
        RESIGNAL;
    END;

    -- Start Transaction
    START TRANSACTION;

    -- Fetch user details for the audit log
    SELECT full_name, IFNULL(student_id, 'N/A') INTO v_student_name, v_student_id_str 
    FROM users WHERE user_id = p_user_id;

    -- 1. Insert into penalties table
    INSERT INTO penalties (user_id, points_deducted, reason, created_by, created_at)
    VALUES (p_user_id, p_points_deducted, p_reason, p_admin_id, CURRENT_TIMESTAMP);

    -- 2. Deduct points from user
    UPDATE users 
    SET total_points = total_points - p_points_deducted
    WHERE user_id = p_user_id;

    -- 3. Log the action
    INSERT INTO audit_logs (admin_id, action, description)
    VALUES (p_admin_id, 'PENALTY_ISSUED', CONCAT('Deducted ', p_points_deducted, ' points from ', v_student_name, ' (', v_student_id_str, '). Reason: ', p_reason));

    -- Commit transaction if all steps succeed
    COMMIT;
END;
//

DELIMITER ;

-- --------------------------------------------------------
-- DATABASE USERS & PRIVILEGES (Security & Least Privilege)
-- --------------------------------------------------------

-- 1. Create the Student App User
CREATE USER IF NOT EXISTS 'quest_student_user'@'localhost' IDENTIFIED BY 'student_secure_pass123';
-- Students can only read general data
GRANT SELECT ON it_quest.quests TO 'quest_student_user'@'localhost';
GRANT SELECT ON it_quest.rewards TO 'quest_student_user'@'localhost';
GRANT SELECT ON it_quest.vw_student_leaderboard TO 'quest_student_user'@'localhost';
GRANT SELECT ON it_quest.vw_user_point_history TO 'quest_student_user'@'localhost';
GRANT SELECT ON it_quest.penalties TO 'quest_student_user'@'localhost';
GRANT SELECT ON it_quest.manual_points TO 'quest_student_user'@'localhost';
-- Students can read and update their own user record (handled by app logic, but DB needs UPDATE)
GRANT SELECT, UPDATE ON it_quest.users TO 'quest_student_user'@'localhost';
-- Students can submit quests and claim rewards
GRANT SELECT, INSERT, UPDATE, DELETE ON it_quest.quest_submissions TO 'quest_student_user'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON it_quest.quest_submission_files TO 'quest_student_user'@'localhost';
GRANT SELECT, INSERT ON it_quest.reward_redemptions TO 'quest_student_user'@'localhost';
GRANT SELECT, INSERT, UPDATE ON it_quest.quest_acceptances TO 'quest_student_user'@'localhost';

-- 2. Create the Admin App User
CREATE USER IF NOT EXISTS 'quest_admin_user'@'localhost' IDENTIFIED BY 'admin_secure_pass123';
-- Admins have full CRUD on operational tables
GRANT SELECT, INSERT, UPDATE, DELETE ON it_quest.quests TO 'quest_admin_user'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON it_quest.rewards TO 'quest_admin_user'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON it_quest.users TO 'quest_admin_user'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON it_quest.quest_submissions TO 'quest_admin_user'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON it_quest.quest_submission_files TO 'quest_admin_user'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON it_quest.reward_redemptions TO 'quest_admin_user'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON it_quest.quest_acceptances TO 'quest_admin_user'@'localhost';
-- Admins can view and insert penalties (via procedure) and audit logs
GRANT SELECT, INSERT, UPDATE, DELETE ON it_quest.penalties TO 'quest_admin_user'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON it_quest.manual_points TO 'quest_admin_user'@'localhost';
GRANT SELECT, INSERT ON it_quest.audit_logs TO 'quest_admin_user'@'localhost';
GRANT EXECUTE ON PROCEDURE it_quest.sp_deduct_penalty TO 'quest_admin_user'@'localhost';
GRANT SELECT ON it_quest.vw_student_leaderboard TO 'quest_admin_user'@'localhost';
GRANT SELECT ON it_quest.vw_user_point_history TO 'quest_admin_user'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;

-- --------------------------------------------------------
-- DEFAULT APPLICATION USERS (App-level authentication)
-- Password for both is 'password123'
-- --------------------------------------------------------