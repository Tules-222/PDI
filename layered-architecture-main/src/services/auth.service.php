<?php

require_once __DIR__ . "/../persistence/users.persistence.php";

function register_user(string $username, string $email, string $password): array
{
  global $database;

  return $database->runTransaction(function () use (
    $username,
    $email,
    $password,
  ) {
    if (get_user_by_email($email) !== null) {
      throw new Exception("El email ya se encuentra en uso");
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $id = save_user([
      "username" => $username,
      "email" => $email,
      "password_hash" => $password_hash,
    ]);

    return ["id" => $id, "username" => $username];
  });
}

function login_user(string $email, string $password): array
{
  $user = get_user_by_email($email);

  if ($user === null) {
    throw new Exception("Credenciales inválidas");
  }

  if (!password_verify($password, $user["password_hash"])) {
    throw new Exception("Credenciales inválidas");
  }

  return [
    "id" => $user["id"],
    "username" => $user["username"],
    "email" => $user["email"],
  ];
}
