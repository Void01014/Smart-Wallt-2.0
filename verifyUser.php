<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if (basename($_SERVER['PHP_SELF']) !== 'index.php') {
        header('Location: home.php');
        exit();
    }
} else {
    if (basename($_SERVER['PHP_SELF']) !== 'signup.php') {
        header('Location: signup.php');
        exit();
    }
}