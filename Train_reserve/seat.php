<?php
$carriage = $_GET['carriage'] ?? 1;

$pdo = new PDO(
    "mysql:host=localhost;dbname=train_reservation_system;charset=utf8",
    "root",
    ""
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ===== GET BOOKED SEATS FROM DATABASE =====
$stmt = $pdo->prepare("
    SELECT bs.seat_number
    FROM booking b
    INNER JOIN booking_seats bs ON b.book_id = bs.book_id
    WHERE bs.carriage = ?
      AND b.status_id = 1
");

$stmt->execute([$carriage]);

$bookedSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);

// ===== HANDLE CONFIRM =====
if (isset($_POST['confirmBooking'])) {

    $user_id = 1;      // TEMP (use session later)
    $train_id = 1;     // TEMP
    $status_id = 1;    // CONFIRMED
    $booking_code = strtoupper(substr(md5(uniqid()), 0, 12));
    $seats = explode(",", $_POST['seats']);

    // Insert booking
    $stmt = $pdo->prepare("
        INSERT INTO booking (user_id, train_id, status_id, booking_code)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$user_id, $train_id, $status_id, $booking_code]);

    $book_id = $pdo->lastInsertId();

    // Insert booking seats
    $stmtSeat = $pdo->prepare("
        INSERT INTO booking_seats (book_id, seat_number, carriage)
        VALUES (?, ?, ?)
    ");

    foreach ($seats as $seat) {
        $stmtSeat->execute([$book_id, $seat, $carriage]);
    }

    // Redirect to success page
    header("Location: ??.php?code=$booking_code");
    exit;
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
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .back-btn {
            align-self: flex-start;
            margin-bottom: 0px;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            background-color: #720A00;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        .back-btn:hover {
            background-color: #8c0d00;
            transform: translateY(-2px);
        }

        .btn-row {
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .next-btn {
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            background-color: #720A00;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        .next-btn:hover {
            background-color: #8c0d00;
            transform: translateY(-2px);
        }

        .next-btn:hover {
            background-color: #8c0d00;
            transform: translateY(-2px);
        }

        .container h2 {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .container p {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .coach {
            display: flex;
            flex-direction: row-reverse;
            margin-top: 20px;
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
            color: white;
            font-size: 13px;
            cursor: pointer;
        }

        .available { background: #4CAF50; }
        .booked { background: #f44336; cursor: not-allowed; }
        .selected { background: #2196F3; }

        .aisle { height: 15px; }

        footer {
            text-align: center;
            padding: 15px;
            background-color: #720A00;
            color: white;
        }

        /* ===== Modal ===== */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 320px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        .modal-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .modal-buttons button {
            padding: 10px 18px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        .cancel-btn {
            background: #9e9e9e;
            color: white;
        }

        .confirm-btn {
            background: #4CAF50;
            color: white;
        }
    </style>
</head>

<div class="modal" id="confirmModal">
    <div class="modal-content">
        <h3>Confirm Seat Selection</h3>
        <p>You have selected:</p>
        <p><b id="modalSeats">None</b></p>

        <form method="POST">
            <input type="hidden" name="carriage" value="<?php echo $carriage; ?>">
            <input type="hidden" name="seats" id="seatInput">

            <div class="modal-buttons">
                <button type="button" class="cancel-btn" onclick="closeConfirm()">Cancel</button>
                <button type="submit" class="confirm-btn" name="confirmBooking">Confirm</button>
            </div>
        </form>
    </div>
</div>

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
        <div class="btn-row">
            <button class="back-btn" onclick="location.href='carriage.php'">Back</button>
            <button class="next-btn" onclick="openConfirm()">Next</button>
        </div>

        <h2>Seat Selection – Carriage <?php echo $carriage; ?></h2>
        <p>Front of train ➡️</p>

        <div class="coach">
            <?php
            for ($row = 1; $row <= 20; $row++) {
                echo "<div class='row'>";

                foreach (["A", "B"] as $l) {
                    $s = $row . $l;
                    echo in_array($s, $bookedSeats)
                        ? "<div class='seat booked'>$s</div>"
                        : "<div class='seat available' onclick=\"toggleSeat(this,'$s')\">$s</div>";
                }

                echo "<div class='aisle'></div>";

                foreach (["C", "D"] as $l) {
                    $s = $row . $l;
                    echo in_array($s, $bookedSeats)
                        ? "<div class='seat booked'>$s</div>"
                        : "<div class='seat available' onclick=\"toggleSeat(this,'$s')\">$s</div>";
                }

                echo "</div>";
            }
            ?>
        </div>

        <p><b>Selected Seats:</b> <span id="selectedSeats">None</span></p>
    </div>
</main>

<footer>
    © 2025 Train Reservation System
</footer>

<script>
let selectedSeats = [];

function toggleSeat(el, seat) {

    // If seat is already selected → allow unselect
    if (selectedSeats.includes(seat)) {
        el.classList.remove("selected");
        selectedSeats = selectedSeats.filter(s => s !== seat);
    } 
    // If seat is NOT selected → check limit
    else {
        if (selectedSeats.length >= 10) {
            alert("You can select a maximum of 10 seats.");
            return;
        }
        el.classList.add("selected");
        selectedSeats.push(seat);
    }

    document.getElementById("selectedSeats").innerText =
        selectedSeats.length ? selectedSeats.join(", ") : "None";
}

function openConfirm() {
    if (selectedSeats.length === 0) {
        alert("Please select at least one seat.");
        return;
    }

    document.getElementById("modalSeats").innerText = selectedSeats.join(", ");
    document.getElementById("seatInput").value = selectedSeats.join(",");
    document.getElementById("confirmModal").style.display = "flex";
}

function closeConfirm() {
    document.getElementById("confirmModal").style.display = "none";
}

function confirmSeats() {
    alert("Seats confirmed: " + selectedSeats.join(", "));
    // location.href = "payment.php";
}
</script>


</body>
</html>
