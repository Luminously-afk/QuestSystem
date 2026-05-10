<?php
class Submission extends Model {
    public function getForUser($userId) {
        $stmt = $this->db->prepare(
            "SELECT s.*, q.title, q.category, q.points
             FROM quest_submissions s
             INNER JOIN quests q ON q.quest_id = s.quest_id
             WHERE s.user_id = :user_id
             ORDER BY s.submitted_at DESC"
        );
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUserQuest($userId, $questId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM quest_submissions
             WHERE user_id = :user_id AND quest_id = :quest_id
             ORDER BY submitted_at DESC
             LIMIT 1"
        );
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':quest_id', $questId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($submissionId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM quest_submissions WHERE submission_id = :submission_id LIMIT 1"
        );
        $stmt->bindParam(':submission_id', $submissionId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($userId, $questId, $proofType, $proofText) {
        $stmt = $this->db->prepare(
            "INSERT INTO quest_submissions (user_id, quest_id, proof_type, proof_text)
             VALUES (:user_id, :quest_id, :proof_type, :proof_text)"
        );
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':quest_id', $questId, PDO::PARAM_INT);
        $stmt->bindParam(':proof_type', $proofType);
        $stmt->bindParam(':proof_text', $proofText);
        if (!$stmt->execute()) {
            return false;
        }
        return $this->db->lastInsertId();
    }

    public function resubmit($submissionId, $proofType, $proofText) {
        $stmt = $this->db->prepare(
            "UPDATE quest_submissions
             SET proof_type = :proof_type,
                 proof_text = :proof_text,
                 status = 'pending',
                 remarks = NULL,
                 submitted_at = NOW(),
                 reviewed_by = NULL,
                 reviewed_at = NULL
             WHERE submission_id = :submission_id"
        );
        $stmt->bindParam(':proof_type', $proofType);
        $stmt->bindParam(':proof_text', $proofText);
        $stmt->bindParam(':submission_id', $submissionId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getAllForAdmin($status = null) {
        $sql =
            "SELECT s.*, u.full_name, u.email, q.title, q.points, q.proof_type AS quest_proof_type
             FROM quest_submissions s
             INNER JOIN users u ON u.user_id = s.user_id
             INNER JOIN quests q ON q.quest_id = s.quest_id";

        if ($status !== null) {
            $sql .= " WHERE s.status = :status";
        }

        $sql .= " ORDER BY s.submitted_at DESC";

        $stmt = $this->db->prepare($sql);
        if ($status !== null) {
            $stmt->bindParam(':status', $status);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilesBySubmissionIds($submissionIds) {
        if (empty($submissionIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($submissionIds), '?'));
        $stmt = $this->db->prepare(
            "SELECT submission_id, file_path
             FROM quest_submission_files
             WHERE submission_id IN ($placeholders)
             ORDER BY file_id ASC"
        );
        foreach ($submissionIds as $index => $submissionId) {
            $stmt->bindValue($index + 1, $submissionId, PDO::PARAM_INT);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $files = [];
        foreach ($rows as $row) {
            if (!isset($files[$row['submission_id']])) {
                $files[$row['submission_id']] = [];
            }
            $files[$row['submission_id']][] = $row['file_path'];
        }
        return $files;
    }

    public function addFiles($submissionId, $paths) {
        if (empty($paths)) {
            return true;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO quest_submission_files (submission_id, file_path)
             VALUES (:submission_id, :file_path)"
        );
        foreach ($paths as $path) {
            $stmt->bindParam(':submission_id', $submissionId, PDO::PARAM_INT);
            $stmt->bindParam(':file_path', $path);
            if (!$stmt->execute()) {
                return false;
            }
        }
        return true;
    }

    public function deleteFiles($submissionId) {
        $stmt = $this->db->prepare(
            "DELETE FROM quest_submission_files WHERE submission_id = :submission_id"
        );
        $stmt->bindParam(':submission_id', $submissionId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function review($submissionId, $status, $remarks, $adminId) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare(
                "UPDATE quest_submissions
                 SET status = :status,
                     remarks = :remarks,
                     reviewed_by = :reviewed_by,
                     reviewed_at = NOW()
                 WHERE submission_id = :submission_id AND status = 'pending'"
            );
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':remarks', $remarks);
            $stmt->bindParam(':reviewed_by', $adminId, PDO::PARAM_INT);
            $stmt->bindParam(':submission_id', $submissionId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
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