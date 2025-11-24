<?php include '../includes/header.php'; ?>
<div class="homepage-container">
    <h2>Welcome to Jeopardy Battle!</h2>
    <p>Test your knowledge, compete with friends, and claim victory!</p>
    <div class="page-container homepage-container">
        <form action="setup.php" method="get">
            <button type="submit">Start Game</button>
        </form>
        <form action="dashboard.php" method="get">
            <button type="submit">Dashboard</button>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
