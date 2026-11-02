<?php

declare(strict_types=1);

$summary = $summary ?? ['productos' => 0, 'publicados' => 0, 'pendientes' => 0, 'emprendimientos' => 0];
$isEntrepreneur = $currentUser['rol'] === 'emprendedor';
?>
<main class="page-content business-page">
  <div class="business-heading">
    <div>
      <p class="eyebrow"><?= $isEntrepreneur ? 'Tu espacio de trabajo' : 'Un lugar para hacer crecer tu idea' ?></p>
      <h1><?= $isEntrepreneur ? 'Armá tu puesto' : 'Tu puesto te espera' ?></h1>
      <p><?= $isEntrepreneur
        ? 'Organizá tu emprendimiento, mostrale tus productos a la feria y atendé tus ventas desde acá.'
        : 'Recorré la feria como cliente y, cuando quieras, abrí tu propio puesto para mostrar y vender tus productos.' ?></p>
    </div>
    <?php if ($isEntrepreneur): ?>
      <a class="button button-primary" href="/productos/create">Publicar producto</a>
    <?php else: ?>
      <form method="post" action="/emprendedor/activar">
        <input type="hidden" name="csrf_token" value="<?= html($csrfToken) ?>">
        <button class="button button-primary" type="submit">Quiero abrir mi puesto</button>
      </form>
    <?php endif; ?>
  </div>

  <?php if (!$isEntrepreneur): ?>
    <section class="business-onboarding">
      <span class="business-onboarding-icon" aria-hidden="true">✦</span>
      <div>
        <h2>Un mismo espacio, dos formas de participar</h2>
        <p>Al abrir tu puesto se crea tu perfil comercial y vas a poder publicar productos. Tu cuenta también conserva las compras, favoritos y el acceso a toda la feria.</p>
      </div>
    </section>
    <div class="business-shortcuts">
      <a class="business-shortcut" href="/">
        <span class="business-shortcut-number">01</span>
        <span><strong>Recorrer la feria</strong><small>Buscá productos por nombre, categoría o precio.</small></span>
      </a>
      <a class="business-shortcut" href="/emprendimientos">
        <span class="business-shortcut-number">02</span>
        <span><strong>Conocer emprendimientos</strong><small>Descubrí quiénes están detrás de cada puesto.</small></span>
      </a>
    </div>
  <?php else: ?>
    <section class="business-metrics" aria-label="Resumen de tu negocio">
      <article class="business-metric">
        <span>Productos en tu puesto</span>
        <strong><?= (int) $summary['productos'] ?></strong>
      </article>
      <article class="business-metric">
        <span>Visibles en la feria</span>
        <strong><?= (int) $summary['publicados'] ?></strong>
      </article>
      <article class="business-metric">
        <span>En revisión</span>
        <strong><?= (int) $summary['pendientes'] ?></strong>
      </article>
      <article class="business-metric">
        <span>Tus puestos</span>
        <strong><?= (int) $summary['emprendimientos'] ?></strong>
      </article>
    </section>

    <section class="business-section">
      <div class="business-section-heading">
        <div>
          <p class="eyebrow">Administración</p>
          <h2>Tu negocio, en un solo lugar</h2>
        </div>
        <a class="text-link" href="/">Ver la feria <span aria-hidden="true">→</span></a>
      </div>
      <div class="business-tools">
        <a class="business-tool business-tool-featured" href="/mis-productos">
          <span class="business-tool-icon" aria-hidden="true">▦</span>
          <strong>Productos</strong>
          <span>Publicá, editá y organizá tu catálogo.</span>
          <span class="business-tool-link">Administrar productos →</span>
        </a>
        <a class="business-tool" href="/mis-emprendimientos">
          <span class="business-tool-icon" aria-hidden="true">⌂</span>
          <strong>Diseño de tu puesto</strong>
          <span>Personalizá tus emprendimientos, colores e imágenes.</span>
          <span class="business-tool-link">Editar mi puesto →</span>
        </a>
        <a class="business-tool" href="/ventas">
          <span class="business-tool-icon" aria-hidden="true">↗</span>
          <strong>Ventas recibidas</strong>
          <span>Revisá pedidos y gestioná su preparación.</span>
          <span class="business-tool-link">Ver ventas →</span>
        </a>
        <a class="business-tool" href="/conversaciones">
          <span class="business-tool-icon" aria-hidden="true">◌</span>
          <strong>Mensajes</strong>
          <span>Respondé consultas de tus clientes.</span>
          <span class="business-tool-link">Ir a mensajes →</span>
        </a>
        <a class="business-tool" href="/mi-rendimiento">
          <span class="business-tool-icon" aria-hidden="true">⌁</span>
          <strong>Rendimiento</strong>
          <span>Consultá el resumen de actividad de tu negocio.</span>
          <span class="business-tool-link">Ver rendimiento →</span>
        </a>
      </div>
    </section>
  <?php endif; ?>
</main>
