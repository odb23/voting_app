<?php
$req_year = isset($_GET['year']) ? $_GET['year'] : "";
$req_dept = isset($_GET['department']) ? $_GET['department'] : "";


$name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "";
$username = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "";
$user_department = isset($_SESSION['department']) ? $_SESSION['department'] : "";
$admin = isset($_SESSION['admin']) ? $_SESSION['admin'] : false;
?>


    <?php
    include './scripts/dbconn.php';

    if (!$admin) {
        $sql = 'SELECT * from executive_position';
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pos = $row['title'];
                echo "<fieldset><legend>" . ucwords(strtolower($pos)) . "</legend>";

                $sql = $conn->prepare("select username, lname, fname  from electorates e  join student s on s.username = e.id where s.department = ? and year = ? and e.executive_position = ?");
                $sql->bind_param("sss", $req_dept, $req_year, $pos);
                $sql->execute();
                $candidiate_results = $sql->get_result();

                if ($result->num_rows > 0) {

                    $total_count = 0;
                    while ($candidate = $candidiate_results->fetch_assoc()) {

                        $sql2 =  $conn->prepare("SELECT COUNT(*) AS count FROM student s INNER JOIN student_votes v ON s.username = v.student_id WHERE v.vote_year = ? AND s.department = ? GROUP BY s.username AND v.candidate_id = ?");

                        $sql2->bind_param("sss", $req_year, $req_dept, $candidate['username']);
                        $sql2->execute();
                        $result = $sql->get_result();

                        $count = 0;
                        if ($result->num_rows > 0) {
                            $count =  $result->fetch_assoc()["count"];
                            $total_count += $count;
                        }

                        echo "<p>" . ucwords(strtolower($candidate['fname'] . " " . $candidate['lname'])) . ": " . $count . " votes </p>";
                    }

                    echo "<p>Total votes: $total_count</p>";

                    // Total votes
                }
                echo "</fieldset>";
            }
        }
    }
    $conn->close();
    $redirect_route = $admin ? "admin.php" : "vote.php";
    echo "<a class='link-button' href='$redirect_route'>Go to Homepage</a>";
?>