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
    </div>

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
                            <input type="text" name="username" class="input">
                        </div>
                        <div class="input-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="input" required>
                        </div>

                        <div class="row">
                            <label for="remember">
                                <input type="checkbox" name="remember" value="1"> Remember me
                            </label>
                        </div>

                        

                        <button type="submit" class="btn btn-primary">Login</button>

                        <a href="auth/register.php">
                            <button class="btn-secondary" type="button">Register</button>
                        </a>

                    </form>
                </div>
            </div>
        
    </div>
    
</body>
</html> 