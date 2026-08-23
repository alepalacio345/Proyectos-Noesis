# 💍 Catálogo Base - Joyería (Estilo Golden Londons)

**Categoría:** `/Catalogos-y-Ecommerce`
**Estado:** Funcional / En Desarrollo (Falta integrar lógica de JS)

## 📝 Descripción
Plantilla base en HTML y CSS para un catálogo digital de pulseras y collares. Ideal para adaptar y vender a tiendas de Instagram o marcas que necesiten ordenar sus pedidos. Actualmente está maquetado para organizar la grilla visual de los productos (basado en la estructura de los 26 assets de imagen) junto con la información básica de precios.

## 🛠️ Stack Tecnológico
* **Estructura y Diseño:** HTML5, CSS3.
* **Lógica (Por implementar):** JavaScript (necesario para capturar los datos del producto y armar la URL hacia WhatsApp).

## 🚀 Cómo usar para un cliente nuevo
1. **Assets:** Reemplazar las fotos en la carpeta `/assets/images/`. Mantener la misma proporción en todas las fotos para no romper la grilla.
2. **Inventario:** Actualizar el HTML con los nombres reales de las piezas y los precios.
3. **Configuración de número:** Cambiar la variable del número de teléfono en el código para que apunte al WhatsApp del cliente.

---

## 🚧 Cosas a Mejorar (To-Do List)
*Estas son las funcionalidades pendientes que tenemos que programar para terminar la plantilla:*

- [ ] **Modal de Venta (Pop-up):** Programar que al hacer clic en la imagen de una pulsera o collar, se abra una ventana modal centrada que muestre la foto más grande, la descripción detallada y el botón de "Comprar por WhatsApp".
- [ ] **Sección de Contactos:** Añadir un footer o una sección fija en la página con los contactos directos para hablar por WhatsApp con atención al cliente (separado de la compra de piezas).
- [ ] **Mensaje Dinámico de WhatsApp (¡Prioridad!):** Modificar el evento del botón de compra con JavaScript para que lea el nombre exacto de la pieza seleccionada y genere un mensaje pre-llenado.
  * *La lógica debe generar un enlace así:* 
    `https://wa.me/580000000000?text=Hola,%20me%20interesa%20comprar%20el%20Collar%20de%20Perlas%20Ref:%20001`

---
**Nota para nosotros:** Cuando completemos el To-Do con JavaScript, empaquetamos esto como nuestro producto estrella para tiendas minoristas.