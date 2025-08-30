<?php
class AppointmentList {
    public function getAppointments($pdo, $userId, $role, $term = '') {
        if ($role === 'patient') {
            $stmt = $pdo->prepare(
                "SELECT a.id, a.title, a.start_at, a.end_at, s.name AS status_name,
                        p.full_name AS patient, d.full_name AS provider
                 FROM appointments a
                 JOIN appointment_status s ON s.id = a.status_id
                 JOIN users p ON p.id = a.created_by
                 JOIN users d ON d.id = a.assigned_to
                 WHERE a.created_by = ? 
                   AND (a.title LIKE ? OR a.notes LIKE ?)
                 ORDER BY a.start_at DESC"
            );
            $stmt->execute([$userId, "%$term%", "%$term%"]);
        } elseif ($role === 'provider') {
            $stmt = $pdo->prepare(
                "SELECT a.id, a.title, a.start_at, a.end_at, s.name AS status_name,
                        p.full_name AS patient, d.full_name AS provider
                 FROM appointments a
                 JOIN appointment_status s ON s.id = a.status_id
                 JOIN users p ON p.id = a.created_by
                 JOIN users d ON d.id = a.assigned_to
                 WHERE a.assigned_to = ? 
                   AND (a.title LIKE ? OR a.notes LIKE ?)
                 ORDER BY a.start_at DESC"
            );
            $stmt->execute([$userId, "%$term%", "%$term%"]);
        } else {
            $stmt = $pdo->prepare(
                "SELECT a.id, a.title, a.start_at, a.end_at, s.name AS status_name,
                        p.full_name AS patient, d.full_name AS provider
                 FROM appointments a
                 JOIN appointment_status s ON s.id = a.status_id
                 JOIN users p ON p.id = a.created_by
                 JOIN users d ON d.id = a.assigned_to
                 WHERE (a.title LIKE ? OR a.notes LIKE ?)
                 ORDER BY a.start_at DESC"
            );
            $stmt->execute(["%$term%", "%$term%"]);
        }

        return $stmt->fetchAll();
    }
}
