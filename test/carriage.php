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
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

         .container h2 {
            margin-bottom: 5px;
        }

        .container p {
            margin-top: 0;
        }

        .container {
            max-width: 900px;
            width: 100%;
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .carriages {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .carriage {
            width: 150px;
            height: 100px;
            margin: 10px;
            background: #4CAF50;
            color: white;
            font-size: 18px;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .carriage small {
            font-size: 13px;
        }

        .first-class {
            background: #3F51B5;
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
        <a href="#">Login</a>
    </nav>
</header>

<main>
    <div class="container">
        <h2>Select Carriage</h2>
        <p>Please choose a carriage</p>

        <div class="carriages">
            <div class="carriage first-class"
                 onclick="location.href='seat.php?carriage=1'">
                Carriage 1
                <small>(First Class)</small>
            </div>

            <?php
                for ($i = 2; $i <= 5; $i++) {
                    echo "<div class='carriage'
                          onclick=\"location.href='seat.php?carriage=$i'\">
                          Carriage $i
                          </div>";
                }
            ?>
        </div>
    </div>
</main>

<footer>
    © 2025 Train Reservation System
</footer>

</body>
</html>
