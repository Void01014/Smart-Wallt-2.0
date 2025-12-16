<?php
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "Smart_Wallet_2.0_db";
    $conn = "";
    
    try{
        $conn = mysqli_connect($db_server,
                    $db_user,
                    $db_pass,
                    $db_name);
    }
    catch(mysqli_sql_exception){
        echo"sorry, there was an error in the connection to the server";
    }
    
?>