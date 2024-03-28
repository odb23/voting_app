<?php
$req_year = $_GET['year'];
$req_dept = $_GET['department'];

$name = $_SESSION['user_name'];
$username = $_SESSION['user_id'];
$user_department = $_SESSION['department'];
$admin = isset($_SESSION['admin']) ? $_SESSION['admin'] : false;


?>
<div class="statistics">
    <h4> <?php echo "Department of " . $req_dept;?></h4>
    <ul>
        <li>Number of Students: <?php 
            include "./scripts/dbconn.php";

            $sql = "select count(*) as count from student where department ='" . strtoupper($req_dept). "'";

            $result = $conn->query($sql);
            if ($result) {
                echo $result->fetch_assoc()['count'];
            } else {
                echo "0";
            }

            $conn->close();
        ?></li>
        <li>Number of Students that voted: <?php 
            include "./scripts/dbconn.php";

           $sql = $conn->prepare("SELECT COUNT(*) AS count FROM student s INNER JOIN student_votes v ON s.username = v.student_id WHERE v.vote_year = ? AND s.department = ? GROUP BY s.username");
           $sql->bind_param("ss", $req_year, $req_dept);

           $sql->execute();
           $result = $sql->get_result();

            if ($result -> num_rows > 0) {
                echo $result->fetch_assoc()["count"];
            } else {
                echo "0";
            }

            $conn->close();
        ?></li>
        <li>Number of verified candidates: <?php 
            include "./scripts/dbconn.php";

            $sql = "select count(*) as count from electorates where department ='" . strtoupper($req_dept). "' and year='". strtoupper($req_year). "'" ;

            $result = $conn->query($sql);
            if ($result) {
                echo $result->fetch_assoc()['count'];
            } else {
                echo "0";
            }

            $conn->close();
        ?></li>
    </ul>
</div>
<p class="italic">Click on any of the candidate you wish to vote for each position</p>
<form action="" method="post">
    <?php
    include './scripts/dbconn.php';

    if (!$admin) {
        $sql = 'SELECT * from executive_position';
        $result = $conn->query($sql);

        $shouldDisable = true;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<fieldset>";
                $pos = $row['title'];
                echo "<legend>" . ucwords(strtolower($pos)) . "</legend>";
                $sql = $conn->prepare("select username, lname, fname  from electorates e  join student s on s.username = e.id where s.department = ? and year = ? and e.executive_position = ?");
                $sql->bind_param("sss", $req_dept, $req_year, $pos);
                $sql->execute();
                $candidiate_results = $sql->get_result();

                if ($result->num_rows > 0) {
                    $shouldDisable = false;
                    while ($candidate = $candidiate_results->fetch_assoc()) {
                        echo "<div><input required type='radio' value='" . $candidate['username'] . "' name='" . $pos . "' id='" . $candidate['username'] . "'>";
                        echo "<label for='" . $candidate['username'] . "'>" . ucwords(strtolower($candidate['fname'] . " " . $candidate['lname'])) . "</label> </div>";
                    }
                }
                echo "</fieldset>";
            }

            echo "<input type='submit' value='Submit Votes' disabled='$shouldDisable'>";
        }
    }



    $conn->close();
    ?>


</form>

