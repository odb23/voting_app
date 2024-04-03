<?php
$req_year = date('Y');
$req_department = isset($_GET['department']) ? $_GET['department'] : "";


$name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "";
$username = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "";
$user_department = isset($_SESSION['department']) ? $_SESSION['department'] : "";
$admin = isset($_SESSION['admin']) ? $_SESSION['admin'] : false;
?>


    <?php
    include './lib/dbconn.php';

    $allow_user = $admin || $req_department === $user_department;
    if ($admin) {
        $user_department = $req_department;
    }

    if ($allow_user) {
        $sql = "SELECT * FROM `POSITION`";
        $result = $db->query($sql);

        if ($result->num_rows > 0) {
            
            while ($row = $result->fetch_assoc()) {
                $pos = $row['TITLE'];
                echo "<fieldset><legend>" . ucwords(strtolower($pos)) . "</legend>";

                $sql = $db->prepare("select c.username, lname, fname, disqualified  from candidates c join users u on c.username = u.username where u.department_name = ? and c.year = ? and c.position_title = ?");
                $sql->bind_param("sss", $user_department, $req_year, $pos);
                $sql->execute();
                $candidiate_results = $sql->get_result();

                if ($candidiate_results->num_rows > 0) {

                    $total_count = 0;
                    while ($candidate = $candidiate_results->fetch_assoc()) {

                        $sql2 =  $db->prepare("SELECT COUNT(*) AS count FROM votes  WHERE vote_year = ? AND candidate_id = ? ");

                        $sql2->bind_param("ss", $req_year, $candidate['username']);
                        $sql2->execute();
                        $_result = $sql2->get_result();

                        $count = 0;
                        if ($_result->num_rows > 0) {
                            $count =  $_result->fetch_assoc()["count"];
                            $total_count += $count;
                        }

                        echo "<p>" . ucwords(strtolower($candidate['fname'] . " " . $candidate['lname'])) . ": " . $count . " votes ". ((bool)$candidate['disqualified'] === true ? "(Disqualified)" : "") .  " </p> <br>";
                    }
                

                    echo "<p>Total votes: $total_count</p>";

                    // Total votes
                }
                echo "</fieldset>";
            }
        }
    }
    $db->close();
    echo "<a class='link-button' href='./index.php'>Go to Homepage</a>";
?>