<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Dentissa | Inicio</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    </head>
    <body>  
        <div class="container">
            <h1>Bienvenido a Dentissa</h1>
            <p>Esta es la página de inicio de la aplicación Dentissa.</p>
            <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesión</a><br>
            <a href="{{ route('register') }}" class="btn btn-secondary">Registrarse</a>
        </div>
    </body>
</html>
    