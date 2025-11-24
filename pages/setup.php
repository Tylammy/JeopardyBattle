<?php
// setup.php - show form and initialize session state when submitted
// set session cookie to expire on browser close
session_set_cookie_params(0);
session_start();

require_once __DIR__ . '/../data/questions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['players'])) {
    $players = array_values(array_filter(array_map('trim', $_POST['players'])));
    if (count($players) < 2) {
        $_SESSION['error'] = "Please enter at least 2 players.";
        header("Location: setup.php");
        exit();
    }

    // initialize game session state
    $_SESSION['players'] = $players;
    $_SESSION['scores'] = array_fill(0, count($players), 0);
    $_SESSION['player_correct'] = array_fill(0, count($players), 0);
    $_SESSION['player_total'] = array_fill(0, count($players), 0);
    $_SESSION['current_player'] = 0;
    $_SESSION['answered'] = [];              // keys like "Computer Science_0"
    $_SESSION['questions_answered'] = 0;
    $_SESSION['category_streaks'] = array_fill(0, count($players), []);
    $_SESSION['final_jeopardy'] = false;
    $_SESSION['final_question'] = null;
    $_SESSION['game_over'] = false;

    // flatten keys and choose a single Daily Double at random
    $flat_keys = [];
    foreach ($questions as $cat => $qs) {
        foreach ($qs as $idx => $q) {
            $flat_keys[] = $cat . '_' . $idx;
        }
    }
    shuffle($flat_keys);
    $_SESSION['daily_double'] = $flat_keys[0] ?? null;

    // store total questions
    $_SESSION['total_questions'] = count($flat_keys);

    header("Location: gameboard.php");
    exit();
}

// show form
include '../includes/header.php';
?>
<div class="page-container">
    <h2>Enter Player Names (2–4)</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <p class="error"><?= htmlspecialchars($_SESSION['error']); ?></p>
        <?php unset($_SESSION['error']); endif; ?>

    <form action="setup.php" method="POST" class="setup-form">
        <label>Player 1</label>
        <input type="text" name="players[]" required>
        <label>Player 2</label>
        <input type="text" name="players[]" required>
        <label>Player 3 (optional)</label>
        <input type="text" name="players[]">
        <label>Player 4 (optional)</label>
        <input type="text" name="players[]">
        <button type="submit" class="btn">Start Game</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>