<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOTING PAGE</title>
    <link rel="stylesheet" type="text/css" href="./styles/global.css">
    <link rel="stylesheet" href="./styles/vote.css">

</head>

<?php
session_start();

$name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "";
$username = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "";
$user_department = isset($_SESSION['department']) ? $_SESSION['department'] : "";
$admin = isset($_SESSION['admin']) ? $_SESSION['admin'] : false;

if ($_SERVER["REQUEST_METHOD"]  == "GET") {

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}
?>

<body>
    <main>
        <?php include "./templates/header.php" ?>

        <h3 class="header">Welcome, <?php
                                    echo ucwords(strtolower($name));
                                    ?>
        </h3>

        <?php

        if ($admin) {
            include "./templates/admin-view-result.php";
        } else  {
            include "./templates/user-vote.php";
        }
        ?>
    </main>
</body>

</html>