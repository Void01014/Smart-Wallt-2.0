<?php
    include("database.php");

    $today = date("Y-m-d");
    $user_id = $_SESSION['login_id'];

    $fetch_recc_trans_sql = "SELECT * FROM recc_transactions WHERE ";

?>