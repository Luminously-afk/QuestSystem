<?php
class Penalty extends Model {
    public function getAll() {
        $stmt = $this->db->prepare(
            "SELECT p.*, u.full_name, u.email, a.full_name AS admin_name
             FROM penalties p
             INNER JOIN users u ON u.user_id = p.user_id
             LEFT JOIN users a ON a.user_id = p.created_by
             ORDER BY p.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($userId, $points, $reason, $adminId) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare(
                "UPDATE users
                 SET total_points = total_points - :points
                 WHERE user_id = :user_id AND total_points >= :points"
            );
            $stmt->bindParam(':points', $points, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                $this->db->rollBack();
                return ['success' => false, 'error' => 'insufficient_points'];
            }

            $insert = $this->db->prepare(
                "INSERT INTO penalties (user_id, points_deducted, reason, created_by)
                 VALUES (:user_id, :points_deducted, :reason, :created_by)"
            );
            $insert->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $insert->bindParam(':points_deducted', $points, PDO::PARAM_INT);
            $insert->bindParam(':reason', $reason);
            $insert->bindParam(':created_by', $adminId, PDO::PARAM_INT);
            $insert->execute();

            $this->db->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'error' => 'exception'];
        }
    }
}
?>