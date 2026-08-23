# 🗄️ Repositorio Central de Proyectos (Base de Operaciones)

Este repositorio es nuestro espacio de trabajo interno. Aquí guardamos todos los proyectos base, plantillas y scripts que ya tenemos listos para clonar, adaptar y vender a nuestros clientes.

El objetivo es mantener todo ordenado para que cualquiera de los dos pueda encontrar el código rápido, hacer las modificaciones necesarias y entregar el proyecto sin empezar desde cero.

## 📁 Estructura del Repositorio

Los proyectos están divididos por categoría comercial. Cada carpeta principal contiene subcarpetas con los proyectos individuales.

### 🛍️ 1. `/Catalogos-y-Ecommerce`
Todo lo que sea para mostrar productos y vender.
* **Qué guardar aquí:** Tiendas online, catálogos interactivos, carritos de compras.
* **Stack principal:** PHP, HTML, CSS.
* **Ejemplos de lo que va aquí:** El catálogo digital estilo Golden Londons, con su estructura de grillas de imágenes y lista de precios.

### 🤖 2. `/Bots-y-Automatizacion`
Sistemas para ahorrarle tiempo al cliente.
* **Qué guardar aquí:** Scripts de auto-respuesta, bots de WhatsApp/Telegram, automatización de mensajería.
* **Stack principal:** Python.

### ⚙️ 3. `/Logica-y-Scripts`
Sistemas de backend y procesamiento.
* **Qué guardar aquí:** Scripts de Web Scraping, sincronización de bases de datos, algoritmos puros.
* **Stack principal:** Python, C, PHP.

### 🎨 4. `/Plantillas-Web`
Maquetación lista para producción.
* **Qué guardar aquí:** Landing pages, portafolios de agencias, currículums web interactivos.
* **Stack principal:** HTML, CSS.
* **Ejemplos de lo que va aquí:** La plantilla base del currículum de Mauricio Villamediana estructurada para imprimir en PDF.

### 🎮 5. `/Juegos-y-Assets`
Mecánicas y bases para vender a otros desarrolladores.
* **Qué guardar aquí:** Proyectos de plataformas 2D, sistemas de físicas, carpetas de assets gráficos organizados.

---

## 🛠️ Reglas para agregar un proyecto nuevo

Para no hacer un desastre en el repositorio y que ambos entendamos el código del otro, cada vez que subamos algo nuevo hay que cumplir esto:

1. **Carpeta aislada:** Crea una subcarpeta para el proyecto dentro de la categoría que toque (ej. `/Catalogos-y-Ecommerce/Catalogo-Ropa-V1`).
2. **Mini-README por proyecto:** Dentro de esa subcarpeta, deja un pequeño archivo de texto o markdown que diga rápido para qué sirve, qué hace falta para instalarlo y qué base de datos usa.
3. **Variables claras:** Deja comentadas (o en un archivo de configuración separado) las variables que siempre le vamos a cambiar al cliente, como colores principales, logos, rutas de imágenes o tokens de API.
4. **No subir basura:** Ignorar carpetas temporales, bases de datos llenas de pruebas locales o archivos pesados innecesarios usando el `.gitignore`.