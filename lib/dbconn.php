<?php
$db_servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "voting_app";

$db = new mysqli($db_servername, $db_username, $db_password, $dbname);

// Check connection
if ($db->connect_error) {
    echo "<script>alert('Connection failed: $db->connect_error);</script>";
    die("Connection failed: " . $db->connect_error);
}
