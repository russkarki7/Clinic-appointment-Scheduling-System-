<?php
include "config/db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Appointment System</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header>
    <h1>Clinic Appointment Scheduling System</h1>
    <p>Manage patients, doctors, and appointments efficiently</p>
</header>

<main class="container">

    <section>
        <h2>Patients</h2>
        <nav class="menu">
            <a href="patients/add.php">Add Patient</a>
            <a href="patients/list.php">View Patients</a>
        </nav>
    </section>

    <section>
        <h2>Doctors</h2>
        <nav class="menu">
            <a href="doctors/add.php">Add Doctor</a>
            <a href="doctors/list.php">View Doctors</a>
        </nav>
    </section>

    <section>
        <h2>Appointments</h2>
        <nav class="menu">
            <a href="appointments/add.php">Book Appointment</a>
            <a href="appointments/list.php">View Appointments</a>
        </nav>
    </section>

</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Clinic Appointment System</p>
</footer>

</body>
</html>
