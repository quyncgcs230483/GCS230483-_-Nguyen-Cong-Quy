<?php
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập, chuyển hướng đến trang login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Kết nối cơ sở dữ liệu
include 'database.php'; // Đảm bảo rằng file này thiết lập kết nối PDO trong $pdo

// Xử lý việc gửi comment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment_submit'])) {
    $post_id = $_POST['post_id'];
    $username = $_SESSION['username'];
    $comment = htmlspecialchars($_POST['comment']);

    try {
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, username, comment) VALUES (:post_id, :username, :comment)");
        $stmt->bindParam(':post_id', $post_id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':comment', $comment);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Lấy tất cả các bài viết từ cơ sở dữ liệu
try {
    $stmt = $pdo->prepare("SELECT id, title, content, image_path, created_at FROM posts ORDER BY created_at DESC");
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            background: url(https://i.imgur.com/TgtXPPS.jpeg) no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        body {
            background: url(https://i.imgur.com/TgtXPPS.jpeg) no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            color: #555; /* Sử dụng màu giống với tiêu đề "Create a New Question" */
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        h2 {

    font-size: 30px;
    text-align: center;
    color: #483D8B; /* Màu giống với Title */

        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.56);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .post {
            border: 1px solid #73C6D9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .post img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .comment-form textarea {
            width: 97.4%;
            height: 80px;
            margin-top: 10px;
            border: 1px solid #73C6D9;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            resize: none;
            background-color: #f8f9fa;
        }
        .btn-container a.logout-btn {
    background:linear-gradient(135deg, #dc2626 0%, #ffffff 100%);
    color: white;
    text-decoration: none;
    padding: 12px 25px;  /* Tăng kích thước padding để nút lớn hơn */
    font-size: 18px;  /* Tăng kích thước chữ */
    border-radius: 5px;
    margin: 10px;
    font-weight: bold;
    text-align: center;
    transition: transform 0.2s, background 0.3s;
    width: auto;  /* Không đổi chiều rộng */
}

.btn-container a.logout-btn:hover {
    background:linear-gradient(135deg, #dc2626 0%, #ffffff 100%);
    transform: translateY(-3px);
}

.btn-container a.logout-btn:active {
    transform: translateY(1px); /* Hiệu ứng khi nhấn vào nút */
}
        button {
            background: linear-gradient(135deg, #73C6D9 0%, #ffffff 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s, background 0.3s;
            margin-top: 10px;
            width: 100%;
        }
    
        .btn-container .btn {
            background: linear-gradient(135deg, #73C6D9 0%, #ffffff 100%); /* Gradient cho nút */
     color: white;
    text-decoration: none;
    padding: 12px 25px;  /* Tăng kích thước padding để nút lớn hơn */
    font-size: 18px;  /* Tăng kích thước chữ */
    border-radius: 5px;
    margin: 10px;
    font-weight: bold;
    text-align: center;
    transition: transform 0.2s, background 0.3s;
    width: auto;  /* Không đổi chiều rộng */
}

.btn-container .btn:hover {
    background: linear-gradient(135deg, #73C6D9 100%, #ffffff 0%); /* Hiệu ứng hover đổi màu */
    transform: translateY(-3px); /* Hiệu ứng hover nâng nút */
}

.btn-container .btn:active {
    transform: translateY(1px); /* Hiệu ứng khi nhấn vào nút */
}


        button:hover {
            background: linear-gradient(135deg, #ffffff 0%, #73C6D9 100%);
            transform: translateY(-3px);
        }

        .btn-container {
            text-align: center;
            margin: 20px 0;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            background: #73C6D9;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 10px;
            margin: 10px;
            font-size: 16px;
            transition: background 0.3s, transform 0.2s;
        }

        .btn:hover {
            background: #5bb0c6;
            transform: translateY(-2px);
        }

        /* Nút "Logout" */



        </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <div class="btn-container">
            <a href="add-posts.php" class="btn">Create New Question</a>
            <a href="edit-profile.php" class="btn">Edit Profile</a>
            <a href="mailto:quyncgcs230483@fpt.edu.vn" class="btn">Contact Admin</a>
            <a href="logout.php" class="btn logout-btn">Logout</a>
        </div>

        <h2>All Posts</h2>
        <?php if (!empty($posts)) : ?>
            <?php foreach ($posts as $post) : ?>
                <div class="post">
                    <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    <?php if (!empty($post['image_path'])) : ?>
                        <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Post Image">
                    <?php endif; ?>
                    <small>Posted on: <?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?></small>

                    <div class="comments">
                        <h4>Comments:</h4>
                        <?php
                        $stmt = $pdo->prepare("SELECT username, comment FROM comments WHERE post_id = :post_id ORDER BY created_at DESC");
                        $stmt->bindParam(':post_id', $post['id']);
                        $stmt->execute();
                        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (!empty($comments)) {
                            foreach ($comments as $comment) {
                                echo '<p><strong>' . htmlspecialchars($comment['username']) . ':</strong> ' . htmlspecialchars($comment['comment']) . '</p>';
                            }
                        } else {
                            echo '<p>No comments yet.</p>';
                        }
                        ?>
                    </div>

                    <form method="POST" class="comment-form">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <textarea name="comment" placeholder="Add a comment..." required></textarea>
                        <button type="submit" name="comment_submit">Comment</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No posts available.</p>
        <?php endif; ?>
    </div>
</body>
</html