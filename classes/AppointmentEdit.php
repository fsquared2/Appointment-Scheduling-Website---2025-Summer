<?php
class AppointmentEdit {
    public function getById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT id, title, notes, start_at, end_at, created_by, assigned_to FROM appointments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($pdo, $id, $title, $notes, $start, $end) {
        $stmt = $pdo->prepare(
            "UPDATE appointments SET title = ?, notes = ?, start_at = ?, end_at = ? WHERE id = ?"
        );
        return $stmt->execute([$title, $notes, $start, $end, $id]);
    }
}
