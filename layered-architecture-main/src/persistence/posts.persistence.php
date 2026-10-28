<?php

function save_post(array $post): int
{
  global $database;

  $conn = $database->getConnection();
  $stmt = $conn->prepare(
    "INSERT INTO posts (title, body, user_id) VALUES (:title, :body, :user_id)",
  );
  $stmt->execute([
    "title" => $post["title"],
    "body" => $post["body"],
    "user_id" => $post["user_id"] ?? null,
  ]);

  return (int) $conn->lastInsertId();
}

function get_post(int $id): ?array
{
  global $database;

  $conn = $database->getConnection();
  $stmt = $conn->prepare("SELECT * FROM posts WHERE id = :id");
  $stmt->execute(["id" => $id]);
  $post = $stmt->fetch();

  return $post ?: null;
}

function get_all_posts(): array
{
  global $database;

  $conn = $database->getConnection();
  $stmt = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");

  return $stmt->fetchAll();
}

function update_post(int $id, array $post): void
{
  global $database;

  $conn = $database->getConnection();
  $stmt = $conn->prepare(
    "UPDATE posts SET title = :title, body = :body, updated_at = now() WHERE id = :id",
  );
  $stmt->execute([
    "title" => $post["title"],
    "body" => $post["body"],
    "id" => $id,
  ]);
}

function delete_post(int $id): void
{
  global $database;

  $conn = $database->getConnection();
  $stmt = $conn->prepare("DELETE FROM posts WHERE id = :id");
  $stmt->execute(["id" => $id]);
}
