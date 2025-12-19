<?php
include("database.php");
include("verifyUser.php");
include("navbar.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="js/create_card.js" defer></script>
    <title>Smart Wallet</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<script>
</script>

<body class="md:flex relative justify-center font-mono bg-gray-50 h-full">
    <main class="md:w-[30%] h-[100%]">
        <form action="create_card.php" method="post" class="flex flex-col items-center gap-5 h-full bg-cyan-400 shadow-[0_0_20px_gray] p-15" id="form">
            <h1 class="text-4xl text-center text-white">Create a Card</h1>
            <div class="w-full">
                <label for="card_name">Card Name</label>
                <input class="bg-white rounded-lg p-2 w-full" placeholder="Make a name for your card" type="text" name="card_name" id="card_name">
            </div>
            <div class="w-full">
                <label for="company_name">Selecet a Company</label>
                <select name="company_name" id="company_name" class="bg-white w-full rounded-lg p-2">
                    <option value="default" disabled selected>choose a company</option>
                    <option value="visa">VISA</option>
                    <option value="mastercard">Mastercard</option>
                    <option value="wafacash">Wafacash</option>
                    <option value="cib">CIB</option>
                </select>
            </div>
            <div class="w-full">
                <label for="balance">Balance</label>
                <input class="bg-white rounded-lg p-2 w-full" placeholder="Enter a balance" type="number" step="0.1" name="balance" id="balance">
            </div>
            <button class="w-50 bg-white p-2 rounded-lg mt-10 hover:shadow-[0_0_10px_gray] hover:bg-blue-500 hover:scale-110 hover:text-white transition duration-200 cursor-pointer" type="submit" name="create_card">Create</button>
        </form>
    </main>

    <?php
    if (isset($_POST["create_card"])) {

        function push($conn){
            $card_name = $_POST["card_name"];
            $company_name = $_POST["company_name"];
            $balance = (float)$_POST["balance"];

            $sql = "INSERT INTO cards (user_id, card_name, company_name, balance, created_date)
                        VALUES (?, ?, ?, ?, CURRENT_DATE)";

            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt === false) {
                die("Prepare failed: " . mysqli_error($conn));
            }

            mysqli_stmt_bind_param($stmt, "dssd", $_SESSION['login_id'], $card_name, $company_name, $balance);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "<script>Swal.fire({icon: 'success', title: 'Good Job', text: 'Your account was created successfully'}).then(() => {
                  window.location.href = 'index.php';
                  });</script>";
        }
        
        push($conn);
    }
    mysqli_close($conn);
    ?>
</body>

</html>