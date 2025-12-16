<?php
include("database.php");
include("verifyUser.php");

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

<body class="relative flex justify-center font-mono">
    <?php
        include("navbar.php");
    ?>
    <main class="md:w-[30%] h-[100vh]">

    </main>

    <?php
        mysqli_close($conn);
    ?>
</body>

</html>