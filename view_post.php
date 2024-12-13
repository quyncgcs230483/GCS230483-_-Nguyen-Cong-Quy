<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

include 'database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid or missing post ID.";
    exit;
}

$post_id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT posts.*, modules.module_name, users.username 
                           FROM posts
                           LEFT JOIN modules ON posts.module_id = modules.id
                           LEFT JOIN users ON posts.user_id = users.id
                           WHERE posts.id = :id");
    $stmt->execute([':id' => $post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        echo "Post not found.";
        exit;
    }
} catch (PDOException $e) {
    die("Error fetching post: " . $e->getMessage());
}

// Xử lý đường dẫn hình ảnh
if (!empty($post['image'])) {
    $image_path = __DIR__ . '/' . $post['image'];
    if (file_exists($image_path)) {
        $image_url = $post['image'];
    } else {
        $image_url = 'path/to/default/image.jpg'; // Đường dẫn fallback nếu ảnh không tồn tại
    }
} else {
    $image_url = 'path/to/default/image.jpg'; // Đường dẫn fallback nếu không có ảnh
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Post</title>
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background-color: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: url(https://tools.corenexis.com/image/cnxm/M24/12/6aedf50d8d.webp);
            background-size: cover;
            background-position: center;
        }

        .container {
            width: 80%;
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.56);
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
        }

        h1 {
            color: rgb(2, 33, 2);
            font-size: 36px;
            margin-bottom: 20px;
        }

        .post-details {
            padding: 15px;
            background-color: white;
            border-radius: 10px;
            margin-top: 20px;
            text-align: left;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            color: #333;
        }

        small {
            color: #777;
            font-size: 14px;
        }

        img {
            max-width: 100%; 
            height: auto;
            max-height: 400px;
            object-fit: contain;
            border-radius: 12px;
            margin-top: 20px;
        }

        .back-btn {
            display: block;
            width: 100%;
            padding: 15px;
            color: white;
            background: linear-gradient(135deg, #ffffff 0%, #006600 100%);
            text-decoration: none;
            border-radius: 50px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .back-btn:hover {
            background: linear-gradient(135deg, #006600 0%, #ffffff 100%);
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($post['title'] ?? 'Untitled'); ?></h1>
        <div class="post-details">
            <p><strong>Module:</strong> <?php echo htmlspecialchars($post['module_name'] ?? 'General'); ?></p>
            <p><strong>Post Content:</strong> <br>
                <?php echo nl2br(htmlspecialchars($post['content'] ?? 'No content available.')); ?>
            </p>
            <p><small>Posted on: <br>
                <?php echo isset($post['created_at']) ? date('F j, Y, g:i a', strtotime($post['created_at'])) : 'Unknown date'; ?>
            </small></p>
        </div>

        <!-- Hiển thị hình ảnh -->
        <div class="post-image">
            <?php if (!empty($post['image'])): ?>
                <img src="<?php echo htmlspecialchars($image_url); ?>" alt="Post Image" style="max-width: 100%; height: auto;">
            <?php else: ?>
                <p>No image available.</p>
            <?php endif; ?>
        </div>

        <!-- Nút quay lại -->
        <a href="manage_posts.php" class="back-btn">Back to Manage Posts</a>
    </div>
</body>
</html>
