<?php
class StudentController extends Controller {
    private $questModel;
    private $submissionModel;
    private $rewardModel;
    private $redemptionModel;
    private $userModel;
    private $acceptanceModel;
    private $qrTokenModel;

    public function __construct() {
        $this->questModel = new Quest();
        $this->submissionModel = new Submission();
        $this->rewardModel = new Reward();
        $this->redemptionModel = new Redemption();
        $this->userModel = new User();
        $this->acceptanceModel = new Acceptance();
        $this->qrTokenModel = new QuestQrToken();
    }

    public function index() {
        $this->requireStudent();

        $stats = $this->userModel->getStudentStats($_SESSION['user_id']);
        
        $this->view('student/index', [
            'name' => $_SESSION['full_name'] ?? 'Student',
            'stats' => $stats
        ]);
    }

    public function history() {
        $this->requireStudent();

        $pointHistory = $this->userModel->getPointHistory($_SESSION['user_id']);
        
        $this->view('student/history', [
            'point_history' => $pointHistory
        ]);
    }

    public function quests() {
        $this->requireStudent();

        $student = $this->userModel->getById($_SESSION['user_id']);
        $yearLevel = $student['year_level'] ?? null;

        $availableQuests = $this->questModel->getVisibleForStudent($_SESSION['user_id'], $yearLevel);
        $acceptedQuests = $this->questModel->getAcceptedForStudent($_SESSION['user_id']);
        $this->view('student/quests/index', [
            'available_quests' => $availableQuests,
            'accepted_quests' => $acceptedQuests
        ]);
    }

    public function acceptQuest($questId = null) {
        $this->requireStudent();

        if ($questId === null || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('student/quests');
        }

        $student = $this->userModel->getById($_SESSION['user_id']);
        $yearLevel = $student['year_level'] ?? null;
        $available = $this->questModel->getAvailableForStudent($_SESSION['user_id'], $yearLevel);

        $isAvailable = false;
        $questToAccept = null;
        foreach ($available as $quest) {
            if ((int)$quest['quest_id'] === (int)$questId) {
                $isAvailable = true;
                $questToAccept = $quest;
                break;
            }
        }

        if (!$isAvailable) {
            $this->redirect('student/quests?error=not_available');
        }

        $existing = $this->acceptanceModel->getByUserQuest($_SESSION['user_id'], $questId);
        if ($existing) {
            $this->redirect('student/quests?error=already_accepted');
        }

        $accepted = $this->acceptanceModel->accept($_SESSION['user_id'], $questId);
        if ($accepted) {
            if (($questToAccept['proof_type'] ?? '') === 'qr') {
                $token = $this->qrTokenModel->createOrGetActiveToken($_SESSION['user_id'], $questId);
                if (!$token) {
                    $data = [
                        'error' => 'Failed to generate QR code. Please try again or contact the admin.',
                        'available_quests' => $this->questModel->getVisibleForStudent($_SESSION['user_id'], $yearLevel),
                        'accepted_quests' => $this->questModel->getAcceptedForStudent($_SESSION['user_id'])
                    ];
                    $this->view('student/quests/index', $data);
                    return;
                }
            }
            $this->redirect('student/quests?success=accepted');
        }

        $this->redirect('student/quests?error=failed');
    }

    public function submit($questId = null) {
        $this->requireStudent();

        if ($questId === null || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('student/quests');
        }

        $quest = $this->questModel->getActiveById($questId);
        if (!$quest) {
            $this->redirect('student/quests');
        }

        if (($quest['proof_type'] ?? '') === 'qr') {
            $student = $this->userModel->getById($_SESSION['user_id']);
            $data = [
                'error' => 'QR quests are verified by admin scan. Show your QR code to receive credit.',
                'available_quests' => $this->questModel->getVisibleForStudent(
                    $_SESSION['user_id'],
                    ($student['year_level'] ?? null)
                ),
                'accepted_quests' => $this->questModel->getAcceptedForStudent($_SESSION['user_id'])
            ];
            $this->view('student/quests/index', $data);
            return;
        }

        $acceptance = $this->acceptanceModel->getByUserQuest($_SESSION['user_id'], $questId);
        if (!$acceptance || ($acceptance['status'] ?? '') !== 'accepted') {
            $this->redirect('student/quests?error=not_accepted');
        }

        $existing = $this->submissionModel->getByUserQuest($_SESSION['user_id'], $questId);
        $proofText = trim($_POST['proof_text'] ?? '');
        $proofType = $quest['proof_type'] ?? 'text';

        $uploadedFiles = $this->normalizeUploadedFiles($_FILES['proof_files'] ?? null);

        $data = [
            'error' => '',
            'available_quests' => $this->questModel->getVisibleForStudent(
                $_SESSION['user_id'],
                ($this->userModel->getById($_SESSION['user_id'])['year_level'] ?? null)
            ),
            'accepted_quests' => $this->questModel->getAcceptedForStudent($_SESSION['user_id']),
            'open_submit_modal' => true,
            'submit_quest_id' => $questId,
            'proof_text' => $proofText
        ];

        if ($proofType === 'text' || $proofType === 'image_text') {
            if ($proofText === '') {
                $data['error'] = 'Proof text is required.';
            }
        }

        if ($proofType === 'image' || $proofType === 'image_text') {
            if (count($uploadedFiles) !== 1) {
                $data['error'] = 'Please upload exactly one image.';
            }
        }

        if ($proofType === 'multi_image') {
            if (count($uploadedFiles) < 1) {
                $data['error'] = 'Please upload at least one image.';
            }
        }

        if ($proofType === 'none') {
            $proofText = null;
        }

        if ($existing && $existing['status'] !== 'rejected') {
            $data['error'] = 'You already submitted this quest.';
        }

        if ($data['error'] === '') {
            $submissionId = null;
            if ($existing && $existing['status'] === 'rejected') {
                $saved = $this->submissionModel->resubmit($existing['submission_id'], $proofType, $proofText ?: null);
                $submissionId = $existing['submission_id'];
            } else {
                $submissionId = $this->submissionModel->create(
                    $_SESSION['user_id'],
                    $questId,
                    $proofType,
                    $proofText ?: null
                );
                $saved = $submissionId !== false;
            }

            if ($saved) {
                if ($existing && $existing['status'] === 'rejected') {
                    $this->removeSubmissionFiles($submissionId);
                }

                if (!empty($uploadedFiles) && in_array($proofType, ['image', 'image_text', 'multi_image'], true)) {
                    $fileResult = $this->storeSubmissionFiles($submissionId, $uploadedFiles);
                    if (!$fileResult['success']) {
                        $data['error'] = $fileResult['error'];
                    } else {
                        $this->submissionModel->addFiles($submissionId, $fileResult['paths']);
                    }
                }
            } else {
                $data['error'] = 'Submission failed. Please try again.';
            }
        }

        if ($data['error'] === '') {
            $this->redirect('student/submissions?success=submitted');
            return;
        }

        $this->view('student/quests/index', $data);
    }

    public function submissions() {
        $this->requireStudent();

        $submissions = $this->submissionModel->getForUser($_SESSION['user_id']);
        $this->view('student/submissions/index', [
            'submissions' => $submissions
        ]);
    }

    public function rewards() {
        $this->requireStudent();

        $user = $this->userModel->getById($_SESSION['user_id']);
        $rewards = $this->rewardModel->getAvailable();
        $redemptions = $this->redemptionModel->getByUser($_SESSION['user_id']);

        $redemptionMap = [];
        foreach ($redemptions as $redemption) {
            if (!isset($redemptionMap[$redemption['reward_id']])) {
                $redemptionMap[$redemption['reward_id']] = $redemption;
            }
        }

        $this->view('student/rewards/index', [
            'user_points' => $user['total_points'] ?? 0,
            'rewards' => $rewards,
            'redemption_map' => $redemptionMap
        ]);
    }

    public function requestReward($rewardId = null) {
        $this->requireStudent();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $rewardId === null) {
            $this->redirect('student/rewards');
        }

        $reward = $this->rewardModel->getById($rewardId);
        if (!$reward || $reward['status'] !== 'available') {
            $this->redirect('student/rewards?error=not_available');
        }

        $existing = $this->redemptionModel->getActiveRequest($_SESSION['user_id'], $rewardId);
        if ($existing) {
            $this->redirect('student/rewards?error=already_requested');
        }

        $user = $this->userModel->getById($_SESSION['user_id']);
        $points = (int) ($user['total_points'] ?? 0);
        if ($points < (int) $reward['required_points']) {
            $this->redirect('student/rewards?error=not_enough_points');
        }

        $created = $this->redemptionModel->create($_SESSION['user_id'], $rewardId);
        if ($created) {
            $this->redirect('student/rewards?success=requested');
        }

        $this->redirect('student/rewards?error=failed');
    }

    public function redemptions() {
        $this->requireStudent();

        $redemptions = $this->redemptionModel->getByUser($_SESSION['user_id']);
        $this->view('student/redemptions/index', [
            'redemptions' => $redemptions
        ]);
    }

    public function leaderboard() {
        $this->requireStudent();

        $leaderboardPoints = $this->userModel->getLeaderboard('points');
        $leaderboardQuests = $this->userModel->getLeaderboard('quests');
        
        $this->view('student/leaderboard', [
            'leaderboard_points' => $leaderboardPoints,
            'leaderboard_quests' => $leaderboardQuests
        ]);
    }

    private function requireStudent() {
        if (!$this->isStudent()) {
            $this->redirect('auth/login');
        }

        $user = $this->userModel->getById($_SESSION['user_id']);
        if (!$user || (int) ($user['is_active'] ?? 1) !== 1) {
            session_unset();
            session_destroy();
            $this->redirect('auth/login?error=inactive');
        }

        if (!empty($_SESSION['must_change_password'])) {
            $this->redirect('auth/changePassword?first=1');
        }
    }

    private function isStudent() {
        return isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'student';
    }

    private function normalizeUploadedFiles($files) {
        $normalized = [];
        if (!$files || empty($files['name']) || !is_array($files['name'])) {
            return $normalized;
        }

        foreach ($files['name'] as $index => $name) {
            if ($name === '') {
                continue;
            }

            $normalized[] = [
                'name' => $name,
                'type' => $files['type'][$index] ?? '',
                'tmp_name' => $files['tmp_name'][$index] ?? '',
                'error' => $files['error'][$index] ?? UPLOAD_ERR_NO_FILE,
                'size' => $files['size'][$index] ?? 0
            ];
        }

        return $normalized;
    }

    private function storeSubmissionFiles($submissionId, $files) {
        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/submissions';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
            chmod($uploadDir, 0777);
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $stored = [];

        foreach ($files as $file) {
            if ($file['error'] !== UPLOAD_ERR_OK) {
                return ['success' => false, 'error' => 'Upload failed. Please try again.'];
            }

            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, $allowedExtensions, true)) {
                return ['success' => false, 'error' => 'Only image files are allowed.'];
            }

            if (!is_uploaded_file($file['tmp_name'])) {
                return ['success' => false, 'error' => 'Invalid upload detected.'];
            }

            $safeName = 'submission_' . $submissionId . '_' . uniqid('', true) . '.' . $extension;
            $targetPath = $uploadDir . '/' . $safeName;
            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                return ['success' => false, 'error' => 'Failed to save uploaded file.'];
            }

            $stored[] = 'uploads/submissions/' . $safeName;
        }

        return ['success' => true, 'paths' => $stored];
    }

    private function removeSubmissionFiles($submissionId) {
        $files = $this->submissionModel->getFilesBySubmissionIds([$submissionId]);
        if (!empty($files[$submissionId])) {
            foreach ($files[$submissionId] as $path) {
                $absolutePath = dirname(__DIR__, 2) . '/public/' . $path;
                if (file_exists($absolutePath)) {
                    unlink($absolutePath);
                }
            }
        }

        $this->submissionModel->deleteFiles($submissionId);
    }
}
?>