<?php

declare(strict_types=1);

$productos = $productos ?? [];
$search = $search ?? '';
$error = $error ?? null;
$usuarioActual = $usuarioActual ?? null;
$totalPublicados = $totalPublicados ?? count($productos);
$cantidadResultados = $cantidadResultados ?? count($productos);
?>
<header>
	<div class="header-container">
		<a class="logo" href="/" aria-label="Overshine Contacts, inicio">
			<img src="/img/emprendi.png" alt="Emprendi" />
		</a>

		<form class="search-container" action="/" method="GET" role="search">
			<input class="search-input" type="search" name="q" value="<?= htmlspecialchars($search, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" placeholder="Buscar productos" aria-label="Buscar productos" />
			<button class="search-button" type="submit">Buscar</button>
		</form>

		<nav class="nav-actions" aria-label="Navegación principal">
			<?php if ($usuarioActual === null): ?>
				<a class="btn btn-login" href="/usuarios/login">Ingresar</a>
				<a class="btn btn-register" href="/usuarios/register">Registrarme</a>
			<?php else: ?>
				<span><?= htmlspecialchars((string) $usuarioActual['nombre'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
				<a href="/mis-productos">Mis productos</a>
				<a href="/mis-compras">Mis compras</a>
				<a href="/mis-ventas">Mis ventas</a>
				<a href="/mi-cuenta">Mi cuenta</a>
				<?php if (($usuarioActual['rol'] ?? '') === 'admin'): ?>
					<a href="/admin/productos">Revisar productos</a>
					<a href="/admin/pedidos">Contratos</a>
				<?php endif; ?>
				<form method="POST" action="/usuarios/logout"><button type="submit">Cerrar sesión</button></form>
			<?php endif; ?>
			<a class="btn btn-cart" href="/productos/create">Publicar producto</a>
		</nav>
	</div>
</header>

<section class="hero">
	<h1>Overshine Contacts</h1>
	<p>Productos, proveedores y oportunidades para hacer crecer tu negocio.</p>
</section>

<main>
	<h2 class="section-title">
		<?= $search !== '' ? 'Resultados de búsqueda' : ($totalPublicados <= 12 ? 'Productos publicados' : 'Productos destacados') ?>
	</h2>
	<?php if ($search !== ''): ?><p class="catalog-caption"><?= $cantidadResultados ?> resultados para “<?= htmlspecialchars($search, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>”</p><?php elseif ($totalPublicados > 12): ?><p class="catalog-caption">Destacados según búsquedas y solicitudes aceptadas.</p><?php endif; ?>

	<?php if ($error !== null): ?>
		<p role="alert"><?= htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
	<?php elseif ($productos === []): ?>
		<p><?= $search !== '' ? 'No se encontraron productos para esa búsqueda.' : 'Todavía no hay productos publicados.' ?></p>
	<?php else: ?>
		<section class="products-grid" aria-label="Catálogo de productos">
			<?php foreach ($productos as $producto): ?>
				<article class="product-card">
					<div class="product-image" aria-hidden="true">Emprendi</div>
					<div class="product-info">
						<span class="product-tag">Producto</span>
						<h3 class="product-name"><?= htmlspecialchars((string) $producto['nombre'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h3>
						<p><?= htmlspecialchars((string) $producto['descripcion'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
						<div class="product-price-box">
							<span class="price-wholesale">$<?= htmlspecialchars((string) $producto['precio'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
							<span class="price-label">Precio</span>
						</div>
						<a class="btn-add" href="/productos/<?= rawurlencode((string) $producto['id']) ?>">Ver producto</a>
					</div>
				</article>
			<?php endforeach; ?>
		</section>
	<?php endif; ?>
</main>
