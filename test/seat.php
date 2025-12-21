<?php
    // Get carriage number
    $carriage = $_GET['carriage'] ?? 1;

    // Example booked seats per carriage
    $bookedSeats = [
        1 => ["1A", "2B", "3C"],
        2 => ["4A", "5D"],
        3 => ["6B", "7C"],
        4 => ["8A", "9D"],
        5 => ["10B", "11C"]
    ];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Seat Selection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            text-align: center;
        }

        .back-btn {
            margin-top: 20px;
            padding: 8px 16px;
            font-size: 14px;
            background: #555;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .back-btn:hover {
            background: #333;
        }

        .coach {
            display: flex;
            flex-direction: row-reverse;
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px auto;
            width: fit-content;
        }

        .row {
            display: flex;
            flex-direction: column;
            margin: 0 8px;
        }

        .seat {
            width: 45px;
            height: 45px;
            margin: 5px 0;
            line-height: 45px;
            border-radius: 6px;
            cursor: pointer;
            color: white;
            font-size: 13px;
        }

        .available { background: #4CAF50; }
        .booked { background: #f44336; cursor: not-allowed; }
        .selected { background: #2196F3; }

        .aisle { height: 15px; }

        .info { margin-top: 15px; }
    </style>
</head>
<body>

<button class="back-btn" onclick="location.href='carriage.php'">
    ⬅ Back to Carriage Selection
</button>

<h2>💺 Seat Selection – Carriage <?php echo $carriage; ?></h2>
<p>Front of train ➡️</p>

<div class="coach">
<?php
    for ($row = 1; $row <= 20; $row++) {
        echo "<div class='row'>";

        // Left side (C, D)
        foreach (["C", "D"] as $letter) {
            $seat = $row . $letter;
            if (in_array($seat, $bookedSeats[$carriage] ?? [])) {
                echo "<div class='seat booked'>$seat</div>";
            } else {
                echo "<div class='seat available' onclick=\"toggleSeat(this, '$seat')\">$seat</div>";
            }
        }

        echo "<div class='aisle'></div>";

        // Right side (A, B)
        foreach (["A", "B"] as $letter) {
            $seat = $row . $letter;
            if (in_array($seat, $bookedSeats[$carriage] ?? [])) {
                echo "<div class='seat booked'>$seat</div>";
            } else {
                echo "<div class='seat available' onclick=\"toggleSeat(this, '$seat')\">$seat</div>";
            }
        }

        echo "</div>";
    }
?>
</div>

<div class="info">
    <p><b>Selected Seats:</b> <span id="selectedSeats">None</span></p>
</div>

<script>
    let selectedSeats = [];

    function toggleSeat(seatDiv, seatNumber) {
        seatDiv.classList.toggle("selected");

        if (selectedSeats.includes(seatNumber)) {
            selectedSeats = selectedSeats.filter(s => s !== seatNumber);
        } else {
            selectedSeats.push(seatNumber);
        }

        document.getElementById("selectedSeats").innerText =
            selectedSeats.length ? selectedSeats.join(", ") : "None";
    }
</script>

</body>
</html>
