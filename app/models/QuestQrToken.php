<?php
class QuestQrToken extends Model {
    public function getByUserQuest($userId, $questId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM quest_qr_tokens WHERE user_id = :user_id AND quest_id = :quest_id LIMIT 1"
        );
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':quest_id', $questId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByToken($token) {
        $stmt = $this->db->prepare(
            "SELECT * FROM quest_qr_tokens WHERE token = :token LIMIT 1"
        );
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createOrGetActiveToken($userId, $questId) {
        $existing = $this->getByUserQuest($userId, $questId);
        if ($existing && ($existing['status'] ?? '') === 'active') {
            return $existing['token'];
        }
        if ($existing) {
            return null;
        }

        $token = bin2hex(random_bytes(16));
        $stmt = $this->db->prepare(
            "INSERT INTO quest_qr_tokens (user_id, quest_id, token, status)
             VALUES (:user_id, :quest_id, :token, 'active')"
        );
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':quest_id', $questId, PDO::PARAM_INT);
        $stmt->bindParam(':token', $token);

        if (!$stmt->execute()) {
            return null;
        }

        return $token;
    }

    public function markRedeemed($tokenId, $adminId) {
        $stmt = $this->db->prepare(
            "UPDATE quest_qr_tokens
             SET status = 'redeemed', redeemed_at = NOW(), redeemed_by = :redeemed_by
             WHERE token_id = :token_id AND status = 'active'"
        );
        $stmt->bindParam(':redeemed_by', $adminId, PDO::PARAM_INT);
        $stmt->bindParam(':token_id', $tokenId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function markExpired($tokenId) {
        $stmt = $this->db->prepare(
            "UPDATE quest_qr_tokens
             SET status = 'expired'
             WHERE token_id = :token_id AND status = 'active'"
        );
        $stmt->bindParam(':token_id', $tokenId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>
