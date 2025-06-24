# Como iniciar el programa?

### Paso 1
Crear una base de datos con el nombre sistemainventario.

### Paso 2
Nos ubicamos en la ruta del proyecto (donde veamos las carpetas app, bootstrap, config, database, etc).

### Paso 3
Ejecutamos el siguiente comando en la terminal:
```
composer install
```

### Paso 4
Copiamos el contenido del archivo **.env.example** y lo pegamos en el archivo **.env**

### Paso 5
Ahora ejecutaremos el siguiente comando para subir las migraciones y seeders (tablas de la base de datos y datos de base):
```
php artisan migrate:fresh --seed
```

### Paso 6
Ejecutar el comando ```php artisan serve``` Para iniciar el proyecto.


# Importante
Al realizar todos estos pasos, se creará automaticamente un usuario con rol de administrador, con este usuario debemos crear nuestros usuarios que utilizaremos en el programa, configurando los permisos de cada usuario y creando roles especificos.
#### Datos del usuario admin:
**Correo:** admin@gmail.com

**Contraseña:** 123
