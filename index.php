<?php 
declare(strict_types=1);
session_start();

$msg = $_SESSION['msg'] ?? null;
$msg_type = $_SESSION['msg_type'] ?? null;
unset($_SESSION['msg'], $_SESSION['msg_type']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="left">
            <div class="right">
                <div class="card">
                    <h2>Welcome to Earthlight holdings</h2>

                <?php 
                if ($msg): ?>
                    <div class="alert alert-<?= $msg_type ?? 'info' ?>">
                        <?= htmlspecialchars($msg) ?>
                    </div>
                <?php endif; ?>

                    <form action="auth/login.php" method="post">
                        <div class="input-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" required>
                        </div>
                        <div class="input-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" required>
                        </div>
                        <button type="submit">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html> 