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
        <p class="text-sm text-gray-600 mb-6">Para recuperar el acceso a tu cuenta, contacta directamente a la doctora por WhatsApp.</p>

        @if($officeSettings->whatsapp_digits)
            @php
                $supportMessage = rawurlencode('Hola, necesito ayuda para recuperar el acceso a mi cuenta de Dentissa.');
            @endphp
            <a href="https://wa.me/{{ $officeSettings->whatsapp_digits }}?text={{ $supportMessage }}"
               class="block w-full rounded-lg bg-dentissa hover:bg-[#c0466b] transition-colors px-4 py-3 text-center text-sm font-medium text-white shadow-sm">
                Contactar por WhatsApp
            </a>
        @else
            <p class="rounded-lg bg-amber-50 p-3 text-sm text-amber-800">El WhatsApp del consultorio aún no está configurado. Contacta directamente a la clínica.</p>
        @endif

        <a href="{{ route('login', request()->boolean('mobile') ? ['mobile' => 1] : []) }}" class="mt-4 inline-block text-sm text-dentissa hover:underline">Volver al inicio de sesión</a>
    </div>
</body>
</html>