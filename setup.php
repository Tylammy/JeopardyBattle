<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $_SESSION['players'] = $_POST['players']
    header("Location: gameboard.html");
    exit();
}

// fallback
header("Location: setup.html");
exit();
?>
