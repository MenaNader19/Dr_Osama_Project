<?php
require_once __DIR__ . '/../config/database.php';

class Enrollment {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function enroll($student_id, $course_id) {
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO enrollments (student_id, course_id) VALUES (?, ?)");
        return $stmt->execute([$student_id, $course_id]);
    }

    public function listEnrollments($student_id = null) {
        if($student_id){
            $stmt = $this->pdo->prepare("SELECT e.*, c.title, c.description FROM enrollments e JOIN courses c ON e.course_id=c.id WHERE e.student_id=?");
            $stmt->execute([$student_id]);
        } else {
            $stmt = $this->pdo->query("SELECT e.*, u.username as student, c.title as course FROM enrollments e JOIN users u ON e.student_id=u.id JOIN courses c ON e.course_id=c.id");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
