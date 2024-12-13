<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Include database configuration
include 'database.php';

// Check if module ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage_modules.php");
    exit;
}

$module_id = $_GET['id'];

// Fetch module details
$stmt = $pdo->prepare("SELECT * FROM modules WHERE id = :id");
$stmt->execute(['id' => $module_id]);
$module = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$module) {
    header("Location: manage_modules.php");
    exit;
}

$module_name = $module['module_name'];
$success_message = $error_message = "";

// Handle update request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $module_name = trim($_POST['module_name']);

    if (empty($module_name)) {
        $error_message = "Module name cannot be empty.";
    } else {
        // Update module in the database
        $update_stmt = $pdo->prepare("UPDATE modules SET module_name = :module_name WHERE id = :id");
        $update_stmt->execute([
            'module_name' => $module_name,
            'id' => $module_id,
        ]);

        // Redirect to manage_modules.php after a successful update
        header("Location: manage_modules.php?update_success=1");
        exit;
    }
}

?>

<!DOCTYPE html>
< lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Module</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url(https://tools.corenexis.com/image/cnxm/M24/12/a8e98f5781.webp) no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
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
            color: rgb(2, 33, 2);;
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

        .btn-back {
            background: linear-gradient(135deg, #ffffff 0@%, #006600 100%);
            color: white;
            margin-top: 10px;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: linear-gradient(135deg,  #006600 0%, #ffffff 100%);
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
        <h1>Edit Module</h1>
        <!-- Hiển thị message dựa trên PHP -->
        <?php if (!empty($success_message)): ?>
            <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="module_name">Module Name:</label>
                <input type="text" id="module_name" name="module_name" class="form-control" placeholder="Enter module name" required>
            </div>
            <div class="form-group">
                <label for="module_description">Module Description:</label>
                <textarea id="module_description" name="module_description" class="form-control" rows="4" placeholder="Enter module description"></textarea>
            </div>
            <button type="submit" class="btn">Update Module</button>
            <a href="manage_modules.php" class="btn btn-back">Back to Manage Modules</a>
        </form>
    </div>
</body>
</html>
