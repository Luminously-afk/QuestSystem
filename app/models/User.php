<?php
class User extends Model {
    public function findByEmail($email) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByStudentId($studentId) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE student_id = :student_id LIMIT 1');
        $stmt->bindParam(':student_id', $studentId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare(
            'INSERT INTO users (full_name, student_id, year_level, email, password, role, must_change_password, is_active)
             VALUES (:full_name, :student_id, :year_level, :email, :password, :role, :must_change_password, :is_active)'
        );
        $stmt->bindParam(':full_name', $data['full_name']);
        $studentId = $data['student_id'] ?? null;
        $stmt->bindValue(
            ':student_id',
            $studentId,
            $studentId === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );
        $yearLevel = $data['year_level'] ?? null;
        $stmt->bindValue(
            ':year_level',
            $yearLevel,
            $yearLevel === null ? PDO::PARAM_NULL : PDO::PARAM_INT
        );
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':role', $data['role']);
        $mustChange = isset($data['must_change_password']) ? (int) $data['must_change_password'] : 0;
        $stmt->bindParam(':must_change_password', $mustChange, PDO::PARAM_INT);
        $isActive = isset($data['is_active']) ? (int) $data['is_active'] : 1;
        $stmt->bindParam(':is_active', $isActive, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getById($userId) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE user_id = :user_id LIMIT 1');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStudentStats($userId) {
        $stmt = $this->db->prepare(
            "SELECT u.total_points, COUNT(s.submission_id) AS completed_count
             FROM users u
             LEFT JOIN quest_submissions s
               ON s.user_id = u.user_id AND s.status = 'approved'
             WHERE u.user_id = :user_id
             GROUP BY u.user_id"
        );
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return ['total_points' => 0, 'completed_count' => 0];
        }
        return $result;
    }

    public function getLeaderboard() {
        $stmt = $this->db->prepare(
            "SELECT u.user_id, u.full_name, u.total_points,
                    COUNT(s.submission_id) AS completed_count
             FROM users u
             LEFT JOIN quest_submissions s
               ON s.user_id = u.user_id AND s.status = 'approved'
             WHERE u.role = 'student' AND u.is_active = 1
             GROUP BY u.user_id
             ORDER BY u.total_points DESC, completed_count DESC, u.full_name ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updatePassword($userId, $passwordHash) {
        $stmt = $this->db->prepare(
            "UPDATE users
             SET password = :password,
                 must_change_password = 0,
                 password_changed_at = NOW()
             WHERE user_id = :user_id"
        );
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getStudents() {
        $stmt = $this->db->prepare(
            "SELECT user_id, full_name, student_id, year_level, email, total_points, must_change_password, is_active, created_at
             FROM users
             WHERE role = 'student'
             ORDER BY created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStudent($userId, $data) {
        $stmt = $this->db->prepare(
            "UPDATE users
             SET full_name = :full_name,
                 student_id = :student_id,
                 year_level = :year_level,
                 email = :email,
                 is_active = :is_active
             WHERE user_id = :user_id"
        );
        $stmt->bindParam(':full_name', $data['full_name']);
        $studentId = $data['student_id'] ?? null;
        $stmt->bindValue(
            ':student_id',
            $studentId,
            $studentId === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );
        $yearLevel = $data['year_level'] ?? null;
        $stmt->bindValue(
            ':year_level',
            $yearLevel,
            $yearLevel === null ? PDO::PARAM_NULL : PDO::PARAM_INT
        );
        $stmt->bindParam(':email', $data['email']);
        $isActive = isset($data['is_active']) ? (int) $data['is_active'] : 1;
        $stmt->bindParam(':is_active', $isActive, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function setActive($userId, $isActive) {
        $stmt = $this->db->prepare(
            "UPDATE users SET is_active = :is_active WHERE user_id = :user_id"
        );
        $stmt->bindParam(':is_active', $isActive, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function emailExistsForOther($email, $userId) {
        $stmt = $this->db->prepare(
            "SELECT user_id FROM users WHERE email = :email AND user_id <> :user_id LIMIT 1"
        );
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function studentIdExistsForOther($studentId, $userId) {
        $stmt = $this->db->prepare(
            "SELECT user_id FROM users WHERE student_id = :student_id AND user_id <> :user_id LIMIT 1"
        );
        $stmt->bindParam(':student_id', $studentId);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>