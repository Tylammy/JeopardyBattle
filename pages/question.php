<?php
session_start();
require_once __DIR__ . '/../data/questions.php';

// Make sure game is started
if (!isset($_SESSION['players'])) {
    header("Location: setup.php");
    exit();
}

// Get question key from URL
$qKey = $_GET['q'] ?? null;
if (!$qKey) {
    header("Location: gameboard.php");
    exit();
}

// Parse category and index
$parts = explode('_', $qKey);
$idx = array_pop($parts);
$category = implode('_', $parts);

// Make sure question exists
if (!isset($questions[$category][$idx])) {
    header("Location: gameboard.php");
    exit();
}

$question = $questions[$category][$idx];
$players = $_SESSION['players'];
$currentPlayerIndex = $_SESSION['current_player'];
$currentPlayerName = $players[$currentPlayerIndex];
$isDailyDouble = $question['daily_double'] ?? false;

// Initialize daily double wager
if (!isset($_SESSION['daily_double_wager'])) {
    $_SESSION['daily_double_wager'] = null;
}

// Handle POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $playerIndex = (int)($_POST['player_index'] ?? $currentPlayerIndex);

    if (isset($_POST['wager'])) {
        $_SESSION['daily_double_wager'] = max(0, intval($_POST['wager']));
    }

    if (isset($_POST['answer'])) {
        $submitted = strtolower(trim($_POST['answer']));
        $correct = strtolower(trim($question['answer']));
        $isCorrect = ($submitted === $correct);

        $points = $isDailyDouble ? ($_SESSION['daily_double_wager'] ?? $question['value']) : $question['value'];
        if (!$isCorrect) $points = -$points;

        $_SESSION['scores'][$playerIndex] += $points;

        // Stats
        $_SESSION['player_total'][$playerIndex] += 1;
        if ($isCorrect) $_SESSION['player_correct'][$playerIndex] += 1;

        // Reset streak if wrong
        if (!$isCorrect) $_SESSION['category_streaks'][$playerIndex][$category] = 0;
        else $_SESSION['category_streaks'][$playerIndex][$category] = ($_SESSION['category_streaks'][$playerIndex][$category] ?? 0) + 1;

        // Mark answered
        if (!in_array($qKey, $_SESSION['answered'])) {
            $_SESSION['answered'][] = $qKey;
            $_SESSION['questions_answered']++;
        }

        // Clear wager
        unset($_SESSION['daily_double_wager']);

        // Advance turn
        $_SESSION['current_player'] = ($currentPlayerIndex + 1) % count($players);

        header("Location: gameboard.php");
        exit();
    }
}

include '../includes/header.php';
?>

<div class="page-container question-page">
    <div class="current-player-top">
        Current Player: <span class="current-player-name"><?= htmlspecialchars($currentPlayerName) ?></span>
    </div>

    <h2><?= htmlspecialchars($category) ?> — $<?= $question['value'] ?> <?= $isDailyDouble ? '<span class="dd">💰 Daily Double!</span>' : '' ?></h2>

    <?php if ($isDailyDouble && !$_SESSION['daily_double_wager']): ?>
        <?php $playerScore = max(0, intval($_SESSION['scores'][$currentPlayerIndex] ?? 0)); ?>
        <?php $maxWager = max($playerScore, $question['value']); ?>
        <p><strong>Daily Double! Place your wager (0–<?= $maxWager ?>)</strong></p>
        <form method="POST">
            <input type="number" name="wager" min="0" max="<?= $maxWager ?>" value="<?= min($question['value'], $maxWager) ?>" required>
            <input type="hidden" name="player_index" value="<?= $currentPlayerIndex ?>">
            <button type="submit" class="btn">Submit Wager</button>
        </form>
    <?php else: ?>
        <p><?= htmlspecialchars($question['question']) ?></p>

        <?php foreach ($players as $idx => $name): ?>
            <form method="POST" style="margin-bottom:10px;">
                <label><?= htmlspecialchars($name) ?>'s answer:</label>
                <input type="text" name="answer" required>
                <input type="hidden" name="player_index" value="<?= $idx ?>">
                <button type="submit" class="btn">Submit Answer</button>
            </form>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
