# 🛒 Sistema de Facturación, POS e Inventario Bimonetario (Venezuela)

Sistema web integral de facturación, punto de venta (POS) y gestión de inventario, diseñado con arquitectura moderna para el comercio minorista y bodegas, con adaptación nativa a la economía multimoneda (USD / VES) de Venezuela.

---

## 🌟 Características Principales

### 🇻🇪 Adaptación Multimoneda (Bimonetario)
- **Precios Base en USD:** Protección financiera contra la inflación manteniendo costos y precios base en divisas.
- **Tasa BCV Dinámica:** Conversión en tiempo real a Bolívares (VES) según la tasa oficial o personalizada, con historial de cambios de tasa.
- **Métodos de Pago Nacionales:** Soporte para pagos combinados (Efectivo USD, Efectivo Bs, Pago Móvil con validación de referencia, Punto de Venta / Débito, Zelle, Transferencias).
- **Cuentas Bancarias Receptoras:** Registro y administración de cuentas de la empresa para conciliar pagos móviles y transferencias.

### 📦 Gestión de Inventario Avanzada
- **Control de Lotes y Vencimiento (FEFO):** Algoritmo *First Expired, First Out* que despacha automáticamente los productos con fecha de vencimiento más próxima.
- **Kardex Integral:** Historial detallado de movimientos de inventario por producto (compras, ventas, ajustes).
- **Ajustes de Inventario:** Submódulo para registrar mermas (daños, pérdidas) y devoluciones con reposición de lotes y motivos auditables.
- **Categorías y Catálogo:** Organización jerárquica de productos con control de stock mínimo y alertas.

### 🏪 Punto de Venta (POS)
- Carrito de compras reactivo en tiempo real con cálculo instantáneo de totales en USD y Bs.
- Modal de cobro inteligente con desglose de métodos de pago y cálculo de vuelto.

---

## 🛠️ Stack Tecnológico

- **Backend:** [Laravel 11](https://laravel.com/) (PHP 8.2+)
- **Reactividad / Frontend:** [Livewire 3](https://livewire.laravel.com/) + [Volt](https://livewire.laravel.com/docs/volt) + [Alpine.js](https://alpinejs.dev/)
- **Estilos:** [Tailwind CSS](https://tailwindcss.com/)
- **Empaquetador de Assets:** [Vite](https://vitejs.dev/)
- **Base de Datos:** [MySQL](https://www.mysql.com/) / MariaDB

---

## 🚀 Requisitos de Instalación

1. **PHP:** >= 8.2 (con extensiones pdo_mysql, mbstring, openssl, cmath, curl)
2. **Composer:** >= 2.x
3. **Node.js:** >= 18.x y npm
4. **Servidor MySQL:** (ej. XAMPP, Laragon o MySQL Server)

---

## ⚙️ Guía de Puesta en Marcha

1. **Clonar o ubicarse en el directorio del proyecto:**
   `ash
   cd Portafolio-Proyectos/Sistema-Facturacion-Inventario
   `

2. **Instalar dependencias de PHP:**
   `ash
   composer install
   `

3. **Instalar dependencias de JavaScript:**
   `ash
   npm install
   `

4. **Configurar el entorno:**
   `ash
   cp .env.example .env
   php artisan key:generate
   `

5. **Configurar la base de datos:**
   - Crear una base de datos vacía en MySQL llamada acturacion_e_inventario.
   - Ajustar las credenciales en tu .env si tu MySQL tiene contraseña.

6. **Ejecutar migraciones y datos iniciales:**
   `ash
   php artisan migrate --seed
   `

7. **Compilar assets del frontend:**
   `ash
   npm run dev
   # O para producción: npm run build
   `

8. **Iniciar el servidor local:**
   `ash
   php artisan serve
   `
   Acceder en el navegador a: http://127.0.0.1:8000

---

## 👥 Desarrolladores
- **Mauricio Villamediana**
- En colaboración con el equipo de **Noesis**.