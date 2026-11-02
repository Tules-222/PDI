<?php
$pasos = [1 => 'Carrito', 2 => 'Pago', 3 => 'Confirmación'];
?>
<nav class="checkout-steps" aria-label="Progreso de compra">
  <ol>
    <?php foreach ($pasos as $numero => $nombre): ?>
      <li class="checkout-step <?= $numero === $pasoActual ? 'is-current' : '' ?> <?= $numero < $pasoActual ? 'is-complete' : '' ?>"
          <?= $numero === $pasoActual ? 'aria-current="step"' : '' ?>>
        <span class="checkout-step-number" aria-hidden="true">
          <?= $numero < $pasoActual ? '✓' : $numero ?>
        </span>
        <span class="checkout-step-label"><?= html($nombre) ?></span>
      </li>
    <?php endforeach; ?>
  </ol>
</nav>
