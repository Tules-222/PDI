<?php

declare(strict_types=1);

$filters = $filters ?? ['desde' => '', 'hasta' => '', 'actor' => '', 'entidad' => '', 'accion' => ''];
$entries = $entries ?? [];
$error = $error ?? null;
?>
<main class="page-content admin-page">
  <a class="back-link" href="/admin">← Volver a administración</a>
  <p class="eyebrow">Registro de actividad</p>
  <h1>Auditoría</h1>
  <p>Se muestran hasta 200 acciones recientes. El PIN administrativo es compartido y no identifica a una persona concreta.</p>
  <?php if ($error !== null): ?><p class="notice notice-error" role="alert"><?= html($error) ?></p><?php endif; ?>
  <section class="form-card">
    <form class="stacked-form" method="get" action="/admin/auditoria">
      <div class="form-grid">
        <div class="form-field">
          <label for="desde">Desde</label>
          <input id="desde" name="desde" type="date" value="<?= html((string) $filters['desde']) ?>">
        </div>
        <div class="form-field">
          <label for="hasta">Hasta</label>
          <input id="hasta" name="hasta" type="date" value="<?= html((string) $filters['hasta']) ?>">
        </div>
        <div class="form-field">
          <label for="actor">Actor (exacto)</label>
          <input id="actor" name="actor" maxlength="100" value="<?= html((string) $filters['actor']) ?>" placeholder="admin_pin_compartido o usuario:ID">
        </div>
        <div class="form-field">
          <label for="entidad">Entidad (exacta)</label>
          <input id="entidad" name="entidad" maxlength="40" value="<?= html((string) $filters['entidad']) ?>">
        </div>
        <div class="form-field">
          <label for="accion">Acción (exacta)</label>
          <input id="accion" name="accion" maxlength="40" value="<?= html((string) $filters['accion']) ?>">
        </div>
      </div>
      <div class="card-actions">
        <button class="button button-primary" type="submit">Filtrar actividad</button>
        <a class="button button-outline" href="/admin/auditoria">Limpiar filtros</a>
      </div>
    </form>
  </section>
  <section class="admin-section" aria-label="Resultados de auditoría">
    <p><?= count($entries) ?> registros<?= count($entries) === 200 ? ' (máximo mostrado)' : '' ?></p>
    <?php if ($entries === []): ?>
      <div class="empty-state"><h2>No hay acciones para esos filtros</h2></div>
    <?php else: ?>
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr><th>Fecha</th><th>Actor</th><th>Acción</th><th>Entidad</th><th>ID</th><th>Detalle</th></tr>
          </thead>
          <tbody>
            <?php foreach ($entries as $entry): ?>
              <tr>
                <td><?= html((string) $entry['creado_en']) ?></td>
                <td><?= html((string) $entry['actor']) ?></td>
                <td><?= html((string) $entry['accion']) ?></td>
                <td><?= html((string) $entry['entidad']) ?></td>
                <td><?= $entry['entidad_id'] === null ? '—' : (int) $entry['entidad_id'] ?></td>
                <td><?= html((string) $entry['detalle']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </section>
</main>
