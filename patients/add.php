<?php
include "../config/db.php";

// Initialize $message to avoid undefined variable warning
$message = "";  // ✅ Add this line

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if ($name == "" || $email == "" || $phone == "") {
        $message = "All fields are required!";
    } else {
        // Check for duplicates
        $stmt = $conn->prepare("SELECT * FROM patients WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            $message = "❌ A patient with this email already exists!";
        } else {
            // Insert patient
            $stmt = $conn->prepare("INSERT INTO patients (name, email, phone) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $phone);
            if ($stmt->execute()) {
                $message = "✅ Patient added successfully!";
            } else {
                $message = "❌ Error: " . $conn->error;
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
    <title>Add Patient</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<main class="container">

    <header>
        <h1>Add New Patient</h1>
        <p>Fill out the form below to add a patient.</p>
        <a href="../index.php" style="display:inline-block; margin-top:10px;">← Back to Dashboard</a>
    </header>

    <?php if($message != ""): ?>
        <p style="color:green; font-weight:bold;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post" style="margin-top:20px; display:flex; flex-direction:column; gap:15px; max-width:400px;">
        <label>
            Name:
            <input type="text" name="name" placeholder="Patient Name" required>
        </label>

        <label>
            Email:
            <input type="email" name="email" placeholder="Patient Email" required>
        </label>

        <label>
            Phone:
            <input type="text" name="phone" placeholder="Patient Phone" required>
        </label>

        <button type="submit" style="background:#009688; color:#fff; padding:10px; border:none; border-radius:5px; cursor:pointer;">
            Add Patient
        </button>
    </form>

</main>

</body>
</html>
