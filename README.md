# Emprendi

Emprendi es un marketplace web para conectar compradores, vendedores y
proveedores. Una cuenta puede comprar y, al activar su perfil emprendedor,
publicar productos y administrar una o varias tiendas. El sitio funciona en
este repositorio desde la aplicación PHP de la raíz.

**Estado funcional documentado:** 02/11/2026. La revisión de historias que
respalda los estados fue realizada el 30/10/2026; las funciones marcadas como
implementadas no implican que cada caso haya sido validado en producción.

## Alcance del producto

- El marketplace activo está implementado con PHP, Slim y vistas renderizadas
  desde el servidor.
- `public/index.php` inicia la aplicación definida en `src/bootstrap.php`.
- `PDI/` contiene otro proyecto Next.js y `layered-architecture-main/` es una
  aplicación de referencia/plantilla. `contacts/` es un sitio estático
  independiente. No son módulos del marketplace activo.
- El backlog de 40 historias está en
  [`BACKLOG-US-EMPRENDI.txt`](./BACKLOG-US-EMPRENDI.txt). Si una historia
  propuesta coincide con una función existente o un pendiente de ese backlog,
  actualizar/vincular esa historia en lugar de duplicarla.

## Usuarios y modelo del negocio

### Actores

| Actor | Qué puede hacer |
|---|---|
| Visitante sin sesión | Recorrer y buscar en el catálogo, ver perfiles y productos públicos, registrarse o iniciar sesión. |
| Usuario con rol `visitante` | Comprar, guardar favoritos, seguir tiendas, conversar con vendedores y administrar sus datos personales. Puede activar el rol emprendedor desde «Mi puesto». |
| Usuario con rol `emprendedor` | Todo lo anterior, más administrar sus tiendas, productos, ventas recibidas y ofertas. Sigue siendo una cuenta de usuario; no existe una cuenta separada para vendedor. |
| Administrador | Acceder al panel con un PIN compartido configurado en `ADMIN_PIN`. No es un rol de usuario con identidad individual. Las acciones se registran como sesión/PIN compartido. |

El registro permite elegir entre `visitante` y `emprendedor`. La migración 021
convierte las cuentas antiguas con productos al rol `emprendedor` y las demás
al rol `visitante`. Cada cuenta tiene al menos una tienda; puede crear más de
una y asociar cada producto a una tienda concreta.

### Entidades principales

- **Usuario:** cuenta, credenciales, rol, estado y datos personales. Los datos
  privados no forman parte del perfil público.
- **Emprendimiento/tienda:** pertenece a un usuario; contiene nombre,
  descripción, ubicación, estado y personalización visual. Puede pausarse.
- **Producto:** pertenece a un usuario y a una tienda; tiene categoría,
  precio, stock, estado de moderación, imágenes y, opcionalmente, una oferta
  aprobada.
- **Compra y artículos:** una compra corresponde al comprador y se divide por
  vendedor. Los artículos conservan datos históricos del producto y de la
  tienda aunque estos cambien o se eliminen.
- **Conversación/mensaje:** intercambio entre comprador y vendedor; el acceso
  depende de ser participante.
- **Favoritos, seguidores, reseñas, reportes, notificaciones y auditoría:**
  funciones vinculadas a cuentas, productos o tiendas según corresponda.

## Funcionamiento actual

### Catálogo y tiendas

- El catálogo muestra únicamente productos aprobados y de tiendas activas.
  Incluye búsqueda, categorías, filtros, orden, detalle, favoritos y reseñas.
- «Más vendidos» cuenta unidades de pedidos creados en los últimos 90 días
  cuyo pago fue aprobado y cuyo pedido está entregado.
- Los perfiles públicos pertenecen a una tienda y muestran su nombre,
  ubicación, descripción y productos aprobados. No exponen correo, teléfono ni
  dirección privada del usuario.
- Una tienda puede mostrar logo, banner y dos colores. Se aceptan imágenes
  JPEG, PNG o WebP de hasta 5 MB; el logo debe medir al menos 100 × 100 px y
  el banner 640 × 180 px. Pausar la tienda oculta sus productos y evita nuevas
  compras; los pedidos guardan el nombre de tienda como referencia histórica.
- Se puede seguir una tienda y consultar la lista de tiendas seguidas.

### Publicación y moderación de productos

- Para publicar un producto se exige categoría, precio válido, descripción de
  al menos 30 caracteres y cuatro palabras, confirmación de que el producto
  es real y al menos una imagen JPEG, PNG o WebP.
- Se permiten hasta cinco imágenes de 5 MB cada una. Se validan el contenido y
  el tipo de archivo en el servidor. Configurar PHP para admitir
  `upload_max_filesize=5M`, `post_max_size=26M` y `max_file_uploads=5`.
- Hay un límite de cinco publicaciones por cuenta por hora y veinte por día;
  el mismo usuario no puede duplicar nombres de producto.
- Una publicación nueva o editada queda pendiente hasta que un administrador
  la apruebe. Si se rechaza, el vendedor recibe el motivo en «Mis productos».
  Los productos antiguos sin imágenes pueden requerir una foto y una nueva
  revisión.
- El vendedor puede proponer precio y fechas para una oferta. Solo se aplica
  después de la aprobación administrativa y durante el período aprobado. Las
  fechas se interpretan con `APP_TIMEZONE` y se almacenan en UTC.

### Compra, envío y pago

1. El comprador agrega al carrito productos aprobados y con stock disponible.
2. Al confirmar, el servidor valida y reserva el stock; la compra se separa
   por vendedor.
3. Cada vendedor debe cargar manualmente el costo real de envío antes de que
   el comprador pueda pagar. La cotización y el seguimiento se gestionan
   manualmente; no hay integración de tarifas, etiquetas ni seguimiento con
   Correo Argentino.
4. Checkout Pro de Mercado Pago está deshabilitado si falta alguna
   configuración requerida o `APP_URL` no usa HTTPS. No se simulan cobros.
   Cuando se habilita, los pagos son centralizados en la cuenta de Mercado
   Pago de Emprendi; la liquidación a vendedores no está automatizada.
5. El vendedor consulta «Ventas recibidas» y actualiza estados. Las reseñas de
   productos solo se habilitan para artículos cuyo vendedor marcó como
   entregados.

El panel también permite consultar compras por identificador con un motivo;
la consulta es de solo lectura y queda auditada.

### Cuenta, comunicación y administración

- El usuario puede editar su perfil y contraseña. Las contraseñas nuevas
  deben tener entre 8 y 72 bytes. Las contraseñas se guardan con hash.
- La recuperación de contraseña usa tokens aleatorios de un solo uso, con una
  hora de vigencia. No revela si un correo está registrado y solo está
  habilitada cuando SMTP y `APP_URL` están configurados correctamente.
- Las conversaciones persisten en base de datos y se actualizan cada cinco
  segundos. Solo comprador y vendedor participantes pueden responder.
  Para abrir una conversación desde administración se exige un motivo y se
  registra el acceso; el panel no muestra su contenido en el listado.
- Usuarios autenticados pueden reportar productos y perfiles comerciales.
  Administración puede revisar reportes, descartarlos, retirar publicaciones
  o bloquear cuentas indicando el motivo.
- El panel permite aprobar/rechazar productos y ofertas, gestionar cuentas,
  bloquear/desbloquear usuarios y eliminar cuentas. La eliminación anonimiza
  datos personales, retira productos del catálogo y conserva el historial de
  pedidos.
- `/admin/auditoria` registra acciones comerciales y administrativas y permite
  filtrar por fechas, actor, entidad y acción. Los usuarios se identifican
  como `usuario:ID`; el PIN compartido no identifica a una persona concreta.
- `/mi-rendimiento` muestra totales históricos. Solo cuenta pagos aprobados;
  considera entregados los pedidos con ese estado y el importe bruto excluye
  el costo de envío.
- `/ayuda` muestra `SUPPORT_EMAIL` solo si está configurado con una dirección
  válida. `/nosotros` presenta información institucional.

## Estado de las historias y trabajo pendiente

La revisión del backlog considera **implementadas** US-001, US-002, US-004 a
US-028 y US-030 a US-039. US-006 y US-016 se validaron en esa revisión.
«Implementada» describe el flujo identificado en el código y/o su validación
indicada en el backlog, no garantiza validación funcional completa en todos
los entornos.

| Historia | Estado actual | Qué falta / cómo tratarla |
|---|---|---|
| US-003 — Recuperación de contraseña | Parcial; el flujo y el envío SMTP configurable están implementados. | Validar envío y restablecimiento de extremo a extremo con credenciales SMTP reales. No afirmar que se envió un correo si SMTP no está configurado. |
| US-029 — Indicadores de reputación | Pendiente por definición de producto. | No inventar etiquetas, métricas ni umbrales; acordar criterios antes de crear historias de implementación. |
| US-040 — Permisos y propiedad de recursos | Parcial; existen sesiones, CSRF y controles de propiedad en varios flujos. | Completar la matriz y probar acceso cruzado en todas las rutas protegidas. Ocultar controles en la interfaz no sustituye autorización en servidor. |

La revisión registra estas historias como issues abiertas: US-003 (#24),
US-029 (#50) y US-040 (#61). Las demás US-001 a US-040 fueron cerradas como
implementadas. Consultar el backlog antes de cambiar estados o crear una
historia que se solape con ellas.

### Decisiones de producto vigentes

- Se permiten varias tiendas por cuenta; productos, perfiles y pedidos deben
  conservar el aislamiento y la asociación a tienda.
- Se mantiene el PIN administrativo compartido; no hay identidades
  administrativas individuales.
- Recuperación de contraseña depende de SMTP configurado por entorno; no se
  debe presentar como enviado un correo que no salió.
- El chat usa actualización periódica, no tiempo real.
- Las ofertas requieren aprobación administrativa.
- Las métricas se basan en pedidos pagados/entregados según la definición de
  cada vista; no inferir reputación a partir de ellas.
- El canal de soporte es configurable; no publicar un contacto ficticio.

## Guía para redactar nuevas historias de usuario con IA

Usar este README como contexto del producto y contrastar el alcance con el
código y el backlog antes de escribir historias.

1. Escribir historias para un actor y un resultado observable: «Como
   [actor], quiero [capacidad], para [valor]».
2. Describir el **cambio nuevo**, no volver a pedir una función ya
   implementada. Si una historia existente es parcial, especificar solo el
   trabajo pendiente y preservar lo que ya funciona.
3. Añadir criterios verificables que cubran el flujo normal y, cuando aplique,
   errores, permisos, propiedad del recurso, transiciones de estado,
   persistencia, límites y efectos sobre otras entidades.
4. Las reglas de autorización deben cumplirse en el servidor. Incluir pruebas
   de acceso no autorizado y de acceso cruzado cuando haya recursos de otros
   usuarios.
5. Marcar explícitamente dependencias externas (SMTP, Mercado Pago, HTTPS,
   configuración del entorno) y separar implementación de validación externa.
   Nunca describir una integración manual como automática.
6. No inventar decisiones de producto. En particular, los criterios de
   reputación de US-029 aún no están definidos.
7. Indicar si la historia es nueva, una continuación de US-003/US-040 o una
   corrección; enlazar/actualizar la issue existente en lugar de duplicarla.

## Arquitectura y archivos relevantes

| Ruta | Responsabilidad |
|---|---|
| `public/index.php` | Punto de entrada HTTP. |
| `src/bootstrap.php` | Configura Slim, carga utilidades y registra las rutas activas. |
| `src/views/` | Plantillas PHP de páginas, layouts y secciones del sitio. |
| `src/utils/` | Funciones compartidas de autenticación, HTML, pagos, productos y personalización de tiendas. |
| `src/database/database.php` | Conexión PDO. |
| `src/database/migrations/` | Migraciones SQL; la más reciente es `021_roles_visitante_emprendedor.mysql.sql`. |
| `scripts/migrate.php` | Aplica migraciones `.sql` pendientes y registra cuáles se ejecutaron. |
| `public/uploads/productos/` | Imágenes de productos cargadas por usuarios. |
| `public/uploads/emprendimientos/` | Logos y banners de tiendas cargados por usuarios. |

Las rutas activas están centralizadas en `src/bootstrap.php`. Las familias
principales son: `/productos`, `/emprendimientos`, `/vendedores`, `/carrito`,
`/compras`, `/ventas`, `/conversaciones`, `/notificaciones`, `/perfil`,
`/favoritos`, `/mi-rendimiento`, `/ayuda`, `/nosotros` y `/admin`.
Las operaciones que modifican datos usan formularios POST y protección CSRF
en los flujos implementados.

La conexión PDO admite los drivers `mysql` y `pgsql`, pero las migraciones
incluidas usan SQL de MySQL; MySQL/MariaDB es el entorno documentado para
instalación. No asumir que cambiar `DB_DRIVER` alcanza para migrar a
PostgreSQL.

## Instalación local

Requisitos: PHP 8.1 o superior, Composer, PDO con el driver de MySQL y una base
MySQL/MariaDB.

1. Copiar `.env.example` a `.env`, crear la base de datos configurada y ajustar
   sus credenciales. Reemplazar `ADMIN_PIN` por un valor aleatorio y privado.
2. Instalar dependencias y aplicar migraciones:

   ```bash
   composer install
   composer run-script migrate
   ```

3. Iniciar el servidor local:

   ```bash
   composer run-script serve
   ```

4. Abrir `http://localhost:8000`. Detener el servidor con `Ctrl+C`.

El migrador aplica en orden los archivos pendientes de
`src/database/migrations/` y registra cada archivo aplicado en
`schema_migrations`. Aplicar las migraciones en cada entorno antes de usar
funciones que dependan de cambios recientes.

### Variables de entorno

| Variable | Uso |
|---|---|
| `DB_DRIVER`, `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS` | Conexión a la base; el ejemplo usa MySQL. |
| `APP_TIMEZONE` | Zona horaria de la aplicación; por defecto `America/Argentina/Buenos_Aires`. |
| `ADMIN_PIN` | PIN compartido para el panel administrativo. |
| `APP_URL` | URL pública HTTPS necesaria para habilitar pagos y recuperación de contraseña. |
| `MP_ACCESS_TOKEN`, `MP_WEBHOOK_SECRET` | Credenciales privadas de Checkout Pro y su webhook; mantener vacías para dejar pagos deshabilitados. |
| `SMTP_HOST`, `SMTP_PORT`, `SMTP_USERNAME`, `SMTP_PASSWORD`, `SMTP_ENCRYPTION`, `SMTP_FROM_EMAIL`, `SMTP_FROM_NAME` | Envío de recuperación de contraseña; `SMTP_ENCRYPTION` acepta `tls` o `ssl`. |
| `SUPPORT_EMAIL` | Dirección de soporte opcional que aparece en `/ayuda` solo si es válida. |

Configurar en Mercado Pago el webhook hacia
`https://tu-dominio/integraciones/mercadopago/webhook`. No subir `.env`, tokens,
contraseñas ni secretos al repositorio.

## Licencia

Este proyecto está licenciado bajo la [Licencia MIT](./LICENSE).
