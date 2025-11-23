<?php
session_start();
require_once "questions.php";

if (!isset($_SESSION['players']) || empty($_SESSION['players'])) {
    header("Location: setup.html");
    exit();
}

// Build dynamic board HTML
$board_html = "<div class='board'>";

foreach ($questions as $category => $qs) {
    $board_html .= "<div class='column'><h3>$category</h3>";

    foreach ($qs as $i => $q) {
        $board_html .= "
            <a class='tile' href='question.php?cat=" . urlencode($category) . "&i=$i'>
                $" . $q['value'] . "
            </a>
        ";
    }

    $board_html .= "</div>";
}

$_SESSION['board_render'] = $board_html;

header("Location: gameboard.html");
exit();
