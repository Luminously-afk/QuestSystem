<?php
class Acceptance extends Model {
    public function getByUserQuest($userId, $questId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM quest_acceptances WHERE user_id = :user_id AND quest_id = :quest_id LIMIT 1"
        );
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':quest_id', $questId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function accept($userId, $questId) {
        $stmt = $this->db->prepare(
            "INSERT INTO quest_acceptances (user_id, quest_id) VALUES (:user_id, :quest_id)"
        );
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':quest_id', $questId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function markCompleted($userId, $questId) {
        $stmt = $this->db->prepare(
            "UPDATE quest_acceptances
             SET status = 'completed', completed_at = NOW()
             WHERE user_id = :user_id AND quest_id = :quest_id AND status = 'accepted'"
        );
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':quest_id', $questId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>