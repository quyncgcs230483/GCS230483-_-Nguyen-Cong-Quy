<?php
// Start session and check if admin is logged in
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Montserrat&display=swap'>
    <style>
        body {
            background: url(https://tools.corenexis.com/image/cnxm/M24/12/36ea7c1a48.webp) no-repeat center center fixed;
            background-size: cover;
            font-family: 'Montserrat', Arial, sans-serif;
            color: #333;
            padding: 20px;
            margin: 0;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.85); /* Semi-transparent background */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        /* Title */
        h1 {
            font-size: 36px;
            color: #006600;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        /* Welcome Message */
        .content p {
            font-size: 20px;
            font-weight: bold;
            color: #555;
        }

        /* Navigation Menu */
        .nav-menu {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .nav-menu a {
            display: inline-block;
            text-decoration: none;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: bold;
            color: white;
            border-radius: 5px;
            transition: transform 0.2s, background 0.3s;
        }

        /* Background for individual buttons */
        .nav-menu a.manage-posts {
            background: linear-gradient(135deg, #ffffff 0%, #006600 100%);
        }

        .nav-menu a.manage-users {
            background: linear-gradient(135deg, #ffffff 0%, #006600 100%);
        }

        .nav-menu a.manage-modules {
            background: linear-gradient(135deg, #ffffff 0%, #006600 100%);
        }

        .nav-menu a.logout-btn {
            background: linear-gradient(135deg, #dc2626 0%, #ffffff 100%);
        }

        /* Hover Effects */
        .nav-menu a:hover {
            transform: translateY(-3px);
            opacity: 0.9;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-menu {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <p>Welcome, <?php echo $_SESSION['admin_username']; ?>!</p>
        </div>
        <h1>Admin Dashboard</h1>
        <div class="nav-menu">
            <a href="manage_posts.php" class="manage-posts">Manage Posts</a>
            <a href="manage_users.php" class="manage-users">Manage Users</a>
            <a href="manage_modules.php" class="manage-modules">Manage Modules</a>
            <a href="adminlogin.php?logout=true" class="logout-btn">Logout</a>
        </div>
    </div>
</body>
</html>