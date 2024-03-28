<?php 
 $servername = "localhost";
 $username = "root";
 $password = "QwertyUIOP123";
 $dbname = "voting_app";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "<script>alert('Connection failed: $conn->connect_error);</script>";
die("Connection failed: " . $conn->connect_error);
}

?>