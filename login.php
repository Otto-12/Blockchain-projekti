<?php
// Create connection
include_once '/u/b/e2101504/public_html/PHPProject/config/config_database.php';
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Start session
session_start();

// Check if the form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = strip_tags($_POST['username']); // Strip HTML tags
    $password = strip_tags($_POST['password']); // Strip HTML tags

    // Check if any of the fields are empty
    if (empty($username) || empty($password)) {
        // Handle empty fields error
        $error_message = "Kaikki kentät ovat pakollisia.";
    } else {
        // Prepare and bind SQL statement
        $sql = "SELECT user_id, username, password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            // Check if user exists
            if (mysqli_num_rows($result) == 1) {
                // Fetch user data
                $row = mysqli_fetch_assoc($result);

                // Verify password
                if (password_verify($password, $row['password'])) {
                    // Start session and store user data
                    $_SESSION["user_id"] = $row["user_id"];
                    $_SESSION["username"] = $row["username"];

                    // Redirect user to dashboard or homepage
                    header("Location: ticket.php");
                    exit();
                } else {
                    // Incorrect password
                    $error_message = "Virheellinen käyttäjätunnus tai salasana.";
                }
            } else {
                // User not found
                $error_message = "Käyttäjää ei löydy.";
            }
        } else {
            // Database query error
            $error_message = "Virhe tietokantakyselyssä: " . mysqli_error($conn);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirjaudu sisään</title>
    <link rel="stylesheet" type="text/css" href="css/login.css"></link>
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
        
        <!-- Login Form -->
        <h1>Kirjaudu sisään</h1>
        <div>
            <?php if (isset($error_message)) { ?>
                <p class="error-message">
                    <?php echo $error_message; ?>
                </p>
            <?php } ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="username">Käyttäjätunnus:</label>
                <input type="text" id="username" name="username" required>
                <br><br>
                <label for="password">Salasana:</label>
                <input type="password" id="password" name="password" required>
                <br><br>
                <input type="submit" value="Kirjaudu sisään">
            </form>
        </div>
    </div>
    
    <!-- Additional Information -->
    <div class="centered">
        <p>Eikö sinulla ole tiliä? <a href="register.php">Rekisteröidy täällä</a></p>
    </div>
</body>

</html>
