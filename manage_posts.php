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

// Fetch posts from the database
try {
    $stmt = $pdo->query("SELECT posts.id, posts.title, posts.content, posts.created_at, modules.module_name
                         FROM posts
                         LEFT JOIN modules ON posts.module_id = modules.id
                         ORDER BY posts.created_at DESC");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching posts: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
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
            align-items: center;
            margin: 20px 0;
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

        .add-post-btn {
            background: linear-gradient(135deg, #ffffff 0%, #006600 100%);
        }

        .add-post-btn:hover {
            background: linear-gradient(135deg, #006600 0%, #ffffff 100%);
        }

        /* Post styles */
        .post {
            background: #ffffff;
            border: 1px solid #73C6D9;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .post h2 a {
            font-size: 24px;
            color: rgb(2, 33, 2);
            text-decoration: none;
        }

        .post p {
            margin: 10px 0;
            font-size: 16px;
        }

        .post-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px;
        }

        .edit-btn,
        .delete-btn {
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

        /* Back button container styles */
        .back-btn-container {
            display: flex;
            justify-content: space-between; /* Spread the button to fill the width */
            align-items: center;
            padding: 20px 0;
        }

        .back-dashboard-btn {
            flex: 1; /* Stretch the button to fit the container */
            margin: 0 10px; /* Add space between buttons and edges */
            text-align: center;
            text-decoration: none;
            background: linear-gradient(135deg, #ffffff 0%, #006600 100%);
            color: white;
            padding: 15px 25px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            transition: transform 0.2s, background 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .back-dashboard-btn:hover {
            background: linear-gradient(135deg, #006600 0%, #ffffff 100%);
            transform: scale(1.05); /* Add a hover effect */
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Manage Posts</h1>

        <div class="btn-container">
            <a href="admin_posts.php" class="btn add-post-btn">Add New Post</a>
        </div>

        <?php if ($posts): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <h2><a href="view_post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
                    <p><strong>Module:</strong> <?php echo htmlspecialchars($post['module_name'] ?? 'General'); ?></p>
                    <p>Post Content: <?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    <p><small>Posted on: <?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?></small></p>
                    <div class="post-actions">
                        <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="edit-btn">Edit</a>
                        <a href="delete_post.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?');" class="delete-btn">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No posts available.</p>
        <?php endif; ?>

        <div class="back-btn-container">
            <a href="admin_dashboard.php" class="btn back-dashboard-btn">Back to Admin Dashboard</a>
        </div>
    </div>
</body>

</html>
