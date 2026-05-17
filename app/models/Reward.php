<?php
class Reward extends Model {
    public function getAll() {
        $stmt = $this->db->prepare('SELECT * FROM rewards ORDER BY created_at DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailable() {
        $stmt = $this->db->prepare(
            "SELECT r.*, 
                    (SELECT COUNT(*) FROM reward_redemptions rr WHERE rr.reward_id = r.reward_id AND rr.status IN ('pending', 'approved')) AS claimed_count
             FROM rewards r 
             WHERE r.status = 'available' 
             ORDER BY r.required_points ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($rewardId) {
        $stmt = $this->db->prepare('SELECT * FROM rewards WHERE reward_id = :reward_id LIMIT 1');
        $stmt->bindParam(':reward_id', $rewardId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            'INSERT INTO rewards (reward_name, description, required_points, stock, status)
             VALUES (:reward_name, :description, :required_points, :stock, :status)'
        );
        $stmt->bindParam(':reward_name', $data['reward_name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':required_points', $data['required_points'], PDO::PARAM_INT);
        $stock = $data['stock'] ?? null;
        $stmt->bindValue(':stock', $stock, $stock === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status']);
        return $stmt->execute();
    }

    public function update($rewardId, $data) {
        $stmt = $this->db->prepare(
            'UPDATE rewards
             SET reward_name = :reward_name,
                 description = :description,
                 required_points = :required_points,
                 stock = :stock,
                 status = :status
             WHERE reward_id = :reward_id'
        );
        $stmt->bindParam(':reward_name', $data['reward_name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':required_points', $data['required_points'], PDO::PARAM_INT);
        $stock = $data['stock'] ?? null;
        $stmt->bindValue(':stock', $stock, $stock === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':reward_id', $rewardId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($rewardId) {
        $stmt = $this->db->prepare('DELETE FROM rewards WHERE reward_id = :reward_id');
        $stmt->bindParam(':reward_id', $rewardId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function decrementStock($rewardId) {
        $stmt = $this->db->prepare(
            'UPDATE rewards SET stock = stock - 1 WHERE reward_id = :reward_id AND stock IS NOT NULL AND stock > 0'
        );
        $stmt->bindParam(':reward_id', $rewardId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function hasStock($rewardId) {
        $stmt = $this->db->prepare(
            'SELECT stock FROM rewards WHERE reward_id = :reward_id LIMIT 1'
        );
        $stmt->bindParam(':reward_id', $rewardId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return false;
        if ($row['stock'] === null) return true; // unlimited
        return (int)$row['stock'] > 0;
    }
}
?>