<?php
include "../config/db.php";

$appointment_id = intval($_GET['id'] ?? 0);
if ($appointment_id <= 0) {
    die("Invalid appointment ID");
}

$stmt = $conn->prepare("DELETE FROM appointments WHERE appointment_id=?");
$stmt->bind_param("i", $appointment_id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: list.php");
    exit;
} else {
    die("Error deleting appointment: " . $stmt->error);
}
