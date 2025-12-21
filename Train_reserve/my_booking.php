<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: linear-gradient(to bottom, #f4f6f8, #e9ecef);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== HEADER ===== */
        header {
            background-color: #720A00;
            color: white;
            padding: 15px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 50px;
            margin-right: 12px;
        }

        header h1 {
            font-size: 22px;
            margin: 0;
        }

        nav a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.3s;
        }

        nav a:hover {
            opacity: 0.8;
        }

        /* ===== MAIN CONTENT ===== */
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .booking-card {
            background: white;
            width: 420px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            color: #333;
        }

        .detail {
            font-size: 18px;
            margin: 12px 0;
        }

        .label {
            font-weight: bold;
            color: #720A00;
        }

        .back-btn {
            display: inline-block;
            margin-top: 25px;
            text-decoration: none;
            background-color: #720A00;
            color: white;
            padding: 10px 22px;
            border-radius: 6px;
            font-weight: 600;
        }

        .back-btn:hover {
            opacity: 0.9;
        }

        /* ===== FOOTER ===== */
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
        <a href="index.php">Home</a>
        <a href="schedule.php">Schedule</a>
        <a href="my_booking.php">My Booking</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<main>
    <div class="booking-card">
        <h2>My Booking</h2>

        <div class="detail">
            <span class="label">Train:</span> Train A
        </div>

        <div class="detail">
            <span class="label">Seat:</span> A1
        </div>

        <div class="detail">
            <span class="label">Time:</span> 09:00
        </div>

        <a href="index.php" class="back-btn">Back to Home</a>
    </div>
</main>

<footer>
    © 2025 Train Reservation System | All Rights Reserved
</footer>

</body>
</html>
