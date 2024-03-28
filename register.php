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
include './scripts/dbconn.php';

$conn->autocommit(false);

// Handle student registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["matric-no"]);
    $fname = strtoupper(htmlspecialchars($_POST["fname"]));
    $lname = strtoupper(htmlspecialchars($_POST["lname"]));
    $password = htmlspecialchars($_POST["password"]);
    $confirmPassword = htmlspecialchars($_POST["confirm-password"]);
    $department = strtoupper(htmlspecialchars($_POST["department"]));

    $errors = [];

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (!empty($errors)) {
        $conn->rollback();
    } else {
        $stmt = $conn->prepare("INSERT INTO student (username, fname, lname,  department) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $fname, $lname, $department);

        if ($stmt->execute()) {
            $stmt = $conn->prepare("INSERT INTO auth (id, pswd) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password);

            if ($stmt->execute()) {
                $conn->commit();
                // Registration successful, redirect to login page
                header("Location: login.php");
                exit();
            } else {
                $conn->rollback();
                $error[] = "Error: " . $stmt->error;
            }
        } else {
            $conn->rollback();
            $error[] = "Error: " . $stmt->error;
        }
    }

    // Store form data in session
    $_SESSION['form_data'] = array(
        'username' => $username,
        'fname' => $fname,
        'lname' => $lname,
        'department' => $department
    );

   
}

// Close the MySQL connection
$conn->close();

?>

<body>
<main>
    <!--  Registration Form -->
    <h1>Create an account</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div><label for="matric-no">Matric Number:</label>
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
                include './scripts/dbconn.php';

                $sql = 'SELECT * from department';
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        if ($row["name"] == "ADMIN") {
                            continue;
                        }
                        echo "<option value='" . $row["name"] . "'>" . $row["name"] . "</option>";
                    }
                }

                $conn->close();
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