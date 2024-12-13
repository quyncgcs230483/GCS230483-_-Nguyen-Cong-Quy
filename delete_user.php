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

// Check if user ID is provided
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete the user from the database
    $delete_stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $delete_stmt->execute(['id' => $user_id]);

    // Redirect to manage users page
    header("Location: manage_users.php");
    exit;
} else {
    // If no user ID is provided, redirect to manage users page
    header("Location: manage_users.php");
    exit;
}
?>
