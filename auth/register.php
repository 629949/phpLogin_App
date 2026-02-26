<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . "/../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = trim($_POST["name"] ?? "");
  $password = $_POST["password"] ?? "";

  if ($name === "" || $password === "") {
    $_SESSION["msg"] = "All fields are required.";
    $_SESSION["msg_type"] = "error";
    header("Location: register.php");
    exit;
  }

  $stmt = $pdo->prepare("SELECT id FROM users WHERE name = ?");
  $stmt->execute([$name]);
  if ($stmt->fetch()) {
    $_SESSION["msg"] = "That name is already registered.";
    $_SESSION["msg_type"] = "error";
    header("Location: register.php");
    exit;
  }

  $hash = password_hash($password, PASSWORD_DEFAULT);

  $stmt = $pdo->prepare("INSERT INTO users (name, password_hash) VALUES (?, ?)");
  $stmt->execute([$name, $hash]);

  $_SESSION["msg"] = "Registration successful. Please login.";
  $_SESSION["msg_type"] = "success";
  header("Location: ../index.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Register</title>
  <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
  <div class="container">
    <div class="left"></div>
    <div class="right">
      <div class="card">
        <h2>Create Account</h2>

        <form method="POST">
          <label>Name</label>
          <input class="input" type="text" name="name" required />

          <label>Password</label>
          <input class="input" type="password" name="password" required />

          <button class="btn btn-primary" type="submit">Register</button>
        </form>

        <p style="margin-top:12px;">
          Already have an account? <a href="../index.php">Login</a>
        </p>
      </div>
    </div>
  </div>
</body>
</html>
