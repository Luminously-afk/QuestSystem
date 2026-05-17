-- Add QR proof type and QR token tracking

ALTER TABLE quests
    MODIFY COLUMN proof_type ENUM('text', 'image', 'image_text', 'multi_image', 'none', 'qr') NOT NULL DEFAULT 'text';

ALTER TABLE quest_submissions
    MODIFY COLUMN proof_type ENUM('text', 'image', 'image_text', 'multi_image', 'none', 'qr') NOT NULL DEFAULT 'text';

CREATE TABLE IF NOT EXISTS quest_qr_tokens (
    token_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    quest_id INT NOT NULL,
    token VARCHAR(64) NOT NULL,
    status ENUM('active', 'redeemed', 'expired') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    redeemed_at TIMESTAMP NULL DEFAULT NULL,
    redeemed_by INT NULL,
    expires_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY uniq_user_quest (user_id, quest_id),
    UNIQUE KEY uniq_token (token),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (quest_id) REFERENCES quests(quest_id) ON DELETE CASCADE,
    FOREIGN KEY (redeemed_by) REFERENCES users(user_id) ON DELETE SET NULL
);
