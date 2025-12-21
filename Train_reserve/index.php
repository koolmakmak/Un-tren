<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Train Reservation System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
        }

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
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 55px;
            margin-right: 12px;
        }

        nav a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-weight: 600;
        }

        nav a.my-booking-btn {
            background-color: white;
            color: #720A00;
            padding: 8px 14px;
            border-radius: 6px;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        footer {
            text-align: center;
            padding: 15px;
            background-color: #720A00;
            color: white;
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
        <a href="index.php">Home</a>
        <a href="schedule.php">Schedule</a>
        <a href="my_booking.php" class="my-booking-btn">My Booking</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<main>
    <h2>Welcome to Train Reservation System</h2>
</main>

<footer>
    © 2025 Train Reservation System | All Rights Reserved
</footer>

</body>
</html>
