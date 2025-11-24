<?php
session_start();
require_once "questions.php"; // database of questions

// ensure players are set
if (!isset($_SESSION['players']) || empty($_SESSION['players'])) {
    header("Location: setup.html");
    exit();
}

// get category and index
$category = $_GET['cat'] ?? '';
$index = isset($_GET['i']) ? intval($_GET['i']) : -1;

// validate question exists
if (!isset($questions[$category][$index])) {
    echo "<p>Invalid question selection.</p>";
    echo "<a href='gameboard.php'>Back to board</a>";
    exit();
}

$qData = $questions[$category][$index];

// handle submitted answer
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $playerIndex = intval($_POST['player']);
    $answer = trim($_POST['answer']);

    if (strcasecmp($answer, $qData['a']) === 0) {
        $_SESSION['scores'][$playerIndex] += $qData['value'];
        $_SESSION['last_msg'] = "Correct! +$" . $qData['value'];
    } else {
        $_SESSION['last_msg'] = "Incorrect. Correct answer: " . $qData['a'];
    }

    // optionally remove question (not persistent)
    unset($questions[$category][$index]);

    header("Location: gameboard.php");
    exit();
}
?>

<?php include '../includes/header.php'; ?>

<h2>Category: <?php echo htmlspecialchars($category); ?></h2>
<p>Value: $<?php echo $qData['value']; ?></p>
<p>Question: <?php echo htmlspecialchars($qData['q']); ?></p>

<form method="POST" action="">
    <label for="player">Player:</label>
    <select name="player" id="player" required>
        <?php foreach ($_SESSION['players'] as $i => $playerName): ?>
            <option value="<?php echo $i; ?>"><?php echo htmlspecialchars($playerName); ?></option>
        <?php endforeach; ?>
    </select>

    <label for="answer">Answer:</label>
    <input type="text" name="answer" id="answer" required>

    <button type="submit">Submit Answer</button>
</form>

<?php include '../includes/footer.php'; ?>
