<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <style>
        body {
            background-color: white;
            color: black;
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 100px;
        }

        h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        p {
            font-size: 24px;
        }
    </style>
</head>
<?php
session_start();

if (!isset($_SESSION['_username'])) {
    header("Location: login.php");
    exit();
}
?>

<body>
    <h1>404</h1>
    <p>Page Not Found</p>
</body>

</html>