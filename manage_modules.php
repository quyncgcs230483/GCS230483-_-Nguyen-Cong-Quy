<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Include database configuration
include 'database.php';

// Handle delete action
if (isset($_GET['delete'])) {
    $module_id = $_GET['delete'];
    $delete_stmt = $pdo->prepare("DELETE FROM modules WHERE id = :module_id");
    $delete_stmt->execute(['module_id' => $module_id]);
    header("Location: manage_modules.php");
    exit;
}

// Fetch all modules from the database
$stmt = $pdo->prepare("SELECT * FROM modules");
$stmt->execute();
$modules = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Modules</title>
    <style>
        /* General styles */
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: url(https://i.imgur.com/TgtXPPS.jpeg) no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 20px auto;
            background: rgba(255, 255, 255, 0.85);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #006600;
            text-align: center;
            font-size: 32px;
            margin-bottom: 20px;
        }

        /* Button styles */
        .btn-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            color: white;
            font-size: 16px;
            padding: 15px 25px;
            transition: transform 0.2s, background 0.3s;
        }

        .btn:hover {
            transform: translateY(-3px);
        }

        .add-module-btn {
            background: linear-gradient(135deg, #ffffff 0%, #006600 100%);
        }

        .add-module-btn:hover {
            background: linear-gradient(135deg, #006600 0%, #ffffff 100%);
        }

        .back-dashboard-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .back-dashboard-btn {
            background: linear-gradient(135deg, #ffffff 0%, #006600 100%);
            color: white;
            text-align: center;
            padding: 15px 25px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            transition: transform 0.2s, background 0.3s;
            flex: 1;
            margin: 0 10px;
        }

        .back-dashboard-btn:hover {
            background: linear-gradient(135deg, #006600 0%, #ffffff 100%);
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            text-align: center;
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background: #f4f4f4;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tr:nth-child(odd) {
            background: #ffffff;
        }

        /* Action buttons */
        .action-btns a {
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 14px;
            color: white;
            transition: transform 0.2s, background 0.3s;
            cursor: pointer;
        }

        .edit-btn {
            background: linear-gradient(135deg, #73C6D9 0%, #ffffff 100%);
        }

        .edit-btn:hover {
            background: linear-gradient(135deg, #ffffff 0%, #73C6D9 100%);
        }

        .delete-btn {
            background: linear-gradient(135deg, #dc2626 0%, #ffffff 100%);
        }

        .delete-btn:hover {
            background: linear-gradient(135deg, #ffffff 0%, #dc2626 100%);
        }
    </style>

    <script>
        function confirmDelete(url) {
            if (confirm('Are you sure you want to delete this module?')) {
                window.location.href = url;
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Manage Modules</h1>

        <!-- Add New Module Button -->
        <div class="btn-container">
            <a href="add_new_modules.php" class="btn add-module-btn">Add New Module</a>
        </div>

        <!-- Modules Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Module Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($modules as $module): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($module['id']); ?></td>
                        <td><?php echo htmlspecialchars($module['module_name']); ?></td>
                        <td class="action-btns">
                            <a href="edit_module.php?id=<?php echo htmlspecialchars($module['id']); ?>" class="edit-btn">Edit</a>
                            <a href="javascript:void(0);" onclick="confirmDelete('manage_modules.php?delete=<?php echo htmlspecialchars($module['id']); ?>')" class="delete-btn">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Back to Admin Dashboard -->
        <div class="back-dashboard-container">
            <a href="admin_dashboard.php" class="btn back-dashboard-btn">Back to Admin Dashboard</a>
        </div>
    </div>
</body>
</html>
