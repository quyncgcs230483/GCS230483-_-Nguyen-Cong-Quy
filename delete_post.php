<?php
// Start session to check admin login status
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Database connection
include 'database.php';

// Check if ID is provided
if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Delete post from the database
    try {
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->execute([':id' => $post_id]);

        // Redirect to the manage posts page after deleting
        header("Location: manage_posts.php");
        exit;
    } catch (PDOException $e) {
        die("Error deleting post: " . $e->getMessage());
    }
} else {
    die("Post ID not provided.");
}
