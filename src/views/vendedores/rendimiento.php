<?php

declare(strict_types=1);

$metricas = $metricas ?? [];
$seguidores = $seguidores ?? 0;
$productosAprobados = $productosAprobados ?? 0;
?>
<main class="page-content">
  <a class="back-link" href="/">← Volver al catálogo</a>
  <p class="eyebrow">Actividad de tu cuenta</p>
  <h1>Rendimiento</h1>
  <p class="form-intro">Totales históricos de todos tus emprendimientos. Solo se cuentan pedidos con pago aprobado; las entregas se cuentan cuando el pedido está marcado como entregado.</p>
  <section class="metrics-grid" aria-label="Métricas históricas de ventas">
    <article class="form-card metric-card"><span>Pedidos pagados</span><strong><?= (int) ($metricas['pedidos_pagados'] ?? 0) ?></strong><p>Pedidos con pago aprobado.</p></article>
    <article class="form-card metric-card"><span>Pedidos entregados</span><strong><?= (int) ($metricas['pedidos_entregados'] ?? 0) ?></strong><p>Pago aprobado y estado entregado.</p></article>
    <article class="form-card metric-card"><span>Unidades pagadas</span><strong><?= (int) ($metricas['unidades_pagadas'] ?? 0) ?></strong><p>Unidades incluidas en pedidos con pago aprobado.</p></article>
    <article class="form-card metric-card"><span>Importe bruto de productos pagados</span><strong><?= html(formatArs($metricas['importe_bruto_pagado'] ?? 0)) ?></strong><p>Suma de precios de productos; excluye costos de envío.</p></article>
    <article class="form-card metric-card"><span>Seguidores únicos</span><strong><?= (int) $seguidores ?></strong><p>Una cuenta que sigue varias de tus tiendas se cuenta una sola vez.</p></article>
    <article class="form-card metric-card"><span>Productos aprobados visibles</span><strong><?= (int) $productosAprobados ?></strong><p>Publicaciones aprobadas de emprendimientos activos.</p></article>
  </section>
</main>
