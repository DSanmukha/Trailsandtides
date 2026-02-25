<?php
$conn = new mysqli("localhost", "root", "", "trailsandtides");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>