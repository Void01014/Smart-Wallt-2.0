<?php
include("database.php");
include("verifyUser.php");

$id = $_SESSION['login_id'];

$user_cards = [];

$fetch_cards_sql = "SELECT id, card_name FROM cards WHERE user_id = $id";

$result = mysqli_query($conn, $fetch_cards_sql);
while ($row = mysqli_fetch_assoc($result)) {
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
    <script src="js/script.js" defer></script>
    <title>Smart Wallet</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<script>
</script>

<body class="relative md:flex justify-center font-mono">
    <?php
    include("navbar.php");
    ?>
    <main class="md:w-[30%] h-[100vh]">
        <form action="manager.php" method="post" class="flex flex-col items-center gap-5 h-max md:h-full bg-cyan-400 shadow-[0_0_20px_gray] p-15" id="form">
            <input type="hidden" name="mode" value="income">
            <div class="flex gap-10">
                <div class="w-full">
                    <select name="repeat" id="repeat" class="bg-white w-full rounded-lg p-2">
                        <option value="none" selected>Don't repeat</option>
                        <option value="daily">Every day</option>
                        <option value="weekly">Every week</option>
                        <option value="monthly">Every month</option>
                    </select>
                </div>
                <div class="self-end flex" id="switch">
                    <button type="button" class="bg-gray-300 w-20 selected rounded-l-lg p-2 cursor-pointer" id="inc">Income</button>
                    <button type="button" class="bg-gray-300 w-20 rounded-r-lg p-2 cursor-pointer" id="exp">Expenses</button>
                </div>
            </div>
            <h1 class="text-4xl text-center text-white">Entries</h1>
            <div class="w-full">
                <label for="card_id">Card</label>
                <select name="card_id" id="card_id" class="bg-white w-full rounded-lg p-2">
                    <option value="default" disabled selected>choose a card</option>
                    <?php
                    foreach ($user_cards as $id => $name) {
                        echo "<option value='$id'>$name</option>" . '<br>';
                    }
                    ?>
                </select>
            </div>
            <div class="w-full">
                <label for="category">category</label>
                <select name="category" id="category" class="bg-white w-full rounded-lg p-2">
                    <option value="default" disabled selected>Select a Category</option>
                    <option value="salary">Salary</option>
                    <option value="freelance">Freelance</option>
                    <option value="gifts">Gifts</option>
                    <option value="investments">Investments</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="w-full">
                <label for="amount">Amount</label>
                <input class="bg-white rounded-lg p-2 w-full" placeholder="Amount" type="number" step="0.1" name="amount" id="amount">
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
        $mode = $_POST["mode"];
        $repeat = $_POST['repeat'];

        function normal_push($conn, $mode)
        {
            $user_id = (int) $_SESSION['login_id'];
            $card_id = (int) $_POST["card_id"];
            $category = $_POST["category"];
            $amount = floatval($_POST["amount"]);
            $date = $_POST["date"];
            $desc = trim($_POST["desc"]);

            //I verify for simple input
            if ($card_id <= 0) die("Invalid card selection");
            if ($amount <= 0) die("Amount must be positive");
            if (empty($desc)) $desc = "No description";

            $fetch_user_sql = "SELECT username FROM users WHERE id = ?";

            $stmt1 = mysqli_prepare($conn, $fetch_user_sql);
            mysqli_stmt_bind_param($stmt1, "i", $user_id);
            mysqli_stmt_execute($stmt1);
            $userRS = mysqli_stmt_get_result($stmt1);
            $user_username = mysqli_fetch_assoc($userRS)['username'];

            $sql = "INSERT INTO transactions (card_id, mode, category, amount, description, from_entity, date)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "issdsss", $card_id, $mode, $category, $amount, $desc, $user_username, $date);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "<script>Swal.fire({icon: 'success', title: 'Operation successful', text: 'Your {$mode} has been added'}).then(() => {
                  window.location.href = 'manager.php';
                  });</script>";
        }

        function recc_push($conn, $mode, $repeat)
        {
            $user_id = (int) $_SESSION['login_id'];
            $card_id = (int) $_POST["card_id"];
            $category = $_POST["category"];
            $amount = floatval($_POST["amount"]);
            $date = $_POST["date"];
            $desc = trim($_POST["desc"]);

            //I verify for simple input
            if ($card_id <= 0) die("Invalid card selection");
            if ($amount <= 0) die("Amount must be positive");
            if (empty($desc)) $desc = "No description";

            $fetch_user_sql = "SELECT username FROM users WHERE id = ?";

            $stmt1 = mysqli_prepare($conn, $fetch_user_sql);
            mysqli_stmt_bind_param($stmt1, "i", $user_id);
            mysqli_stmt_execute($stmt1);
            $userRS = mysqli_stmt_get_result($stmt1);
            $user_username = mysqli_fetch_assoc($userRS)['username'];

            $sql = "INSERT INTO recc_transactions (card_id, mode, category, amount, description, start_date, recurrence)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "issdsss", $card_id, $mode, $category, $amount, $desc, $date, $repeat);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "<script>Swal.fire({icon: 'success', title: 'Operation successful', text: 'Your {$mode} has been added'}).then(() => {
                  window.location.href = 'manager.php';
                  });</script>";
        }

        if($repeat == "none"){
            if ($mode == "income") {
                normal_push($conn, 'income');
            }
            if ($mode == "expense") {
                normal_push($conn, 'expense');
            }
        }else{
            if ($mode == "income") {
                recc_push($conn, 'income', $repeat);
            }
            if ($mode == "expense") {
                recc_push($conn, 'expense', $repeat);
            }
        }
    }
    mysqli_close($conn);
    ?>
</body>

</html>