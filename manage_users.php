<?php
// Start the session to check admin login status
session_start();

// Check if the admin is logged in (add your own authentication logic here)
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Database connection
include 'database.php'; // Ensure your database configuration file is included

// Fetch users from the database
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id ASC"); // Sắp xếp theo ID tăng dần
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching users: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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

        .add-user-btn {
            background: linear-gradient(135deg, #ffffff 0%, #006600 100%);
        }

        .add-user-btn:hover {
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
</head>
<body>
    <div class="container">
        <h1>Manage Users</h1>

        <!-- Centered Add New User button -->
        <div class="btn-container">
            <a href="add_new_users.php" class="btn add-user-btn">Add New User</a>
        </div>

        <!-- User table -->
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users): ?>
                    <?php $index = 1; // Biến đếm bắt đầu từ 1 ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $index++; ?></td>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td class="action-btns">
                                <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="edit-btn">Edit</a>
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');" class="delete-btn">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No users available.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Back to Admin Dashboard buttons -->
        <div class="back-dashboard-container">
            <a href="admin_dashboard.php" class="btn back-dashboard-btn">Back to Admin Dashboard</a>
        </div>
    </div>
</body>
</html>
