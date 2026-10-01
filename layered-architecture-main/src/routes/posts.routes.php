<?php

use Slim\App;

/** @var App $app */

require_once __DIR__ . "/../controllers/posts.controller.php";
require_once __DIR__ . "/../middlewares/auth.middleware.php";

$app->get("/posts", "show_posts");

$app->get("/posts/create", "show_post_create_form")->add("authMiddleware");
$app->post("/posts", "handle_post_create")->add("authMiddleware");

$app->get("/posts/{id}", "show_post");
$app->get("/posts/{id}/edit", "show_post_edit_form")->add("authMiddleware");
$app->post("/posts/{id}/update", "handle_post_update")->add("authMiddleware");
$app->post("/posts/{id}/delete", "handle_post_delete")->add("authMiddleware");
