<?php

$req_year = date("Y");
$name = $_SESSION['user_name'];
$username = $_SESSION['user_id'];
$user_department = $_SESSION['department'];
$admin = isset($_SESSION['admin']) ? $_SESSION['admin'] : false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    print_r($_POST);
    if ($_POST['form-name'] === "vote-form") {
        include "./lib/dbconn.php";


        $votes_errors = [];
        $db->autocommit(false);
        $get_positions = $db->query('select * from position');

        if ($get_positions->num_rows > 0) {
            $vote_counter = 0;
            $positions_len = $get_positions->num_rows;
            while ($position = $get_positions->fetch_assoc()) {
                $pos_key = str_replace(" ", "_", $position['TITLE']);
                $position_candidate = isset($_POST[$pos_key]) ? $_POST[$pos_key] : "";

                if (empty($position_candidate)) {
                    $db->rollback();
                    $votes_errors[] = "You have not selected your preferred in position " . $position['TITLE'];
                    break;
                } else if ($position_candidate === 'null') {
                    $positions_len -= 1;
                    continue;
                }

                $insert_vote = $db->prepare("insert ignore into votes (voter_id, candidate_id, vote_year) values ( ?, ?, ?)");
                $insert_vote->bind_param("sss", $username, $position_candidate, $req_year);

                if ($insert_vote->execute()) {
                    $vote_counter += 1;
                } else {
                    $db->rollback();
                    $votes_errors[] = "Error occured: " . $db->error;
                    break;
                }
            }

            if ($vote_counter === $positions_len) {
                $db->commit();
                echo "<script> alert('Votes recorded!'); </script>";
            } else {
                $db->rollback();
                $votes_errors[] = "You have not selected a candidate in one or more position";
            }
        } else {
        }

        $db->close();
        $_POST = array();
    }

}


?>
<div class="statistics">
    <h3 class="header"> <?php echo "Department of " . $user_department; ?></h3>
    <ul>
        <li>
            <svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" width="45px" height="45px" viewBox="0 0 125.023 125.023">

                <path d="M65.176,57.92c16,0,28.952-12.972,28.952-28.962C94.128,12.963,81.176,0,65.176,0C49.183,0,36.218,12.964,36.218,28.958
			C36.218,44.949,49.183,57.92,65.176,57.92z" />
                <path d="M72.632,59.087l-7.211,7.264l-6.993-7.34c-0.024,0.006-0.05,0.006-0.066,0.012c-1.167,0.28-6.12,1.856-12.546,6.465
			c-0.057,0.04-1.362,0.945-1.973,1.328c-1.213,0.766-3.024,1.875-5.215,2.922c-2.178,1.064-4.758,2.027-7.106,2.531
			c-1.159,0.23-2.206,0.293-3.047,0.266c-0.869-0.016-1.369-0.204-1.899-0.436c-0.285-0.066-0.496-0.334-0.808-0.482
			c-0.244-0.324-0.597-0.479-0.862-0.939c-0.142-0.203-0.305-0.373-0.457-0.593l-0.411-0.761c-0.318-0.452-0.519-1.126-0.776-1.706
			c-0.281-0.558-0.426-1.292-0.635-1.935c-0.218-0.637-0.364-1.336-0.491-2.037c-0.322-1.348-0.473-2.755-0.63-4.047
			c-0.193-1.274-0.181-2.553-0.276-3.632c-0.003-0.031-0.001-0.058-0.003-0.089c0.613-0.878,1.446-1.67,2.459-2.405
			c1.012-0.727,1.808-1.937,2.336-3.094c2.054-4.563,2.947-7.176,4.421-11.962c0.622-2.016-3.096-4.247-5.522,1.459
			c-1.026,2.067-0.578,2.279-1.621,4.338l-0.373,0.701c0,0-0.215-1.988-0.243-2.589c-0.323-6.89-0.618-10.586-0.949-17.476
			c-0.09-1.911-0.886-2.762-2.361-2.66c-1.404,0.101-2.021,0.966-1.946,2.823c0.151,3.761,0.331,4.323,0.483,8.081
			c0.071,1.417-0.851,1.148-0.845-0.006c-0.244-5.126-0.477-6.258-0.683-11.385c-0.058-1.392-0.637-2.305-2.064-2.458
			c-1.379-0.146-2.321,0.999-2.251,2.742c0.205,4.955,0.45,5.915,0.654,10.871c0.072,1.466-0.83,1.235-0.833,0.133
			c-0.183-3.928-0.299-4.667-0.583-8.588c-0.055-0.79-0.535-1.828-1.156-2.242c-1.515-1.009-3.171,0.277-3.101,2.369
			c0.146,4.387,0.383,5.577,0.564,9.96c0.109,1.125-0.772,1.427-0.82,0.117c-0.136-2.791-0.241-2.389-0.394-5.177
			c-0.07-1.271-0.794-1.997-2.072-2.046c-1.291-0.047-2.002,0.704-2.212,1.918c-0.09,0.497-0.042,1.022-0.019,1.531
			c0.294,6.608,0.471,10.029,0.959,16.622c0.174,2.309,0.451,3.921,0.829,5.785c0.378,1.864,1.418,2.743,1.667,3.666
			c-0.058,1.068-0.128,2.19-0.086,3.477c0.023,1.71,0.033,3.558,0.27,5.615c0.082,1.012,0.19,2.062,0.416,3.182
			c0.215,1.114,0.345,2.219,0.72,3.428c0.348,1.197,0.616,2.388,1.18,3.666c0.259,0.63,0.52,1.264,0.783,1.9
			c0.312,0.643,0.69,1.293,1.051,1.939c0.659,1.296,1.715,2.576,2.692,3.828c1.162,1.193,2.332,2.404,3.784,3.361
			c2.788,1.992,6.115,3.328,9.163,3.834c3.063,0.549,5.932,0.553,8.498,0.308c0.689-0.077,1.532-0.168,2.192-0.269l0.019,33.848
			h59.882v-12.961c1.321,3.738,2.566,8.053,3.745,12.961h23.102C116.131,93.336,98.253,67.534,72.632,59.087z M65.487,123.662
			h-0.128l-6.987-9.557l6.987-46.678h0.128l6.992,46.678L65.487,123.662z" />
            </svg>
            <div>
                <p><?php
                    include "./lib/dbconn.php";

                    $sql = "select count(*) as count from candidates c inner join users u on u.username = c.username  where u.department_name ='" . strtoupper($user_department) . "' and c.year='" . strtoupper($req_year) . "'";

                    $result = $db->query($sql);
                    if ($result) {
                        echo $result->fetch_assoc()['count'];
                    } else {
                        echo "0";
                    }

                    $db->close();
                    ?>
                </p>
                <p>
                    Verified Candidate(s)
                </p>
            </div>
        </li>
        <li>
            <svg width="45px" height="45px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.5 6.5C1.5 3.46243 3.96243 1 7 1C10.0376 1 12.5 3.46243 12.5 6.5C12.5 9.53757 10.0376 12 7 12C3.96243 12 1.5 9.53757 1.5 6.5Z" fill="#000000" />
                <path d="M14.4999 6.5C14.4999 8.00034 14.0593 9.39779 13.3005 10.57C14.2774 11.4585 15.5754 12 16.9999 12C20.0375 12 22.4999 9.53757 22.4999 6.5C22.4999 3.46243 20.0375 1 16.9999 1C15.5754 1 14.2774 1.54153 13.3005 2.42996C14.0593 3.60221 14.4999 4.99966 14.4999 6.5Z" fill="#000000" />
                <path d="M0 18C0 15.7909 1.79086 14 4 14H10C12.2091 14 14 15.7909 14 18V22C14 22.5523 13.5523 23 13 23H1C0.447716 23 0 22.5523 0 22V18Z" fill="#000000" />
                <path d="M16 18V23H23C23.5522 23 24 22.5523 24 22V18C24 15.7909 22.2091 14 20 14H14.4722C15.4222 15.0615 16 16.4633 16 18Z" fill="#000000" />
            </svg>
            <div>
                <p> <?php
                    include "./lib/dbconn.php";

                    $sql = "select count(*) as count from users where department_name ='" . strtoupper($user_department) . "'";

                    $result = $db->query($sql);
                    if ($result) {
                        echo $result->fetch_assoc()['count'];
                    } else {
                        echo "0";
                    }

                    $db->close();
                    ?></p>
                <p>Voter(s)</p>
            </div>
        </li>
        <li> <svg height="45px" width="45px" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve">
                <style type="text/css">
                    .st0 {
                        fill: #000000;
                    }
                </style>
                <g>
                    <path class="st0" d="M195.928,376.064H148.67l-43.168,82.738H512l-43.168-82.738h-42.957l9.478,28.779h-28.799L364.51,185.406
		c0,0-5.038,1.349-12.576,3.281l-0.52-2.614c-0.928-4.518-2.642-8.621-4.68-12.527c-2.051-3.9-4.441-7.581-6.914-11.01l-0.716-0.991
		l-0.899-0.78c-5.677-5.017-15.949-13.237-33.402-24.598l0.042,0.028c-18.274-11.945-38.938-18.345-56.946-18.774
		c-38.826-0.801-81.727-1.68-93.2-1.911l-10.856-6.984l6.415-9.465L83.967,53.197L0,178.176l65.525,45.852l8.796-12.971
		l22.414,15.141l32.298,48.009c4.771,7.082,11.27,12.871,18.908,16.757l0.077,0.042c0.014,0,5.754,2.867,13.82,6.942
		c8.052,4.075,18.416,9.359,27.556,14.158l0.014,0.007c7.364,3.85,15.725,7.194,24.563,10.146l0.112,0.035l5.621,1.743
		l15.569,80.806h-29.769L195.928,376.064z M220.757,301.771c-8.031-2.684-15.408-5.67-21.345-8.782h0.014
		c-18.422-9.66-41.285-21.05-41.742-21.282c-4.286-2.185-8.044-5.522-10.742-9.542l-34.638-51.501l-25.87-17.474l45.29-66.789
		l16.441,10.568l3.049,0.056c0.028,0.007,12.745,0.267,31.14,0.64c18.401,0.379,42.465,0.871,65.103,1.342
		c13.089,0.204,30.795,5.466,45.528,15.218l0.45,0.295l-0.407-0.267c15.696,10.23,24.9,17.509,29.93,21.865
		c0.619,0.871,1.209,1.742,1.771,2.614l-29.776,2.494l-36.605,13.251l-1.398,1.124c-11.755,9.436-18.682,23.6-18.921,38.636v0.681
		c-0.006,14.846,6.506,28.94,17.825,38.552l2.382,2.016l0.295,0.042c0.886,0.717,2.122,1.728,3.921,3.239
		c4.026,3.379,10.651,9.028,21.443,18.401c1.982,1.721,3.106,3.274,3.738,4.56c0.639,1.293,0.836,2.326,0.844,3.337
		c0.014,2.108-1.082,4.722-3.865,7.103c-2.74,2.333-6.913,4.054-11.719,4.047c-1.939,0-3.977-0.267-6.113-0.885l-0.498-0.14
		l0.541,0.154c-8.347-2.445-17.143-4.798-25.743-7.285L220.757,301.771z" />
                </g>
            </svg>
            <div>
                <p><?php
                    include "./lib/dbconn.php";

                    $sql = $db->prepare("SELECT COUNT(DISTINCT v.VOTER_ID) as `count` FROM votes v INNER JOIN users u ON u.USERNAME = v.VOTER_ID WHERE v.VOTE_YEAR = ? AND u.DEPARTMENT_NAME = ?");
                    $sql->bind_param("ss", $req_year, $user_department);

                    $sql->execute();
                    $result = $sql->get_result();

                    if ($result->num_rows > 0) {
                        echo $result->fetch_assoc()["count"];
                    } else {
                        echo "0";
                    }

                    $db->close();
                    ?></p>
                <p>Vote(s)</p>
            </div>
        </li>

    </ul>
</div>
<div class="flex justify-center" style="width: 100%; ">
    <a class='link-button w-fit' style="margin: auto;" href="<?php echo "./results.php?department=$user_department" ?>">
        View election results </a>
</div><br>
<br>
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
                $sql = $db->prepare("select c.username as username, u.lname, u.fname, c.disqualified  from candidates c  join users u on c.username = u.username where u.department_name = ? and year = ? and c.position_title = ?");
                $sql->bind_param("sss", $user_department, $req_year, $pos);
                $sql->execute();
                $candidiate_results = $sql->get_result();

                $disable_section = false;
                $cand_res_len = $candidiate_results->num_rows;
                if ($cand_res_len > 0) {
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

                        if ($cand_res_len === 1 && $candidate['disqualified'] === true) {
                            echo "<input type='hidden' name='" . $pos . "' value='null'";
                        }


                        echo "<div class='radio-container'><input required type='radio' value='" . $candidate_username . "' name='" . $pos . "' id='" . $candidate_username . "' " . ($selected_candidate == $candidate_username ? "checked" : "") . " " . ($disable_section || (bool) $candidate['disqualified'] == true ? "disabled" : "") . " >";
                        echo "<label for='" . $candidate_username . "'>" . ucwords(strtolower($candidate['fname'] . " " . $candidate['lname'])) . " " . ((bool)$candidate['disqualified'] === true ? "(Disqualified)" : "") . "</label> </div>";
                    }
                }
                echo "</fieldset>";
            }


            if ($total_votes !== 3) echo "<input type='submit' value='Submit Votes' " . ($total_votes == 3 ? "disabled" : "")  . " > ";
        }
    }
    $db->close();
    ?>
</form>