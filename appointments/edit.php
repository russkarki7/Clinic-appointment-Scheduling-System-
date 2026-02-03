<?php
include "../config/db.php";

$message = "";
$appointment_id = intval($_GET['id'] ?? 0);
if ($appointment_id <= 0) die("Invalid appointment ID");

// Fetch patients & doctors
$patients = $conn->query("SELECT * FROM patients ORDER BY name ASC");
$doctors = $conn->query("SELECT * FROM doctors ORDER BY name ASC");

// Fetch existing appointment
$stmt = $conn->prepare("SELECT * FROM appointments WHERE appointment_id=?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$appointment = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$appointment) die("Appointment not found");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = intval($_POST['patient_id']);
    $doctor_id = intval($_POST['doctor_id']);
    $date = date("Y-m-d", strtotime($_POST['appointment_date']));
    $start_time = date("H:i:s", strtotime($_POST['start_time']));
    $end_time = date("H:i:s", strtotime($_POST['end_time']));

    if ($start_time >= $end_time) {
        $message = "End time must be after start time.";
    } else {
        $stmt = $conn->prepare("
            SELECT * FROM appointments
            WHERE doctor_id=? AND appointment_date=? AND appointment_id<>? 
              AND NOT (end_time <= ? OR start_time >= ?)
        ");
        $stmt->bind_param("isiss", $doctor_id, $date, $appointment_id, $start_time, $end_time);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $message = "Doctor already has an overlapping appointment.";
        } else {
            $stmt = $conn->prepare("
                UPDATE appointments
                SET patient_id=?, doctor_id=?, appointment_date=?, start_time=?, end_time=?
                WHERE appointment_id=?
            ");
            $stmt->bind_param("iisssi", $patient_id, $doctor_id, $date, $start_time, $end_time, $appointment_id);
            if ($stmt->execute()) {
                $message = "Appointment updated successfully!";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
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
                    <option value="<?php echo $p['patient_id']; ?>" <?php if($p['patient_id']==$appointment['patient_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($p['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label>

        <label>
            Doctor:
            <select name="doctor_id" required>
                <?php while($d = $doctors->fetch_assoc()): ?>
                    <option value="<?php echo $d['doctor_id']; ?>" <?php if($d['doctor_id']==$appointment['doctor_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($d['name']); ?> (<?php echo htmlspecialchars($d['specialization']); ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </label>

        <label>
            Date:
            <input type="date" name="appointment_date" value="<?php echo $appointment['appointment_date']; ?>" required>
        </label>

        <label>
            Start Time:
            <input type="time" name="start_time" value="<?php echo $appointment['start_time']; ?>" required>
        </label>

        <label>
            End Time:
            <input type="time" name="end_time" value="<?php echo $appointment['end_time']; ?>" required>
        </label>

        <button type="submit" style="background:#4a6cf7; color:#fff; padding:10px; border:none; border-radius:5px; cursor:pointer;">
            Update Appointment
        </button>
    </form>
</main>
</body>
</html>
