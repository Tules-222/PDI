<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;

require_once __DIR__ . "/../services/auth.service.php";

function show_register_form(Request $req, Response $res): Response
{
  global $renderer;

  return view($renderer, $res, "auth/register.php");
}

function handle_register(Request $req, Response $res): Response
{
  global $renderer;

  $data = (array) $req->getParsedBody();
  try {
    register_user(
      $data["username"] ?? "",
      $data["email"] ?? "",
      $data["password"] ?? "",
    );

    return $res->withHeader("Location", "/auth/login")->withStatus(302);
  } catch (Exception $e) {
    return view($renderer, $res, "/auth/register.php", [
      "error" => $e->getMessage(),
    ]);
  }
}

function show_login_form(Request $req, Response $res): Response
{
  global $renderer;

  return view($renderer, $res, "/auth/login.php");
}

function handle_login(Request $req, Response $res): Response
{
  global $renderer;

  $data = (array) $req->getParsedBody();
  $email = $data["email"] ?? "";
  $password = $data["password"] ?? "";

  try {
    $user = login_user($email, $password);

    $_SESSION["user"] = $user;

    return $res->withHeader("Location", "/")->withStatus(302);
  } catch (Exception $e) {
    return view($renderer, $res, "/auth/login.php", [
      "error" => $e->getMessage(),
      "old" => ["email" => $email],
    ]);
  }
}
