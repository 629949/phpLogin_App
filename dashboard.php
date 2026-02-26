<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . "/config/db.php";

if (!isset($_SESSION["user_id"]) && isset($_COOKIE["remember_user"], $_COOKIE["remember_token"])) {
  $userId = (int)$_COOKIE["remember_user"];
  $rawToken = $_COOKIE["remember_token"];

  $stmt = $pdo->prepare("SELECT id, token_hash, expires_at FROM auth_tokens WHERE user_id = ? ORDER BY id DESC");
  $stmt->execute([$userId]);
  $tokens = $stmt->fetchAll();

  $now = new DateTime();

  foreach ($tokens as $t) {
    if (new DateTime($t["expires_at"]) < $now) continue;
    if (password_verify($rawToken, $t["token_hash"])) {
      $stmt2 = $pdo->prepare("SELECT id, name FROM users WHERE id = ?");
      $stmt2->execute([$userId]);
      $u = $stmt2->fetch();
      if ($u) {
        $_SESSION["user_id"] = (int)$u["id"];
        $_SESSION["user_name"] = $u["name"];
      }
      break;
    }
  }
}

if (!isset($_SESSION["user_id"])) {
  header("Location: index.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Dashboard</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
  <div style="max-width:900px;margin:30px auto;background:white;padding:20px;border-radius:12px;">
    <h2>Dashboard</h2>
    <p>Welcome, <b><?= htmlspecialchars($_SESSION["user_name"]) ?></b> 👋</p>

    <hr>

    <p>This is your protected area. Only logged-in users can see this.</p>

    <a href="auth/logout.php"><button class="btn btn-secondary" style="max-width:220px;">Logout</button></a>
  </div>
</body>
</html>



 