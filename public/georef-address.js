(() => {
  const form = document.querySelector('[data-georef-address]');
  if (!form) return;

  const province = form.querySelector('[name="provincia"]');
  const provinceList = form.querySelector('#provincias-argentinas');
  const locality = form.querySelector('[name="localidad"]');
  const list = form.querySelector('#localidades-argentinas');
  const status = form.querySelector('[data-georef-status]');
  if (!province || !provinceList || !locality || !list || !status) return;
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
  let localityRequest = 0;

  const loadProvinces = async () => {
    status.textContent = 'Cargando provincias...';
    const response = await fetch('/georef/provincias', { headers: { Accept: 'application/json' } });
    if (!response.ok) throw new Error('No se pudieron cargar las provincias.');
    const payload = await response.json();
    provinceList.replaceChildren();
    for (const item of payload.provincias) {
      const option = document.createElement('option');
      option.value = item.nombre;
      provinceList.append(option);
    }
    province.value = selectedProvince;
    status.textContent = 'Elegí una provincia y buscá una localidad. Si no aparece, podés escribirla.';
    if (province.value) await loadLocalities();
  };

  const loadLocalities = async () => {
    list.replaceChildren();
    const requestId = ++localityRequest;
    if (!province.value) {
      status.textContent = 'Escribí o elegí una provincia para buscar localidades.';
      return;
    }
    const query = locality.value.trim();
    if (query.length < 2) {
      status.textContent = 'Escribí al menos dos letras. Si tu localidad no aparece, podés ingresarla manualmente.';
      return;
    }

    const params = new URLSearchParams({ provincia: province.value, q: query });
    const response = await fetch(`/georef/localidades?${params}`, { headers: { Accept: 'application/json' } });
    if (!response.ok) throw new Error('No se pudieron buscar las localidades.');
    const payload = await response.json();
    if (requestId !== localityRequest) return;
    const seenLocalities = new Set();
    for (const item of payload.localidades) {
      const name = item.nombre.trim();
      const district = item.municipio?.nombre || item.departamento?.nombre || '';
      const normalizedName = name.toLocaleLowerCase('es-AR');
      const key = item.id || `${normalizedName}|${district.toLocaleLowerCase('es-AR')}`;
      if (seenLocalities.has(key)) continue;
      seenLocalities.add(key);
      const option = document.createElement('option');
      option.value = name;
      option.label = district && district.toLocaleLowerCase('es-AR') !== normalizedName
        ? `${name}, ${district}`
        : name;
      list.append(option);
    }
    status.textContent = list.options.length > 0
      ? 'Las sugerencias incluyen el municipio o departamento. Si no aparece tu localidad, podés escribirla.'
      : 'No encontramos sugerencias; podés escribir la localidad manualmente.';
  };

  province.addEventListener('change', () => {
    locality.value = '';
    list.replaceChildren();
    localityRequest += 1;
    status.textContent = 'Escribí tu localidad para buscarla o ingresala manualmente.';
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
    status.textContent = 'No se pudieron cargar las sugerencias. Podés escribir la provincia y la localidad manualmente.';
  });
})();
