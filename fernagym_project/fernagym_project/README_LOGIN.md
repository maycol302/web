# Sistema de Login y Gestión de Membresías - Fernagym

## 📋 Descripción
Sistema completo de autenticación de usuarios con gestión de membresías que permite:
- Registro e inicio de sesión de usuarios
- Visualización detallada de membresías (fecha de pago, tipo de plan, días restantes)
- Gestión administrativa de usuarios y pagos
- Seguimiento de estado de membresías

## 🚀 Instalación

### 1. Configuración de la Base de Datos
1. Ejecuta el script SQL: `sql/update_database.sql`
2. O visita: `setup.php` para inicializar automáticamente

### 2. Estructura de Tablas
- **usuarios**: Información de usuarios registrados
- **planes**: Tipos de membresías disponibles
- **membresias**: Membresías activas de usuarios
- **pagos**: Registro de pagos realizados
- **sesiones**: Control de sesiones activas

## 🔐 Credenciales de Prueba
- **Admin**: admin@fernagym.cl / password
- **Usuario**: Regístrate con cualquier email válido

## 📁 Archivos del Sistema

### Archivos Principales
- `login.php` - Inicio de sesión
- `registro.php` - Registro de nuevos usuarios
- `dashboard.php` - Panel de usuario con detalles de membresía
- `admin.php` - Panel administrativo
- `logout.php` - Cierre de sesión

### Archivos de Soporte
- `aprobar-pago.php` - Aprobación de pagos pendientes
- `setup.php` - Inicialización de base de datos
- `conexion.php` - Configuración de conexión PDO

## 📊 Funcionalidades

### Para Usuarios
1. **Registro**: Crear cuenta con datos personales
2. **Login**: Acceso seguro con validación de credenciales
3. **Dashboard**: 
   - Información personal
   - Estado actual de membresía
   - Días restantes hasta vencimiento
   - Historial de pagos
   - Progreso visual de la membresía

### Para Administradores
1. **Gestión de Usuarios**: Ver todos los usuarios registrados
2. **Gestión de Pagos**: Aprobar pagos pendientes
3. **Estadísticas**: Total de usuarios, membresías activas y pagos pendientes
4. **Control de Membresías**: Ver estado de todas las membresías

## 🎯 Flujo de Uso

### Usuario Nuevo
1. Registrarse en `registro.php`
2. Iniciar sesión en `login.php`
3. Ir a `dashboard.php` para ver información
4. Inscribirse en un plan desde el formulario de inscripción
5. Subir comprobante de pago
6. Esperar aprobación del administrador

### Administrador
1. Iniciar sesión con cuenta admin
2. Ir a `admin.php`
3. Ver pagos pendientes
4. Aprobar pagos y activar membresías
5. Monitorear usuarios y membresías

## 📅 Información de Membresía Mostrada

### Dashboard Usuario
- **Plan actual**: Nombre del plan activo
- **Fecha de pago**: Cuando se realizó el pago
- **Tipo de membresía**: Básico, Premium, VIP, etc.
- **Días restantes**: Cálculo automático hasta vencimiento
- **Estado**: Activa, Pendiente, Vencida
- **Progreso visual**: Barra de progreso de la membresía

### Historial Completo
- Todos los pagos realizados
- Fechas de inicio y fin de cada membresía
- Montos pagados
- Estados de cada transacción

## 🔧 Configuración Técnica

### Requisitos
- PHP 7.4+
- MySQL 5.7+
- Extensiones PHP: PDO, PDO_MySQL

### Seguridad
- Hashing de contraseñas con password_hash()
- Validación de RUT chileno
- Sanitización de entradas
- Protección contra SQL injection con PDO prepared statements
- Validación de archivos de comprobante

## 📞 Soporte
Para problemas o consultas, contactar a: contacto@fernagym.cl
