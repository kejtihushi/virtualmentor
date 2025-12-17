<?php
$servername = "localhost";
$username = "root"; // Ndrysho sipas phpMyAdmin
$password = ""; // Fjalëkalimi në phpMyAdmin (lëre bosh nëse s’ke vendosur një)
$dbname = "virtualmentor";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Lidhja dështoi: " . $conn->connect_error);
}
?>
