<?php
include "../config/db.php";

$message = "";

// Get doctor ID
$doctor_id = intval($_GET['id'] ?? 0);

// Fetch existing data
$stmt = $conn->prepare("SELECT * FROM doctors WHERE doctor_id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();
$stmt->close();

if (!$doctor) {
    die("❌ Doctor not found");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $specialization = trim($_POST['specialization']);

    if ($name == "" || $specialization == "") {
        $message = "All fields are required!";
    } else {
        $stmt = $conn->prepare("UPDATE doctors SET name=?, specialization=? WHERE doctor_id=?");
        $stmt->bind_param("ssi", $name, $specialization, $doctor_id);
        if ($stmt->execute()) {
            $message = "✅ Doctor updated successfully!";
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
    <title>Edit Doctor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<main class="container">
    <header>
        <h1>Edit Doctor</h1>
        <a href="list.php" style="display:inline-block; margin-top:10px;">← Back to Doctors List</a>
    </header>

    <?php if($message != ""): ?>
        <p style="color:green; font-weight:bold;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post" style="margin-top:20px; display:flex; flex-direction:column; gap:15px; max-width:400px;">
        <label>
            Name:
            <input type="text" name="name" value="<?php echo htmlspecialchars($doctor['name']); ?>" required>
        </label>

        <label>
            Specialization:
            <input type="text" name="specialization" value="<?php echo htmlspecialchars($doctor['specialization']); ?>" required>
        </label>

        <button type="submit" style="background:#4a6cf7; color:#fff; padding:10px; border:none; border-radius:5px; cursor:pointer;">
            Update Doctor
        </button>
    </form>
</main>

</body>
</html>
