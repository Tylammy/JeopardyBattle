<?php
session_start();
include '../includes/header.php';

$players = $_SESSION['players'] ?? [];
$scores = $_SESSION['scores'] ?? [];
$player_correct = $_SESSION['player_correct'] ?? [];
$player_total = $_SESSION['player_total'] ?? [];

$suggestions_pool = [
    "Focus on categories where your accuracy is below 60%. Try quick mini-quizzes.",
    "Practice timed recall — speed matters for buzzer games.",
    "Review common incorrect answers to find patterns.",
    "When unsure, use elimination — remove obviously wrong options first.",
    "If leading, consider conservative wagers; if behind, bet more aggressively on Daily Doubles.",
];
?>
<div class="page-container">
    <h2>Dashboard</h2>

    <?php if (empty($players)): ?>
        <p>No game in progress. Start a new game to see stats.</p>
    <?php else: ?>
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Player</th>
                    <th>Score</th>
                    <th>Correct</th>
                    <th>Answered</th>
                    <th>Accuracy</th>
                    <th>Suggestion</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($players as $i => $p):
                    $correct = $player_correct[$i] ?? 0;
                    $total = $player_total[$i] ?? 0;
                    $accuracy = $total > 0 ? round($correct / $total * 100, 1) : null;
                    $suggestion = ($total > 0) ? $suggestions_pool[array_rand($suggestions_pool)] : '';
                ?>
                <tr>
                    <td><?= htmlspecialchars($p) ?></td>
                    <td>$<?= intval($scores[$i] ?? 0) ?></td>
                    <td><?= intval($correct) ?></td>
                    <td><?= intval($total) ?></td>
                    <td><?= $accuracy === null ? '—' : $accuracy . '%' ?></td>
                    <td><?= htmlspecialchars($suggestion) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p style="margin-top:1rem;"><a class="btn" href="index.php">Back to Home</a></p>
    <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>
