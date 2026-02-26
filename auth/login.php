<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . "/../config/db.php";

$name = trim($_POST["name"] ?? "");
$password = $_POST["password"] ?? "";
$remember = isset($_POST["remember"]);

if ($name === "" || $password === "") {
  $_SESSION["msg"] = "Please fill in all fields.";
  $_SESSION["msg_type"] = "error";
  header("Location: ../index.php");
  exit;
}

$stmt = $pdo->prepare("SELECT id, name, password_hash FROM users WHERE name = ?");
$stmt->execute([$name]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user["password_hash"])) {
  $_SESSION["msg"] = "Invalid name or password.";
  $_SESSION["msg_type"] = "error";
  header("Location: ../index.php");
  exit;
}

$_SESSION["user_id"] = (int)$user["id"];
$_SESSION["user_name"] = $user["name"];

if ($remember) {
  $rawToken = bin2hex(random_bytes(32));
  $tokenHash = password_hash($rawToken, PASSWORD_DEFAULT);
  $expires = new DateTime("+14 days");

  $stmt = $pdo->prepare("INSERT INTO auth_tokens (user_id, token_hash, expires_at) VALUES (?, ?, ?)");
  $stmt->execute([$user["id"], $tokenHash, $expires->format("Y-m-d H:i:s")]);

  setcookie("remember_user", (string)$user["id"], time() + 60*60*24*14, "/", "", false, true);
  setcookie("remember_token", $rawToken, time() + 60*60*24*14, "/", "", false, true);
}

header("Location: ../dashboard.php");
exit;
