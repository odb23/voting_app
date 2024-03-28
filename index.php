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
    
    $currentYear =  date('Y');
    $dept = isset($_SESSION['department']) ? $_SESSION['department'] : "";
    $admin = isset($_SESSION['admin']) ? $_SESSION['admin'] : false;
    $route = !$admin ? "Location: vote.php?year=$currentYear&department=$dept" : "Location: vote.php";
    header($route);
    exit();
}


?>


</html>