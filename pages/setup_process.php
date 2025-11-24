<?php
// ensure session expires on browser close
ini_set('session.cookie_lifetime', 0);
ini_set('session.gc_maxlifetime', 3600);
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['players'])) {
    $players = array_filter($_POST['players']);
    if (count($players) < 2) {
        $_SESSION['error'] = "Please enter at least 2 players.";
        header("Location: setup.php");
        exit();
    }

    $_SESSION['players'] = $players;
    $_SESSION['scores'] = array_fill(0, count($players), 0);
    $_SESSION['correct'] = array_fill(0, count($players), 0);
    $_SESSION['questions_answered'] = [];
    $_SESSION['current_player'] = 0; // index of current player's turn
    $_SESSION['category_streaks'] = []; // track consecutive correct answers per category

    header("Location: gameboard.php");
    exit();
} else {
    header("Location: setup.php");
    exit();
}
