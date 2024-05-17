# Proyecto de Gestión de Biblioteca

Este es un proyecto demo para la gestión de una biblioteca, donde los usuarios pueden registrarse, iniciar sesión y realizar operaciones CRUD sobre los libros.

## Requisitos Previos

- XAMPP con PHP y MySQL
- Navegador web actualizado

## Configuración del entorno

1. **Instalación de XAMPP**: Asegúrate de tener XAMPP instalado. Si no lo tienes, puedes descargarlo desde [aquí](https://www.apachefriends.org/index.html).

2. **Clonar el repositorio**: Clona este repositorio en tu directorio `htdocs` de XAMPP, que generalmente se encuentra en `C:\xampp\htdocs`.

___
cd C:\xampp\htdocs
git clone url_del_repositorio login
___

3. **Estructura de Carpetas**: Asegúrate de que la estructura de las carpetas se mantiene como se indica a continuación:

___
C:.
├───assets
│   ├───css
│   ├───fonts
│   ├───images
│   │   └───icons
│   └───js
│       └───demo
├───funcs
├───libros
└───vendor
___

4. **Base de Datos**:
   
- Inicia el módulo Apache y MySQL en el panel de control de XAMPP.
- Accede a `phpMyAdmin` desde tu navegador en `http://localhost/phpmyadmin/`.
- Crea una nueva base de datos llamada `biblioteca` y ejecuta el SQL proporcionado para estructurar tu base de datos.

5. **Configuración de la Base de Datos**:
   
- Asegúrate de que el archivo de conexión en `funcs/conexion.php` esté configurado correctamente para conectar con MySQL.

## Ejecución del Proyecto

Abre un navegador y accede a `http://localhost/login/` para iniciar la aplicación. Sigue las instrucciones en pantalla para registrar un nuevo usuario o iniciar sesión si ya tienes una cuenta.

## Puntos a Mejorar

- Añadir filtros para evitar la inserción de libros duplicados.
- Implementar un sistema de autenticación más robusto, como JWT o OAuth.
- Organizar el código en un patrón MVC para mejorar la mantenibilidad y escalabilidad del proyecto.

## Tiempo de Desarrollo

Aproximadamente 4 horas han sido dedicadas al desarrollo de este proyecto.

Gracias por utilizar nuestro sistema de gestión de biblioteca.