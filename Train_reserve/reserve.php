<?php
session_start();
require 'connect.php'; // Include the database connection

// Read form values (if submitted)
$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';
$date = $_GET['date'] ?? '';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Not logged in → redirect to login page
    header("Location: login.php");
    exit();
}

// Fetch the list of stations from the database
$query = "SELECT id, name FROM stations";
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
        }

        .search-box {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
        }

        select, input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ffffffff;
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

        .result {
            margin-top: 30px;
            padding: 20px;
            background: #ffffffff;
            border-radius: 8px;
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
        <a href="index.php">Home</a>
        <a href="schedule.php">Schedule</a>
        <a href="My_booking.php">My Booking</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<main>
    <div class="container">

        <h2>Search Trains</h2>

        <form method="GET">
            <div class="search-box">
                <div>
                    <label>From</label>
                    <select name="from" required>
                        <option value="">Select Station</option>
                        <?php
                        // Display all stations in the dropdown
                        while ($row = $result->fetch_assoc()) {
                            $selected = ($from == $row['id']) ? 'selected' : '';
                            echo '<option value="' . $row['id'] . '" ' . $selected . '>' . htmlspecialchars($row['name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label>To</label>
                    <select name="to" required>
                        <option value="">Select Station</option>
                        <?php
                        // Reset result pointer to loop over stations again for 'To' selection
                        $result->data_seek(0);
                        while ($row = $result->fetch_assoc()) {
                            $selected = ($to == $row['id']) ? 'selected' : '';
                            echo '<option value="' . $row['id'] . '" ' . $selected . '>' . htmlspecialchars($row['name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label>Date</label>
                    <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" required>
                </div>

                <div style="align-self: end;">
                    <button type="submit" name="search">Search</button>
                </div>
            </div>
        </form>

        <?php if ($from && $to && $date && isset($_SESSION['user_id'])): ?>
            <div class="result">
                <h3>Search Result</h3>
                <p><strong>From:</strong> <?= htmlspecialchars($from) ?></p>
                <p><strong>To:</strong> <?= htmlspecialchars($to) ?></p>
                <p><strong>Date:</strong> <?= htmlspecialchars($date) ?></p>
                <p>No trains found (database not connected yet).</p>
            </div>
        <?php endif; ?>

    </div>
</main>

<footer>
    © 2025 Train Reservation System | All Rights Reserved
</footer>

</body>
</html>
