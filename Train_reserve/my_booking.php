<?php
session_start();
require_once 'connect.php';

// Get booking code from URL
$booking_code = $_GET['code'] ?? '';

// Initialize variables
$booking_details = null;
$seats = [];

if ($booking_code) {
    // Fetch booking details from database
    $stmt = $conn->prepare("
        SELECT 
            b.booking_code,
            b.book_id,
            b.user_id,
            b.train_id,
            b.booking_date,
            GROUP_CONCAT(CONCAT(bs.seat_number, ' (Carriage ', bs.carriage, ')') SEPARATOR ', ') as seats,
            bs.carriage
        FROM booking b
        INNER JOIN booking_seats bs ON b.book_id = bs.book_id
        WHERE b.booking_code = ?
        GROUP BY b.book_id
    ");
    
    $stmt->bind_param("s", $booking_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $booking_details = $result->fetch_assoc();
        
        // Get individual seats for display
        $stmt2 = $conn->prepare("
            SELECT seat_number, carriage
            FROM booking_seats
            WHERE book_id = ?
            ORDER BY carriage, seat_number
        ");
        $stmt2->bind_param("i", $booking_details['book_id']);
        $stmt2->execute();
        $seats_result = $stmt2->get_result();
        
        while ($seat = $seats_result->fetch_assoc()) {
            $seats[] = $seat;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Booking - Confirmation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: linear-gradient(to bottom, #f4f6f8, #e9ecef);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== HEADER ===== */
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
            height: 50px;
            margin-right: 12px;
        }

        header h1 {
            font-size: 22px;
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

        /* ===== MAIN CONTENT ===== */
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .booking-card {
            background: white;
            width: 500px;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .success-icon {
            text-align: center;
            font-size: 64px;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
            color: #333;
        }

        .booking-code {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #720A00;
            background: #f0f0f0;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            letter-spacing: 2px;
        }

        .detail-section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .detail-section:last-of-type {
            border-bottom: none;
        }

        .detail-section h3 {
            font-size: 16px;
            color: #720A00;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .detail {
            font-size: 16px;
            margin: 8px 0;
            display: flex;
            justify-content: space-between;
        }

        .label {
            font-weight: 600;
            color: #555;
        }

        .value {
            color: #333;
        }

        .seats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .seat-badge {
            background: #4CAF50;
            color: white;
            padding: 8px;
            border-radius: 6px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .back-btn, .print-btn {
            flex: 1;
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            border: none;
            font-size: 16px;
            transition: background 0.3s, transform 0.2s;
        }

        .back-btn {
            background-color: #720A00;
            color: white;
        }

        .back-btn:hover {
            background-color: #8c0d00;
            transform: translateY(-2px);
        }

        .print-btn {
            background-color: #4CAF50;
            color: white;
        }

        .print-btn:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .no-booking {
            text-align: center;
            padding: 40px;
        }

        .no-booking p {
            font-size: 18px;
            color: #666;
            margin-bottom: 20px;
        }

        /* ===== FOOTER ===== */
        footer {
            text-align: center;
            padding: 15px;
            background-color: #720A00;
            color: white;
            font-size: 14px;
        }

        @media print {
            header, footer, .button-group {
                display: none;
            }
            
            body {
                background: white;
            }
            
            .booking-card {
                box-shadow: none;
                width: 100%;
            }
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
        <a href="my_booking.php">My Booking</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<main>
    <div class="booking-card">
        <?php if ($booking_details): ?>
            <div class="success-icon">✓</div>
            <h2>Booking Confirmed!</h2>
            
            <div class="booking-code">
                <?= htmlspecialchars($booking_details['booking_code']) ?>
            </div>

            <div class="detail-section">
                <h3>Booking Information</h3>
                <div class="detail">
                    <span class="label">Booking ID:</span>
                    <span class="value">#<?= htmlspecialchars($booking_details['book_id']) ?></span>
                </div>
                <div class="detail">
                    <span class="label">Booking Date:</span>
                    <span class="value"><?= date('F j, Y, g:i a', strtotime($booking_details['booking_date'])) ?></span>
                </div>
                <div class="detail">
                    <span class="label">Train ID:</span>
                    <span class="value">Train <?= htmlspecialchars($booking_details['train_id']) ?></span>
                </div>
            </div>

            <div class="detail-section">
                <h3>Seat Details</h3>
                <div class="detail">
                    <span class="label">Total Seats:</span>
                    <span class="value"><?= count($seats) ?> seat(s)</span>
                </div>
                <div class="seats-grid">
                    <?php foreach ($seats as $seat): ?>
                        <div class="seat-badge">
                            <?= htmlspecialchars($seat['seat_number']) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="detail" style="margin-top: 15px;">
                    <span class="label">Carriage:</span>
                    <span class="value">Carriage <?= htmlspecialchars($booking_details['carriage']) ?></span>
                </div>
            </div>

            <div class="button-group">
                <a href="index.php" class="back-btn">Back to Home</a>
                <button class="print-btn" onclick="window.print()">Print Ticket</button>
            </div>

        <?php else: ?>
            <div class="no-booking">
                <div class="success-icon" style="color: #f44336;">✗</div>
                <h2>No Booking Found</h2>
                <p>The booking code is invalid or the booking does not exist.</p>
                <a href="index.php" class="back-btn" style="display: inline-block;">Back to Home</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<footer>
    © 2025 Train Reservation System | All Rights Reserved
</footer>

</body>
</html>