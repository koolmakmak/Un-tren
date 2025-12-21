<!DOCTYPE html>
<html>
<head>
    <title>Select Carriage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            text-align: center;
        }

        h2 {
            margin-top: 30px;
        }

        .carriages {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .carriage {
            width: 140px;
            height: 90px;
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

        .carriage:hover {
            background: #388E3C;
        }

        /* First class special style */
        .first-class {
            background: #9C27B0; /* purple */
        }

        .first-class:hover {
            background: #7B1FA2;
        }

        .class-label {
            font-size: 13px;
            margin-top: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Select Carriage</h2>
<p>Please choose a carriage</p>

<div class="carriages">
    <?php
        for ($i = 1; $i <= 5; $i++) {

            if ($i == 1) {
                // Carriage 1 – First Class
                echo "
                <div class='carriage first-class'
                     onclick=\"location.href='seat.php?carriage=1'\">
                    <div>Carriage 1</div>
                    <div class='class-label'>(First Class)</div>
                </div>";
            } else {
                // Normal carriages
                echo "
                <div class='carriage'
                     onclick=\"location.href='seat.php?carriage=$i'\">
                    Carriage $i
                </div>";
            }
        }
    ?>
</div>

</body>
</html>
