<?php
include "../config/db.php";

$message = "";

// Get appointment ID
$appointment_id = intval($_GET['id'] ?? 0);

// Fetch patients & doctors
$patients = $conn->query("SELECT * FROM patients ORDER BY name ASC");
$doctors = $conn->query("SELECT * FROM doctors ORDER BY name ASC");

// Fetch existing appointment
$stmt = $conn->prepare("SELECT * FROM appointments WHERE appointment_id=?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$appointment = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$appointment) die("❌ Appointment not found");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = intval($_POST['patient_id']);
    $doctor_id = intval($_POST['doctor_id']);
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];

    // Check overlapping excluding current appointment
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE doctor_id=? AND appointment_date=? AND appointment_time=? AND appointment_id<>?");
    $stmt->bind_param("issi", $doctor_id, $date, $time, $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "❌ This doctor already has an appointment at this date and time!";
    } else {
        $stmt = $conn->prepare("UPDATE appointments SET patient_id=?, doctor_id=?, appointment_date=?, appointment_time=? WHERE appointment_id=?");
        $stmt->bind_param("iissi", $patient_id, $doctor_id, $date, $time, $appointment_id);
        if ($stmt->execute()) $message = "✅ Appointment updated successfully!";
        else $message = "❌ Error: " . $conn->error;
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Appointment</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="container">
    <header>
        <h1>Edit Appointment</h1>
        <a href="list.php" style="display:inline-block; margin-top:10px;">← Back to Appointments</a>
    </header>

    <?php if($message != ""): ?>
        <p style="color:green; font-weight:bold;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post" style="margin-top:20px; display:flex; flex-direction:column; gap:15px; max-width:400px;">
        <label>
            Patient:
            <select name="patient_id" required>
                <?php while($p = $patients->fetch_assoc()): ?>
                    <option value="<?php echo $p['patient_id']; ?>" <?php if($p['patient_id']==$appointment['patient_id']) echo 'selected'; ?>><?php echo htmlspecialchars($p['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </label>

        <label>
            Doctor:
            <select name="doctor_id" required>
                <?php while($d = $doctors->fetch_assoc()): ?>
                    <option value="<?php echo $d['doctor_id']; ?>" <?php if($d['doctor_id']==$appointment['doctor_id']) echo 'selected'; ?>><?php echo htmlspecialchars($d['name']); ?> (<?php echo htmlspecialchars($d['specialization']); ?>)</option>
                <?php endwhile; ?>
            </select>
        </label>

        <label>
            Date:
            <input type="date" name="appointment_date" value="<?php echo $appointment['appointment_date']; ?>" required>
        </label>

        <label>
            Time:
            <input type="time" name="appointment_time" value="<?php echo $appointment['appointment_time']; ?>" required>
        </label>

        <button type="submit" style="background:#4a6cf7; color:#fff; padding:10px; border:none; border-radius:5px; cursor:pointer;">
            Update Appointment
        </button>
    </form>
</main>
</body>
</html>
