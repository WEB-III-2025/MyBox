# MyBox - Sistema de Almacenamiento y Compartición de Archivos  
**Universidad Técnica Nacional - ITI-621 Tecnologías y Sistemas Web III**  
**Profesor:** Jorge Ruiz (york)
**Estudiantes:** 
- Ahian Danier Quesada Guadamuz
- Jose Pablo Luna Vargas
- Douglas David Guevara Vargas
- Sofia Nazareth Gutierrez Flores

**Fecha de entrega:** 31 de octubre de 2025  
**Puntos totales:** 80 / 80

---

## Descripción del Proyecto

**MyBox** es un sistema web de almacenamiento tipo *DropBox* desarrollado en **PHP nativo** con **MySQL**, que permite a los usuarios:

- Crear, navegar y borrar carpetas
- Subir, descargar y borrar archivos
- Compartir archivos y carpetas con otros usuarios
- Visualizar contenido compartido de forma recursiva
- Restricción de subida a 20 MB
- Acceso restringido por IP
- Interfaz mejorada con iconos y colores

> **Nota importante:** Este sistema **no usa frameworks**, solo PHP puro, HTML, CSS y MySQL.

---

## Requisitos del Sistema

| Requisito | Detalle |
|---------|--------|
| Servidor web | XAMPP (Apache + MySQL) |
| PHP | ≥ 7.4 |
| MySQL | ≥ 5.7 |
| Sistema operativo | Windows (rutas en `C:\` o disco que posees) |
| Navegador | Chrome / Firefox |

---

## Instalación y Configuración

### 1. Clonar el repositorio
git clone https://github.com/WEB-III-2025/MyBox.git
### 2. Configurar XAMPP
- Instalar XAMPP desde [https://www.apachefriends.org/es/download.html].
- Iniciar los módulos de Apache y MySQL desde el panel de control de XAMPP.
- Colocar la carpeta del proyecto en `C:\xampp\htdocs\`.
### 3. Configurar la base de datos
 - Ejecutar el script SQL `mybox.sql` en MySQL para crear la base de datos y las tablas necesarias.
    - Configurar las credenciales de la base de datos en `conexion.inc`.
### 4. Configurar el archivo `.htaccess`
 - Asegurarse de que el archivo `.htaccess` en la raíz del proyecto contiene las reglas necesarias para la restricción de acceso por IP.
### 5. Crear el directorio de almacenamiento
    - Crear la carpeta `C:\\myboxusers\\` para almacenar los archivos subidos.
### 6. Acceder al sistema
 - Abrir el navegador y navegar a `http://localhost/MyBox/`.
 - Iniciar sesión con las credenciales proporcionadas o registrarse si es necesario.
## Uso del Sistema
- Navegar por las carpetas, subir y descargar archivos.
- Compartir archivos y carpetas con otros usuarios.
- Visualizar archivos y carpetas compartidas en la sección correspondiente.
- Borrar archivos y carpetas según sea necesario.
## Video Demostrativo
- El video está adjunto en el repositorio como `MyBox_Demo.mp4`.