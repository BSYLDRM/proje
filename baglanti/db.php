<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "proje";

// Create connection
$db = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($db->connect_error) {
  die("Veritabani baglantisi hatasi: " . $db->connect_error);
}
echo "";
?>
