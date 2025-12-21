<?php
// Start session
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('connect.php');

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $email = $_POST['email'];

    // Validate inputs
    if ($password != $cpassword) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL query to insert data
        $stmt = $conn->prepare("INSERT INTO users_info (firstname, lastname, username, password, cpassword, email) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $firstname, $lastname, $username, $hashed_password, $cpassword, $email);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Train Reservation System</title>
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(to bottom, #f4f6f8, #e9ecef);
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: #720A00;
            color: white;
            padding: 15px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        header .logo img{
            height: 55px;
            margin-right: 12px;
        }

        header .logo {
            display: flex;
            align-items: center;
        }

        header h1 {
            font-size: 24px;
            margin: 0;
        }

        nav{
            display: flex;
            gap: 24.5px;
        }

        nav a{
            color: white;
            text-decoration: none;
            font-weight: 600;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .container {
            max-width: 900px;
            width: 100%;
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #720A00;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 14px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #8c0d00;
        }

        .error-message, .success-message {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

        footer {
            text-align: center;
            padding: 15px;
            background-color: #720A00;
            color: white;
            font-size: 14px;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <img src="assets/train.png" alt="Train Logo">
        <h1>Train Reservation System</h1>
    </div>
    <nav>
        <a href="index.html">Home</a>
        <a href="login.php">Login</a>
        <a href="signup.php" class="active">Sign Up</a>
    </nav>
</header>

<main>
    <div class="container">

        <h2>Sign Up</h2>

        <form action="signup.php" method="post">
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" name="firstname" required>
            </div>

            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" name="lastname" required>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="cpassword">Confirm Password</label>
                <input type="password" name="cpassword" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" required>
            </div>

            <?php if (isset($error_message)): ?>
                <div class="error-message"><?= $error_message ?></div>
            <?php endif; ?>
            <?php if (isset($success_message)): ?>
                <div class="success-message"><?= $success_message ?></div>
            <?php endif; ?>

            <div class="center">
                <button type="submit" name="sub">Submit</button>           
            </div>
        </form>

    </div>
</main>

<footer>
    © 2025 Train Reservation System | All Rights Reserved
</footer>

</body>
</html>
