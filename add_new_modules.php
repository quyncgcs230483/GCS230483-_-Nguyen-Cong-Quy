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

// Handle form submission to add a new module
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $module_name = trim($_POST['module_name']);

    // Validate input
    if (empty($module_name)) {
        $error_message = "Module name is required!";
    } else {
        // Insert the new module into the database
        try {
            $stmt = $pdo->prepare("INSERT INTO modules (module_name) VALUES (:module_name)");
            $stmt->execute([':module_name' => $module_name]);

            // Redirect to manage_modules.php after adding
            header("Location: manage_modules.php");
            exit;
        } catch (PDOException $e) {
            $error_message = "Error adding module: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Module</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url(https://tools.corenexis.com/image/cnxm/M24/12/679d27e20d.webp);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-size: cover;
            background-position: center;
        }

        .container {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 600px;
            text-align: center;
            box-sizing: border-box;
        }

        h1 {
            color: #333; 
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
            background: linear-gradient(135deg, #ffffff 0%, #4e73df 100%);
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
            background: linear-gradient(135deg, #4e73df 0%, #ffffff 100%);
            transform: translateY(-3px);
        }

        .btn-back {
            background: linear-gradient(135deg, #ffffff 0%, #e74a3b 100%);
            color: white;
            text-decoration: none;
            padding: 10px 30px;
            border-radius: 50px;
            font-size: 1rem;
            display: inline-block;
            margin-top: 15px;
            font-weight: 600;
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #e74a3b 0%, #ffffff 100%);
        }

        .message {
            margin-bottom: 20px;
            font-size: 1rem;
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
        <h1>Add New Module</h1>
        
        <!-- Message section -->
        <div class="message success" style="display: none;">Module added successfully!</div>
        <div class="message error" style="display: none;">Error adding module!</div>

        <form method="POST" id="addModuleForm">
            <div class="form-group">
                <label for="module_name">Module Name</label>
                <input type="text" id="module_name" name="module_name" class="form-control" placeholder="Enter module name" required>
            </div>
            <button type="submit" class="btn">Add Module</button>
        </form>

        <a href="manage_modules.php" class="btn-back">Back to Manage Modules</a>
    </div>
</body>
</html>
