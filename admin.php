<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Section</title>
    <link rel="stylesheet" type="text/css" href="./styles/global.css">
    <link rel="stylesheet" type="text/css" href="./styles/admin.css">
</head>
<?php 
session_start();
$admin = isset($_SESSION['admin']) ? $_SESSION['admin'] : false;

if ($_SERVER["REQUEST_METHOD"] == "GET")  {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    if (!$admin) {
        header("Location: index.php");
        exit();
    }
}
?>

<body>
    <main>
    <?php include "./templates/header.php"?>
    
        <h3 class="header">Welcome, Admin
        </h3>

        <fieldset>
            <legend>Candidate Management</legend>
            <p class="italic">Candidate must be a registered user of the platform.</p>
            <form action="" method="post">
                <h3>Add Candidate</h3>
                <div>
                    <label>Enter username (or matric number) </label>
                    <input type="text" required name="username" id="username" minlength="9" maxlength="10">
                </div>

                <div><label for="position">Position:</label>
                    <select id="position" name="position" required >
                        <option value="">Select position</option>
                        <?php
                        include './scripts/dbconn.php';

                        $sql = 'SELECT * from executive_position';
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                               
                                echo "<option value='" . $row["title"] . "'>" . $row["title"] . "</option>";
                            }
                        }

                        $conn->close();
                        ?>
                    </select>
                </div>

                <input class="w-fit" type="submit" value="Save Candidate">
            </form>
            <br><br>
            <hr><br><br>
            <form action="" method="post">
                <h3>Remove Admin</h3>
                <div>
                    <label>Enter username (or matric number) </label>
                    <input type="text" required name="username" id="username" minlength="9" maxlength="10">
                </div>

                <input class="w-fit" type="submit" value="Remove Candidate">
            </form>
        </fieldset>

        <fieldset>
            <legend>Admin Management</legend>
            <p class="italic">Admin must be a registered user of the platform.</p>
            <form action="" method="post">
                <h3>Add Admin</h3>
                <div>
                    <label>Enter username (or matric number) </label>
                    <input type="text" required name="username" id="username" minlength="9" maxlength="10">
                </div>

                <input class="w-fit" type="submit" value="Add Admin">
            </form>
            <br><br>
            <hr><br><br>
            <form action="" method="post">
                <h3>Remove Admin</h3>
                <div>
                    <label>Enter username (or matric number) </label>
                    <input type="text" required name="username" id="username" minlength="9" maxlength="10">
                </div>

                <input class="w-fit" type="submit" value="Remove Admin">
            </form>
        </fieldset>

        <fieldset>
            <legend>Department Management</legend>
            <form action="" method="post">
                <h3>Add Department</h3>
                <div>
                    <label>Enter department title</label>
                    <input type="text" required name="department" id="department">
                </div>

                <input class="w-fit" type="submit" value="Save Changes">
            </form>
            <br><br>
            <hr><br><br>
            <form action="" method="post">
                <h3>Remove Department</h3>
                <div>
                    <label>Enter department title </label>
                    <input type="text" required name="department" id="department">
                </div>

                <input class="w-fit" type="submit" value="Save Changes">
            </form>
        </fieldset>


    </main>
</body>

</html>