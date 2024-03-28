<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="./styles/global.css">
    <link rel="stylesheet" type="text/css" href="./styles/auth.css">
</head>
<?php
session_start();
include './scripts/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["matric-no"]);
    $password = htmlspecialchars($_POST["password"]);

    $errors = [];

    $stmt = $conn->prepare("SELECT * FROM student WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result();

    if ($user->num_rows < 1) {
        $errors[] = "Invalid credentials. Try again!";
    } else {
        $sql = $conn->prepare("select * from auth where id = ?");
        $sql->bind_param("s", $username);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows < 1) {
            $errors[] = "Invalid credentials. Try again!";
        } else {
            $row = $result->fetch_assoc();
            if ($row["id"] !== $username || $row["pswd"] !== $password) {
                $errors[] = "Invalid credentials. Try again!";
            } else {
                $user_row = $user->fetch_assoc();
                $sql = $conn->prepare("select * from admin where username = ?");
                $sql->bind_param("s", $username);
                $sql->execute();
                $result = $sql->get_result();

                if ($result->num_rows > 0) {
                    $_SESSION['admin'] = true;
                }
                $_SESSION['user_id'] = $user_row['username'];
                $_SESSION['user_name'] = $user_row['lname'] . " " . $user_row['fname'];
                $_SESSION['department'] = $user_row['department'];

                header("Location: index.php");
                exit();
            }
        }
    }

    // Store form data in session
    $_SESSION['form_data'] = array(
        'matric-no' => $username,
        'password' => $password
    );
} else if ($_SERVER["REQUEST_METHOD"] == "GET")  {
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
}

$conn->close();
?>

<body>
    <main>
        <!--  Registration Form -->
        <h1>Sign In</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div><label for="matric-no">Username (Matric Number):</label>
                <input type="text" id="matric-no" name="matric-no" required minlength="5" maxlength="10" value="<?php echo isset($_SESSION['form_data']['matric-no']) ? $_SESSION['form_data']['matric-no'] : ''; ?>">
            </div>

            <div><label for="password">Password:</label>
                <input type="password" id="password" name="password" required minlength="8" value="<?php echo isset($_SESSION['form_data']['password']) ? $_SESSION['form_data']['password'] : ''; ?>">
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


            <input type="submit" value="Login">
        </form>

        <a href="register.php">Create an account</a>
    </main>
    </form>
</body>

</html>