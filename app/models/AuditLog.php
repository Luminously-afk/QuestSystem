<?php
class AuditLog extends Model {
    public function create($adminId, $action, $description) {
        $stmt = $this->db->prepare(
            'INSERT INTO audit_logs (admin_id, action, description) VALUES (:admin_id, :action, :description)'
        );
        $stmt->bindParam(':admin_id', $adminId, PDO::PARAM_INT);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }

    public function getAll($limit = 200) {
        $stmt = $this->db->prepare(
            'SELECT a.*, u.full_name
             FROM audit_logs a
             LEFT JOIN users u ON u.user_id = a.admin_id
             ORDER BY a.created_at DESC
             LIMIT :limit'
        );
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>