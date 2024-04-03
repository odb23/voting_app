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

<form action="results.php" method="get">
    <br>
    <div><label for="department"> Department:</label>
        <div><select id="department" class="w-fit" name="department" required value="<?php echo isset($_SESSION['form_data']['department']) ? $_SESSION['form_data']['department'] : ''; ?>"></div>
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
<div class="flex justify-center" style="width: 100%; ">
<a class='link-button w-fit' style="margin: auto;" href='./admin.php'>Go to Admin Panel</a>
</div>

