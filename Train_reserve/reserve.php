<?php
// Read form values (if submitted)
$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';
$date = $_GET['date'] ?? '';

if (isset($_GET['search'])) {
    if (!isset($_SESSION['user_id'])) {
        // Not logged in → redirect to login page
        header("Location: login.php");
        exit();
    }
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

        .result {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
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
        <a href="index.html">Home</a>
        <a href="#">Schedule</a>
        <a href="#">My Booking</a>
        <a href="#">Login</a>
    </nav>
</header>

<main>
    <div class="container">

        <h2>Search Trains</h2>

        <form method="GET">
            <div class="search-box">
                <div>
                    <label>From</label>
                    <input type="text" name="from" value="<?= htmlspecialchars($from) ?>" required>
                </div>

                <div>
                    <label>To</label>
                    <input type="text" name="to" value="<?= htmlspecialchars($to) ?>" required>
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
