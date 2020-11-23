<?php
$servername = "localhost";
$username = "jens";
$password = "Admin@2020";
$dbname = "logs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO logentries (username, password, ip_address, date)
VALUES ('John', 'Doe', '123', now())";

if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>