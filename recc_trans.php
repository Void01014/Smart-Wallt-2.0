<?php
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

    $fetch_recc_trans_sql = "SELECT * FROM recc_transactions t INNER JOIN cards c
                             ON t.card_id = c.id";

    $result = mysqli_query($conn, $fetch_recc_trans_sql);

    while($row = mysqli_fetch_assoc($result)){
        $trans_id = (int) $row['id'];
        $card_id = $row['card_id'];
        $mode = $row['mode'];
        $category = $row['category'];
        $amount = $row['amount'];
        $desc = $row['description'];
        $last_executed = $row['last_executed'];
        $date = $today;

        $start_date = $row['start_date'];
        $start_DAY = date("D", strtotime($start_date));
        $start_day = date("d", strtotime($start_date));
        $start_month = date("m", strtotime($start_date));
        
        if($row['recurrence'] == "daily"){
            if($row['start_date'] !== $today && $today !== $last_executed){
                normal_push($conn, $card_id,$mode, $category, $amount, $desc, $date);
                $update_ex_date_sql = "UPDATE recc_transactions SET last_executed = ? WHERE id = ?";
                $upd_stmt = mysqli_prepare($conn, $update_ex_date_sql);
                mysqli_stmt_bind_param($upd_stmt, "si", $today, $trans_id);
                mysqli_stmt_execute($upd_stmt);
                mysqli_stmt_close($upd_stmt);
            }
        }elseif($row['recurrence'] == "weekly" && $today !== $last_executed){
            if($current_DAY == $start_DAY){
                normal_push($conn, $card_id,$mode, $category, $amount, $desc, $date);
                $update_ex_date_sql = "UPDATE recc_transactions SET last_executed = ? WHERE id = ?";
                $upd_stmt = mysqli_prepare($conn, $update_ex_date_sql);
                mysqli_stmt_bind_param($upd_stmt, "si", $today, $trans_id);
                mysqli_stmt_execute($upd_stmt);
                mysqli_stmt_close($upd_stmt);

            }
        }elseif($row['recurrence'] == "monthly" && $today !== $last_executed){
            if($current_month !== $start_month && $current_day == $start_day){
                normal_push($conn, $card_id,$mode, $category, $amount, $desc, $date);
                $update_ex_date_sql = "UPDATE recc_transactions SET last_executed = ? WHERE id = ?";
                $upd_stmt = mysqli_prepare($conn, $update_ex_date_sql);
                mysqli_stmt_bind_param($upd_stmt, "si", $today, $trans_id);
                mysqli_stmt_execute($upd_stmt);
                mysqli_stmt_close($upd_stmt);
            }
        }
    }

?>