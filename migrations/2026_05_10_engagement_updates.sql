-- IT Engagement System incremental migration

-- Users: year level + active flag
ALTER TABLE users
    ADD COLUMN year_level TINYINT NULL AFTER student_id,
    ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER must_change_password;

-- Quests: scope and proof type
ALTER TABLE quests
    ADD COLUMN scope_type ENUM('all', 'year', 'multi') NOT NULL DEFAULT 'all' AFTER category,
    ADD COLUMN scope_years VARCHAR(20) NULL AFTER scope_type,
    ADD COLUMN proof_type ENUM('text', 'image', 'image_text', 'multi_image', 'none') NOT NULL DEFAULT 'text' AFTER scope_years;

-- Submissions: store proof type and allow nullable proof text
ALTER TABLE quest_submissions
    ADD COLUMN proof_type ENUM('text', 'image', 'image_text', 'multi_image', 'none') NOT NULL DEFAULT 'text' AFTER quest_id,
    MODIFY COLUMN proof_text TEXT NULL;

-- Quest acceptance tracking
CREATE TABLE IF NOT EXISTS quest_acceptances (
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

-- Submission files for image proofs
CREATE TABLE IF NOT EXISTS quest_submission_files (
    file_id INT AUTO_INCREMENT PRIMARY KEY,
    submission_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (submission_id) REFERENCES quest_submissions(submission_id) ON DELETE CASCADE
);

-- Penalties (point deductions)
CREATE TABLE IF NOT EXISTS penalties (
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
