<?php
session_start();
require_once 'connect.php'; // Include the database connection

// Read form values (if submitted)
$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';
$date = $_GET['date'] ?? '';

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // Not logged in → redirect to login page
    //echo "<script>alert('Please log in to make a reservation.');</script>";
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
    <title>UTR Train Reservation System</title>
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
        <h1>UTR Train Reservation System</h1>
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

        <?php if ($from && $to && $date && (!isset($_SESSION['user_id']))): 
        $stmt = $conn->prepare("SELECT
    sr.service_date,
 
    CONCAT(
        s.headcode_digit,
        LEFT(ds.station_code, 1),
        LPAD(sr.sequence_no, 2, '0')
    ) AS headcode,
 
    s.name AS service_type,
    ts.name AS train_service_name,
 
    -- Route termini stations
    rs_first.station_id AS start_station_id,
    ss_first.name AS start_station_name,
 
    rs_last.station_id AS dest_station_id,
    ss_last.name AS dest_station_name
 
FROM service_runs sr
JOIN train_services ts 
    ON sr.train_service_id = ts.id
JOIN services s 
    ON ts.service_type_id = s.id
 
-- Selected stations (for filtering the service runs)
JOIN route_stations rs_start 
    ON rs_start.route_id = ts.route_id
JOIN stations ss 
    ON rs_start.station_id = ss.id
JOIN route_stations rs_end 
    ON rs_end.route_id = ts.route_id
JOIN stations ds 
    ON rs_end.station_id = ds.id
 
-- Termini stations of the route
JOIN route_stations rs_first 
    ON rs_first.route_id = ts.route_id AND rs_first.stop_order = (
        SELECT MIN(stop_order) FROM route_stations WHERE route_id = ts.route_id
    )
JOIN stations ss_first 
    ON rs_first.station_id = ss_first.id
JOIN route_stations rs_last 
    ON rs_last.route_id = ts.route_id AND rs_last.stop_order = (
        SELECT MAX(stop_order) FROM route_stations WHERE route_id = ts.route_id
    )
JOIN stations ss_last 
    ON rs_last.station_id = ss_last.id
 
WHERE ss.id = ?             -- selected start station
  AND ds.id = ?             -- selected destination station
  AND sr.service_date =  ?
  AND rs_start.stop_order < rs_end.stop_order
 
ORDER BY sr.service_date, sr.id;");
        $stmt->bind_param("iis", $from, $to, $date);
        $stmt->execute();
        $search_result = $stmt->get_result();
        ?>
            <div class="result">
        <h3>Available Trains for <?= htmlspecialchars($date) ?></h3>
        
        <?php if ($search_result->num_rows > 0): ?>
    <table class="schedule-table">
        <thead>
            <tr>
                <th>Headcode</th>
                <th>Service Type</th>
                <th>Train Name</th>
                <th>From</th>
                <th>To</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $search_result->fetch_assoc()): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['headcode']) ?></strong></td>
                    
                    <td><?= htmlspecialchars($row['service_type']) ?></td>
                    
                    <td><?= htmlspecialchars($row['train_service_name']) ?></td>
                    
                    <td>
                        <small>ID: <?= htmlspecialchars($row['start_station_id']) ?></small><br>
                        <?= htmlspecialchars($row['start_station_name']) ?>
                    </td>
                    
                    <td>
                        <small>ID: <?= htmlspecialchars($row['dest_station_id']) ?></small><br>
                        <?= htmlspecialchars($row['dest_station_name']) ?>
                    </td>
                    <td>
                        <a href="reserve_seats.php" 
                           style="background-color: #720A00; 
                                  color: white; 
                                  padding: 8px 16px; 
                                  text-decoration: none; 
                                  border-radius: 4px; 
                                  font-weight: bold;
                                  display: inline-block;">
                           Select
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
            <div class="no-results">
                <p>🚫 No trains found for the selected route and date.</p>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

    </div>
</main>

<footer>
    © 2025 UTR Train Reservation System | All Rights Reserved
</footer>

</body>
</html>
