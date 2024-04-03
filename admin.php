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
$name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "";


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    if (!$admin) {
        header("Location: index.php");
        exit();
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "./lib/admin-functions.php";


    $form_name = $_POST["form-name"];

    switch ($form_name) {
        case "add-admin":
            $add_admin_errors = [];
            $username = isset($_POST["username"]) ? strtoupper($_POST["username"]) : "";
            $res = addAdminAccount($username, $add_admin_errors);
            if ($res) {
                echo "<script> alert('User added'); </script>";
            }
            break;
        case "remove-admin":
            $remove_admin_errors = [];
            $username = isset($_POST["username"]) ? strtoupper($_POST["username"]) : "";
            $res = removeAdminFromAccount($username, $remove_admin_errors);
            if ($res) {
                echo "<script> alert('Admin rights removed from user'); </script>";
            }
            break;
        case "add-candidate":
            $add_cand_errors = [];
            $username = isset($_POST["username"]) ? strtoupper($_POST["username"]) : "";
            $position = isset($_POST["position"]) ? $_POST["position"] : "";
            $year = date('Y');

            $res = addCandidate($username, $position, $year, $add_cand_errors);
            if ($res) {
                echo "<script> alert('Candidate added'); </script>";
            }
            break;
        case "remove-candidate":
            $remove_cand_errors = [];
            $username = isset($_POST["username"]) ? strtoupper($_POST["username"]) : "";
            $type = isset($_POST["type"]) ? strtoupper($_POST["type"]) : "";
            $year = date('Y');

            $res = false;
            $message = "";
            switch ($type) {
                case 'DELETE':
                    $res = removeCandidate($username, $year, $remove_cand_errors);
                    $message = "Candidate removed!";
                    break;
                case "DISQUALIFICATION":
                    $res = disqualifyCandidate($username, $year, $remove_cand_errors);
                    $message = "Candidate disqualification successful!";
                    break;
                case "ADD_QUALIFICATION":
                    $res = removeDisqualificationFromCandidate($username, $year, $remove_cand_errors);
                    "Candidate disqualification removed!";
                    break;
                default:
            }
            if ($res) {
                echo "<script> alert($message); </script>";
            }

            break;
        case "add-department":
            $add_dept_errors = [];
            $_department = isset($_POST["department"]) ? $_POST["department"] : "";

            $res = addDepartment($_department, $add_dept_errors);
            if ($res) {
                echo "<script> alert('Department added'); </script>";
            }
            break;
        case "remove-department":
            $remove_dept_errors = [];
            $_department = isset($_POST["department"]) ? $_POST["department"] : "";

            $res = removeDepartment($_department, $remove_dept_errors);
            if ($res) {
                echo "<script> alert('Department remoced!'); </script>";
            }
            break;
        case "logout-form":
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit();
            break;
        default:
    }

    $_POST = array();
}
?>

<body>
    <main>
        <?php include "./templates/header.php" ?>

        <section class="admin-grid">
            <fieldset>
                <legend>Admin Management</legend>
                <p class="italic">Admin must be a registered user of the platform.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                    <input type="hidden" name="form-name" value="add-admin">
                    <h3>Add Admin</h3>
                    <div>
                        <label>Enter username (or matric number) </label>
                        <input type="text" required name="username" id="username" minlength="5" maxlength="10">
                    </div>
                    <?php
                    // Display error messages
                    if (!empty($add_admin_errors)) {
                        echo "<div class='error'> ";
                        foreach ($add_admin_errors as $error) {
                            echo "<p>$error</p>";
                        }
                        echo "</div> ";
                    } ?>
                    <input class="w-fit" type="submit" value="Add Admin">
                </form>

                <br>
                <br>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                    <input type="hidden" name="form-name" value="remove-admin">
                    <h3>Remove Admin</h3>
                    <div>
                        <label>Enter username (or matric number) </label>
                        <input type="text" required name="username" id="username" minlength="9" maxlength="10">
                    </div>
                    <?php
                    // Display error messages
                    if (!empty($remove_admin_errors)) {
                        echo "<div class='error'> ";
                        foreach ($remove_admin_errors as $error) {
                            echo "<p>$error</p>";
                        }
                        echo "</div> ";
                    } ?>

                    <input class="w-fit" type="submit" value="Remove Admin">
                </form>
            </fieldset>

            <fieldset>
                <legend>Candidate Management</legend>
                <p class="italic">Candidate must be a registered user of the platform.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                    <input type="hidden" name="form-name" value="add-candidate">
                    <h3>Add Candidate</h3>
                    <div>
                        <label>Enter username (or matric number) </label>
                        <input type="text" required name="username" id="username" minlength="9" maxlength="10">
                    </div>
                    <div><label for="position">Position:</label>
                        <select id="position" name="position" required>
                            <option value="">Select position</option>
                            <?php
                            include './lib/dbconn.php';

                            $sql = 'SELECT * from position';
                            $result = $db->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {

                                    echo "<option value='" . $row["TITLE"] . "'>" . $row["TITLE"] . "</option>";
                                }
                            }

                            $db->close();
                            ?>
                        </select>
                    </div>
                    <?php
                    // Display error messages
                    if (!empty($add_cand_errors)) {
                        echo "<div class='error'> ";
                        foreach ($add_cand_errors as $error) {
                            echo "<p>$error</p>";
                        }
                        echo "</div> ";
                    } ?>

                    <input class="w-fit" type="submit" value="Save Candidate">
                </form>
                <br>
                <br>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                    <input type="hidden" name="form-name" value="remove-candidate">

                    <h3>Update candidacy</h3>
                    <div>
                        <label>Enter username (or matric number) </label>
                        <input type="text" required name="username" id="username" minlength="9" maxlength="10">
                    </div>
                    <div>
                        <label> Type </label>
                        <select id="type" name="type" required>
                            <option value="">- Select -</option>
                            <option value="DELETE">Remove Candidacy</option>
                            <option value="DISQUALIFICATION">Disqualify</option>
                            <option value="ADD_QUALIFICATION">Remove Disqualification</option>

                        </select>
                    </div>
                    <?php
                    // Display error messages
                    if (!empty($remove_cand_errors)) {
                        echo "<div class='error'> ";
                        foreach ($remove_cand_errors as $error) {
                            echo "<p>$error</p>";
                        }
                        echo "</div> ";
                    } ?>


                    <input class="w-fit" type="submit" value="Remove Candidate">
                </form>
            </fieldset>

            <fieldset>
                <legend>Department Management</legend>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">

                    <input type="hidden" name="form-name" value="add-department">

                    <h3>Add Department</h3>
                    <div>
                        <label>Enter department title</label>
                        <input type="text" required name="department" id="department">
                    </div>
                    <?php
                    // Display error messages
                    if (!empty($add_dept_errors)) {
                        echo "<div class='error'> ";
                        foreach ($add_dept_errors as $error) {
                            echo "<p>$error</p>";
                        }
                        echo "</div> ";
                    } ?>


                    <input class="w-fit" type="submit" value="Save Changes">
                </form>
                <br>
                <br>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                    <input type="hidden" name="form-name" value="remove-department">
                    <h3>Remove Department</h3>
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
                    <?php
                    // Display error messages
                    if (!empty($remove_dept_errors)) {
                        echo "<div class='error'> ";
                        foreach ($remove_dept_errors as $error) {
                            echo "<p>$error</p>";
                        }
                        echo "</div> ";
                    } ?>

                    <input class="w-fit" type="submit" value="Save Changes">
                </form>
            </fieldset>
        </section>


    </main>
</body>

</html>