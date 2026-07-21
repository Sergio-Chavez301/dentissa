<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña | Dentissa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dentissa: '#d75078', // Color rosa corporativo
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-sm border p-8">
        <h1 class="text-2xl font-bold mb-2">Recuperar contraseña</h1>
        <p class="text-sm text-gray-600 mb-6">Ingresa tu correo y te enviaremos un enlace para restablecerla.</p>

        @if (session('status'))
            <div class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                <input id="email" name="email" type="email" required value="{{ old('email') }}" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-dentissa">
            </div>
            @error('email')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <button type="submit" class="w-full rounded-lg bg-dentissa hover:bg-[#c0466b] transition-colors px-4 py-2 text-sm font-medium text-white shadow-sm">Enviar enlace</button>
        </form>

        <a href="{{ route('login') }}" class="mt-4 inline-block text-sm text-dentissa hover:underline">Volver al inicio de sesión</a>
    </div>
</body>
</html>