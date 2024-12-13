<?php
include 'database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $stmt = $pdo->prepare("UPDATE users SET password_reset_token = :token, password_reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = :email");
        $stmt->execute(['token' => $token, 'email' => $email]);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'quyncgcs230483@fpt.edu.vn';
            $mail->Password = 'ieew jcph ulju xuym';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('quyncgcs230483@fpt.edu.vn', 'Your Name');
            $mail->addAddress($email, $user['username']);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset';
            $mail->Body = '<p>You requested a password reset. Click <a href="http://your-website.com/reset_password.php?token=' . $token . '">here</a> to reset your password.</p>';

            $mail->send();
            $successMessage = "Password reset link sent to your email.";
        } catch (Exception $e) {
            $errorMessage = "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        $errorMessage = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="css/Homepage.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: url('https://i.imgur.com/JwEA3t1.jpeg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Montserrat', Arial, sans-serif;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.56);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
            backdrop-filter: blur(8px);
        }

        h2 {
            color: #73C6D9;
            font-size: 26px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .notification {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            font-size: 15px;
        }

        .success {
            background-color: #e0ffe0;
            color: #008000;
            border: 1px solid #008000;
        }

        .error {
            background-color: #ffe0e0;
            color: #d00000;
            border: 1px solid #d00000;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 16px;
            color: #483D8B;
            font-weight: bold;
        }

        input[type="email"] {
            padding: 10px;
            border: 1px solid #73C6D9;
            border-radius: 5px;
            font-size: 16px;
            background-color: #f8f9fa;
            color: #333;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="email"]:focus {
            border-color: #73C6D9;
            box-shadow: 0 0 8px rgba(115, 198, 217, 0.5);
            outline: none;
        }

        button {
            padding: 12px;
            background: linear-gradient(135deg, #73C6D9 0%, #FFFFFF 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background: linear-gradient(135deg, #FFFFFF 0%, #73C6D9 100%);
            transform: translateY(-3px);
        }

        a {
    display: block;
    text-align: center;
    padding: 12px;
    background-color: white;
    border: 2px solid #73C6D9;
    color: #73C6D9;
    font-size: 16px;
    border-radius: 5px;
    text-decoration: none;
    transition: all 0.3s ease;
    margin-top: 15px; /* Add this margin to match the spacing */
}

a:hover {
    background-color: #73C6D9;
    color: white;
}
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <?php if (isset($successMessage)): ?>
            <div class="notification success"><?= $successMessage ?></div>
        <?php elseif (isset($errorMessage)): ?>
            <div class="notification error"><?= $errorMessage ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <button type="submit" name="submit">Reset Password</button>
        </form>

        <a href="login.php">Back to user login</a>
    </div>
</body>
</html>
