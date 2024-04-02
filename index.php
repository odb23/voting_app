<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<?php
session_start();


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    
    header("Location: vote.php");
    exit();
}


?>


</html>