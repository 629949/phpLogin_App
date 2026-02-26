<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . "/../config/db.php";

$uid = (int)($_GET["uid"] ?? 0);
$token = $_GET["token"] ?? "";

if ($uid <= 0 || $token === "") {
  die("Invalid reset link.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $newPass = $_POST["password"] ?? "";
  if ($newPass === "") die("Password required.");

  $stmt = $pdo->prepare("SELECT id, token_hash, expires_at FROM password_resets WHERE user_id = ? ORDER BY id DESC");
  $stmt->execute([$uid]);
  $rows = $stmt->fetchAll();

  $now = new DateTime();
  $validRowId = null;

  foreach ($rows as $r) {
    if (new DateTime($r["expires_at"]) < $now) continue;
    if (password_verify($token, $r["token_hash"])) {
      $validRowId = (int)$r["id"];
      break;
    }
  }

  if (!$validRowId) {
    die("Reset token expired or invalid.");
  }

  $newHash = password_hash($newPass, PASSWORD_DEFAULT);

  $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
  $stmt->execute([$newHash, $uid]);

  $stmt = $pdo->prepare("DELETE FROM password_resets WHERE user_id = ?");
  $stmt->execute([$uid]);

  $_SESSION["msg"] = "Password reset successful. Please login.";
  $_SESSION["msg_type"] = "success";
  header("Location: ../index.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Reset Password</title>
  <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
  <div class="container">
    <div class="left"></div>
    <div class="right">
      <div class="card">
        <h2>Reset Password</h2>

        <form method="POST">
          <label>New Password</label>
          <input class="input" type="password" name="password" required />
          <button class="btn btn-primary" type="submit">Reset</button>
        </form>

        <p style="margin-top:12px;">
          <a href="../index.php">Back to Login</a>
        </p>
      </div>
    </div>
  </div>
</body>
</html>
