<?php
session_start();
include 'database.php'; // Include the database connection

$error_message = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            header("Location: Homepage.php"); // Redirect to Homepage or welcome page
            exit;
        } else {
            $error_message = 'Invalid username or password';
        }
    } else {
        $error_message = 'Please fill in both fields';
    }
}
?>

<!DOCTYPE html>
<html lang="EN">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <style>
      button {
        background: black;
      }

      .form-container {
        position: absolute;
        top: 0;
        height: 100%;
        transition: all 0.6s ease-in-out;
      }

      .subbmit {
        position: relative;
        border-radius: 30px;
        border: 1px solid #686c78;
        background-color:rgb(0, 0, 0);
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

      .subbmit a {
        color: #fff;
        text-decoration: none;
        
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

      .forgot {
        margin-left: 150px;
      }
    </style>
  </head>
  <body>
    <div class="container" id="container">
      <!-- Login Form -->
      <div class="form-container login-container">
        <form method="POST" action="">
          <h1>Sign In</h1>
          <div class="form-control">
            <input type="text" name="username" id="username" placeholder="  Username" value="" />
            <small id="username-error"></small>
          </div>
          <div class="form-control">
            <input type="password" name="password" id="password" placeholder="  Password" required />
            <small id="password-error"></small>
          </div>
          <div class="forgot">
            <a href="forgot_password.php">Forgot password?</a>
          </div>
          <button type="submit" name="login" value="submit">Sign In</button>
          <div class="subbmit">
            <a href="adminlogin.php">Admin Login</a>
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
            <button class="ghost" id="register">
              <a href="Register.php">Register <i class="fa-solid fa-arrow-right"></i></a>
            </button>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
