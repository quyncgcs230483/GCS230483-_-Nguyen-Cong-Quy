<?php
// Include your database configuration
include 'database.php'; // Ensure this file contains your PDO connection code

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $adminKeyInput = trim($_POST['Admin_key']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $verifyPassword = $_POST['verify_password'];
    
    // Predefined Admin Key
    $validAdminKey = '123';  // Change this to your actual admin key

    // Basic validation checks
    if ($adminKeyInput !== $validAdminKey) {
        $error = "Invalid Admin Key!";
    } elseif (empty($username) || empty($password) || empty($verifyPassword)) {
        $error = "All fields are required!";
    } elseif ($password !== $verifyPassword) {
        $error = "Passwords do not match!";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Prepare and execute SQL query
            $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();

            $success = "Admin registered successfully!";
        } catch (PDOException $e) {
            $error = "Registration failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nguyễn Công Quý</title>
    <link rel="stylesheet" href="css/style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <style>
       button{
        background: black;
      }
        .overlay {
        background-image: url("https://tools.corenexis.com/image/cnxm/M24/12/f83e304df2.webp");
        background-repeat: no-repeat;
        background-size: cover;
        background-position: 0 0;
        color: #fff;
        position: relative;
        left: -100%;
        height: 100%;
        width: 200%;
        transform: translateX(0);
        transition: transform 0.6s ease-in-out;s
        }
        .form-container {
        position: absolute;
        top: 0;
        height: 100%;
        transition: all 0.6s ease-in-out;
        }
        .form{
          height: 70vh;
        }
        .subbmit {
        position: relative;
        border-radius: 30px;
        border: 1px solid #686c78;
        background-color:rgb(0, 0, 0);
        background: black;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        margin: 20px;
        padding: 12px 60px;
        letter-spacing: 1px;
        text-transform: capitalize;
        transition: 0.3s ease-in-out;
        cursor: pointer;
        text-align: center;
      }
      .subbmit:hover {
        letter-spacing: 2px;
        background-color: black;
      }
      .subbmit:active {
        transform: scale(0.95);
      }

      .subbmit:focus {
        outline: none;
      }

      .subbmit.ghost {
        background-color: rgba(255, 255, 255, 0.2);
        border: 2px solid #fff;
        color: #fff;
      }

      .message {
        color: green;
        text-align: center;
      }

      .error {
        color: red;
        text-align: center;
      }
    </style>
    </head>
    <body>

    <div class="container" id="container">
      <!-- Registration Form -->
      <div class="form-container login-container">
        <form method="POST" action="">
          <h1>Admin Sign Up</h1>
          <?php if (!empty($success)): ?>
            <p class="message"><?php echo $success; ?></p>
          <?php elseif (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
          <?php endif; ?>
          <div class="form-control">
            <input type="text" name="Admin_key" id="Admin key" placeholder="  Admin Key" value="">
            <small id="username-error"></small>
          </div>
          <div class="form-control">
            <input type="text" name="username" id="username" placeholder="  Username" value="" >
            <small id="username-error"></small>
          </div>
          <div class="form-control">
            <input type="password" name="password" id="password" placeholder="  Password" required />
            <small id="password-error"></small>
          </div>
          <div class="form-control">
            <input type="password" name="verify_password" id="password" placeholder="  Verify Password" required />
            <small id="password-error"></small>
          </div>
          <button type="submit" name="register" value="submit">Sign Up</button>
          <div class="subbmit">
            <a href="adminlogin.php">Back to Login</a>
          </div>
        </form>
      </div>
      <div class="overlay-container">
        <div class="overlay">
          <div class="overlay-panel overlay-left">
            <h1 class="title">Start your posts<br /></h1>
            <p>Welcome to my website</p>
            <button class="ghost" id="login">
              Sign In <i class="fa-solid fa-arrow-left"></i>
            </button>
          </div>
          <div class="overlay-panel overlay-right">
            <h1 class="title">Start your posts <br /></h1>
            <p>Welcome to my website</p>
            <button class="ghost" id="register"> <a href="adminlogin.php">login <i class="fa-solid fa-arrow-right"></a></i></button>
      </body>
      </html>