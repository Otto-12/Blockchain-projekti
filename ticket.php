<?php
// Start session
session_start();
if (isset($_SESSION["user_id"]) && isset($_SESSION["username"])) {
    // If logged in, set $logged_in to true and retrieve the username
    $logged_in = true;
    $username = strip_tags($_SESSION["username"]); // Strip HTML tags
    $user_id = $_SESSION["user_id"]; // Added this line to retrieve user_id
} else {
    // If not logged in, set $logged_in to false
    $logged_in = false;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create connection
    include_once '/u/b/e2101504/public_html/PHPProject/Blockchain-projekti/config/config_database.php';
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve selected numbers from the form submission
    $selected_numbers = $_POST['selected_numbers'];

    // Check if the user is logged in
    if ($logged_in) {
        // Get current timestamp
        $timestamp = date("Y-m-d H:i:s");

        // Fetch the latest block index and current hash from the database
        $sql = "SELECT MAX(block_index) AS max_index, current_hash FROM tickets";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $block_index = $row['max_index'] + 1;
        $previous_hash = $row['current_hash'];

        // Hash the submit action of the lottery numbers
        $hashed_action = hash("sha256", $timestamp . $selected_numbers . $previous_hash);

        // Insert the selected numbers into the database
        $sql = "INSERT INTO tickets (user_id, block_index, timestamp, ticket_data, current_hash, previous_hash) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iissss", $user_id, $block_index, $timestamp, $selected_numbers, $hashed_action, $previous_hash); // Updated bind parameters
        if (mysqli_stmt_execute($stmt)) {
            // Update previous hash for the previous block
            if ($block_index > 1) {
                $previous_block_index = $block_index - 1;
                $sql = "SELECT current_hash FROM tickets WHERE block_index = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $previous_block_index);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $current_hash);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);

                $sql = "UPDATE tickets SET previous_hash = ? WHERE block_index = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "si", $current_hash, $block_index);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            echo "Lottery numbers saved successfully. Action Hash: " . $hashed_action;
            // Redirect to index.php
            header("Location: timer.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "User not logged in.";
    }

    // Close connection
    mysqli_close($conn);
}

?>
<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Number Selection</title>
  
    <link rel="stylesheet" type="text/css" href="css/ticket.css"> </link>

</head>

<body>
    
    <div class="container">
    <div class="menu">
        <img src="photos/logo_real.jfif" alt="Logo" class="menu-logo">
        <a href="frontpage.php">Home</a>
        <a href="timer.php">Today's Lottery</a>
        <a href="ticket.php">Make a ticket</a>
        <a href="oldtickets.php">Your Tickets</a>
        <a href="blockchain.php">Past winning number</a>
        <a href="register.php">Register</a>
        <a href="login.php">Log In</a>
    </div>
        <h1>Choose 6 numbers for your lottery ticket</h1>
        <form action="" method="post">
            <div class="number-selector">
                <?php
                // Generate numbers from 1 to 49
                for ($i = 1; $i <= 49; $i++) {
                    echo "<div class='number' data-number='$i'>$i</div>";
                }
                ?>
            </div>
            <input type="hidden" name="selected_numbers" id="selected_numbers">
            <br>
            <input type="submit" value="Lähetä">
        </form>
    </div>

    <div class="user-info">
        <?php
        // Display user information
        if ($logged_in) {
            echo "<p class='logged-in'>Logged in as: $username</p>";
            echo "<p><a href='logout.php'>Logout</a></p>";
        } else {
            echo "<p class='not-logged-in'>Not logged in</p>";
        }
        ?>
    </div>

    <script>
        const numbers = document.querySelectorAll('.number');
        let selectedCount = 0;

        numbers.forEach(number => {
            number.addEventListener('click', () => {
                if (number.classList.contains('selected')) {
                    // If already selected, unselect it
                    number.classList.remove('selected');
                    selectedCount--;
                } else if (selectedCount < 6) {
                    // If not selected and less than 6 numbers selected, select it
                    number.classList.add('selected');
                    selectedCount++;
                }

                // Update hidden input value with selected numbers
                const selectedNumbers = Array.from(document.querySelectorAll('.number.selected'))
                    .map(selectedNumber => selectedNumber.dataset.number)
                    .join(',');
                document.getElementById('selected_numbers').value = selectedNumbers;

                // Enable all numbers
                numbers.forEach(num => {
                    num.classList.remove('disabled');
                });

                // Disable further selection if 6 numbers are selected
                if (selectedCount === 6) {
                    numbers.forEach(num => {
                        if (!num.classList.contains('selected')) {
                            num.classList.add('disabled');
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>
