<?php
    include("database.php");

    $today = date("Y-m-d");
    $current_DAY = date("D");
    $current_day = date("d");
    $current_month = date("m");
    $user_id = (int) $_SESSION['login_id'];

    function normal_push($conn, $card_id, $mode, $category, $amount, $desc, $date){
            $sql = "INSERT INTO transactions (card_id, mode, category, amount, description, date)
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "issdss", $card_id, $mode, $category, $amount, $desc, $date);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "<script>Swal.fire({icon: 'success', title: 'Operation successful', text: 'Your {$mode} has been added'}).then(() => {
                  window.location.href = 'manager.php';
                  });</script>";
    }

    $fetch_recc_trans_sql = "SELECT * FROM transactions t
                             INNER JOIN cards c ON t.card_id = c.id
                             WHERE c.user_id = ?";

    $stmt = mysqli_prepare($conn, $fetch_recc_trans_sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while($row = mysqli_fetch_assoc($result)){
        $card_id = $row['card_id'];
        $mode = $row['mode'];
        $category = $row['category'];
        $amount = $row['amount'];
        $desc = $row['description'];
        $date = $today;

        $start_date = $row['start_date'];
        $start_DAY = date("D", strtotime($start_date));
        $start_day = date("d", strtotime($start_date));
        $start_month = date("m", strtotime($start_date));
        
        if($row['recurrence'] == "daily"){
            if($row['start_date'] !== $today){
                normal_push($conn, $card_id,$mode, $category, $amount, $desc, $date);
            }
        }elseif($row['recurrence'] == "weekly"){
            if($current_DAY == $start_DAY){
                normal_push($conn, $card_id,$mode, $category, $amount, $desc, $date);
            }
        }elseif($row['recurrence'] == "monthly"){
            if($current_month !== $start_month && $current_day == $start_day){
                normal_push($conn, $card_id,$mode, $category, $amount, $desc, $date);
            }
        }
    }

?>