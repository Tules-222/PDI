(() => {
  const form = document.querySelector('[data-georef-address]');
  if (!form) return;

  const province = form.querySelector('[name="provincia"]');
  const locality = form.querySelector('[name="localidad"]');
  const list = form.querySelector('#localidades-argentinas');
  const status = form.querySelector('[data-georef-status]');
  if (!province || !locality || !list || !status) return;
  locality.setAttribute('aria-describedby', status.id);
  province.setAttribute('aria-describedby', status.id);

  const phone = form.querySelector('[data-argentina-phone]');
  phone?.addEventListener('input', () => {
    let remainingDigits = 13;
    phone.value = phone.value.replace(/\d/g, (digit) => {
      remainingDigits -= 1;
      return remainingDigits >= 0 ? digit : '';
    });
  });

  const selectedProvince = province.dataset.selected || '';
  let localityTimer;

  const loadProvinces = async () => {
    status.textContent = 'Cargando provincias...';
    const response = await fetch('/georef/provincias', { headers: { Accept: 'application/json' } });
    if (!response.ok) throw new Error('No se pudieron cargar las provincias.');
    const payload = await response.json();
    province.replaceChildren(new Option('Elegí una provincia', ''));
    for (const item of payload.provincias) {
      const option = document.createElement('option');
      option.value = item.nombre;
      option.textContent = item.nombre;
      province.append(option);
    }
    province.value = selectedProvince;
    status.textContent = 'Elegí una provincia y una localidad de Argentina.';
    if (province.value) await loadLocalities();
  };

  const loadLocalities = async () => {
    list.replaceChildren();
    if (!province.value) return;
    const query = locality.value.trim();
    if (query.length < 2) {
      status.textContent = 'Escribí al menos dos letras para buscar tu localidad.';
      return;
    }

    const params = new URLSearchParams({ provincia: province.value, q: query });
    const response = await fetch(`/georef/localidades?${params}`, { headers: { Accept: 'application/json' } });
    if (!response.ok) throw new Error('No se pudieron buscar las localidades.');
    const payload = await response.json();
    const seenLocalities = new Set();
    for (const item of payload.localidades) {
      const normalizedName = item.nombre.trim().toLocaleLowerCase('es-AR');
      if (seenLocalities.has(normalizedName)) continue;
      seenLocalities.add(normalizedName);
      const option = document.createElement('option');
      option.value = item.nombre;
      option.textContent = item.nombre;
      list.append(option);
    }
    status.textContent = payload.localidades.length > 0
      ? 'Seleccioná una localidad existente de la lista.'
      : 'No encontramos esa localidad en la provincia seleccionada.';
  };

  province.addEventListener('change', () => {
    locality.value = '';
    list.replaceChildren();
    status.textContent = 'Escribí tu localidad para buscarla.';
  });
  locality.addEventListener('input', () => {
    window.clearTimeout(localityTimer);
    localityTimer = window.setTimeout(() => {
      loadLocalities().catch(() => {
        status.textContent = 'No se pudieron consultar las localidades. Intentá nuevamente.';
      });
    }, 250);
  });

  loadProvinces().catch(() => {
    status.textContent = 'No se pudieron cargar las provincias. Recargá la página para volver a intentar.';
    province.disabled = true;
    locality.disabled = true;
  });
})();
