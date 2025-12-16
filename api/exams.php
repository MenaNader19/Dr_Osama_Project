<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../models/Exam.php';
require_once __DIR__ . '/../config/database.php';

$examModel = new Exam($pdo);
$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents("php://input"), true);

switch ($action) {
    case 'create':
        $course_id = $input['course_id'] ?? 0;
        $title = $input['title'] ?? '';
        $desc = $input['description'] ?? '';
        if($course_id && $title){
            $examModel->create($course_id, $title, $desc);
            sendResponse('success', 'Exam created');
        }
        sendResponse('error', 'Course ID and title required');
        break;

    case 'list':
        $course_id = $input['course_id'] ?? null;
        $exams = $examModel->listAll($course_id);
        sendResponse('success', 'Exams fetched', $exams);
        break;

    case 'submit':
        $exam_id = $input['exam_id'] ?? 0;
        $student_id = $input['student_id'] ?? 1; // replace with session user
        $answers = $input['answers'] ?? [];
        if($exam_id){
            $examModel->submit($exam_id, $student_id, $answers);
            sendResponse('success', 'Exam submitted');
        }
        sendResponse('error', 'Exam ID required');
        break;

    default:
        sendResponse('error', 'Invalid action');
}
?>
