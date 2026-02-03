<?php
include "../config/db.php";

// Get patient ID from URL
$patient_id = intval($_GET['id'] ?? 0);

if ($patient_id > 0) {
    $stmt = $conn->prepare("DELETE FROM patients WHERE patient_id=?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to list.php
header("Location: list.php");
exit;
?>
