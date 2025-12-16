<?php
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';

$userModel = new User($pdo);
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $action = $_GET['action'] ?? '';

    $input = json_decode(file_get_contents("php://input"), true);

    switch ($action) {
        case 'register':
            $username = $input['username'] ?? '';
            $email = $input['email'] ?? '';
            $password = $input['password'] ?? '';
            $role = $input['role'] ?? 'student';

            if ($username && $email && $password) {
                $result = $userModel->register($username, $email, $password, $role);
                if ($result) sendResponse('success', 'User registered successfully');
                else sendResponse('error', 'Email already exists');
            } else {
                sendResponse('error', 'All fields are required');
            }
            break;

        case 'login':
            $email = $input['email'] ?? '';
            $password = $input['password'] ?? '';
            if ($email && $password) {
                $user = $userModel->login($email, $password);
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    sendResponse('success', 'Logged in successfully', ['user' => $user]);
                } else {
                    sendResponse('error', 'Invalid email or password');
                }
            } else {
                sendResponse('error', 'Email and password are required');
            }
            break;

        case 'logout':
            session_unset();
            session_destroy();
            sendResponse('success', 'Logged out successfully');
            break;

        default:
            sendResponse('error', 'Invalid action');
    }
} else {
    sendResponse('error', 'Invalid request method');
}
?>
