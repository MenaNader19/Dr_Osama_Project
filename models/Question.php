<?php

class Question {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // CREATE QUESTION (already working)
    public function create($exam_id, $text, $type, $marks, $options = []) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO questions (exam_id, question_text, question_type, marks)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$exam_id, $text, $type, $marks]);
        $question_id = $this->pdo->lastInsertId();

        if ($type === 'mcq') {
            $optStmt = $this->pdo->prepare(
                "INSERT INTO question_options (question_id, option_text, is_correct)
                 VALUES (?, ?, ?)"
            );

            foreach ($options as $opt) {
                $isCorrect = (!empty($opt['is_correct'])) ? 1 : 0;
                $optStmt->execute([$question_id, $opt['text'], $isCorrect]);
            }
        }

        return true;
    }

    // ✅ FETCH ALL QUESTIONS
    public function getAll() {
        $stmt = $this->pdo->query(
            "SELECT q.id, q.exam_id, q.question_text, q.question_type, q.marks,
                    e.title AS exam
             FROM questions q
             JOIN exams e ON e.id = q.exam_id"
        );

        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($questions as &$q) {
            $optStmt = $this->pdo->prepare(
                "SELECT id, option_text, is_correct
                 FROM question_options
                 WHERE question_id = ?"
            );
            $optStmt->execute([$q['id']]);
            $q['options'] = $optStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $questions;
    }

    // FETCH QUESTIONS BY EXAM
    public function getByExam($exam_id) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM questions WHERE exam_id = ?"
        );
        $stmt->execute([$exam_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
