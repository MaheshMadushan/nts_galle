<?php
require_once './includes/config.inc.php';
require_once './includes/connect&functions.inc.php';
$db = connect(DB_HOST, DB_NAME, DB_USERNAME, DB_PASSWORD);

$current_pass = $_POST['current_pass'];
$new_pass = $_POST['new_pass'];

$UserSessionName = $_POST['UserSessionName'];


$sql = "SELECT password FROM student_details";
$sql .= " WHERE ";
$sql .= " id=? ";

$stmt = mysqli_stmt_init($db);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "sql statement error";
    printf("fatal error.please contact Admin immideately");
    exit();
} else {

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 's', $UserSessionName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
}

if (password_verify($current_pass, $row[0])) {

    $hash = password_hash($new_pass, PASSWORD_DEFAULT);

    $sql = "UPDATE student_details";
    $sql .= " SET ";
    $sql .= "PASSWORD=?";
    $sql .= " WHERE ";
    $sql .= "id=?";

    $stmtUpdate = mysqli_stmt_init($db);
    if (!mysqli_stmt_prepare($stmtUpdate, $sql)) {
        echo "sql statement error";
        printf("fatal error.please contact Admin immideately");
        exit();
    } else {
        mysqli_stmt_prepare($stmtUpdate, $sql);
        mysqli_stmt_bind_param($stmtUpdate, 'ss', $hash, $UserSessionName);
        mysqli_stmt_execute($stmtUpdate);
        printf("Password successfully updated!");
    }
} else {
    echo "Invalid current password!!!";
}

$db->close();