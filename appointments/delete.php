<?php
include "../config/db.php";

$appointment_id = intval($_GET['id'] ?? 0);

if ($appointment_id > 0) {
    $stmt = $conn->prepare("DELETE FROM appointments WHERE appointment_id=?");
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: list.php");
exit;
?>
