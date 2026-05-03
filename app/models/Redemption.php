<?php
class Redemption extends Model {
    public function getByUser($userId) {
        $stmt = $this->db->prepare(
            "SELECT r.*, w.reward_name, w.required_points, w.description
             FROM reward_redemptions r
             INNER JOIN rewards w ON w.reward_id = r.reward_id
             WHERE r.user_id = :user_id
             ORDER BY r.requested_at DESC"
        );
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllForAdmin($status = null) {
        $sql =
            "SELECT r.*, u.full_name, u.email, w.reward_name, w.required_points
             FROM reward_redemptions r
             INNER JOIN users u ON u.user_id = r.user_id
             INNER JOIN rewards w ON w.reward_id = r.reward_id";

        if ($status !== null) {
            $sql .= " WHERE r.status = :status";
        }

        $sql .= " ORDER BY r.requested_at DESC";

        $stmt = $this->db->prepare($sql);
        if ($status !== null) {
            $stmt->bindParam(':status', $status);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveRequest($userId, $rewardId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM reward_redemptions
             WHERE user_id = :user_id AND reward_id = :reward_id
               AND status IN ('pending', 'approved')
             ORDER BY requested_at DESC
             LIMIT 1"
        );
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':reward_id', $rewardId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($userId, $rewardId) {
        $stmt = $this->db->prepare(
            "INSERT INTO reward_redemptions (user_id, reward_id)
             VALUES (:user_id, :reward_id)"
        );
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':reward_id', $rewardId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function review($redemptionId, $status, $remarks, $adminId) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare(
                "SELECT r.status, r.user_id, w.required_points, u.total_points
                 FROM reward_redemptions r
                 INNER JOIN rewards w ON w.reward_id = r.reward_id
                 INNER JOIN users u ON u.user_id = r.user_id
                 WHERE r.redemption_id = :redemption_id
                 LIMIT 1
                 FOR UPDATE"
            );
            $stmt->bindParam(':redemption_id', $redemptionId, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row || $row['status'] !== 'pending') {
                $this->db->rollBack();
                return ['success' => false, 'error' => 'not_pending'];
            }

            if ($status === 'approved' && (int) $row['total_points'] < (int) $row['required_points']) {
                $this->db->rollBack();
                return ['success' => false, 'error' => 'not_enough_points'];
            }

            $update = $this->db->prepare(
                "UPDATE reward_redemptions
                 SET status = :status,
                     remarks = :remarks,
                     reviewed_by = :reviewed_by,
                     reviewed_at = NOW()
                 WHERE redemption_id = :redemption_id AND status = 'pending'"
            );
            $update->bindParam(':status', $status);
            $update->bindParam(':remarks', $remarks);
            $update->bindParam(':reviewed_by', $adminId, PDO::PARAM_INT);
            $update->bindParam(':redemption_id', $redemptionId, PDO::PARAM_INT);
            $update->execute();

            if ($update->rowCount() === 0) {
                $this->db->rollBack();
                return ['success' => false, 'error' => 'not_pending'];
            }

            $this->db->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'error' => 'exception'];
        }
    }
}
?>