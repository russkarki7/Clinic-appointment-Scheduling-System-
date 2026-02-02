<?php
include "../config/db.php";

$message = "";

// Fetch patients & doctors for dropdowns
$patients = $conn->query("SELECT * FROM patients ORDER BY name ASC");
$doctors = $conn->query("SELECT * FROM doctors ORDER BY name ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = intval($_POST['patient_id']);
    $doctor_id = intval($_POST['doctor_id']);
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];

    // Check for overlapping appointment
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE doctor_id=? AND appointment_date=? AND appointment_time=?");
    $stmt->bind_param("iss", $doctor_id, $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "❌ This doctor already has an appointment at this date and time!";
    } else {
        // Insert appointment
        $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $patient_id, $doctor_id, $date, $time);
        if ($stmt->execute()) {
            $message = "✅ Appointment booked successfully!";
        } else {
            $message = "❌ Error: " . $conn->error;
        }
        $stmt->close();
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
        <a href="../index.php" style="display:inline-block; margin-top:10px;">← Dashboard</a>
    </header>

    <?php if($message != ""): ?>
        <p style="color:green; font-weight:bold;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post" style="margin-top:20px; display:flex; flex-direction:column; gap:15px; max-width:400px;">
        <label>
            Patient:
            <select name="patient_id" required>
                <option value="">Select Patient</option>
                <?php while($p = $patients->fetch_assoc()): ?>
                    <option value="<?php echo $p['patient_id']; ?>"><?php echo htmlspecialchars($p['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </label>

        <label>
            Doctor:
            <select name="doctor_id" required>
                <option value="">Select Doctor</option>
                <?php while($d = $doctors->fetch_assoc()): ?>
                    <option value="<?php echo $d['doctor_id']; ?>"><?php echo htmlspecialchars($d['name']); ?> (<?php echo htmlspecialchars($d['specialization']); ?>)</option>
                <?php endwhile; ?>
            </select>
        </label>

        <label>
            Date:
            <input type="date" name="appointment_date" required>
        </label>

        <label>
            Time:
            <input type="time" name="appointment_time" required>
        </label>

        <button type="submit" style="background:#009688; color:#fff; padding:10px; border:none; border-radius:5px; cursor:pointer;">
            Book Appointment
        </button>
    </form>
</main>

</body>
</html>
