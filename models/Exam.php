<?php
require_once __DIR__ . '/../config/database.php';

class Exam {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($course_id, $title, $description) {
        $stmt = $this->pdo->prepare("INSERT INTO exams (course_id, title, description) VALUES (?, ?, ?)");
        return $stmt->execute([$course_id, $title, $description]);
    }

    public function listAll($course_id = null) {
        if($course_id){
            $stmt = $this->pdo->prepare("SELECT * FROM exams WHERE course_id=?");
            $stmt->execute([$course_id]);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM exams");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function submit($exam_id, $student_id, $answers) {
        $stmt = $this->pdo->prepare("INSERT INTO exam_submissions (exam_id, student_id, answers) VALUES (?, ?, ?)");
        return $stmt->execute([$exam_id, $student_id, json_encode($answers)]);
    }
}
?>
