(() => {
  const storageKey = 'emprendi-account-theme';

  function applyTheme(isDark) {
    document.documentElement.dataset.theme = isDark ? 'dark' : 'light';

    const button = document.getElementById('site-theme-toggle');
    if (button instanceof HTMLButtonElement) {
      button.setAttribute('aria-pressed', String(isDark));
      button.textContent = isDark ? 'Activar modo claro' : 'Activar modo oscuro';
    }
  }

  let isDark = false;
  try {
    isDark = window.localStorage.getItem(storageKey) === 'dark';
  } catch (error) {
    console.warn('No se pudo leer la preferencia de tema guardada.', error);
  }
  applyTheme(isDark);

  document.addEventListener('DOMContentLoaded', () => {
    applyTheme(isDark);

    const button = document.getElementById('site-theme-toggle');
    if (!(button instanceof HTMLButtonElement)) {
      return;
    }

    button.addEventListener('click', () => {
      isDark = document.documentElement.dataset.theme !== 'dark';
      applyTheme(isDark);

      try {
        window.localStorage.setItem(storageKey, isDark ? 'dark' : 'light');
      } catch (error) {
        console.warn('No se pudo guardar la preferencia de tema.', error);
      }
    });
  });
})();
