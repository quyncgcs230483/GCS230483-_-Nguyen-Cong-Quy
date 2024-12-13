<?php 
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Include database configuration
include 'database.php';

// Initialize messages
$message = '';
$message_class = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input data
    $module_id = trim($_POST['module_id']);
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $username = trim($_POST['username']);
    $image = $_FILES['image'];

    // Validate inputs
    if (empty($module_id) || empty($title) || empty($content) || empty($username)) {
        $message = "All fields are required.";
        $message_class = "error";
    } else {
        // Handle image upload
        $image_path = null;
        if (!empty($image['name'])) {
            $target_dir = "uploads/";
            $image_name = basename($image['name']);
            $target_file = $target_dir . uniqid() . "_" . $image_name;

            // Validate file type
            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_type, $allowed_types)) {
                if (move_uploaded_file($image['tmp_name'], $target_file)) {
                    $image_path = $target_file;
                } else {
                    $message = "Failed to upload image.";
                    $message_class = "error";
                }
            } else {
                $message = "Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.";
                $message_class = "error";
            }
        }

        // Insert data into the database
        if ($message_class !== "error") {
            try {
                $stmt = $pdo->prepare("INSERT INTO posts (username, module_id, title, content, image, created_at) 
                                       VALUES (:username, :module_id, :title, :content, :image, NOW())");
                $stmt->execute([
                    ':username' => $username,
                    ':module_id' => $module_id,
                    ':title' => $title,
                    ':content' => $content,
                    ':image' => $image_path,
                ]);

                $message = "Post created successfully!";
                $message_class = "success";
            } catch (PDOException $e) {
                $message = "Failed to create post: " . $e->getMessage();
                $message_class = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Post</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url(https://tools.corenexis.com/image/cnxm/M24/12/3ab40f64c7.webp);
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
        <h1>Add New Post</h1>
        <?php if (!empty($message)): ?>
            <p class="message <?php echo htmlspecialchars($message_class); ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="module_id">Module:</label>
                <select id="module_id" name="module_id" class="form-control" required>
                    <option value="">Select Module</option>
                    <option value="6">CSS</option>
                    <option value="3">HTML</option>
                    <option value="2">Java</option>
                    <option value="1">Python</option>
                </select>
            </div>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea id="content" name="content" class="form-control" rows="6" required></textarea>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <select id="username" name="username" class="form-control" required>
                    <option value="">Select User</option>
                    <option value="Cong Quy">Cong Quy</option>
                    <option value="ellierosee">ellierosee</option>
                    <option value="Minh Tri">Minh Tri</option>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" class="form-control">
            </div>
            <button type="submit" class="btn">Add Post</button>
        </form>
        <br>
        <a href="manage_posts.php" class="btn">Back to Manage Posts</a>
    </div>
</body>
</html>
