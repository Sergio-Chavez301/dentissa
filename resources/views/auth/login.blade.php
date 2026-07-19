<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Melissa López N.</title>
    <!-- Tailwind CSS para un desarrollo rápido y limpio -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Estilos personalizados para combinar con el branding */
        .text-dentissa { color: #d75078; }
        .bg-dentissa { background-color: #d75078; }
        .bg-dentissa:hover { background-color: #c24066; }
        .border-dentissa:focus { border-color: #d75078; --tw-ring-color: #d75078; }
    </style>
</head>
<body class="h-full flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-sm border border-gray-150">
        
        <!-- Contenedor superior con icono de retorno -->
        <div class="relative flex items-center justify-center mb-6">
            <!-- Enlace para regresar al Home -->
            <a href="/" class="absolute left-0 text-gray-400 hover:text-dentissa transition-colors" title="Regresar al inicio">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>

            <!-- Identidad Corporativa -->
            <div class="text-center flex flex-col items-center">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Melissa López" class="mb-2" style="max-height: 75px;">
                <h2 class="text-2xl font-bold tracking-tight text-[#1e1e24] mb-0">
                    Melissa López N.
                </h2>
                <span class="text-xs uppercase font-semibold tracking-widest mt-1 text-dentissa">
                    Odontología Integral
                </span>
            </div>
        </div>

        <!-- Formulario de Entrada -->
        <form class="mt-8 space-y-6" action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <!-- Campo: Nombre de Usuario -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">
                        Nombre de Usuario
                    </label>
                    <div class="mt-1">
                        <input id="username" 
                               name="username" 
                               type="text" 
                               required 
                               value="{{ old('username') }}" 
                               placeholder="Ej. sergiochavez"
                               class="appearance-none block w-full px-3 py-2 border {{ $errors->has('username') ? 'border-red-300 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 placeholder-gray-400 focus:ring-[#d75078] focus:border-[#d75078]' }} rounded-lg shadow-sm focus:outline-none sm:text-sm">
                    </div>
                    @error('username')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo: Contraseña -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Contraseña
                    </label>
                    <div class="mt-1">
                        <input id="password" 
                               name="password" 
                               type="password" 
                               required 
                               placeholder="••••••••"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#d75078] focus:border-[#d75078] sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Recordar dispositivo -->
            <div class="flex items-center">
                <input id="remember" 
                       name="remember" 
                       type="checkbox" 
                       class="h-4 w-4 text-dentissa focus:ring-dentissa border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-900">
                    Recordarme en este equipo
                </label>
            </div>

            <!-- Botón de Ingreso -->
            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-dentissa transition duration-150">
                    Ingresar al Sistema
                </button>
            </div>

            @if (session('status'))
                <div class="rounded-lg bg-green-50 p-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="text-sm text-center">
                <a href="{{ route('password.request') }}" class="font-medium text-dentissa hover:text-[#c24066]">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>
        </form>
        
    </div>
</body>
</html>