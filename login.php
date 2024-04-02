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
include './lib/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    $errors = [];

    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? and `password`  = ?");
    $uppercase_username = strtoupper($username);
    $stmt->bind_param("ss", $uppercase_username, $password);
    $stmt->execute();
    $user = $stmt->get_result();

    if ($user->num_rows < 1) {
        $errors[] = "Invalid credentials. Try again!";
    } else {
        $row = $user->fetch_assoc();

        if ($row["USERNAME"] !== $uppercase_username || $row["PASSWORD"] !== $password) {
            $errors[] = "Invalid credentials. Try again!";
        } else {
            $sql = $db->prepare("SELECT * from `ADMIN` where username = ?");
            $sql->bind_param("s", $uppercase_username);
            $sql->execute();
            $res = $sql->get_result();

            $_SESSION['admin'] = $res->num_rows > 0 ? true : false;
            $_SESSION['user_id'] = $row['USERNAME'];
            $_SESSION['user_name'] = $row['LNAME'] . " " . $row['FNAME'];
            $_SESSION['department'] = $row['DEPARTMENT_NAME'];


            header("Location: index.php");
            exit();
        }
    }

    $_POST = array();
    // Store form data in session
    $_SESSION['form_data'] = array(
        'username' => $username,
        'password' => $password
    );
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
}

$db->close();
?>

<body>
    <main>
        <!--  Login Form -->
        <h1>Sign In</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div><label for="username">Username (Matric Number):</label>
                <input type="text" id="username" name="username" required minlength="5" maxlength="10" value="<?php echo isset($_SESSION['form_data']['username']) ? $_SESSION['form_data']['username'] : ''; ?>">
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