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

        h2 {
            margin-top: 10px;
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
            flex-direction: row-reverse; /* Row 1 on the right (front) */
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

        .available {
            background-color: #4CAF50;
        }

        .booked {
            background-color: #f44336;
            cursor: not-allowed;
        }

        .selected {
            background-color: #2196F3;
        }

        .aisle {
            height: 15px;
        }

        .info {
            margin-top: 15px;
        }

        .legend span {
            margin: 0 10px;
        }
    </style>
</head>
<body>

<button class="back-btn" onclick="goBack()">⬅ Back to Carriage Selection</button>

<h2 id="title">💺 Seat Selection</h2>
<p>Front of train ➡️</p>

<div class="coach" id="coach"></div>

<div class="info">
    <p><b>Selected Seats:</b> <span id="selectedSeats">None</span></p>
</div>

<div class="legend">
    <span style="color:#4CAF50;">■</span> Available
    <span style="color:#f44336;">■</span> Booked
    <span style="color:#2196F3;">■</span> Selected
</div>

<script>
    function goBack() {
        window.location.href = "carriage.html";
    }

    const params = new URLSearchParams(window.location.search);
    const carriage = params.get("carriage");

    document.getElementById("title").innerText =
        "Seat Selection – Carriage " + carriage;

    const coach = document.getElementById("coach");
    const selectedSeatsText = document.getElementById("selectedSeats");
    let selectedSeats = [];

    // Example booked seats per carriage
    const bookedSeats = {
        1: ["1A", "2B", "3C"],
        2: ["4A", "5D"],
        3: ["6B", "7C"],
        4: ["8A", "9D"],
        5: ["10B", "11C"]
    };

    for (let row = 1; row <= 20; row++) {
        const rowDiv = document.createElement("div");
        rowDiv.className = "row";

        // Left side (C, D)
        ["C", "D"].forEach(letter => {
            rowDiv.appendChild(createSeat(row + letter));
        });

        // Aisle
        const aisle = document.createElement("div");
        aisle.className = "aisle";
        rowDiv.appendChild(aisle);

        // Right side (A, B)
        ["A", "B"].forEach(letter => {
            rowDiv.appendChild(createSeat(row + letter));
        });

        coach.appendChild(rowDiv);
    }

    function createSeat(seatNumber) {
        const seat = document.createElement("div");
        seat.innerText = seatNumber;

        if (bookedSeats[carriage]?.includes(seatNumber)) {
            seat.className = "seat booked";
            return seat;
        }

        seat.className = "seat available";
        seat.onclick = () => {
            seat.classList.toggle("selected");

            if (selectedSeats.includes(seatNumber)) {
                selectedSeats = selectedSeats.filter(s => s !== seatNumber);
            } else {
                selectedSeats.push(seatNumber);
            }

            selectedSeatsText.innerText =
                selectedSeats.length ? selectedSeats.join(", ") : "None";
        };

        return seat;
    }
</script>

</body>
</html>
