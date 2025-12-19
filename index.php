<?php
include("database.php");
include("verifyUser.php");
$id = $_SESSION['login_id'];

$user_query = "SELECT username, email FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $user_query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$fetch_cards_sql = "SELECT card_name, id FROM cards WHERE user_id = ?";
$card_stmt = mysqli_prepare($conn, $fetch_cards_sql);
mysqli_stmt_bind_param($card_stmt, "i", $id);
mysqli_stmt_execute($card_stmt);
$cards_result = mysqli_stmt_get_result($card_stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Profile - Smart Wallet</title>
</head>

<body class="md:flex relative justify-center font-mono bg-gray-50 h-full">
    <?php include("navbar.php"); ?>

    <main class="md:w-[30%] h-[100vh] bg-red-500">
        <div class="flex flex-col items-center gap-6 h-full bg-cyan-400 shadow-[0_0_20px_gray] p-10 pt-20 text-white">
            
            <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center shadow-lg mt-5">
                <span class="text-5xl text-cyan-500 font-bold">
                    <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                </span>
            </div>

            <div class="text-center">
                <h1 class="text-3xl font-bold uppercase tracking-wider"><?php echo $user['username']; ?></h1>
                <p class="text-cyan-100 italic"><?php echo $user['email']; ?></p>
            </div>

            <hr class="w-full border-cyan-300 my-4">

            <div class="w-full space-y-4">
                <div class="bg-cyan-500/30 p-4 rounded-lg border border-cyan-200">
                    <p class="text-xl uppercase text-cyan-100">User ID</p>
                    <p class="text-2xl font-bold">#<?php echo $id; ?></p>
                </div>

                <div class="bg-cyan-500/30 p-4 rounded-lg border border-cyan-200">
                    <p class="text-xs uppercase text-cyan-100">Connected Cards</p>
                    <div class="mt-2">
                        <?php if(mysqli_num_rows($cards_result) > 0): ?>
                            <ul class="list-disc list-inside">
                                <?php while($card = mysqli_fetch_assoc($cards_result)): ?>
                                    <li class="text-sm"><?php echo $card['card_name']; ?></li>
                                <?php endwhile; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-sm opacity-70">No cards linked yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </main>
</body>
</html>