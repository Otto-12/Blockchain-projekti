<?php           
// Tietokantayhteyden tiedot
$servername = "mariadb.vamk.fi";
$username = "";
$password = "";
$dbname = "";

// Luo yhteys tietokantaan
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Tarkista yhteyden onnistuminen
if (!$conn) {
    die("Yhteys tietokantaan epäonnistui: " . mysqli_connect_error());
}


 ?>
