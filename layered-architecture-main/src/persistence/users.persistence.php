<?php

function save_user(array $user): int
{
  global $database;

  $conn = $database->getConnection();
  $stmt = $conn->prepare(
    "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)",
  );
  $stmt->execute([
    "username" => $user["username"],
    "email" => $user["email"],
    "password_hash" => $user["password_hash"],
  ]);

  return (int) $conn->lastInsertId();
}

function get_user_by_email(string $email): ?array
{
  global $database;

  $conn = $database->getConnection();
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
  $stmt->execute(["email" => $email]);
  $user = $stmt->fetch();

  return $user ?: null;
}
