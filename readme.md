FINAL PROGRAMACION 3
CARRILLO FERNANDO JOSÉ

Este proyecto es un sistema web de administración de usuarios. El objetivo principal es permitir a los usuarios registrarse, iniciar sesión, editar sus perfiles y que los administradores puedan gestionar usuarios y visualizar logs (auditoría). También se incorporaron herramientas como RECAPTCHA, envios de correos con PHPMailer y un sistema de logs.

## Herramientas Utilizadas
Lenguajes y tecnologías:

PHP (POO)

HTML5, CSS3, JavaScript

MySQL

Librerías y servicios:

PHPMailer (para enviar correos de verificación y recuperación)

Google reCAPTCHA v2

Servidor local: XAMPP

Control de versiones: Git (GitHub)


## clonar proyecto en tu repositorio en carpeta htdocts ya que esta modo localhost


## La BD esta aqui en el proyecto listo para importar al mysql

    *bd_usuarios 

## funcionalidades que se desarrollaron

Inicio de sesión con validaciones y protección contra usuarios no verificados o suspendidos.

Registro de nuevo usario 

Recuperación de contraseña por email.

Restablecimiento de la contraseña

Edición del perfil de usuario.

Visualización de perfil (tipo ficha de usuario).

Administración de usuarios (listar, editar, eliminar - solo admins).

## Registro y visualización de logs del sistema

se implemtaron algunas acciones, en si habia muchas para ahcer pero me enfoque en lo justo y necesario. 

    Login exitoso / fallido

    Registro de usuario

    Edición de perfil

    Eliminación de usuario

    Visualización de la página de logs

## Quiero destacar que al ultimo empeze a utilizar alertas para mostrar sms    

## como punto opcional se implemento verificacion en timpo real del correo utilizando js