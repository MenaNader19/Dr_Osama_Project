<?php
session_start();

function sendResponse($status, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
?>
