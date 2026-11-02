document.querySelectorAll('[data-add-to-cart]').forEach((form) => {
  form.addEventListener('submit', async (event) => {
    const button = form.querySelector('button[type="submit"]');
    const cartCount = document.getElementById('cart-count');
    const toast = document.getElementById('toast');

    if (!button || !cartCount || !toast) {
      return;
    }

    event.preventDefault();
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.textContent = 'Agregando...';

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
        },
        body: new URLSearchParams(new FormData(form)),
      });
      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.error || 'No se pudo agregar el producto.');
      }

      cartCount.textContent = result.cantidad;
      button.innerHTML = originalContent;
      button.textContent = 'Agregado';
      button.classList.add('added');
      toast.textContent = 'Producto agregado al carrito';
      toast.classList.add('show');

      window.setTimeout(() => {
        button.innerHTML = originalContent;
        button.classList.remove('added');
        toast.classList.remove('show');
      }, 1600);
    } catch (error) {
      button.innerHTML = originalContent;
      button.classList.remove('added');
      toast.textContent = error instanceof Error
        ? error.message
        : 'No se pudo agregar el producto.';
      toast.classList.add('show');
      window.setTimeout(() => toast.classList.remove('show'), 2500);
    } finally {
      button.disabled = false;
    }
  });
});

const filterButtons = document.querySelectorAll('[data-filter]');
const productCards = document.querySelectorAll('#product-grid .card');
const resultsNote = document.getElementById('results-note');

filterButtons.forEach((filterButton) => {
  filterButton.addEventListener('click', () => {
    const filter = filterButton.dataset.filter;
    let visibleCount = 0;

    filterButtons.forEach((button) => {
      const isSelected = button === filterButton;
      button.classList.toggle('active', isSelected);
      button.setAttribute('aria-selected', String(isSelected));
    });

    productCards.forEach((card) => {
      const matches = filter === 'all'
        || (filter === 'offers' && card.dataset.offer === 'true')
        || (filter === 'best' && card.dataset.best === 'true');

      card.hidden = !matches;
      if (matches) visibleCount += 1;
    });

    if (resultsNote) {
      resultsNote.textContent = visibleCount === 0
        ? 'No hay productos para este filtro.'
        : '';
    }
  });
});
