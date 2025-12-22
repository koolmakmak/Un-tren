<?php
// Start session
session_start();
require_once('connect.php');

// Fetch the schedule from the database - ensure 'status' is included if you want to show it
$query = "SELECT service_date, headcode, service_type, train_service_name, start_station_name, dest_station_name FROM passenger_timetable ORDER BY service_date ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>UTR Train Reservation System - Schedule</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Keeping your original styles... */
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
        .logo { display: flex; align-items: center; }
        .logo img { height: 55px; margin-right: 12px; }
        header h1 { font-size: 24px; margin: 0; }
        nav a { color: white; margin-left: 20px; text-decoration: none; font-weight: 600; }
        nav a.active { text-decoration: underline; }

        main {
            flex: 1;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .schedule-container {
            width: 100%;
            max-width: 1100px; /* Widened slightly for more columns */
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            overflow-x: auto; /* Adds scroll on small screens */
        }
        h2 { color: #333; margin-bottom: 20px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; font-size: 14px; }
        table th { background-color: #f8f9fa; color: #720A00; text-transform: uppercase; }
        table tr:hover { background-color: #f1f1f1; }
        footer { text-align: center; padding: 15px; background-color: #720A00; color: white; font-size: 14px; }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <img src="assets/train.png" alt="Train Logo">
        <h1>UTR Train Reservation System</h1>
    </div>
    <nav>
        <a href="index.php">Home</a>
        <a href="schedule.php" class="active">Schedule</a>
        <a href="my_booking.php">My Booking</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<main>
    <div class="schedule-container">
        <h2>Train Timetable</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Headcode</th>
                    <th>Type</th>
                    <th>Service Name</th>
                    <th>Origin</th>
                    <th>Destination</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['service_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['headcode']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['service_type']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['train_service_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['start_station_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['dest_station_name']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>No schedules found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<footer>
    © 2025 UTR Train Reservation System | All Rights Reserved
</footer>

</body>
</html>