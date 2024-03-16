<?php
session_start();
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["username"])) {
    // If not logged in, redirect to the login page or display an access denied message
    header("Location: login.php"); // Redirect to the login page
    exit; // Stop further execution of the script
}

// Include the database configuration file to establish a connection
include_once '/u/b/e2101504/public_html/PHPProject/Blockchain-projekti/config/config_database.php';

// Establish a connection to the database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if the connection was successful
if (!$conn) {
    // If connection fails, display an error message and terminate the script
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (isset($_SESSION["user_id"]) && isset($_SESSION["username"])) {
    // If logged in, set $logged_in to true and retrieve the username
    $logged_in = true;
    $username = strip_tags($_SESSION["username"]); // Strip HTML tags
    $user_id = $_SESSION["user_id"];
} else {
    // If not logged in, set $logged_in to false
    $logged_in = false;
}

// Set the default timezone to Europe/Helsinki
date_default_timezone_set('Europe/Helsinki');

// Retrieve blockchain data from the transfer_info table for the logged-in user only
$sql = "SELECT * FROM tickets WHERE user_id = $user_id ORDER BY block_index ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Tickets</title>
    <link rel="stylesheet" type="text/css" href="css/blockchain.css"> </link>
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
        <h1>Your Tickets</h1>
    </div>
   
    <table>
        <thead>
            <tr>
               
                <th>User Id</th>
                <th>Ticket Data</th>
                <th>Time Stamp</th>
                <th>Previous Hash</th>
                <th>Current Hash</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop through each row of blockchain data and output it in the table
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
              
                echo "<td>" . $row["user_id"] . "</td>";
                echo "<td>" . strip_tags($row["ticket_data"]) . "</td>"; // Strip HTML tags
                echo "<td>" . $row["timestamp"] . "</td>";
                echo "<td>" . $row["previous_hash"] . "</td>";
                echo "<td>" . $row["current_hash"] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Close the database connection
    mysqli_close($conn);

    // Check if the user is logged in and display appropriate message
    if ($logged_in) {
        echo "<p style='color: green; font-weight: normal; text-align: center; font-family: Arial;'>Logged in as: $username</p>";
        echo "<p style='text-align: center; font-family: Arial;'><a href='logout.php'>Logout</a></p>";
    } else {
        echo "<p style='color: red; font-weight: normal; text-align: center; font-family: Arial;'>Not logged in</p>";
    }


    ?>

</body>

</html>
