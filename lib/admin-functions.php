<?php

declare(strict_types=1);

function addAdminAccount(string $username, array &$errors): bool
{
    if (!isset($username) || empty($username)) {
        $errors[] = "Username cannot be empty";
        return false;
    }
    include "./lib/dbconn.php";

    echo ($username);

    $sql = $db->prepare("SELECT username from users where username = ?");
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows < 1) {
        $errors[] = "No user record found";
        return false;
    }

    $sql = $db->prepare("INSERT INTO `ADMIN` (USERNAME) VALUES (?)");
    $sql->bind_param("s", $username);
    $val = $sql->execute();

    $db->close();
    return $val;
}

function removeAdminFromAccount(string $username, array &$errors): bool
{
    if (!isset($username) || empty($username)) {
        $errors[] = "Username cannot be empty";
        return false;
    }
    include "./lib/dbconn.php";

    $sql = $db->prepare("SELECT username from users where username = ?");
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows < 1) {
        $errors[] = "No user record found";
        return false;
    }

    $sql = $db->prepare("DELETE FROM `ADMIN` WHERE USERNAME = (?)");
    $sql->bind_param("s", $username);
    $val = $sql->execute();

    $db->close();
    return $val;
}

function addCandidate(string $username, string $position, int $year, array &$errors)
{
    if (!isset($username) || empty($username)) {
        $errors[] = "Username cannot be empty";
        return false;
    }
    if (!isset($position) || empty($position)) {
        $errors[] = "Position cannot be empty";
        return false;
    }
    if (!isset($year)) {
        $errors[] = "Year cannot be empty";
        return false;
    }
    include "./lib/dbconn.php";

    $sql = $db->prepare("SELECT username from users where username = ?");
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows < 1) {
        $errors[] = "No user record found";
        return false;
    }

    $sql = $db->prepare("SELECT * from position where title = ?");
    $sql->bind_param("s", $position);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows < 1) {
        $errors[] = "Position is not valid";
        return false;
    }

    $sql = $db->prepare("SELECT * from candidates where username = ? and year = ?");
    $sql->bind_param("ss", $username, $year);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "User is a registed candidate for this year.";
        return false;
    }

    $sql = $db->prepare("INSERT INTO CANDIDATES (USERNAME, POSITION_TITLE, `YEAR`)  VALUES (?, ?, ?)");
    $sql->bind_param("sss", $username, $position, $year);
    $val = $sql->execute();

    $db->close();
    return $val;
}

function removeCandidate(string $username, int $year, array &$errors)
{
    if (!isset($username) || empty($username)) {
        $errors[] = "Username cannot be empty";
        return false;
    }
    if (!isset($year)) {
        $errors[] = "Year cannot be empty";
        return false;
    }
    include "./lib/dbconn.php";

    $sql = $db->prepare("SELECT * from candidates where username = ? and year = ?");
    $sql->bind_param("ss", $username, $year);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows < 1) {
        $errors[] = "No candidacy record found for user.";
        return false;
    }

    $db->autocommit(false);

    $remove_votes = $db->prepare("DELETE FROM VOTES WHERE CANDIDATE_ID = ? AND VOTE_YEAR = ?");
    $remove_votes->bind_param("ss", $username, $year);

    if ($remove_votes->execute()) {
        $sql = $db->prepare("DELETE FROM CANDIDATES WHERE  USERNAME = ? AND  `YEAR` = ?");
        $sql->bind_param("ss", $username, $year);
        $val = $sql->execute();
        if ($val) {
            $db->commit();
        } else {
            $db->rollback();
            $errors[] = $db->error;
        }
    } else {
        $db->rollback();
        $errors[] = $db->error;
    }

    $db->close();
    return $val;
}

function addDepartment(string $department, array &$errors): bool
{
    if (!isset($department) || empty($department)) {
        $errors[] = "Department cannot be empty";
        return false;
    }
    include "./lib/dbconn.php";

    $department = strtoupper(trim($department));
    $sql = $db->prepare("SELECT * from department where `name` = ?");
    $sql->bind_param("s", $department);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Department already exist";
        return false;
    }

    $sql = $db->prepare("INSERT INTO `DEPARTMENT` (NAME) VALUES (?)");
    $sql->bind_param("s", $department);
    $val = $sql->execute();

    $db->close();
    return $val;
}

function removeDepartment(string $department, array &$errors): bool
{
    if (!isset($department) || empty($department)) {
        $errors[] = "Department cannot be empty";
        return false;
    }
    include "./lib/dbconn.php";

    $sql = $db->prepare("SELECT * from department where name = ?");
    $sql->bind_param("s", $department);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows < 1) {
        $errors[] = "No department record found.";
        return false;
    }


    $sql = $db->prepare("DELETE FROM department WHERE department = ?");
    $sql->bind_param("s", $department);
    $val = $sql->execute();

    $db->close();
    return $val;
}

function disqualifyCandidate(string $username, int $year, array &$errors): bool
{

    if (!isset($username) || empty($username)) {
        $errors[] = "Username cannot be empty";
        return false;
    }
    if (!isset($year)) {
        $errors[] = "Year cannot be empty";
        return false;
    }
    include "./lib/dbconn.php";

    $sql = $db->prepare("SELECT * from candidates where username = ? and year = ?");
    $sql->bind_param("ss", $username, $year);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows < 1) {
        $errors[] = "No candidacy record found for user.";
        return false;
    }

    $sql = $db->prepare("UPDATE CANDIDATES SET DISQUALIFIED = TRUE WHERE  USERNAME = ? AND  `YEAR` = ?");
    $sql->bind_param("ss", $username, $year);
    $val = $sql->execute();

    $db->close();
    return $val;
}

function removeDisqualificationFromCandidate(string $username, int $year, array &$errors): bool
{

    if (!isset($username) || empty($username)) {
        $errors[] = "Username cannot be empty";
        return false;
    }
    if (!isset($year)) {
        $errors[] = "Year cannot be empty";
        return false;
    }
    include "./lib/dbconn.php";

    $sql = $db->prepare("SELECT * from candidates where username = ? and year = ?");
    $sql->bind_param("ss", $username, $year);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows < 1) {
        $errors[] = "No candidacy record found for user.";
        return false;
    }

    $sql = $db->prepare("UPDATE CANDIDATES SET DISQUALIFIED = FALSE WHERE  USERNAME = ? AND  `YEAR` = ?");
    $sql->bind_param("ss", $username, $year);
    $val = $sql->execute();

    $db->close();
    return $val;
}
