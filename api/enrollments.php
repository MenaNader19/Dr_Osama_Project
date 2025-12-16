<?php
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../config/database.php';

$enrollModel = new Enrollment($pdo);
$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents("php://input"), true);

switch ($action) {
    case 'enroll':
        $student_id = $input['student_id'] ?? 1; // replace with session user
        $course_id = $input['course_id'] ?? 0;
        if($course_id){
            $enrollModel->enroll($student_id, $course_id);
            sendResponse('success', 'Enrolled successfully');
        }
        sendResponse('error', 'Course ID required');
        break;

    case 'list':
        $student_id = $input['student_id'] ?? null;
        $enrollments = $enrollModel->listEnrollments($student_id);
        sendResponse('success', 'Enrollments fetched', $enrollments);
        break;

    default:
        sendResponse('error', 'Invalid action');
}
?>
