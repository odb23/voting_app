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
if ($_SERVER["REQUEST_METHOD"]  == "GET") {

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $req_year = isset($_GET['year']) ? $_GET['year'] : "";
    $req_dept = isset($_GET['department']) ? $_GET['department'] : "";


    $name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "";
    $username = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "";
    $user_department = isset($_SESSION['department']) ? $_SESSION['department'] : "";
    $admin = isset($_SESSION['admin']) ? $_SESSION['admin'] : false;

    if ((!$req_dept || !$req_year) && !$admin) {
        $currentYear =  date('Y');
        header("Location: vote.php?year=$currentYear&department=$user_department");
        exit();
    }
}
?>

<body>
    <main>
        <!-- <?php include "./templates/header.php" ?> -->

        <h3 class="header">Welcome, <?php
                                    echo ucwords(strtolower($name));
                                    ?>
        </h3>

        <?php

        if ($admin) {
         echo "<a class='link-button' href='results.php'>Go to View Results</a>";
        } else if ($req_dept == $user_department && !$admin) {
            include "./templates/user-vote.php";
        } else {
            echo "<h4>You are not allowed to view this page</h4>";
        }
        ?>
    </main>
</body>

</html>