<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dentissa | Registrarse</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-sm border p-8">
        <h1 class="text-2xl font-bold mb-2">Registrarse</h1>
        <p class="text-sm text-gray-600 mb-6">La inscripción estará disponible cuando el flujo completo de usuarios esté habilitado.</p>
        <a href="{{ route('login') }}" class="text-dentissa font-medium">Ya tengo una cuenta</a><br>
        <a href="{{ route('home') }}" class="text-sm text-gray-600 mt-2 inline-block">Volver al inicio</a>
    </div>
</body>
</html>