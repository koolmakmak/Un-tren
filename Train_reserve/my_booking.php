<<<<<<<< HEAD:Train_reserve/index.php
<?php
require 'connect.php'; // Include the database connection file

// Fetch stations from the database
$query = "SELECT name FROM stations";
$result = $conn->query($query);

// Check for errors in the query
if (!$result) {
    die('Error: ' . $conn->error);
}
?>

========
>>>>>>>> Kaan-bookingseat:Train_reserve/my_booking.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<<<<<<<< HEAD:Train_reserve/index.php
    <title>UTR Train Reservation System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
========
    <title>My Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

>>>>>>>> Kaan-bookingseat:Train_reserve/my_booking.php
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
<<<<<<<< HEAD:Train_reserve/index.php
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(to bottom, #f4f6f8, #e9ecef);
========
            background: linear-gradient(to bottom, #f4f6f8, #e9ecef);
            margin: 0;
            min-height: 100vh;
>>>>>>>> Kaan-bookingseat:Train_reserve/my_booking.php
            display: flex;
            flex-direction: column;
        }

<<<<<<<< HEAD:Train_reserve/index.php
========
        /* ===== HEADER ===== */
>>>>>>>> Kaan-bookingseat:Train_reserve/my_booking.php
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
<<<<<<<< HEAD:Train_reserve/index.php
            height: 55px;
========
            height: 50px;
>>>>>>>> Kaan-bookingseat:Train_reserve/my_booking.php
            margin-right: 12px;
        }

        header h1 {
<<<<<<<< HEAD:Train_reserve/index.php
            font-size: 24px;
========
            font-size: 22px;
>>>>>>>> Kaan-bookingseat:Train_reserve/my_booking.php
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

<<<<<<<< HEAD:Train_reserve/index.php
========
        /* ===== MAIN CONTENT ===== */
>>>>>>>> Kaan-bookingseat:Train_reserve/my_booking.php
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

<<<<<<<< HEAD:Train_reserve/index.php
        .container {
            max-width: 900px;
            width: 100%;
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }

        .container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .search-box {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
        }

        .station-list {
            margin-right: 20px;
        }

        .select-train {
            max-height: 400px;
            max-width: 350px;
            list-style: none;
            overflow-y: auto;
        }

        .select-train a {
            transition: opacity 0.3s;
            text-decoration: none;
            color: black;
        }

        .select-train a:hover {
            opacity: 0.8;
        }

        li {
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
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
            transition: background 0.3s, transform 0.2s;
        }

        button:hover {
            background-color: #8c0d00;
            transform: translateY(-2px);
        }

========
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
>>>>>>>> Kaan-bookingseat:Train_reserve/my_booking.php
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
<<<<<<<< HEAD:Train_reserve/index.php
        <h1>UTR Train Reservation System</h1>
    </div>
    <nav>
        <a href="#">Home</a>
        <a href="#">Schedule</a>
        <a href="#">My Booking</a>
========
        <h1>Train Reservation System</h1>
    </div>
    <nav>
        <a href="index.php">Home</a>
        <a href="schedule.php">Schedule</a>
        <a href="my_booking.php">My Booking</a>
>>>>>>>> Kaan-bookingseat:Train_reserve/my_booking.php
        <a href="login.php">Login</a>
    </nav>
</header>

<main>
<<<<<<<< HEAD:Train_reserve/index.php
    <div class="container">
        <img src="assets/route.jpg" alt="Train Route" style="width:50%; border-radius:10px;">
        <div class="station-list">
            <h2>List of Stations</h2>
            <ul class="select-train">
                <?php
                // Loop through the fetched stations and display them as list items
                while ($row = $result->fetch_assoc()) {
                    echo '<li><a onclick = "(target) => {console.log(target.id)}" href="reserve.php?station=' . urlencode($row['name']) . '">' . htmlspecialchars($row['name']) . '</a></li>';
                }
                ?>
            </ul>
        </div>
========
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
>>>>>>>> Kaan-bookingseat:Train_reserve/my_booking.php
    </div>
</main>

<footer>
<<<<<<<< HEAD:Train_reserve/index.php
    © 2025 UTR Train Reservation System | All Rights Reserved
========
    © 2025 Train Reservation System | All Rights Reserved
>>>>>>>> Kaan-bookingseat:Train_reserve/my_booking.php
</footer>

</body>
</html>
