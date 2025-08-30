<?php
class AppointmentCreate {
    public function getProviders($pdo) {
        $stmt = $pdo->prepare("SELECT id, full_name FROM users WHERE role = ?");
        $stmt->execute(['provider']);
        return $stmt->fetchAll();
    }

    public function create($pdo, $title, $notes, $start, $end, $createdBy, $assignedTo) {
        $stmt = $pdo->prepare(
            "INSERT INTO appointments 
             (title, notes, start_at, end_at, status_id, created_by, assigned_to)
             VALUES (?, ?, ?, ?, 0, ?, ?)"
        );
        return $stmt->execute([$title, $notes, $start, $end, $createdBy, $assignedTo]);
    }
}
