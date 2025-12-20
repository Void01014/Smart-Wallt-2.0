<?php
include("database.php");
include("verifyUser.php");
include("recc_trans.php");

$id = $_SESSION['login_id'];

$user_cards = [];

$fetch_cards_sql = "SELECT id, card_name FROM cards WHERE user_id = $id";

$result = mysqli_query($conn, $fetch_cards_sql);
while($row = mysqli_fetch_assoc($result)){
    $user_cards[$row['id']] = $row['card_name'];
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="js/trans.js" defer></script>
    <title>Smart Wallet</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<script>
</script>

<body class="md:flex relative justify-center font-mono bg-gray-50">
    <?php
        include("navbar.php");
    ?>
    <main class="md:w-[30%] h-[100vh]">
        <form action="transaction.php" method="post" class="flex flex-col items-center gap-5 h-full bg-cyan-400 shadow-[0_0_20px_gray] p-15" id="form">
            <h1 class="text-4xl text-center text-white">Transactions</h1>
            <div class="w-full">
                <label for="card_id">Card</label>
                <select name="card_id" id="card_id" class="bg-white w-full rounded-lg p-2">
                    <option value="default" disabled selected>choose a card</option>
                    <?php
                        foreach($user_cards as $id => $name){
                            echo "<option value='$id'>$name</option>" . '<br>';
                        }
                    ?>                    
                </select>
            </div>
            <div class="w-full">
                <label for="amount">Amount</label>
                <input class="bg-white rounded-lg p-2 w-full" placeholder="Amount" type="number" step="0.1" name="amount" id="amount">
            </div>
            <div class="w-full">
                <input type="hidden" name="id_form" id="id_form" value="email">
                <label for="recipient_field">Recipient ID</label>
                <span class="text-gray-200">(use email or id)</span>
                <div class="mb-2 flex" id="switch">
                <button type="button" class="bg-gray-300 w-20 selected rounded-l-lg p-2 cursor-pointer" id="email">email</button>
                <button type="button" class="bg-gray-300 w-20 rounded-r-lg p-2 cursor-pointer" id="id">ID</button>
            </div>
                <input class="bg-white rounded-lg p-2 w-full" placeholder="Recipient email" type="text" name="recipient_field" id="recipient_field">
            </div>
            <div class="w-full">
                <label for="date">Date</label>
                <input class="bg-white rounded-lg p-2 w-full" placeholder="text" type="date" name="date" id="date">
            </div>
            <div class="w-full">
                <label for="desc">Description</label>
                <input class="bg-white rounded-lg p-2 w-full" placeholder="Write a short description" type="text" name="desc" id="desc">
            </div>
            <button class="w-50 bg-white p-2 rounded-lg mt-10 hover:shadow-[0_0_10px_gray] hover:bg-blue-500 hover:scale-110 hover:text-white transition duration-200 cursor-pointer" type="submit" name="add">Add</button>
        </form>
    </main>

    <?php
    if (isset($_POST["add"])) {
        $sender_id = (int) $_SESSION['login_id'];
        $mode = "transfer";
        $card_id = (int) $_POST["card_id"];
        $category = "send";
        $amount = floatval($_POST["amount"]);
        $recipient_field = trim($_POST["recipient_field"]); 
        $id_form = $_POST["id_form"];
        $date = $_POST["date"];
        $desc = trim($_POST["desc"]);

        //I verify for simple input
        if ($card_id <= 0) die("Invalid card selection");
        if ($amount <= 0) die("Amount must be positive");
        if (empty($desc)) $desc = "No description"; 

        $fetch_sender_sql = "SELECT username FROM users WHERE id = ?";
        $fetch_recipient_sql = "SELECT username, id, email FROM users WHERE id = ? OR email = ?";

        $stmt1 = mysqli_prepare($conn, $fetch_sender_sql);
        $stmt2 = mysqli_prepare($conn, $fetch_recipient_sql);

        mysqli_stmt_bind_param($stmt1, "i", $sender_id);
        mysqli_stmt_bind_param($stmt2, "ss", $recipient_field, $recipient_field);

        mysqli_stmt_execute($stmt1);
        $senderRS = mysqli_stmt_get_result($stmt1);
        
        mysqli_stmt_execute($stmt2);
        $recipientRS = mysqli_stmt_get_result($stmt2);
        $recipient_data = mysqli_fetch_assoc($recipientRS);

        $sender_username = mysqli_fetch_assoc($senderRS)['username'];
        $recipient_id = (int) $recipient_data['id'];
        $recipient_username = $recipient_data['username'];
        $recipient_email = $recipient_data['email'];                


        if($id_form == "email"){
            $sql = "INSERT INTO transactions (card_id, mode, category, amount, description, from_entity, to_entity, recipient_id, recipient_email, date)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        }else{
            $sql = "INSERT INTO transactions (card_id, mode, category, amount, description, from_entity, to_entity, recipient_id, recipient_email, date)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        }
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "issdsssdss", $card_id, $mode, $category, $amount, $desc, $sender_username, $recipient_username , $recipient_id, $recipient_email, $date);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<script>Swal.fire({icon: 'success', title: 'Operation successful', text: 'Your {$mode} has been added'}).then(() => {
              window.location.href = 'transaction.php';
              });</script>";
        
    }
    mysqli_close($conn);
    ?>
</body>

</html>