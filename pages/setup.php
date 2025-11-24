<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Player Setup - Jeopardy Battle</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>

<div class="page-container">
    <h2>Enter Player Names</h2>

    <?php
    // Show error if fewer than 2 players submitted previously
    if (isset($_SESSION['error'])) {
        echo "<p style='color:red; font-weight:bold;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>

    <form action="setup_process.php" method="POST">
        <label>Player 1:</label>
        <input type="text" name="players[]" required>

        <label>Player 2:</label>
        <input type="text" name="players[]" required>

        <button type="submit">Start Game</button>
    </form>
</div>

</body>
</html>
