<?php
include "../config/db.php";

$message = "";

// Get patient ID from URL
$patient_id = intval($_GET['id'] ?? 0);

// Fetch existing data
$stmt = $conn->prepare("SELECT * FROM patients WHERE patient_id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();
$stmt->close();

if (!$patient) {
    die("❌ Patient not found");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if ($name == "" || $email == "" || $phone == "") {
        $message = "All fields are required!";
    } else {
        $stmt = $conn->prepare("UPDATE patients SET name=?, email=?, phone=? WHERE patient_id=?");
        $stmt->bind_param("sssi", $name, $email, $phone, $patient_id);
        if ($stmt->execute()) {
            $message = "✅ Patient updated successfully!";
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
    <title>Edit Patient</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<main class="container">
    <header>
        <h1>Edit Patient</h1>
        <a href="list.php" style="display:inline-block; margin-top:10px;">← Back to Patients List</a>
    </header>

    <?php if($message != ""): ?>
        <p style="color:green; font-weight:bold;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post" style="margin-top:20px; display:flex; flex-direction:column; gap:15px; max-width:400px;">
        <label>
            Name:
            <input type="text" name="name" value="<?php echo htmlspecialchars($patient['name']); ?>" required>
        </label>

        <label>
            Email:
            <input type="email" name="email" value="<?php echo htmlspecialchars($patient['email']); ?>" required>
        </label>

        <label>
            Phone:
            <input type="text" name="phone" value="<?php echo htmlspecialchars($patient['phone']); ?>" required>
        </label>

        <button type="submit" style="background:#4a6cf7; color:#fff; padding:10px; border:none; border-radius:5px; cursor:pointer;">
            Update Patient
        </button>
    </form>
</main>

</body>
</html>
