<?php
class AppointmentDelete {
    public function getById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT id, title, created_by, assigned_to FROM appointments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
