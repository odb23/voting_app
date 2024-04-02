<?php

$req_year = date("Y");
$name = $_SESSION['user_name'];
$username = $_SESSION['user_id'];
$user_department = $_SESSION['department'];
$admin = isset($_SESSION['admin']) ? $_SESSION['admin'] : false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($_POST['form-name'] === "vote-form") {
        include "./lib/dbconn.php";

        print_r($_POST);

        $votes_errors = [];
        $db->autocommit(false);
        $get_positions = $db->query('select * from position');

        if ($get_positions->num_rows > 0) {
            $vote_counter = 0;
            while ($position = $get_positions->fetch_assoc()) {
                print_r($position);
                $pos_key = str_replace(" ", "_", $position['TITLE']);
                $position_candidate = isset($_POST[$pos_key]) ? $_POST[$pos_key] : "";

                if (empty($position_candidate)) {
                    echo("in empty if block guyzzz");
                    echo ($position_candidate);
                    $db->rollback();
                    $votes_errors[] = "You have not selected your preferred in position " . $position['TITLE'];
                    break;
                }

                $insert_vote = $db->prepare("insert into votes (voter_id, candidate_id, vote_year) values ( ?, ?, ?)");
                $insert_vote->bind_param("sss", $username, $position_candidate, $req_year);

                if ($insert_vote->execute()) {
                    $vote_counter += 1;
                } else {
                    $db->rollback();
                    $votes_errors[] = "Error occured: " . $db->error;
                    break;
                }
            }

            if ($vote_counter === 3) {
                $db->commit();
                echo "<script> alert('Votes recorded!'); </script>";
            }
        } else {
        }

        $db->close();
    }

    $_POST = array();
}


?>
<div class="statistics">
    <h4> <?php echo "Department of " . $user_department; ?></h4>
    <ul>
        <li>Number of Students: <?php
                                include "./lib/dbconn.php";

                                $sql = "select count(*) as count from users where department_name ='" . strtoupper($user_department) . "'";

                                $result = $db->query($sql);
                                if ($result) {
                                    echo $result->fetch_assoc()['count'];
                                } else {
                                    echo "0";
                                }

                                $db->close();
                                ?></li>
        <li>Number of Students that voted: <?php
                                            include "./lib/dbconn.php";

                                            $sql = $db->prepare("SELECT COUNT(DISTINCT v.voter_id) AS count FROM users s INNER JOIN votes v ON s.username = v.voter_id INNER JOIN candidates c ON c.username = v.candidate_id WHERE c.year = ? AND s.department_name = ? GROUP BY v.voter_id");
                                            $sql->bind_param("ss", $req_year, $user_department);

                                            $sql->execute();
                                            $result = $sql->get_result();

                                            if ($result->num_rows > 0) {
                                                echo $result->fetch_assoc()["count"];
                                            } else {
                                                echo "0";
                                            }

                                            $db->close();
                                            ?></li>
        <li>Number of verified candidates: <?php
                                            include "./lib/dbconn.php";

                                            $sql = "select count(*) as count from candidates c inner join users u on u.username = c.username  where u.department_name ='" . strtoupper($user_department) . "' and c.year='" . strtoupper($req_year) . "'";

                                            $result = $db->query($sql);
                                            if ($result) {
                                                echo $result->fetch_assoc()['count'];
                                            } else {
                                                echo "0";
                                            }

                                            $db->close();
                                            ?></li>
    </ul>
</div>
<p class="italic">Select a candidate in each position to vote</p>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
    <input type="hidden" name="form-name" value="vote-form">

    <?php
    include './lib/dbconn.php';

    if (!$admin) {
        $sql = 'SELECT * from position';
        $result = $db->query($sql);

        $total_votes = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<fieldset>";
                $pos = $row['TITLE'];
                echo "<legend>" . ucwords(strtolower($pos)) . "</legend>";
                $sql = $db->prepare("select c.username as username, u.lname, u.fname  from candidates c  join users u on c.username = u.username where u.department_name = ? and year = ? and c.position_title = ?");
                $sql->bind_param("sss", $user_department, $req_year, $pos);
                $sql->execute();
                $candidiate_results = $sql->get_result();

                $disable_section = false;
                if ($candidiate_results->num_rows > 0) {
                    $selected_candidate = "";
                    while ($candidate = $candidiate_results->fetch_assoc()) {
                        $candidate_username = $candidate['username'];
                        $user_votes_sql = $db->prepare("select * from votes where voter_id = ? and candidate_id = ? and vote_year = ?");
                        $user_votes_sql->bind_param("sss", $username, $candidate_username, $req_year);
                        $user_votes_sql->execute();
                        $user_vote_res = $user_votes_sql->get_result();

                        if ($user_vote_res->num_rows === 1) {
                            $disable_section = true;
                            $total_votes += 1;
                            $selected_candidate = $candidate_username;
                        }

                        echo "<div class='radio-container'><input required type='radio' value='" . $candidate_username . "' name='" . $pos . "' id='" . $candidate_username . "' " . ($selected_candidate == $candidate_username ? "checked" : "") . " " . ($disable_section ? "disabled" : "") . " >";
                        echo "<label for='" . $candidate_username . "'>" . ucwords(strtolower($candidate['fname'] . " " . $candidate['lname'])) . "</label> </div>";
                    }
                }
                echo "</fieldset>";
            }


           if ($total_votes !== 3) echo "<input type='submit' value='Submit Votes' " . ($total_votes == 3 ? "disabled" : "")  . " > <br><br><hr><br><br>";


        }
    }
    $db->close();
    ?>
</form>

<a class='link-button' href="<?php echo "./results.php?department=$user_department" ?>"> View election results </a>