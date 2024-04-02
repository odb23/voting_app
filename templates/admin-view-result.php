<?php
if ( $_SERVER["REQUEST_METHOD"] == "POST") {
    $fn = isset($_POST['form-name']) ? $_POST['form-name'] : "";

    if ($fn == "admin-view-result") {
        $department = $_POST['department'];
        $currentYear = date('Y');

        header("Location: results.php?department=$department", true);
        exit();
    }
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="hidden" name="form-name" value="admin-view-result">
    <div><label for="department">Select Department:</label>
        <select id="department" name="department" required value="<?php echo isset($_SESSION['form_data']['department']) ? $_SESSION['form_data']['department'] : ''; ?>">
            <option value="">Select Department</option>
            <?php
            include './lib/dbconn.php';

            $sql = 'SELECT * from department';
            $result = $db->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($row["NAME"] == "ADMIN") {
                        continue;
                    }
                    echo "<option value='" . $row["NAME"] . "'>" . $row["NAME"] . "</option>";
                }
            }

            $db->close();
            ?>
        </select>
    </div>
    <input class="w-fit" type="submit" value="View Results">
</form>
<br><br>
<hr> <br> <br>

<a class='link-button' href='./admin.php'>Go to Admin Panel</a>