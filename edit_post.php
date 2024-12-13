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

    // Fetch post from the database
    try {
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute([':id' => $post_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post) {
            die("Post not found.");
        }
    } catch (PDOException $e) {
        die("Error fetching post: " . $e->getMessage());
    }
}

// Fetch all modules for the dropdown
try {
    $stmt = $pdo->prepare("SELECT * FROM modules");
    $stmt->execute();
    $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching modules: " . $e->getMessage());
}

// Handle form submission for updating the post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $module_id = trim($_POST['module_id']);
    $image_path = $post['image']; // Keep current image if no new upload

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;

        // Move uploaded file to target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = $target_file; // Update image path with new file
        } else {
            die("Error uploading image. Please try again.");
        }
    }

    // Update post in the database
    try {
        $stmt = $pdo->prepare("
            UPDATE posts 
            SET title = :title, content = :content, module_id = :module_id, image = :image 
            WHERE id = :id
        ");
        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':module_id' => $module_id,
            ':image' => $image_path,
            ':id' => $post_id
        ]);

        // Redirect to the manage posts page after updating
        header("Location: manage_posts.php");
        exit;
    } catch (PDOException $e) {
        die("Error updating post: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: url(https://tools.corenexis.com/image/cnxm/M24/12/a18d76416e.webp) no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            width: 97.8%;
            max-width: 900px;
            background: rgba(255, 255, 255, 0.56);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: rgb(2, 33, 2);
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"], textarea, select, input[type="file"] {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        input[type="text"], select {
            height: 40px;
        }

        img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn, .btn-back {
            flex: 1;
            padding: 15px;
            font-size: 1.2rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            text-align: center;
            background: linear-gradient(135deg, #ffffff 0%, #006600 100%);
            color: white;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn:hover, .btn-back:hover {
            background: linear-gradient(135deg, #006600 0%, #ffffff 100%);
            color: #006600;
            transform: scale(1.02);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Post</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea name="content" id="content" rows="6" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="module_id">Module:</label>
                <select id="module_id" name="module_id" required>
                    <option value="">Select Module</option>
                    <?php foreach ($modules as $module): ?>
                        <option value="<?php echo htmlspecialchars($module['id']); ?>" <?php if ($post['module_id'] == $module['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($module['module_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Current Image:</label>
                <?php if (!empty($post['image'])): ?>
                    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Current Image">
                <?php else: ?>
                    <p>No image uploaded.</p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="image">Upload New Image:</label>
                <input type="file" name="image" id="image" accept="image/*">
            </div>
            <div class="button-group">
                <button type="submit" class="btn">Update Post</button>
                <a href="manage_posts.php" class="btn-back">Back to Manage Posts</a>
            </div>
        </form>
    </div>
</body>
</html>
