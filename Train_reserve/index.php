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
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 55px;
            margin-right: 12px;
        }

        header h1 {
            font-size: 24px;
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
        <a href="#">Home</a>
        <a href="#">Schedule</a>
        <a href="#">My Booking</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<main>
    <div class="container">
        <img src="assets/route.jpg" alt="Train Route" style="width:50%; border-radius:10px;">
        <div class="station-list">
            <h2>List of Stations</h2>
            <ul class="select-train">
                <?php
                // Loop through the fetched stations and display them as list items
                while ($row = $result->fetch_assoc()) {
                    echo '<li><a href="reserve.php?station=' . urlencode($row['name']) . '">' . htmlspecialchars($row['name']) . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</main>

<footer>
    © 2025 Train Reservation System | All Rights Reserved
</footer>

</body>
</html>
