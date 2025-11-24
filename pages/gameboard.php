<?php
session_start();
require_once __DIR__ . '/../data/questions.php';

if (!isset($_SESSION['players']) || !isset($_SESSION['scores'])) {
    header("Location: setup.php");
    exit();
}

$players = $_SESSION['players'];
$currentPlayer = $_SESSION['current_player'] ?? 0;
$scores = $_SESSION['scores'] ?? [];
$answered = $_SESSION['answered'] ?? [];

$totalQuestions = 0;
foreach ($questions as $cat => $qs) $totalQuestions += count($qs);
$_SESSION['total_questions'] = $totalQuestions;

if (($_SESSION['questions_answered'] ?? 0) >= $totalQuestions && empty($_SESSION['final_jeopardy'])) {
    $_SESSION['final_jeopardy'] = true;
    header("Location: final_jeopardy.php");
    exit();
}

include '../includes/header.php';
?>

<div class="page-container">
    <div class="current-player-top">
        It's <?= htmlspecialchars($players[$currentPlayer]) ?>'s turn to pick a question.
    </div>

    <div class="player-scores-row">
        <?php foreach ($players as $i => $p): ?>
            <div class="player-score <?= $i === $currentPlayer ? 'active' : '' ?>">
                <div class="player-name"><?= htmlspecialchars($p) ?></div>
                <div class="score-amount">$<?= intval($scores[$i] ?? 0) ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="board">
        <?php foreach ($questions as $category => $qs): ?>
            <div class="column">
                <h3><?= htmlspecialchars($category) ?></h3>
                <?php foreach ($qs as $i => $q):
                    $key = $category . '_' . $i;
                    $isAnswered = in_array($key, $answered, true);
                    ?>
                    <?php if ($isAnswered): ?>
                        <div class="tile answered">$<?= $q['value'] ?></div>
                    <?php else: ?>
                        <form method="GET" action="question.php" class="tile-form">
                            <input type="hidden" name="cat" value="<?= htmlspecialchars($category) ?>">
                            <input type="hidden" name="i" value="<?= $i ?>">
                            <button type="submit" class="tile">$<?= $q['value'] ?></button>
                        </form>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="board-footer">
        Questions answered: <?= intval($_SESSION['questions_answered'] ?? 0) ?> / <?= $totalQuestions ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
