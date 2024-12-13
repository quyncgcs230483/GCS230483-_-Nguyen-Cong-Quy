<?php
// Include your database configuration
include 'database.php';  // Ensure this file contains your PDO connection

session_start(); // Start session for login state management

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        try {
            // Query to fetch admin user by username
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify if user exists and password is correct
            if ($user && password_verify($password, $user['password'])) {
                // Set session variables and redirect
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $username;
                header("Location: admin_dashboard.php"); // Redirect to admin dashboard
                exit();
            } else {
                $error = "Invalid username or password!";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "All fields are required!";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css">
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
        transition: transform 0.6s ease-in-out;
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
        border-radius: 50px;
        border: 1px solid #686c78;
        background-color: #686c78;
        background: black;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        margin: 20px;
        padding: 5px 50px;
        letter-spacing:1px;
        text-transform: capitalize;
        transition: 0.3s ease-in-out;
        cursor: pointer;
      }
      .subbmit:hover {
        letter-spacing: 2px;
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
    </style>

  </head>
  <div class="container" id="container">
      <div class="form-container login-container">
        <form method="POST" action="">
          <h1> Admin Sign In</h1>
          <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
          <?php endif; ?>
          <div class="form-control">
            <input type="text" name="username" id="username" placeholder="  Username" required />
          </div>
          <div class="form-control">
            <input type="password" name="password" id="password" placeholder="  Password" required />
          </div>
          <button type="submit" name="login" value="submit">Sign In</button>
          <div class="subbmit">
            <a href="login.php">Back to user login</a>
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
              <a href="adminregister.php">Admin Register<i class="fa-solid fa-arrow-right"></i></a>
            </button>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
