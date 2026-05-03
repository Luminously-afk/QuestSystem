<?php
class Quest extends Model {
    public function getAll() {
        $stmt = $this->db->prepare('SELECT * FROM quests ORDER BY created_at DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($questId) {
        $stmt = $this->db->prepare('SELECT * FROM quests WHERE quest_id = :quest_id LIMIT 1');
        $stmt->bindParam(':quest_id', $questId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            'INSERT INTO quests (title, description, category, points, deadline, status, created_by)
             VALUES (:title, :description, :category, :points, :deadline, :status, :created_by)'
        );
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':points', $data['points'], PDO::PARAM_INT);
        $stmt->bindParam(':deadline', $data['deadline']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':created_by', $data['created_by'], PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function update($questId, $data) {
        $stmt = $this->db->prepare(
            'UPDATE quests
             SET title = :title,
                 description = :description,
                 category = :category,
                 points = :points,
                 deadline = :deadline,
                 status = :status
             WHERE quest_id = :quest_id'
        );
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':points', $data['points'], PDO::PARAM_INT);
        $stmt->bindParam(':deadline', $data['deadline']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':quest_id', $questId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($questId) {
        $stmt = $this->db->prepare('DELETE FROM quests WHERE quest_id = :quest_id');
        $stmt->bindParam(':quest_id', $questId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getActiveWithStatus($userId) {
        $stmt = $this->db->prepare(
            "SELECT q.*, s.status AS submission_status, s.submission_id
             FROM quests q
             LEFT JOIN quest_submissions s
               ON s.quest_id = q.quest_id AND s.user_id = :user_id
             WHERE q.status = 'active' AND q.deadline >= NOW()
             ORDER BY q.deadline ASC"
        );
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveById($questId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM quests
             WHERE quest_id = :quest_id AND status = 'active' AND deadline >= NOW()
             LIMIT 1"
        );
        $stmt->bindParam(':quest_id', $questId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>