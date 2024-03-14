<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Set character encoding and viewport for responsive design -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title of the page -->
    <title>Lottery Timer</title>
    <!-- Link to external stylesheet for styling -->
    <link rel="stylesheet" type="text/css" href="https://www.cc.puv.fi/~e2101504/PHPProject/css/timer.css">
</head>

<body>
    <div class="container">
        <!-- Navigation menu -->
        <div class="menu">
            <!-- Logo -->
            <img src="photos/logo_real.jfif" alt="Logo" class="menu-logo">
            <!-- Navigation links -->
            <a href="frontpage.php">Home</a>
            <a href="timer.php">Today's Lottery</a>
            <a href="ticket.php">Make a ticket</a>
            <a href="oldtickets.php">Your Tickets</a>
            <a href="blockchain.php">Past winning number</a>
            <a href="register.php">Register</a>
            <a href="login.php">Log In</a>
        </div>
    </div>

    <!-- Headline image with overlay -->
    <div class="headline-image">
        <img src="photos/Money.jpg" alt="Headline Image">
        <div class="overlay"></div>
        <!-- Headline text -->
        <h1 class="headline">Todays Raffle</h1>
    </div>

    <!-- Timer container -->
    <div class="container2">
        <h1>Lottery Draw Timer</h1>
        <!-- Timer display -->
        <div id="timer"></div>
    </div>

    <!-- JavaScript code to handle timer functionality -->
    <script>
        // Function to update timer display
        function updateTimer() {
            // Set the date and time for the next lottery draw (1 hour from now)
            var nextDraw = new Date();
            nextDraw.setMinutes(nextDraw.getMinutes() + 60);

            // Update the countdown every second
            var timerInterval = setInterval(function () {
                // Get the current date and time
                var now = new Date().getTime();

                // Calculate the remaining time
                var distance = nextDraw - now;

                // Calculate minutes and seconds
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Display the timer
                document.getElementById("timer").innerHTML = "Next draw in: " + minutes + "m " + seconds + "s";

                // If the countdown is over, redirect to the lottery page
                if (distance < 0) {
                    clearInterval(timerInterval); // Stop the timer interval
                    window.location.href = "index.php";
                }
            }, 1000);
        }

        // Call updateTimer function immediately to ensure timer starts immediately
        updateTimer();
    </script>
</body>

</html>
