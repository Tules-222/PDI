<div class="container">
  <h1>Publicaciones</h1>
  <p><a href="/posts/create">Crear nueva publicación</a></p>

  <?php if (empty($posts)): ?>
    <p>Todavía no hay publicaciones.</p>
  <?php else: ?>
    <ul>
      <?php foreach ($posts as $post): ?>
        <li>
          <a href="/posts/<?= $post["id"] ?>"><?= html($post["title"]) ?></a>
          - <a href="/posts/<?= $post["id"] ?>/edit">Editar</a>
          <form action="/posts/<?= $post[
            "id"
          ] ?>/delete" method="post" style="display:inline">
            <button type="submit">Eliminar</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
