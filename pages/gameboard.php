<?php
session_start();
require_once "questions.php"; // question database

if (!isset($_SESSION['players']) || empty($_SESSION['players'])) {
    header("Location: setup.html");
    exit();
}

include '../includes/header.php';

// display player scores
echo "<div class='player-scores'>";
foreach ($_SESSION['players'] as $i => $playerName) {
    $score = $_SESSION['scores'][$i] ?? 0;
    echo "<div class='player-score'><strong>" . htmlspecialchars($playerName) . ":</strong> $" . $score . "</div>";
}
echo "</div>";

// show last answer message if exists
if (isset($_SESSION['last_msg'])) {
    echo "<p style='text-align:center; font-weight:bold; color:#ffd700;'>" . $_SESSION['last_msg'] . "</p>";
    unset($_SESSION['last_msg']);
}

// build board
echo "<div class='board'>";
foreach ($questions as $category => $qs) {
    echo "<div class='column'><h3>$category</h3>";
    foreach ($qs as $i => $q) {
        echo "<a class='tile' href='question.php?cat=" . urlencode($category) . "&i=$i'>
                $" . $q['value'] . "
              </a>";
    }
    echo "</div>";
}
echo "</div>";

include '../includes/footer.php';
