# Overshine Contacts

**Descripción:**
Página donde emprendedores y distribuidores puedan empezar o expandir su negocio, crear su propia página o publicación de forma sencilla, contactar con proveedores y vender sus productos.

Integrantes:

- {Tules, Roman} | [@Tules-222](https://github.com/Tules-222)
- {Pogonza, David} | [@PogonzaDavid](https://github.com/PogonzaDavid)
- {Alcaraz, Eluney} | [@eluneyalcaraz-esc](https://github.com/eluneyalcaraz-esc)

Proyecto institucional **E.E.S.T Nº4 de Berazategui**.

## Elevator's Pitch

- Para pequeños o grandes comerciantes, proveedores y empresas.
- Quienes precisan materia prima para sus productos o vender la que poseen.
- El Overshine Contacts es una página informativa de comercio.
- Que permite desarrollar tus ideas de negocio con facilidad.
- Diferente a MercadoLibre y Shoppify.
- Nuestro proyecto permite encontrar información, materiales y proveedores, y obtenerlos mediante este portal de compras.

## Requisitos

- PHP 8.1 o superior.
- Extensión PDO habilitada.
- MySQL o PostgreSQL según la configuración en el archivo `.env`.

## Migraciones

Colocá tus archivos `.sql` en `src/database/migrations/` y ejecuta:

```bash
composer migrate
```

## Cuentas y moderación

El registro público siempre crea cuentas con rol `usuario`; no permite elegir permisos ni asignarse como administrador. Las contraseñas se guardan cifradas. Para habilitar una cuenta administradora, primero registrala normalmente y luego, desde una sesión administrativa de la base de datos, cambiá su rol usando su correo:

```sql
UPDATE usuarios SET rol = 'admin' WHERE gmail = 'admin@tu-dominio.com';
```

Las publicaciones nuevas aparecen como `aprobado` en el catálogo. Un administrador puede revisar productos desde `/admin/productos` y rechazarlos u ocultarlos si hace falta. La portada muestra todos los productos aprobados mientras haya hasta 12; al superar ese número destaca los 12 con más búsquedas y pedidos aceptados.

Cada producto debe pertenecer a una cuenta. La base de datos impide guardar productos sin dueño y elimina en cascada los productos cuando se elimina su cuenta. Los pedidos conservan el nombre del producto, precio y vendedor como historial aunque el producto o la cuenta del vendedor se eliminen.

Desde el detalle, una persona autenticada puede solicitar una compra indicando cantidad. La solicitud queda pendiente hasta que el vendedor la acepte o rechace desde `/mis-ventas`; comprador y vendedor pueden consultar sus historiales en `/mis-compras` y `/mis-ventas`. El panel `/admin/pedidos` conserva snapshots de comprador, propietario/vendedor y producto incluso tras borrar una cuenta. Este flujo registra solicitudes, pero no procesa pagos ni envíos.

La revisión humana ayuda a moderar el contenido, pero no sustituye controles técnicos: se deben validar datos, escapar la salida HTML y, si se habilitan imágenes o archivos, validar tipo y contenido, limitar tamaño y almacenarlos fuera de una ruta ejecutable.

## Licencia

Este proyecto está licenciado bajo la [Licencia MIT](./LICENSE).

---

Generado por la [plantilla](https://github.com/PDI-EEST4/slim-template-2026), a discreción de los integrantes del proyecto.
