<?php
include "../config/db.php";

// Fetch all patients
$result = $conn->query("SELECT * FROM patients ORDER BY patient_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patients List</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }
        th {
            background: #009688;
            color: #fff;
        }
        tr:nth-child(even) {
            background: #f4f4f4;
        }
        a.action-btn {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 5px;
            color: #fff;
            margin-right: 5px;
        }
        a.edit-btn { background: #4a6cf7; }
        a.delete-btn { background: #e74c3c; }
    </style>
</head>
<body>

<main class="container">
    <header>
        <h1>All Patients</h1>
        <a href="add.php" style="display:inline-block; margin-top:10px;">+ Add New Patient</a>
        <a href="../index.php" style="display:inline-block; margin-top:10px;">← Dashboard</a>
    </header>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
        <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['patient_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['patient_id']; ?>" class="action-btn edit-btn">Edit</a>
                        <a href="delete.php?id=<?php echo $row['patient_id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this patient?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">No patients found</td></tr>
        <?php endif; ?>
    </table>
</main>

</body>
</html>
