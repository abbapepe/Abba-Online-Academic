<?php
$mysqli = new mysqli("localhost", "root", "", "bene_college");

if ($mysqli->connect_error) {
    die("Database Connection Failed: " . $mysqli->connect_error);
}
?>
