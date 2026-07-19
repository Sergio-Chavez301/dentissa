<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña | Dentissa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-sm border p-8">
        <h1 class="text-2xl font-bold mb-2">Restablecer contraseña</h1>
        <p class="text-sm text-gray-600 mb-6">Elige una nueva contraseña para tu cuenta.</p>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                <input id="email" name="email" type="email" required class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Nueva contraseña</label>
                <input id="password" name="password" type="password" required class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
            </div>
            @error('email')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('password')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <button type="submit" class="w-full rounded-lg bg-dentissa px-4 py-2 text-sm font-medium text-white">Guardar contraseña</button>
        </form>
    </div>
</body>
</html>
