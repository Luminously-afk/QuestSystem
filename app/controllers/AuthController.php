<?php
class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index() {
        $this->login();
    }

    public function login() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectByRole($_SESSION['role'] ?? 'student');
        }

        $data = [
            'error' => '',
            'success' => '',
            'info' => '',
            'old' => ['identifier' => '']
        ];

        if (isset($_GET['registered'])) {
            $data['success'] = 'Registration successful. Please log in.';
        }

        if (isset($_GET['error']) && $_GET['error'] === 'registration_disabled') {
            $data['info'] = 'Registration is disabled. Please contact the admin for an account.';
        }

        if (isset($_GET['error']) && $_GET['error'] === 'inactive') {
            $data['error'] = 'Your account is deactivated. Please contact the admin.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifier = trim($_POST['identifier'] ?? '');
            $password = $_POST['password'] ?? '';
            $data['old']['identifier'] = $identifier;

            if ($identifier === '' || $password === '') {
                $data['error'] = 'Email or student ID and password are required.';
            } else {
                if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
                    $user = $this->userModel->findByEmail($identifier);
                } elseif ($this->isValidStudentId($identifier)) {
                    $user = $this->userModel->findByStudentId($identifier);
                } else {
                    $user = null;
                }

                if (!$user || !password_verify($password, $user['password'])) {
                    $data['error'] = 'Invalid email or password.';
                } elseif ((int) ($user['is_active'] ?? 1) !== 1) {
                    $data['error'] = 'Your account is deactivated. Please contact the admin.';
                } else {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['must_change_password'] = (int) ($user['must_change_password'] ?? 0) === 1;

                    if ($_SESSION['must_change_password']) {
                        $this->redirect('auth/changePassword?first=1');
                    }

                    $this->redirectByRole($user['role']);
                }
            }
        }

        $this->view('auth/login', $data);
    }

    public function register() {
        $this->redirect('auth/login?error=registration_disabled');
    }

    public function changePassword() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }

        $data = [
            'error' => '',
            'success' => '',
            'first_login' => isset($_GET['first'])
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($newPassword === '' || $confirmPassword === '') {
                $data['error'] = 'All fields are required.';
            } elseif (strlen($newPassword) < 6) {
                $data['error'] = 'Password must be at least 6 characters.';
            } elseif ($newPassword !== $confirmPassword) {
                $data['error'] = 'Passwords do not match.';
            } else {
                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $updated = $this->userModel->updatePassword($_SESSION['user_id'], $passwordHash);
                if ($updated) {
                    $_SESSION['must_change_password'] = false;
                    $this->redirectByRole($_SESSION['role'] ?? 'student');
                } else {
                    $data['error'] = 'Password update failed. Please try again.';
                }
            }
        }

        $this->view('auth/change_password', $data);
    }

    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('auth/login');
    }

    private function redirectByRole($role) {
        if ($role === 'admin') {
            $this->redirect('admin/index');
        }
        $this->redirect('student/index');
    }

    private function isValidStudentId($value) {
        return preg_match('/^[A-Za-z0-9]{4}-\d{4}$/', $value) === 1;
    }
}
?>