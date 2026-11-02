<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <a href="/" class="logo" aria-label="Emprendi, inicio">
        <span class="logo-mark" aria-hidden="true">E</span>
        <span class="logo-text">Emprendi</span>
      </a>
      <p class="footer-desc">El marketplace que impulsa a emprendedores nuevos a vender sus productos en línea y llegar a todo el país.</p>
      <address class="contact">
        <a href="mailto:emprendi@gmail.com">
          <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
          emprendi@gmail.com
        </a>
        <a href="tel:+5420301885">
          <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/></svg>
          +54 2030 1885
        </a>
        <a href="https://github.com/Tules-222/PDI" target="_blank" rel="noopener noreferrer" class="footer-github">
          <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 19c-4.3 1.4-4.3-2.5-6-3m12 6v-3.9a3.4 3.4 0 0 0-.9-2.7c3-.3 6.1-1.5 6.1-6.7a5.2 5.2 0 0 0-1.4-3.6 4.8 4.8 0 0 0-.1-3.6s-1.2-.4-3.8 1.4a13.2 13.2 0 0 0-6.9 0C5.4 1.1 4.2 1.5 4.2 1.5a4.8 4.8 0 0 0-.1 3.6A5.2 5.2 0 0 0 2.7 8.7c0 5.2 3.1 6.4 6.1 6.7a3.4 3.4 0 0 0-.9 2.7V22"/></svg>
          GitHub
        </a>
      </address>
    </div>

    <nav aria-label="Comprar">
      <h2>Comprar</h2>
      <ul><li><a href="/#productos">Categorías</a></li><li><a href="/#productos">Ofertas</a></li><li><a href="/#productos">Más vendidos</a></li><li><a href="#">Compra protegida</a></li></ul>
    </nav>
    <nav aria-label="Vender">
      <h2>Vender</h2>
      <ul>
        <?php if (empty($usuarioAutenticado)): ?>
          <li><a href="/auth/register">Empieza a vender</a></li>
        <?php endif; ?>
        <li><a href="#">Tarifas</a></li><li><a href="#">Guía para emprendedores</a></li><li><a href="#">Centro de vendedores</a></li>
      </ul>
    </nav>
    <nav aria-label="Ayuda">
      <h2>Ayuda</h2>
      <ul><li><a href="#">Preguntas frecuentes</a></li><li><a href="#">Envíos</a></li><li><a href="#">Devoluciones</a></li><li><a href="#">Términos y privacidad</a></li></ul>
    </nav>
  </div>
  <div class="footer-bottom">
    <p class="container">© 2026 Emprendi. Todos los derechos reservados.</p>
  </div>
</footer>
