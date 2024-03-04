<?php
$servername = "102.220.22.167";
$username = "sibo";
$password = "Bosisi$2o23#";
$database = "mne";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";
?>