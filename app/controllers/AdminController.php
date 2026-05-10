<?php
class AdminController extends Controller {
    private $questModel;
    private $submissionModel;
    private $rewardModel;
    private $redemptionModel;
    private $userModel;
    private $auditLogModel;
    private $acceptanceModel;
    private $penaltyModel;

    public function __construct() {
        $this->questModel = new Quest();
        $this->submissionModel = new Submission();
        $this->rewardModel = new Reward();
        $this->redemptionModel = new Redemption();
        $this->userModel = new User();
        $this->auditLogModel = new AuditLog();
        $this->acceptanceModel = new Acceptance();
        $this->penaltyModel = new Penalty();
    }

    public function index() {
        $this->requireAdmin();

        $this->view('admin/index', [
            'name' => $_SESSION['full_name'] ?? 'Admin'
        ]);
    }

    public function quests() {
        $this->requireAdmin();

        $quests = $this->questModel->getAll();
        $this->view('admin/quests/index', [
            'quests' => $quests
        ]);
    }

    public function createQuest() {
        $this->requireAdmin();

        $data = [
            'error' => '',
            'old' => [
                'title' => '',
                'description' => '',
                'category' => '',
                'scope_type' => 'all',
                'scope_years' => [],
                'proof_type' => 'text',
                'points' => '',
                'deadline' => '',
                'status' => 'active'
            ]
        ];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/quests');
        }

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $scopeType = $_POST['scope_type'] ?? 'all';
        $scopeYears = $_POST['scope_years'] ?? [];
        $proofType = $_POST['proof_type'] ?? 'text';
        $pointsRaw = $_POST['points'] ?? '';
        $deadlineRaw = trim($_POST['deadline'] ?? '');
        $status = $_POST['status'] ?? 'active';

        $data['old'] = [
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'scope_type' => $scopeType,
            'scope_years' => is_array($scopeYears) ? $scopeYears : [],
            'proof_type' => $proofType,
            'points' => $pointsRaw,
            'deadline' => $deadlineRaw,
            'status' => $status
        ];

        $points = (int) $pointsRaw;
        $deadline = $this->normalizeDeadline($deadlineRaw);
        $status = in_array($status, ['active', 'inactive'], true) ? $status : 'inactive';
        $scopeResult = $this->normalizeScope($scopeType, $scopeYears);
        $proofType = $this->normalizeProofType($proofType);

        if ($title === '' || $description === '' || $category === '' || $deadlineRaw === '') {
            $data['error'] = 'All fields are required.';
        } elseif ($points <= 0) {
            $data['error'] = 'Points must be greater than zero.';
        } elseif ($deadline === null) {
            $data['error'] = 'Please enter a valid deadline.';
        } elseif ($scopeResult['error'] !== '') {
            $data['error'] = $scopeResult['error'];
        } else {
            $created = $this->questModel->create([
                'title' => $title,
                'description' => $description,
                'category' => $category,
                'scope_type' => $scopeResult['scope_type'],
                'scope_years' => $scopeResult['scope_years'],
                'proof_type' => $proofType,
                'points' => $points,
                'deadline' => $deadline,
                'status' => $status,
                'created_by' => $_SESSION['user_id']
            ]);

            if ($created) {
                $this->auditLogModel->create(
                    $_SESSION['user_id'],
                    'quest_create',
                    'Created quest: ' . $title
                );
                $this->redirect('admin/quests?success=created');
                return;
            } else {
                $data['error'] = 'Failed to create quest. Please try again.';
            }
        }

        $data['quests'] = $this->questModel->getAll();
        $data['open_create_modal'] = true;
        $this->view('admin/quests/index', $data);
    }

    public function editQuest($questId = null) {
        $this->requireAdmin();

        if ($questId === null) {
            $this->redirect('admin/quests');
        }

        $quest = $this->questModel->getById($questId);
        if (!$quest) {
            $this->redirect('admin/quests');
        }

        $quest['deadline_input'] = $this->formatDeadlineForInput($quest['deadline']);

        $data = [
            'error' => '',
            'quest' => $quest
        ];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/quests');
        }

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $scopeType = $_POST['scope_type'] ?? $quest['scope_type'];
        $scopeYears = $_POST['scope_years'] ?? [];
        $proofType = $_POST['proof_type'] ?? $quest['proof_type'];
        $pointsRaw = $_POST['points'] ?? '';
        $deadlineRaw = trim($_POST['deadline'] ?? $quest['deadline_input']);
        $status = $_POST['status'] ?? $quest['status'];

        $points = (int) $pointsRaw;
        $deadline = $this->normalizeDeadline($deadlineRaw);
        $status = in_array($status, ['active', 'inactive'], true) ? $status : 'inactive';
        $scopeResult = $this->normalizeScope($scopeType, $scopeYears);
        $proofType = $this->normalizeProofType($proofType);

        $data['quest'] = [
            'quest_id' => $quest['quest_id'],
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'scope_type' => $scopeType,
            'scope_years' => is_array($scopeYears) ? $scopeYears : [],
            'proof_type' => $proofType,
            'points' => $pointsRaw,
            'deadline_input' => $deadlineRaw,
            'status' => $status
        ];

        if ($title === '' || $description === '' || $category === '' || $deadlineRaw === '') {
            $data['error'] = 'All fields are required.';
        } elseif ($points <= 0) {
            $data['error'] = 'Points must be greater than zero.';
        } elseif ($deadline === null) {
            $data['error'] = 'Please enter a valid deadline.';
        } elseif ($scopeResult['error'] !== '') {
            $data['error'] = $scopeResult['error'];
        } else {
            $updated = $this->questModel->update($questId, [
                'title' => $title,
                'description' => $description,
                'category' => $category,
                'scope_type' => $scopeResult['scope_type'],
                'scope_years' => $scopeResult['scope_years'],
                'proof_type' => $proofType,
                'points' => $points,
                'deadline' => $deadline,
                'status' => $status
            ]);

            if ($updated) {
                $this->auditLogModel->create(
                    $_SESSION['user_id'],
                    'quest_update',
                    'Updated quest: ' . $title
                );
                $this->redirect('admin/quests?success=updated');
                return;
            } else {
                $data['error'] = 'Failed to update quest. Please try again.';
            }
        }

        $data['quests'] = $this->questModel->getAll();
        $data['open_edit_modal'] = true;
        $this->view('admin/quests/index', $data);
    }

    public function deleteQuest() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/quests');
        }

        $questId = $_POST['quest_id'] ?? null;
        if ($questId === null) {
            $this->redirect('admin/quests');
        }

        $quest = $this->questModel->getById($questId);
        $this->questModel->delete($questId);
        if ($quest) {
            $this->auditLogModel->create(
                $_SESSION['user_id'],
                'quest_delete',
                'Deleted quest: ' . $quest['title']
            );
        }
        $this->redirect('admin/quests?success=deleted');
    }

    public function submissions() {
        $this->requireAdmin();

        $submissions = $this->submissionModel->getAllForAdmin();
        $submissionIds = array_map(function ($submission) {
            return (int) $submission['submission_id'];
        }, $submissions);
        $files = $this->submissionModel->getFilesBySubmissionIds($submissionIds);
        $this->view('admin/submissions/index', [
            'submissions' => $submissions,
            'submission_files' => $files
        ]);
    }

    public function reviewSubmission() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/submissions');
        }

        $submissionId = $_POST['submission_id'] ?? null;
        $status = $_POST['status'] ?? '';
        $remarks = trim($_POST['remarks'] ?? '');
        $remarks = $remarks === '' ? null : $remarks;

        if ($submissionId === null || !in_array($status, ['approved', 'rejected'], true)) {
            $this->redirect('admin/submissions?error=invalid');
        }

        $result = $this->submissionModel->review($submissionId, $status, $remarks, $_SESSION['user_id']);
        if ($result['success']) {
            if ($status === 'approved') {
                $submission = $this->submissionModel->getById($submissionId);
                if ($submission) {
                    $this->acceptanceModel->markCompleted($submission['user_id'], $submission['quest_id']);
                }
            }
            $this->auditLogModel->create(
                $_SESSION['user_id'],
                'submission_review',
                'Submission ' . $submissionId . ' marked ' . $status
            );
            $this->redirect('admin/submissions?success=' . $status);
        }

        $this->redirect('admin/submissions?error=' . $result['error']);
    }

    public function rewards() {
        $this->requireAdmin();

        $rewards = $this->rewardModel->getAll();
        $this->view('admin/rewards/index', [
            'rewards' => $rewards
        ]);
    }

    public function createReward() {
        $this->requireAdmin();

        $data = [
            'error' => '',
            'old' => [
                'reward_name' => '',
                'description' => '',
                'required_points' => '',
                'status' => 'available'
            ]
        ];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/rewards');
        }

        $rewardName = trim($_POST['reward_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $pointsRaw = $_POST['required_points'] ?? '';
        $status = $_POST['status'] ?? 'available';

        $data['old'] = [
            'reward_name' => $rewardName,
            'description' => $description,
            'required_points' => $pointsRaw,
            'status' => $status
        ];

        $points = (int) $pointsRaw;
        $status = in_array($status, ['available', 'unavailable'], true) ? $status : 'unavailable';

        if ($rewardName === '' || $description === '') {
            $data['error'] = 'All fields are required.';
        } elseif ($points <= 0) {
            $data['error'] = 'Required points must be greater than zero.';
        } else {
            $created = $this->rewardModel->create([
                'reward_name' => $rewardName,
                'description' => $description,
                'required_points' => $points,
                'status' => $status
            ]);

            if ($created) {
                $this->auditLogModel->create(
                    $_SESSION['user_id'],
                    'reward_create',
                    'Created reward: ' . $rewardName
                );
                $this->redirect('admin/rewards?success=created');
                return;
            } else {
                $data['error'] = 'Failed to create reward. Please try again.';
            }
        }

        $data['rewards'] = $this->rewardModel->getAll();
        $data['open_create_modal'] = true;
        $this->view('admin/rewards/index', $data);
    }

    public function editReward($rewardId = null) {
        $this->requireAdmin();

        if ($rewardId === null) {
            $this->redirect('admin/rewards');
        }

        $reward = $this->rewardModel->getById($rewardId);
        if (!$reward) {
            $this->redirect('admin/rewards');
        }

        $data = [
            'error' => '',
            'reward' => $reward
        ];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/rewards');
        }

        $rewardName = trim($_POST['reward_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $pointsRaw = $_POST['required_points'] ?? '';
        $status = $_POST['status'] ?? $reward['status'];

        $points = (int) $pointsRaw;
        $status = in_array($status, ['available', 'unavailable'], true) ? $status : 'unavailable';

        $data['reward'] = [
            'reward_id' => $rewardId,
            'reward_name' => $rewardName,
            'description' => $description,
            'required_points' => $pointsRaw,
            'status' => $status
        ];

        if ($rewardName === '' || $description === '') {
            $data['error'] = 'All fields are required.';
        } elseif ($points <= 0) {
            $data['error'] = 'Required points must be greater than zero.';
        } else {
            $updated = $this->rewardModel->update($rewardId, [
                'reward_name' => $rewardName,
                'description' => $description,
                'required_points' => $points,
                'status' => $status
            ]);

            if ($updated) {
                $this->auditLogModel->create(
                    $_SESSION['user_id'],
                    'reward_update',
                    'Updated reward: ' . $rewardName
                );
                $this->redirect('admin/rewards?success=updated');
                return;
            } else {
                $data['error'] = 'Failed to update reward. Please try again.';
            }
        }

        $data['rewards'] = $this->rewardModel->getAll();
        $data['open_edit_modal'] = true;
        $this->view('admin/rewards/index', $data);
    }

    public function deleteReward() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/rewards');
        }

        $rewardId = $_POST['reward_id'] ?? null;
        if ($rewardId === null) {
            $this->redirect('admin/rewards');
        }

        $reward = $this->rewardModel->getById($rewardId);
        $this->rewardModel->delete($rewardId);
        if ($reward) {
            $this->auditLogModel->create(
                $_SESSION['user_id'],
                'reward_delete',
                'Deleted reward: ' . $reward['reward_name']
            );
        }
        $this->redirect('admin/rewards?success=deleted');
    }

    public function redemptions() {
        $this->requireAdmin();

        $redemptions = $this->redemptionModel->getAllForAdmin();
        $this->view('admin/redemptions/index', [
            'redemptions' => $redemptions
        ]);
    }

    public function reviewRedemption() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/redemptions');
        }

        $redemptionId = $_POST['redemption_id'] ?? null;
        $status = $_POST['status'] ?? '';
        $remarks = trim($_POST['remarks'] ?? '');
        $remarks = $remarks === '' ? null : $remarks;

        if ($redemptionId === null || !in_array($status, ['approved', 'rejected'], true)) {
            $this->redirect('admin/redemptions?error=invalid');
        }

        $result = $this->redemptionModel->review($redemptionId, $status, $remarks, $_SESSION['user_id']);
        if ($result['success']) {
            $this->auditLogModel->create(
                $_SESSION['user_id'],
                'redemption_review',
                'Redemption ' . $redemptionId . ' marked ' . $status
            );
            $this->redirect('admin/redemptions?success=' . $status);
        }

        $this->redirect('admin/redemptions?error=' . $result['error']);
    }

    public function leaderboard() {
        $this->requireAdmin();

        $leaderboard = $this->userModel->getLeaderboard();
        $this->view('admin/leaderboard', [
            'leaderboard' => $leaderboard
        ]);
    }

    public function auditLogs() {
        $this->requireAdmin();

        $logs = $this->auditLogModel->getAll();
        $this->view('admin/audit_logs', [
            'logs' => $logs
        ]);
    }

    public function penalties() {
        $this->requireAdmin();

        $penalties = $this->penaltyModel->getAll();
        $students = $this->userModel->getStudents();

        $this->view('admin/penalties/index', [
            'penalties' => $penalties,
            'students' => $students
        ]);
    }

    public function createPenalty() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/penalties');
        }

        $userId = (int) ($_POST['user_id'] ?? 0);
        $pointsRaw = $_POST['points_deducted'] ?? '';
        $reason = trim($_POST['reason'] ?? '');

        $data = [
            'error' => '',
            'penalties' => $this->penaltyModel->getAll(),
            'students' => $this->userModel->getStudents(),
            'open_create_modal' => true
        ];

        $points = (int) $pointsRaw;

        if ($userId <= 0 || $reason === '') {
            $data['error'] = 'Student and reason are required.';
        } elseif ($points <= 0) {
            $data['error'] = 'Points deducted must be greater than zero.';
        } else {
            $result = $this->penaltyModel->create($userId, $points, $reason, $_SESSION['user_id']);
            if ($result['success']) {
                $this->auditLogModel->create(
                    $_SESSION['user_id'],
                    'penalty_create',
                    'Deducted ' . $points . ' points from user ' . $userId
                );
                $this->redirect('admin/penalties?success=created');
                return;
            }

            if (($result['error'] ?? '') === 'insufficient_points') {
                $data['error'] = 'Student does not have enough points for this penalty.';
            } else {
                $data['error'] = 'Failed to apply penalty. Please try again.';
            }
        }

        $this->view('admin/penalties/index', $data);
    }

    public function students() {
        $this->requireAdmin();

        $students = $this->userModel->getStudents();
        $this->view('admin/students/index', [
            'students' => $students
        ]);
    }

    public function createStudent() {
        $this->requireAdmin();

        $data = [
            'error' => '',
            'success' => '',
            'generated_password' => '',
            'old' => [
                'full_name' => '',
                'student_id' => '',
                'email' => '',
                'year_level' => ''
            ]
        ];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/students');
        }

        $fullName = trim($_POST['full_name'] ?? '');
        $studentId = trim($_POST['student_id'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $yearLevelRaw = trim($_POST['year_level'] ?? '');
        $yearLevel = $this->normalizeYearLevel($yearLevelRaw);

        $data['old'] = [
            'full_name' => $fullName,
            'student_id' => $studentId,
            'email' => $email,
            'year_level' => $yearLevelRaw
        ];

        if ($fullName === '' || $studentId === '' || $email === '' || $yearLevelRaw === '') {
            $data['error'] = 'Full name, student ID, year level, and email are required.';
        } elseif (!$this->isValidStudentId($studentId)) {
            $data['error'] = 'Student ID format is invalid. Use 241c-1234.';
        } elseif ($yearLevel === null) {
            $data['error'] = 'Year level must be between 1 and 4.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data['error'] = 'Please enter a valid email address.';
        } elseif ($this->userModel->findByEmail($email)) {
            $data['error'] = 'Email is already registered.';
        } elseif ($this->userModel->findByStudentId($studentId)) {
            $data['error'] = 'Student ID is already registered.';
        } else {
            $plainPassword = $this->generateSimplePassword();
            $passwordHash = password_hash($plainPassword, PASSWORD_DEFAULT);
            $created = $this->userModel->create([
                'full_name' => $fullName,
                'student_id' => $studentId,
                'year_level' => $yearLevel,
                'email' => $email,
                'password' => $passwordHash,
                'role' => 'student',
                'must_change_password' => 1,
                'is_active' => 1
            ]);

            if ($created) {
                $this->auditLogModel->create(
                    $_SESSION['user_id'],
                    'student_create',
                    'Created student account: ' . $fullName
                );
                $data['success'] = 'Student account created. Share the password below with the student.';
                $data['generated_password'] = $plainPassword;
                $data['old'] = ['full_name' => '', 'email' => '', 'student_id' => '', 'year_level' => ''];
            } else {
                $data['error'] = 'Failed to create student account. Please try again.';
            }
        }

        $data['students'] = $this->userModel->getStudents();
        $data['open_create_modal'] = true;
        $this->view('admin/students/index', $data);
    }

    public function editStudent($userId = null) {
        $this->requireAdmin();

        if ($userId === null) {
            $userId = $_POST['user_id'] ?? null;
        }

        if ($userId === null || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/students');
        }

        $student = $this->userModel->getById($userId);
        if (!$student || ($student['role'] ?? '') !== 'student') {
            $this->redirect('admin/students');
        }

        $fullName = trim($_POST['full_name'] ?? '');
        $studentId = trim($_POST['student_id'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $yearLevelRaw = trim($_POST['year_level'] ?? '');
        $yearLevel = $this->normalizeYearLevel($yearLevelRaw);
        $isActive = isset($_POST['is_active']) ? (int) $_POST['is_active'] : (int) ($student['is_active'] ?? 1);

        $data = [
            'error' => '',
            'students' => $this->userModel->getStudents(),
            'open_edit_modal' => true,
            'student' => [
                'user_id' => $userId,
                'full_name' => $fullName,
                'student_id' => $studentId,
                'email' => $email,
                'year_level' => $yearLevelRaw,
                'is_active' => $isActive
            ]
        ];

        if ($fullName === '' || $studentId === '' || $email === '' || $yearLevelRaw === '') {
            $data['error'] = 'Full name, student ID, year level, and email are required.';
        } elseif (!$this->isValidStudentId($studentId)) {
            $data['error'] = 'Student ID format is invalid. Use 241c-1234.';
        } elseif ($yearLevel === null) {
            $data['error'] = 'Year level must be between 1 and 4.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data['error'] = 'Please enter a valid email address.';
        } elseif ($this->userModel->emailExistsForOther($email, $userId)) {
            $data['error'] = 'Email is already registered.';
        } elseif ($this->userModel->studentIdExistsForOther($studentId, $userId)) {
            $data['error'] = 'Student ID is already registered.';
        } else {
            $updated = $this->userModel->updateStudent($userId, [
                'full_name' => $fullName,
                'student_id' => $studentId,
                'email' => $email,
                'year_level' => $yearLevel,
                'is_active' => $isActive
            ]);

            if ($updated) {
                $this->auditLogModel->create(
                    $_SESSION['user_id'],
                    'student_update',
                    'Updated student account: ' . $fullName
                );
                $this->redirect('admin/students?success=updated');
                return;
            }
            $data['error'] = 'Failed to update student account. Please try again.';
        }

        $this->view('admin/students/index', $data);
    }

    public function toggleStudentStatus() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/students');
        }

        $userId = (int) ($_POST['user_id'] ?? 0);
        $isActive = (int) ($_POST['is_active'] ?? 1);

        if ($userId <= 0) {
            $this->redirect('admin/students?error=invalid');
        }

        $updated = $this->userModel->setActive($userId, $isActive);
        if ($updated) {
            $this->auditLogModel->create(
                $_SESSION['user_id'],
                'student_status',
                'Set student ' . $userId . ' active=' . $isActive
            );
            $this->redirect('admin/students?success=status');
        }

        $this->redirect('admin/students?error=failed');
    }

    private function requireAdmin() {
        if (!$this->isAdmin()) {
            $this->redirect('auth/login');
        }

        if (!empty($_SESSION['must_change_password'])) {
            $this->redirect('auth/changePassword?first=1');
        }
    }

    private function isAdmin() {
        return isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'admin';
    }

    private function normalizeDeadline($input) {
        $input = trim($input);
        if ($input === '') {
            return null;
        }

        $timestamp = strtotime($input);
        if ($timestamp === false) {
            return null;
        }

        return date('Y-m-d H:i:s', $timestamp);
    }

    private function formatDeadlineForInput($dbValue) {
        $timestamp = strtotime($dbValue);
        if ($timestamp === false) {
            return '';
        }

        return date('Y-m-d\TH:i', $timestamp);
    }

    private function normalizeYearLevel($input) {
        if ($input === '' || $input === null) {
            return null;
        }

        $level = (int) $input;
        if ($level < 1 || $level > 4) {
            return null;
        }

        return $level;
    }

    private function normalizeScope($scopeType, $scopeYears) {
        $allowedTypes = ['all', 'year', 'multi'];
        $scopeType = in_array($scopeType, $allowedTypes, true) ? $scopeType : 'all';

        $years = array_map('strval', (array) $scopeYears);
        $years = array_values(array_unique(array_filter($years, function ($year) {
            return in_array($year, ['1', '2', '3', '4'], true);
        })));

        if ($scopeType === 'all') {
            return ['scope_type' => 'all', 'scope_years' => null, 'error' => ''];
        }

        if ($scopeType === 'year' && count($years) !== 1) {
            return ['scope_type' => $scopeType, 'scope_years' => null, 'error' => 'Select exactly one year level for single-year scope.'];
        }

        if ($scopeType === 'multi' && count($years) < 1) {
            return ['scope_type' => $scopeType, 'scope_years' => null, 'error' => 'Select at least one year level for multi-year scope.'];
        }

        return ['scope_type' => $scopeType, 'scope_years' => implode(',', $years), 'error' => ''];
    }

    private function normalizeProofType($proofType) {
        $allowed = ['text', 'image', 'image_text', 'multi_image', 'none'];
        return in_array($proofType, $allowed, true) ? $proofType : 'text';
    }

    private function generateSimplePassword($length = 8) {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $password = '';
        $maxIndex = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $maxIndex)];
        }
        return $password;
    }

    private function isValidStudentId($value) {
        return preg_match('/^[A-Za-z0-9]{4}-\d{4}$/', $value) === 1;
    }
}
?>