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

        // Get quest deadline for auto-expiry
        $questStmt = $this->db->prepare(
            "SELECT deadline FROM quests WHERE quest_id = :quest_id LIMIT 1"
        );
        $questStmt->bindParam(':quest_id', $questId, PDO::PARAM_INT);
        $questStmt->execute();
        $quest = $questStmt->fetch(PDO::FETCH_ASSOC);
        $expiresAt = $quest ? $quest['deadline'] : null;

        $token = bin2hex(random_bytes(16));
        $stmt = $this->db->prepare(
            "INSERT INTO quest_qr_tokens (user_id, quest_id, token, status, expires_at)
             VALUES (:user_id, :quest_id, :token, 'active', :expires_at)"
        );
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':quest_id', $questId, PDO::PARAM_INT);
        $stmt->bindParam(':token', $token);
        $stmt->bindValue(':expires_at', $expiresAt, $expiresAt === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

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

    public function expireStaleTokens() {
        $stmt = $this->db->prepare(
            "UPDATE quest_qr_tokens
             SET status = 'expired'
             WHERE status = 'active' AND expires_at IS NOT NULL AND expires_at < NOW()"
        );
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>
