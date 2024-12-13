<?php
// Include the database configuration file
include 'database.php';

// Initialize messages
$success_message = $error_message = "";

// Initialize form values to retain user input
$username = $email = $full_name = ""; // Initialize full_name here

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Get form data
    $full_name = trim($_POST['fullname']); // Capture full name
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate form fields
    if (empty($full_name) || empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format!";
    } else {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        
        if ($stmt->rowCount() > 0) {
            $error_message = "Username or email already taken!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            
            // Insert user into database
            $insert_stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name) VALUES (:username, :email, :password, :full_name)");
            $insert_stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => $hashed_password,
                'full_name' => $full_name, // Insert full_name here
            ]);
            $success_message = "Registration successful!";
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
        background-image: url("https://tools.corenexis.com/image/cnxm/M24/12/6d9bbeb1ae.webp");
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
        .subbmit {
        position: relative;
        border-radius: 30px;
        border: 1px solid #686c78;
        background-color: #686c78;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        margin: 20px;
        padding: 12px 60px;
        letter-spacing: 1px;
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
          <h1> Sign Up</h1>
          <?php if (!empty($success_message)) { echo "<p class='message'>$success_message</p>"; } ?>
          <?php if (!empty($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
          <div class="form-control">
            <input type="text" name="fullname" id="fullname" placeholder="  Fullname" value="<?php echo htmlspecialchars($full_name); ?>" required />
            <small id="username-error"></small>
          </div>
          <div class="form-control">
            <input type="text" name="username" id="username" placeholder="  Username" value="<?php echo htmlspecialchars($username); ?>" required />
            <small id="username-error"></small>
          </div>
          <div class="form-control">
            <input type="email" name="email" id="email" placeholder="  Email" value="<?php echo htmlspecialchars($email); ?>" required />
            <small id="email-error"></small>
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
            <button class="ghost" id="register"> <a href="login.php" >login <i class="fa-solid fa-arrow-right"></a></i></button>
      </body>
      </html>