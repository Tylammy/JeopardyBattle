<?php
session_start();
require_once "questions.php";

$cat = $_GET['cat'] ?? '';
$i = $_GET['i'] ?? '';

$question = $questions[$cat][$i] ?? null;

if (!$question) {
    echo "Invalid question.";
    exit();
}

echo "
    <h2>$cat — $" . $question['value'] . "</h2>
    <p>" . $question['q'] . "</p>

    <form method='POST'>
        <input type='text' name='answer'>
        <button type='submit'>Submit</button>
    </form>
";
?>
