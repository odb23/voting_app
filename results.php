<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="stylesheet" type="text/css" href="./styles/global.css">
    <link rel="stylesheet" href="./styles/vote.css">

</head>

<?php
session_start();

$server_req_method = $_SERVER["REQUEST_METHOD"];

$name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "";
$username = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "";
$user_department = isset($_SESSION['department']) ? $_SESSION['department'] : "";
$admin = isset($_SESSION['admin']) ? $_SESSION['admin'] : false;


$admin_view_result = false;

if ($server_req_method == "GET") {
    $req_year = isset($_GET['year']) ? $_GET['year'] : "";
    $req_dept = isset($_GET['department']) ? $_GET['department'] : "";
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

        <h4> <?php echo $req_year . " Department of " . $req_dept ?> Election Results </h4>
        <?php
            include "./templates/user-results.php";
       
        ?>
    </main>

</body>

</html>