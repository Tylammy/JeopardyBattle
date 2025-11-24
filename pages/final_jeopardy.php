<?php
session_start();
require_once __DIR__ . '/../data/questions.php';

// Ensure game is running and final jeopardy is triggered
if (!isset($_SESSION['final_jeopardy']) || !$_SESSION['final_jeopardy'] || !isset($_SESSION['players'])) {
    header("Location: gameboard.php");
    exit();
}

$players = $_SESSION['players'];

// Hard-coded Final Jeopardy question (can be updated)
$finalQuestion = [
    'category' => 'Final Jeopardy',
    'question' => 'What is the chemical symbol for gold?',
    'answer' => 'au',
    'value' => 1000 // base points for wager
];

$submittedAnswers = $_POST['answers'] ?? [];
$wagers = $_POST['wagers'] ?? [];

// Handle submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_final'])) {
    foreach ($players as $index => $player) {
        $wager = max(0, intval($wagers[$index] ?? 0));
        $answer = strtolower(trim($submittedAnswers[$index] ?? ''));
        $correct = strtolower($finalQuestion['answer']);

        if ($answer === $correct) {
            $_SESSION['scores'][$index] = ($_SESSION['scores'][$index] ?? 0) + $wager;
        } else {
            $_SESSION['scores'][$index] = ($_SESSION['scores'][$index] ?? 0) - $wager;
        }
    }

    // Clear final jeopardy flag
    unset($_SESSION['final_jeopardy']);

    header("Location: gameboard.php");
    exit();
}

include '../includes/header.php';
?>

<div class="page-container final-jeopardy">
    <h2><?= htmlspecialchars($finalQuestion['category']) ?></h2>
    <p><?= htmlspecialchars($finalQuestion['question']) ?></p>

    <form method="POST">
        <?php foreach ($players as $index => $player): 
            $score = $_SESSION['scores'][$index] ?? 0;
            $maxWager = max(0, $score);
        ?>
            <div class="final-player">
                <h3><?= htmlspecialchars($player) ?> (Current Score: $<?= $score ?>)</h3>
                <label>Wager (0 - <?= $maxWager ?>):</label>
                <input type="number" name="wagers[<?= $index ?>]" min="0" max="<?= $maxWager ?>" value="<?= min($maxWager, 100) ?>" required>
                <label>Answer:</label>
                <input type="text" name="answers[<?= $index ?>]" required>
            </div>
            <hr>
        <?php endforeach; ?>
        <button type="submit" name="submit_final" class="btn">Submit Final Jeopardy</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
