<?php           
// Tietokantayhteyden tiedot
$servername = "mariadb.vamk.fi";
$username = "e2101504";
$password = "9kcSag6sfXA";
$dbname = "e2101504_Lottery";

// Luo yhteys tietokantaan
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Tarkista yhteyden onnistuminen
if (!$conn) {
    die("Yhteys tietokantaan epäonnistui: " . mysqli_connect_error());
}


 ?>