<?php
// Start session
session_start();

// Create connection using database credentials
include_once '/u/b/e2101504/public_html/PHPProject/config/config_database.php';
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (isset($_SESSION["user_id"]) && isset($_SESSION["username"])) {
    // If logged in, set $logged_in to true and retrieve the username
    $logged_in = true;
    $username = strip_tags($_SESSION["username"]); // Strip HTML tags

    // Function to generate 6 random numbers
    function generateRandomNumbers()
    {
        $numbers = array();
        for ($i = 0; $i < 6; $i++) {
            $numbers[] = rand(1, 49); // Change range if needed
        }
        sort($numbers); // Sort the numbers in ascending order
        return implode(", ", $numbers); // Convert array to string separated by comma and space
    }

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve lottery numbers
    $numbers = generateRandomNumbers();

    // Get current timestamp
    $timestamp = date("Y-m-d H:i:s");

    // Generate current hash value
    $current_hash = hash('sha256', $numbers);

    // Retrieve previous hash value
    $sql_previous_hash = "SELECT current_hash FROM lottery_info ORDER BY timestamp DESC LIMIT 1";
    $result_previous_hash = mysqli_query($conn, $sql_previous_hash);
    $row_previous_hash = mysqli_fetch_assoc($result_previous_hash);
    $previous_hash = $row_previous_hash['current_hash'];

    // Insert the lottery numbers into the database
    $sql = "INSERT INTO lottery_info (timestamp, data, current_hash, previous_hash) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $timestamp, $numbers, $current_hash, $previous_hash);
    if (mysqli_stmt_execute($stmt)) {
        error_log("Lottery numbers saved successfully.");
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
} else {
    // If not logged in, set $logged_in to false
    $logged_in = false;
}
?>

<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lottery Number Generator</title>

    <link rel="stylesheet" type="text/css" href="css/index.css"></link>
</head>

<body>
    <div class="container">
        <!-- Navigation Menu -->
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
        
        <!-- Display Today's Numbers -->
        <h1>Todays numbers</h1>
        <div>
            <div class="numbers">
                <?php echo strip_tags($numbers); ?> <!-- Strip HTML tags -->
            </div>
        </div>
    </div>
    
    <!-- Display User Information -->
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
</body>

</html>
