<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso denegado | Dentissa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; display: grid; place-items: center; padding: 24px; color: #34313a; background: #f7f5f6; }
        .access-card { width: min(100%, 520px); padding: 40px; border: 1px solid #eee8eb; border-radius: 18px; background: #fff; box-shadow: 0 16px 48px rgb(52 31 39 / 8%); text-align: center; }
        .access-icon { width: 64px; height: 64px; display: grid; place-items: center; margin: 0 auto 20px; border-radius: 50%; color: #a51d4b; background: #fff0f4; font-size: 30px; }
        .access-card h1 { margin-bottom: 12px; font-size: 25px; }
        .access-card p { margin-bottom: 24px; color: #6d6870; line-height: 1.6; }
        .btn-dentissa { color: #fff; background: #d75078; border-color: #d75078; }
        .btn-dentissa:hover { color: #fff; background: #b83d60; border-color: #b83d60; }
    </style>
</head>
<body>
    <main class="access-card">
        <div class="access-icon" aria-hidden="true"><i class="bi bi-shield-lock"></i></div>
        <h1>Acceso denegado</h1>
        <p>Esta sección no está disponible para tu rol. Puedes volver al panel que te corresponde.</p>
        <a class="btn btn-dentissa px-4 py-2" href="{{ $dashboardRoute }}">
            <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>Volver a mi panel
        </a>
    </main>
</body>
</html>
