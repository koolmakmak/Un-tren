@ -1,113 +0,0 @@
<?php
include "db.php";

// Fetch train types
$trainTypes = $conn->query("SELECT * FROM train_types");

// Fetch routes
$routes = $conn->query("SELECT * FROM routes");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Page</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f6f8;
            margin: 0;
        }

        header {
            background: #720A00;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .container {
            max-width: 600px;
            background: white;
            margin: 40px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
        }

        button {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            background: #720A00;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>

<header>
    <h2>Admin – Add Train Schedule</h2>
</header>

<div class="container">

<form method="post" action="save_schedule.php">

    <!-- 1️⃣ Type of Train -->
    <label>Type of Train</label>
    <select name="train_type" required>
        <option value="">-- Select Train Type --</option>
        <?php while($row = $trainTypes->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>">
                <?= $row['type_name'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <!-- 2️⃣ Route -->
    <label>Route</label>
    <select name="route" required>
        <option value="">-- Select Route --</option>
        <?php while($row = $routes->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>">
                <?= $row['route_name'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <!-- 3️⃣ Date -->
    <label>Date</label>
    <input type="date" name="travel_date" required>

    <button type="submit">Add Schedule</button>

</form>

</div>

</body>
</html>