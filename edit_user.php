<?php
// Start the session to check admin login status
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Include the database configuration file
include 'database.php';

$error_message = $success_message = "";

// Check if the user ID is provided
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch user details
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch();

    // If user is not found, redirect to manage users page
    if (!$user) {
        header("Location: manage_users.php");
        exit;
    }

    // If form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
        $full_name = trim($_POST['fullname']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']); // New password field

        // Validate form fields
        if (empty($full_name) || empty($username) || empty($email)) {
            $error_message = "All fields are required!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Invalid email format!";
        } else {
            // Check if the provided email already exists in the database
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
            $stmt->execute(['email' => $email, 'id' => $user_id]);
            $existing_user = $stmt->fetch();

            if ($existing_user) {
                $error_message = "The email address is already in use!";
            } else {
                // If a new password is provided, hash it
                if (!empty($password)) {
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    $update_stmt = $pdo->prepare("UPDATE users SET full_name = :full_name, username = :username, email = :email, password = :password WHERE id = :id");
                    $update_stmt->execute([
                        'full_name' => $full_name,
                        'username' => $username,
                        'email' => $email,
                        'password' => $hashed_password,
                        'id' => $user_id
                    ]);
                } else {
                    // If no new password, update without changing the password
                    $update_stmt = $pdo->prepare("UPDATE users SET full_name = :full_name, username = :username, email = :email WHERE id = :id");
                    $update_stmt->execute([
                        'full_name' => $full_name,
                        'username' => $username,
                        'email' => $email,
                        'id' => $user_id
                    ]);
                }

                $success_message = "User updated successfully!";
            }
        }
    }
} else {
    header("Location: manage_users.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url(https://tools.corenexis.com/image/cnxm/M24/12/0b2f6686cd.webp);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background: rgba(255, 255, 255, 0.56);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 600px;
            text-align: center;
            box-sizing: border-box;
        }

        h1 {
            color: rgb(2, 33, 2);
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            font-weight: 600;
            font-size: 1rem;
        }

        .form-control {
            background-color: white;
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1rem;
        }

        .btn {
            background: linear-gradient(135deg, #ffffff 0%, #006600 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            margin-top: 15px;
            width: 100%;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s, transform 0.3s;
            text-decoration: none;
            display: block;
            box-sizing: border-box;
        }

        .btn:hover {
            background: linear-gradient(135deg, #006600 0%, #ffffff 100%);
            transform: translateY(-3px);
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .message {
            margin-top: 20px;
            font-weight: bold;
        }

        .success {
            color: #1cc88a;
        }

        .error {
            color: #e74a3b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit User</h1>
        <?php if (!empty($success_message)) { echo "<p class='success'>$success_message</p>"; } ?>
        <?php if (!empty($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Full Name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="New Password (leave blank to keep current)">
            </div>
            <div class="btn-group">
                <button type="submit" name="edit_user" class="btn">Save changes</button>
                <a href="manage_users.php" class="btn">Back to Manage Users</a>
            </div>
        </form>
    </div>
</body>
</html>
