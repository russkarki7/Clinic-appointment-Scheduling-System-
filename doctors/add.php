<?php
include "../config/db.php";

// Initialize $message to avoid undefined variable warning
$message = "";  // ✅ Add this line

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $specialization = trim($_POST['specialization']);

    if ($name == "" || $specialization == "") {
        $message = "All fields are required!";
    } else {
        // Check for duplicate doctor (same name)
        $stmt = $conn->prepare("SELECT * FROM doctors WHERE name=?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            $message = "❌ A doctor with this name already exists!";
        } else {
            // Insert new doctor
            $stmt = $conn->prepare("INSERT INTO doctors (name, specialization) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $specialization);

            if ($stmt->execute()) {
                $message = "✅ Doctor added successfully!";
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
    <title>Add Doctor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<main class="container">
    <header>
        <h1>Add New Doctor</h1>
        <a href="../index.php" style="display:inline-block; margin-top:10px;">← Back to Dashboard</a>
    </header>

    <?php if($message != ""): ?>
        <p style="color:green; font-weight:bold;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post" style="margin-top:20px; display:flex; flex-direction:column; gap:15px; max-width:400px;">
        <label>
            Name:
            <input type="text" name="name" placeholder="Doctor Name" required>
        </label>

        <label>
            Specialization:
            <input type="text" name="specialization" placeholder="Specialization" required>
        </label>

        <button type="submit" style="background:#009688; color:#fff; padding:10px; border:none; border-radius:5px; cursor:pointer;">
            Add Doctor
        </button>
    </form>
</main>

</body>
</html>
