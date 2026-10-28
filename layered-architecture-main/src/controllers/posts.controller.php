<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;

require_once __DIR__ . "/../services/posts.service.php";

function show_posts(Request $req, Response $res): Response
{
  global $renderer;

  $posts = fetch_posts();

  return view($renderer, $res, "posts/index.php", [
    "posts" => $posts,
  ]);
}

function show_post(Request $req, Response $res, array $args): Response
{
  global $renderer;

  $id = (int) ($args["id"] ?? 0);
  $post = fetch_post($id);

  if ($post === null) {
    return view($renderer, $res->withStatus(404), "posts/not_found.php");
  }

  return view($renderer, $res, "posts/show.php", [
    "post" => $post,
  ]);
}

function show_post_create_form(Request $req, Response $res): Response
{
  global $renderer;

  return view($renderer, $res, "posts/form.php");
}

function show_post_edit_form(Request $req, Response $res, array $args): Response
{
  global $renderer;

  $id = (int) ($args["id"] ?? 0);
  $post = fetch_post($id);

  if ($post === null) {
    return view($renderer, $res->withStatus(404), "posts/not_found.php");
  }

  $user_id = (int) $req->getAttribute("user_id");
  if ($post["user_id"] !== $user_id) {
    return $res->withStatus(403)->getBody()->write("Acceso denegado");
  }

  return view($renderer, $res, "posts/form.php", [
    "post" => $post,
  ]);
}

function handle_post_create(Request $req, Response $res): Response
{
  $data = (array) $req->getParsedBody();

  $title = $data["title"] ?? "";
  $body = $data["body"] ?? "";

  $user_id = (int) $req->getAttribute("user_id");

  $created = create_post($title, $body, $user_id);

  return $res->withHeader("Location", "/posts")->withStatus(302);
}

function handle_post_update(Request $req, Response $res, array $args): Response
{
  $id = (int) ($args["id"] ?? 0);
  $data = (array) $req->getParsedBody();

  $user_id = (int) $req->getAttribute("user_id");

  $post = fetch_post($id);
  if ($post === null) {
    return view(
      $GLOBALS["renderer"],
      $res->withStatus(404),
      "posts/not_found.php",
    );
  }

  if ($post["user_id"] !== $user_id) {
    return $res->withStatus(403)->getBody()->write("Acceso denegado");
  }

  $title = $data["title"] ?? "";
  $body = $data["body"] ?? "";

  modify_post($id, $title, $body);

  return $res->withHeader("Location", "/posts")->withStatus(302);
}

function handle_post_delete(Request $req, Response $res, array $args): Response
{
  $id = (int) ($args["id"] ?? 0);

  $user_id = (int) $req->getAttribute("user_id");

  $post = fetch_post($id);
  if ($post === null) {
    return view(
      $GLOBALS["renderer"],
      $res->withStatus(404),
      "posts/not_found.php",
    );
  }

  if ($post["user_id"] !== $user_id) {
    return $res->withStatus(403)->getBody()->write("Acceso denegado");
  }

  remove_post($id);

  return $res->withHeader("Location", "/posts")->withStatus(302);
}
