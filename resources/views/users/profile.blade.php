<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | Dentissa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 px-4 py-10">
    <div class="mx-auto max-w-2xl rounded-2xl bg-white p-8 shadow-sm border">
        <h1 class="text-2xl font-bold mb-2">Mi perfil</h1>
        <p class="text-sm text-gray-600 mb-6">Actualiza tus datos personales y tu contraseña si lo deseas.</p>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Apellidos</label>
                    <input name="apellidos" value="{{ old('apellidos', $usuario->apellidos) }}" required class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Usuario</label>
                <input name="username" value="{{ old('username', $usuario->username) }}" required class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                <input name="email" type="email" value="{{ old('email', $usuario->email) }}" required class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nueva contraseña</label>
                    <input name="password" type="password" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                    <input name="password_confirmation" type="password" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
                </div>
            </div>
            @error('nombre')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            @error('apellidos')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            @error('username')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            @error('email')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            @error('password')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            <button type="submit" class="w-full rounded-lg bg-dentissa px-4 py-2 text-sm font-medium text-white">Guardar cambios</button>
        </form>
    </div>
</body>
</html>
