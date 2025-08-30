<?php
class AppointmentStatus {
    public function setStatus($pdo, $id, $status) {
        $stmt = $pdo->prepare("UPDATE appointments SET status_id = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function getTitle($pdo, $id) {
        $stmt = $pdo->prepare("SELECT title FROM appointments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
