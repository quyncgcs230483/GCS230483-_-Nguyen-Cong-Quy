<?php
// Include database configuration file
include 'database.php'; // Ensure this file sets up your PDO connection in $pdo

// Initialize a message variable
$message = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $module_id = $_POST['module_id'];
// Initialize image path as empty
$imagePath = '';
$message = '';

// Check if a file is uploaded and there are no errors
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    // Define the upload directory and check if it exists
    $uploadDir = __DIR__ . '/uploads/';

    // Create the 'uploads' directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            $message = "Failed to create upload directory.";
        }
    }

    // Get the image file name and sanitize it to avoid special characters
    $imageName = basename($_FILES['image']['name']);
    $imageName = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $imageName);

    // Check the file type (e.g., .jpg, .png, .jpeg)
    $imageFileType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];  // Allowed file types

    if (!in_array($imageFileType, $allowedTypes)) {
        $message = "Only image files (JPG, JPEG, PNG, GIF) are allowed.";
    } else {
        // Create the full target file path
        $targetFile = $uploadDir . $imageName;

        // Check file size (e.g., max 5MB)
        if ($_FILES['image']['size'] > 5 * 1024 * 1024) { // 5MB
            $message = "The image file is too large. The maximum size is 5MB.";
        } else {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Save the image path to the database
                $imagePath = 'uploads/' . $imageName;
                $message = "Image uploaded successfully!";
            } else {
                $message = "Error moving the uploaded file. Please check file permissions.";
            }
        }
    }
} else {
    $message = "No image file was uploaded or there was an error during the upload process.";
}

echo $message;

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, module_id, image_path) VALUES (:title, :content, :module_id, :image_path)");
        
        // Bind parameters
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':module_id', $module_id);
        $stmt->bindParam(':image_path', $imagePath);
        
        // Execute the statement
        if ($stmt->execute()) {
            $message = "Question created successfully!";
        } else {
            $message = "Error creating the question.";
        }
    } catch (PDOException $e) {
        $message = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Question</title>
    <link href="css/Homepage.css" rel="stylesheet">
    <style>
        body {
            background: url(https://tools.corenexis.com/image/cnxm/M24/12/42ad76c85c.webp) no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 800px;
            width: 100%;
            margin: auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.56); 
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(8px);
        }

        h1 {
            font-size: 26px;
            font-family: 'Arial', sans-serif;
            color: #73C6D9;
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        form label {
            font-size: 16px;
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            color: #483D8B;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        textarea,
        select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #73C6D9;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            background-color: #f8f9fa;
            color: #333;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            border-color: #73C6D9;
            box-shadow: 0 0 8px rgba(115, 198, 217, 0.5);
            outline: none;
        }

        select {
            appearance: none;
            cursor: pointer;
        }

        input[type="file"] {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #73C6D9;
            border-radius: 5px;
            width: 100%;
            background-color: #f8f9fa;
            color: #333;
            cursor: pointer;
        }

        input[type="file"]::file-selector-button {
            background-color: #73C6D9;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="file"]::file-selector-button:hover {
            background-color: #5bb0c6;
        }

        .back-to-dashboard {
            display: block;
            margin-top: 15px;
            font-size: 16px;
            font-family: 'Arial', sans-serif;
            color: #73C6D9;
            text-decoration: none;
            border: 2px solid #73C6D9;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            transition: all 0.3s ease-in-out;
            background-color: white;
        }

        .back-to-dashboard:hover {
            background-color: #73C6D9;
            color: white;
            text-decoration: none;
        }

        button {
            background: linear-gradient(135deg, #73C6D9 0%, #FFFFFF 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
            margin-top: 10px;
            width: 100%;
            font-weight: bold;
        }

        button:hover {
            background: linear-gradient(135deg, #FFFFFF 0%, #73C6D9 100%);
            transform: translateY(-3px);
        }

        .message {
            margin-top: 10px;
            padding: 10px;
            color: #2e7d32;
            border-radius: 5px;
            display: <?php echo !empty($message) ? 'block' : 'none'; ?>;
            text-align: center;
            font-size: 17px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create a New Question</h1>
        <div class="message"><?php echo $message; ?></div>
        
        <form method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>
            
            <label for="content">Content:</label>
            <textarea name="content" id="content" required></textarea>
            
            <label for="module_id">Module:</label>
            <select id="module_id" name="module_id" required>
                <option value="">Select Module</option>
                <?php
                try {
                    $stmt = $pdo->query("SELECT id, module_name FROM modules ORDER BY module_name ASC");
                    while ($module = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value=\"{$module['id']}\">" . htmlspecialchars($module['module_name']) . "</option>";
                    }
                } catch (PDOException $e) {
                    echo "<option value=\"\">Error fetching modules</option>";
                }
                ?>
            </select>
            
            <label for="image">Upload Image:</label>
            <input type="file" name="image" id="image">
            
            <button type="submit">Create Question</button>
        </form>
        <a href="Homepage.php" class="back-to-dashboard">Back to Dashboard</a>
    </div>
</body>
</html>
