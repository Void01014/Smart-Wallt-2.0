<?php
session_start();
$data = json_decode(file_get_contents("php://input"), true);

if ($data['otp'] == $_SESSION['otp']) {
    $_SESSION['logged_in'] = true;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
