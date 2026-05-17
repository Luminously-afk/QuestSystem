-- =================================================================
-- Migration: Feature Additions (2026-05-18)
-- 1. Quest Difficulty column
-- 2. Quest Max Slots column
-- 3. Reward Stock column
-- =================================================================

USE it_quest;

-- 1. Add difficulty to quests
ALTER TABLE quests
    ADD COLUMN difficulty ENUM('easy','medium','hard') NOT NULL DEFAULT 'medium'
    AFTER category;

-- 2. Add max_slots to quests (NULL = unlimited)
ALTER TABLE quests
    ADD COLUMN max_slots INT NULL DEFAULT NULL
    AFTER points;

-- 3. Add stock to rewards (NULL = unlimited)
ALTER TABLE rewards
    ADD COLUMN stock INT NULL DEFAULT NULL
    AFTER required_points;

-- 4. Update leaderboard view to include event participation count
DROP VIEW IF EXISTS vw_student_leaderboard;
CREATE VIEW vw_student_leaderboard AS
SELECT 
    u.user_id, 
    u.full_name, 
    u.student_id,
    u.year_level,
    u.total_points,
    COUNT(s.submission_id) AS completed_quests,
    SUM(CASE WHEN q.category = 'Event' THEN 1 ELSE 0 END) AS event_participations
FROM users u
LEFT JOIN quest_submissions s ON u.user_id = s.user_id AND s.status = 'approved'
LEFT JOIN quests q ON s.quest_id = q.quest_id
WHERE u.role = 'student' AND u.is_active = 1
GROUP BY u.user_id
ORDER BY u.total_points DESC, completed_quests DESC;

-- 5. Grant permissions on new columns (existing grants cover the tables)
-- No additional grants needed since column-level is covered by table grants.
