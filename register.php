<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="./styles/global.css">
    <link rel="stylesheet" type="text/css" href="./styles/auth.css">
</head>
<?php

if ($_SERVER["REQUEST_METHOD"] == "GET") {
} else
    // Handle student registration
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include './lib/dbconn.php';

        $username = strtoupper(htmlspecialchars($_POST["matric-no"]));
        $fname = strtoupper(htmlspecialchars($_POST["fname"]));
        $lname = strtoupper(htmlspecialchars($_POST["lname"]));
        $password = htmlspecialchars($_POST["password"]);
        $confirmPassword = htmlspecialchars($_POST["confirm-password"]);
        $department = strtoupper(htmlspecialchars($_POST["department"]));

        $errors = [];

        if ($department != "ADMIN" && !preg_match("/^[0-9]{9,10}$/", $username)) {
            $errors[] = "Students are required to use matric number";
            
        }

        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
        }

        if (!empty($errors)) {
            // Store form data in session
            $_SESSION['form_data'] = array(
                'username' => $username,
                'fname' => $fname,
                'lname' => $lname,
                'department' => $department
            );
        } else {
            $stmt = $db->prepare("INSERT INTO USERS (USERNAME, FNAME, LNAME,  DEPARTMENT_NAME, `PASSWORD`) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $fname, $lname, $department, $password);

            if ($stmt->execute()) {
                // Registration successful, redirect to login page
                header("Location: login.php");
                exit();
            } else {
                $error[] = "Error: " . $stmt->error;
                // Store form data in session
                $_SESSION['form_data'] = array(
                    'username' => $username,
                    'fname' => $fname,
                    'lname' => $lname,
                    'department' => $department
                );
            }
        }

        $_POST = array();
        $db->close();
    }

// Close the MySQL connection

?>

<body>
    <main>
        <!--  Registration Form -->
        <h1>Create an account</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div><label for="matric-no">Username (Matric number):</label>
                <input type="text" id="matric-no" name="matric-no" required minlength="9" maxlength="10" value="<?php echo isset($_SESSION['form_data']['username']) ? $_SESSION['form_data']['username'] : ''; ?>">
            </div>
            <div><label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" required value="<?php echo isset($_SESSION['form_data']['fname']) ? $_SESSION['form_data']['fname'] : ''; ?>">
            </div>
            <div><label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname" required value="<?php echo isset($_SESSION['form_data']['lname']) ? $_SESSION['form_data']['lname'] : ''; ?>">
            </div>
            <div><label for="department">Department:</label>
                <select id="department" name="department" required value="<?php echo isset($_SESSION['form_data']['department']) ? $_SESSION['form_data']['department'] : ''; ?>">
                    <option value="">Select Department</option>
                    <?php
                    include './lib/dbconn.php';

                    $sql = 'SELECT * from department';
                    $result = $db->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            print_r($row);
                            echo "<option value='" . $row["NAME"] . "'>" . $row["NAME"] . "</option>";
                        }
                    }

                    $db->close();
                    ?>
                </select>
            </div>
            <div><label for="password">Password:</label>
                <input type="password" id="password" name="password" required minlength="8">
            </div>
            <div><label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required minlength="8">
            </div>


            <?php
            // Display error messages
            if (!empty($errors)) {
                echo "<div class='error'> ";
                foreach ($errors as $error) {
                    echo "<p>$error</p>";
                }
                echo "</div> ";
            }
            ?>

            <input type="submit" value="Register">
        </form>

        <a href="login.php">Login your account</a>
    </main>
</body>

</html>