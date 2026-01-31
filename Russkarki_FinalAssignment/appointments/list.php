<?php
include "../config/db.php";

// Fetch all appointments with patient & doctor names
$sql = "SELECT a.appointment_id, p.name AS patient_name, d.name AS doctor_name, d.specialization, a.appointment_date, a.appointment_time
        FROM appointments a
        JOIN patients p ON a.patient_id = p.patient_id
        JOIN doctors d ON a.doctor_id = d.doctor_id
        ORDER BY a.appointment_date ASC, a.appointment_time ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointments List</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        table { width:100%; border-collapse: collapse; margin-top:20px; }
        th, td { border:1px solid #ccc; padding:12px; text-align:left; }
        th { background:#009688; color:#fff; }
        tr:nth-child(even){background:#f4f4f4;}
        a.action-btn { text-decoration:none; padding:6px 12px; border-radius:5px; color:#fff; margin-right:5px; }
        a.edit-btn { background:#4a6cf7; }
        a.delete-btn { background:#e74c3c; }
    </style>
</head>
<body>

<main class="container">
    <header>
        <h1>All Appointments</h1>
        <a href="add.php" style="display:inline-block; margin-top:10px;">+ Book Appointment</a>
        <a href="../index.php" style="display:inline-block; margin-top:10px;">← Dashboard</a>
    </header>

    <table>
        <tr>
            <th>ID</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Specialization</th>
            <th>Date</th>
            <th>Time</th>
            <th>Actions</th>
        </tr>
        <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['appointment_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['specialization']); ?></td>
                    <td><?php echo $row['appointment_date']; ?></td>
                    <td><?php echo $row['appointment_time']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['appointment_id']; ?>" class="action-btn edit-btn">Edit</a>
                        <a href="delete.php?id=<?php echo $row['appointment_id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to cancel this appointment?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7" style="text-align:center;">No appointments found</td></tr>
        <?php endif; ?>
    </table>
</main>

</body>
</html>
