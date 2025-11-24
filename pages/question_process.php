<?php
session_start();
require_once "../data/questions.php";

$current = $_SESSION['current_question'];
$answer = trim($_POST['answer']);
$wager = isset($_POST['wager']) ? (int)$_POST['wager'] : $current['value'];
$player = $_SESSION['current_player'];

$correct_answer = strtolower($questions[$current['category']][$current['index']]['answer']);

if (strtolower($answer) === $correct_answer) {
    $_SESSION['scores'][$player] += $wager;
    $_SESSION['category_bonus'][$current['category']][] = $player;
} else {
    $_SESSION['scores'][$player] -= $wager;
    $_SESSION['category_bonus'][$current['category']] = [];
}

$_SESSION['answered_questions'][] = $current['category'] . "-" . $current['index'];

$_SESSION['current_player'] = ($_SESSION['current_player'] + 1) % count($_SESSION['players']);

unset($_SESSION['current_question']);
header("Location: gameboard.php");
exit();
?>
