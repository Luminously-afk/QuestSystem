<?php
class StudentController extends Controller {
    private $questModel;
    private $submissionModel;
    private $rewardModel;
    private $redemptionModel;
    private $userModel;

    public function __construct() {
        $this->questModel = new Quest();
        $this->submissionModel = new Submission();
        $this->rewardModel = new Reward();
        $this->redemptionModel = new Redemption();
        $this->userModel = new User();
    }

    public function index() {
        $this->requireStudent();

        $stats = $this->userModel->getStudentStats($_SESSION['user_id']);
        $this->view('student/index', [
            'name' => $_SESSION['full_name'] ?? 'Student',
            'stats' => $stats
        ]);
    }

    public function quests() {
        $this->requireStudent();

        $quests = $this->questModel->getActiveWithStatus($_SESSION['user_id']);
        $this->view('student/quests/index', [
            'quests' => $quests
        ]);
    }

    public function submit($questId = null) {
        $this->requireStudent();

        if ($questId === null) {
            $this->redirect('student/quests');
        }

        $quest = $this->questModel->getActiveById($questId);
        if (!$quest) {
            $this->redirect('student/quests');
        }

        $existing = $this->submissionModel->getByUserQuest($_SESSION['user_id'], $questId);

        $data = [
            'error' => '',
            'quest' => $quest,
            'existing' => $existing
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proofText = trim($_POST['proof_text'] ?? '');

            if ($proofText === '') {
                $data['error'] = 'Proof text is required.';
            } elseif ($existing && $existing['status'] !== 'rejected') {
                $data['error'] = 'You already submitted this quest.';
            } else {
                if ($existing && $existing['status'] === 'rejected') {
                    $saved = $this->submissionModel->resubmit($existing['submission_id'], $proofText);
                } else {
                    $saved = $this->submissionModel->create($_SESSION['user_id'], $questId, $proofText);
                }

                if ($saved) {
                    $this->redirect('student/submissions?success=submitted');
                } else {
                    $data['error'] = 'Submission failed. Please try again.';
                }
            }
        }

        $this->view('student/quests/submit', $data);
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

        $leaderboard = $this->userModel->getLeaderboard();
        $this->view('student/leaderboard', [
            'leaderboard' => $leaderboard
        ]);
    }

    private function requireStudent() {
        if (!$this->isStudent()) {
            $this->redirect('auth/login');
        }

        if (!empty($_SESSION['must_change_password'])) {
            $this->redirect('auth/changePassword?first=1');
        }
    }

    private function isStudent() {
        return isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'student';
    }
}
?>