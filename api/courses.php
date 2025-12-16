<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../config/database.php';

$courseModel = new Course($pdo);
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents("php://input"), true);

switch ($action) {
    case 'create':
        $title = $input['title'] ?? '';
        $desc = $input['description'] ?? '';
        $teacher_id = $input['teacher_id'] ?? 1; 
        if($title){
            $courseModel->create($title, $desc, $teacher_id);
            sendResponse('success', 'Course created successfully');
        }
        sendResponse('error', 'Title is required');
        break;

    case 'list':
        $courses = $courseModel->listAll();
        sendResponse('success', 'Courses fetched', $courses);
        break;

    case 'update':
        $id = $input['id'] ?? 0;
        $title = $input['title'] ?? '';
        $desc = $input['description'] ?? '';
        if($id && $title){
            $courseModel->update($id, $title, $desc);
            sendResponse('success', 'Course updated');
        }
        sendResponse('error', 'ID and title required');
        break;

    case 'delete':
        $id = $input['id'] ?? 0;
        if($id){
            $courseModel->delete($id);
            sendResponse('success', 'Course deleted');
        }
        sendResponse('error', 'ID required');
        break;

    default:
        sendResponse('error', 'Invalid action');
}
?>
