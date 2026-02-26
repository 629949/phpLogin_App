<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . "/../config/db.php";

$resetLink = null;
$msg = $_SESSION['msg'] ?? null;
$msg_type = $_SESSION['msg_type'] ?? null;
unset($_SESSION['msg'], $_SESSION['msg_type']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = trim($_POST["name"] ?? "");

  $stmt = $pdo->prepare("SELECT id FROM users WHERE name = ?");
  $stmt->execute([$name]);
  $user = $stmt->fetch();

  if (!$user) {
    $_SESSION["msg"] = "No account found with that name.";
    $_SESSION["msg_type"] = "error";
    header("Location: forgot_password.php");
    exit;
  }

  $rawToken = bin2hex(random_bytes(32));
  $tokenHash = password_hash($rawToken, PASSWORD_DEFAULT);
  $expires = new DateTime("+30 minutes");

  $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token_hash, expires_at) VALUES (?, ?, ?)");
  $stmt->execute([$user["id"], $tokenHash, $expires->format("Y-m-d H:i:s")]);

  $resetLink = "reset_password.php?uid=" . $user["id"] . "&token=" . $rawToken;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Forgot Password</title>
  <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
  <div class="container">
    <div class="left"></div>
    <div class="right">
      <div class="card">
        <h2>Forgot Password</h2>

        <?php if ($msg): ?>
          <div class="msg <?= htmlspecialchars($msg_type) ?>">
            <?= htmlspecialchars($msg) ?>
          </div>
        <?php endif; ?>

        <form method="POST">
          <label>Enter your Name</label>
          <input class="input" type="text" name="name" required />
          <button class="btn btn-primary" type="submit">Generate Reset Link</button>
        </form>

        <?php if ($resetLink): ?>
          <div class="msg success" style="margin-top:12px;">
            Reset link generated (demo):<br>
            <a href="<?= htmlspecialchars($resetLink) ?>"><?= htmlspecialchars($resetLink) ?></a>
          </div>
        <?php endif; ?>

        <p style="margin-top:12px;">
          <a href="../index.php">Back to Login</a>
        </p>
      </div>
    </div>
  </div>
</body>
</html>
