<?php

require_once __DIR__ . "/../persistence/posts.persistence.php";

function create_post(string $title, string $body, ?int $user_id): array
{
  global $database;

  return $database->runTransaction(function () use ($title, $body, $user_id) {
    $id = save_post([
      "title" => $title,
      "body" => $body,
      "user_id" => $user_id,
    ]);

    return ["id" => $id, "title" => $title];
  });
}

function fetch_post(int $id): ?array
{
  return get_post($id);
}

function fetch_posts(): array
{
  return get_all_posts();
}

function modify_post(int $id, string $title, string $body): void
{
  global $database;

  $database->runTransaction(function () use ($id, $title, $body) {
    update_post($id, ["title" => $title, "body" => $body]);
  });
}

function remove_post(int $id): void
{
  global $database;

  $database->runTransaction(function () use ($id) {
    delete_post($id);
  });
}
