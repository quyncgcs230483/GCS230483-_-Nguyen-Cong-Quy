<?php
// Start the session to check admin login status
session_start();

// Check if the admin is logged in (add your own authentication logic here)
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Include the database configuration file
include 'database.php';

// Initialize messages
$success_message = $error_message = "";

// Initialize form values
$full_name = $username = $email = "";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    // Get form data
    $full_name = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate form fields
    if (empty($full_name) || empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format!";
    } else {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        
        if ($stmt->rowCount() > 0) {
            $error_message = "Username or email already taken!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            
            // Insert new user into database
            $insert_stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name) VALUES (:username, :email, :password, :full_name)");
            $insert_stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => $hashed_password,
                'full_name' => $full_name,
            ]);
            $success_message = "New user added successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url(https://tools.corenexis.com/image/cnxm/M24/12/63871959af.webp);
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
            color: rgb(2, 33, 2); /* Title color */
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

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
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
        <h1>Add New User</h1>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Full Name" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <!-- Buttons container -->
            <div class="btn-group">
                <button type="submit" name="add_user" class="btn">Add User</button>
                <a href="manage_users.php" class="btn">Back to Manage Users</a>
            </div>
        </form>
    </div>
</body>
</html>
