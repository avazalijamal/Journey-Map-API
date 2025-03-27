<?php
$host = "localhost";
$dbname = "u879108216_journeymap";
$username = "u879108216_journeymap";
$password = "Salam12345Salam";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}
?>
