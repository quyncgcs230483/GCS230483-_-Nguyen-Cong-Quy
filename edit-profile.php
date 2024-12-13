<?php
include 'database.php'; // Ensure the connection to the database

// Fetch the user details to display in the form
// Here, we assume you're fetching user details based on the user ID or any other mechanism.
// For demonstration, I will assume you want to fetch the first user for simplicity.
$stmt = $pdo->prepare("SELECT * FROM users LIMIT 1");
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle profile update
$message = '';  // For error or success messages
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $current_password = trim($_POST['password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate inputs
    if (empty($fullname) || empty($email)) {
        $message = "Please fill in all fields.";
    } else {
        // Handle password change
        if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
            // Check if current password matches
            if (password_verify($current_password, $user['password'])) {
                if ($new_password === $confirm_password) {
                    // Update password
                    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET fullname = :fullname, email = :email, password = :new_password WHERE id = :user_id");
                    $stmt->bindParam(':new_password', $new_hashed_password, PDO::PARAM_STR);
                } else {
                    $message = "New passwords do not match.";
                }
            } else {
                $message = "Current password is incorrect.";
            }
        } else {
            // Update profile without changing password
            $stmt = $pdo->prepare("UPDATE users SET fullname = :fullname, email = :email WHERE id = :user_id");
        }

        // Update profile details
        if (empty($message)) {
            try {
                $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->execute();

                $message = "Profile updated successfully!";
            } catch (PDOException $e) {
                $message = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    
    <style>
      /* Tổng quan phong cách trang */
    
     
/* Tổng quan phong cách trang */
body {
            background: url(https://tools.corenexis.com/image/cnxm/M24/12/832169784c.webp) no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        h1 {
            font-size: 26px;
            font-family: 'Montserrat', Arial, sans-serif;
            color: #73C6D9;
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* Khung trắng bao bọc chính */
        .container {
            max-width: 700px;
            width: 100%;
            margin: auto;
            padding: 25px 35px;
            background: rgba(255, 255, 255, 0.56); /* Nền trong suốt */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(8px);
        }

        /* Form và các trường con */
        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        label {
            font-size: 16px;
            font-family: 'Montserrat', Arial, sans-serif;
            font-weight: bold;
            color: #483D8B;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
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
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #73C6D9;
            box-shadow: 0 0 8px rgba(115, 198, 217, 0.5);
            outline: none;
        }

        /* Nút chính */
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

        /* Nút Back to Dashboard */
        a.back-link {
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

        a.back-link:hover {
            background-color: #73C6D9;
            color: white;
            text-decoration: none;
        }

        /* Tin nhắn thông báo */
        .message {
            font-size: 16px;
            color: #c2185b;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }

    </style>
</head>
<body>

    <div class="container">
        <h1>Edit Profile</h1>
        
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" value="" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="" required>

            <label for="password">Current Password:</label>
            <input type="password" name="password">

            <label for="new_password">New Password:</label>
            <input type="password" name="new_password">

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password">

            <button type="submit" name="update">Update Profile</button>
        </form>

        <a href="Homepage.php" class="back-link">Back to Dashboard</a>
    </div>

</body>
</html>

