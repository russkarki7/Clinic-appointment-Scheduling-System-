<?php
include "../config/db.php";

$message = "";

// Fetch patients & doctors
$patients = $conn->query("SELECT * FROM patients ORDER BY name ASC");
$doctors = $conn->query("SELECT * FROM doctors ORDER BY name ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
$patient_id = intval($_POST['patient_id']);
    $doctor_id = intval($_POST['doctor_id']);
    $date = date("Y-m-d", strtotime($_POST['appointment_date']));
    $start = date("H:i:s", strtotime($_POST['start_time']));
    $end = date("H:i:s", strtotime($_POST['end_time']));

    if ($start >= $end) {
        $message = "End time must be after start time.";
    } else {
        // Check overlapping appointments
        $check = $conn->prepare("
            SELECT appointment_id 
            FROM appointments 
            WHERE doctor_id=? AND appointment_date=? 
              AND NOT (end_time <= ? OR start_time >= ?)
        ");
        $check->bind_param("isss", $doctor_id, $date, $start, $end);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {
            $message = "Doctor already has an overlapping appointment.";
        } else {
            $stmt = $conn->prepare("
                INSERT INTO appointments (patient_id, doctor_id, appointment_date, start_time, end_time) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("iisss", $patient_id, $doctor_id, $date, $start, $end);
            if ($stmt->execute()) {
                $message = "Appointment booked successfully!";
            } else {
                $message = "DB Error: " . $stmt->error;
            }
        }
    }
    } catch(Exception $ex) {
        var_dump($ex);
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="container">
    <header>
        <h1>Book Appointment</h1>
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
                    <option value="<?php echo $p['patient_id']; ?>"><?php echo htmlspecialchars($p['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </label>

        <label>
            Doctor:
            <select name="doctor_id" required>
                <?php while($d = $doctors->fetch_assoc()): ?>
                    <option value="<?php echo $d['doctor_id']; ?>">
                        <?php echo htmlspecialchars($d['name']); ?> (<?php echo htmlspecialchars($d['specialization']); ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </label>

        <label>
            Date:
            <input type="date" name="appointment_date" required>
        </label>

        <label>
            Start Time:
            <input type="time" name="start_time" required>
        </label>

        <label>
            End Time:
            <input type="time" name="end_time" required>
        </label>

        <button type="submit" style="background:#4a6cf7; color:#fff; padding:10px; border:none; border-radius:5px; cursor:pointer;">
            Book Appointment
        </button>
    </form>
</main>
</body>
</html>
