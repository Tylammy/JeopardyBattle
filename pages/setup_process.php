<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['players'])) {
    $players = array_filter($_POST['players']); // remove empty entries

    if (count($players) < 2) {
        $_SESSION['error'] = "Please enter at least 2 players.";
        header("Location: setup.php");
        exit();
    }

    $_SESSION['players'] = $players;
    $_SESSION['scores'] = array_fill(0, count($players), 0);

    // Redirect to gameboard.php after successful setup
    header("Location: gameboard.php");
    exit();
} else {
    header("Location: setup.php");
    exit();
}
