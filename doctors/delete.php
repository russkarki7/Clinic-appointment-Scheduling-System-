<?php
include "../config/db.php";

// Get doctor ID from URL
$doctor_id = intval($_GET['id'] ?? 0);

if ($doctor_id > 0) {
    $stmt = $conn->prepare("DELETE FROM doctors WHERE doctor_id=?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect to list page
header("Location: list.php");
exit;
?>

