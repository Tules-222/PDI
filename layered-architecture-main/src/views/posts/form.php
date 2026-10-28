<div class="container">
  <h1><?= isset($post) ? "Editar post" : "Crear post" ?></h1>

  <form method="post" action="<?= isset($post)
    ? "/posts/" . $post["id"] . "/update"
    : "/posts" ?>">
    <div>
      <label>Título</label>
      <input type="text" name="title" value="<?= html(
        $post["title"] ?? "",
      ) ?>" required />
    </div>
    <div>
      <label>Contenido</label>
      <textarea name="body" rows="8" required><?= html(
        $post["body"] ?? "",
      ) ?></textarea>
    </div>
    <div>
      <label> imagenes </label>
      <input type="URL" name="">
      </div>
    <div>
      <button type="submit"><?php echo isset($post)
        ? "Actualizar"
        : "Crear"; ?></button>
      <a href="/posts">Cancelar</a>
    </div>
  </form>
</div>
