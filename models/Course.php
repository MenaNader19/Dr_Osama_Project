<?php
require_once __DIR__ . '/../config/database.php';

class Course {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($title, $description, $teacher_id) {
        $stmt = $this->pdo->prepare("INSERT INTO courses (title, description, teacher_id) VALUES (?, ?, ?)");
        return $stmt->execute([$title, $description, $teacher_id]);
    }

    public function listAll() {
        $stmt = $this->pdo->query("SELECT c.*, u.username as teacher FROM courses c JOIN users u ON c.teacher_id = u.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $title, $description) {
        $stmt = $this->pdo->prepare("UPDATE courses SET title=?, description=? WHERE id=?");
        return $stmt->execute([$title, $description, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM courses WHERE id=?");
        return $stmt->execute([$id]);
    }
}
?>
