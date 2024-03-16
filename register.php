<?php
include_once '/u/b/e2101504/public_html/PHPProject/Blockchain-projekti/config/config_database.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve form data and apply strip_tags()
    $username = strip_tags(mysqli_real_escape_string($conn, $_POST['username']));
    $email = strip_tags(mysqli_real_escape_string($conn, $_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check for empty fields
    if (empty($username) || empty($email) || empty($password)) {
        // Handle empty fields error
        $error_message = "All fields are required.";
    } else {
        // Check if the username or email already exists
        $sql_check = "SELECT * FROM users WHERE username='$username' OR email='$email'";
        $result_check = mysqli_query($conn, $sql_check);
        if (mysqli_num_rows($result_check) > 0) {
            $error_message = "The username or email already exists. Please choose a different one.";
        } else {
            // Prepare SQL statement
            $sql_register = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

            // Execute SQL statement
            if (mysqli_query($conn, $sql_register)) {
                // Redirect user to login page after successful registration
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Error: " . $sql_register . "<br>" . mysqli_error($conn);
            }
        }
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

    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="css/register.css">
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
        
        <!-- Registration Form -->
        <h1>Register</h1>
        <div>
            <?php if (isset($error_message)) { ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php } ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <br><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <br><br>
                <input type="submit" value="Register">
            </form>
        </div>
    </div>
    
    <!-- Additional Information -->
    <div class="centered">
        <p>Already have an account? <a href="login.php">Log in here</a></p>
    </div>
</body>

</html>
