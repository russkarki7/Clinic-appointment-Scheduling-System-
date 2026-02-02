<?php
include "../config/db.php";

// Fetch all doctors
$result = $conn->query("SELECT * FROM doctors ORDER BY doctor_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctors List</title>
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
        <h1>All Doctors</h1>
        <a href="add.php" style="display:inline-block; margin-top:10px;">+ Add New Doctor</a>
        <a href="../index.php" style="display:inline-block; margin-top:10px;">← Dashboard</a>
    </header>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Specialization</th>
            <th>Actions</th>
        </tr>
        <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['doctor_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['specialization']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['doctor_id']; ?>" class="action-btn edit-btn">Edit</a>
                        <a href="delete.php?id=<?php echo $row['doctor_id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this doctor?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4" style="text-align:center;">No doctors found</td></tr>
        <?php endif; ?>
    </table>
</main>

</body>
</html>
