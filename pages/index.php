<?php include '../includes/header.php'; ?>

<div class="homepage-container">
    <h2>Welcome to Jeopardy Battle!</h2>
    <p>Test your knowledge, compete with friends, and claim victory!</p>

    <div class="home-buttons">
        <!-- Start Game button -->
        <form action="setup.php" method="get">
            <button type="submit">Start Game</button>
        </form>


        <!-- Dashboard -->
        <form action="dashboard.php" method="get">
            <button type="submit">Dashboard</button>
        </form>

        <!-- Login -->
        <form action="login.php" method="get">
            <button type="submit">Login</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
