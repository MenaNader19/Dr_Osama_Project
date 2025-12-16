<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../models/Question.php';

$questionModel = new Question($pdo);
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // ✅ Fetch ALL questions
    if (!isset($_GET['exam_id'])) {
        $questions = $questionModel->getAll();
        sendResponse('success', 'All questions fetched', $questions);
        exit;
    }

    // Fetch by exam
    $questions = $questionModel->getByExam($_GET['exam_id']);
    sendResponse('success', 'Exam questions fetched', $questions);
    exit;
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    $questionModel->create(
        $input['exam_id'],
        $input['question_text'],
        $input['question_type'],
        $input['marks'],
        $input['options'] ?? []
    );

    sendResponse('success', 'Question created');
}
