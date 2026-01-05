<?php
include("database.php");

if(isset($_POST['id'], $_POST['mode'], $_POST['category'], $_POST['amount'], $_POST['description'], $_POST['date'])){
    $id = $_POST['id'];
    $mode = $_POST['mode'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    // prepare statement
    $sql = "UPDATE transactions SET category = ?, amount = ?, description = ?, date = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sdssi", $category, $amount, $description, $date, $id); 
    $result = mysqli_stmt_execute($stmt);

    echo json_encode(['success' => $result && mysqli_stmt_affected_rows($stmt) > 0]);
}
?>
