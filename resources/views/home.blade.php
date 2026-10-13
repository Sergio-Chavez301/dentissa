<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Odontología integral con atención cercana. Solicita una cita con Melissa López N.">
    <title>Melissa López N. | Odontología Integral</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light;
            --ink: #183a37;
            --ink-soft: #46615d;
            --paper: #f7f8f5;
            --white: #fff;
            --line: #dbe4df;
            --rose: #d84170;
            --rose-dark: #b82c59;
            --lime: #d7e56b;
            --serif: "DM Serif Display", Georgia, serif;
            --sans: "DM Sans", sans-serif;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; color: var(--ink); background: var(--paper); font-family: var(--sans); }
        a { color: inherit; }
        button, input, select, textarea { font: inherit; }
        button, a { -webkit-tap-highlight-color: transparent; }
        .wrap { width: min(1180px, calc(100% - 48px)); margin-inline: auto; }
        .topline { color: #e4efea; background: var(--ink); font-size: 12px; }
        .topline-inner { min-height: 34px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .topline p { margin: 0; }
        .nav { position: sticky; top: 0; z-index: 5; border-bottom: 1px solid var(--line); background: #f7f8f5f5; backdrop-filter: blur(12px); }
        .nav-inner { min-height: 76px; display: flex; align-items: center; justify-content: space-between; gap: 28px; }
        .brand { display: inline-flex; align-items: center; }
        .brand img { display: block; width: auto; max-width: 178px; height: 60px; object-fit: contain; }
        .nav-links { display: flex; align-items: center; gap: 30px; font-size: 14px; font-weight: 600; }
        .nav-links a { text-decoration: none; }
        .nav-links a:not(.button):hover { color: var(--rose); }
        .button { min-height: 48px; display: inline-flex; align-items: center; justify-content: center; gap: 10px; padding: 0 20px; border: 1px solid transparent; border-radius: 3px; color: var(--white); background: var(--rose); font-weight: 700; text-decoration: none; cursor: pointer; transition: background .2s ease, transform .2s ease; }
        .button:hover { color: var(--white); background: var(--rose-dark); transform: translateY(-2px); }
        .button-outline { color: var(--ink); background: transparent; border-color: var(--ink); }
        .button-outline:hover { color: var(--white); background: var(--ink); }
        .button:focus-visible, a:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible { outline: 3px solid var(--lime); outline-offset: 3px; }
        .hero { padding: 30px 0 48px; overflow: hidden; }
        .hero-grid { display: grid; grid-template-columns: 1fr .88fr; align-items: center; gap: 70px; }
        .eyebrow { display: inline-flex; align-items: center; gap: 10px; color: var(--rose-dark); font-size: 12px; font-weight: 700; text-transform: uppercase; }
        .eyebrow::before { content: ""; width: 26px; height: 2px; background: currentColor; }
        h1, h2, h3, p { margin-top: 0; }
        h1 { max-width: 640px; margin: 18px 0 18px; font: 60px/1.04 var(--serif); letter-spacing: 0; }
        .hero-copy { max-width: 530px; color: var(--ink-soft); font-size: 18px; line-height: 1.7; }
        .hero-actions { display: flex; align-items: center; flex-wrap: wrap; gap: 12px; margin-top: 30px; }
        .hero-note { display: flex; align-items: center; gap: 12px; margin-top: 26px; padding-top: 16px; border-top: 1px solid var(--line); color: var(--ink-soft); font-size: 13px; }
        .note-mark { width: 34px; height: 34px; display: grid; place-items: center; flex: 0 0 auto; border-radius: 50%; color: var(--ink); background: var(--lime); font-weight: 700; }
        .hero-visual { position: relative; min-height: 410px; }
        .hero-photo { position: absolute; inset: 0 20px 28px 0; width: calc(100% - 20px); height: calc(100% - 28px); object-fit: cover; object-position: center 30%; border-radius: 3px; }
        .visual-caption { position: absolute; right: 0; bottom: 0; width: min(260px, 76%); padding: 18px 20px; color: var(--ink); background: var(--lime); }
        .visual-caption span { display: block; margin-bottom: 5px; color: #4f5d25; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .visual-caption strong { font: 22px/1.15 var(--serif); }
        .section { padding: 88px 0; }
        .section-heading { max-width: 660px; margin-bottom: 42px; }
        .section-heading h2 { margin: 14px 0; font: 46px/1.08 var(--serif); }
        .section-heading p { color: var(--ink-soft); font-size: 16px; line-height: 1.7; }
        .services { background: var(--white); border-block: 1px solid var(--line); }
        .service-list { display: grid; grid-template-columns: repeat(3, 1fr); border-top: 1px solid var(--line); }
        .service { padding: 26px 24px 18px 0; border-bottom: 1px solid var(--line); }
        .service + .service { padding-left: 24px; border-left: 1px solid var(--line); }
        .service-index { color: var(--rose); font-size: 12px; font-weight: 700; }
        .service h3 { margin: 22px 0 10px; font: 26px/1.15 var(--serif); }
        .service p { max-width: 300px; margin: 0; color: var(--ink-soft); font-size: 14px; line-height: 1.7; }
        .process { color: var(--white); background: var(--ink); }
        .process .eyebrow { color: var(--lime); }
        .process .section-heading p { color: #c6d7d1; }
        .steps { display: grid; grid-template-columns: repeat(3, 1fr); border-top: 1px solid #54716c; }
        .step { padding: 24px 24px 0 0; }
        .step + .step { padding-left: 24px; border-left: 1px solid #54716c; }
        .step-number { color: var(--lime); font-size: 13px; font-weight: 700; }
        .step h3 { margin: 24px 0 10px; font: 25px/1.15 var(--serif); }
        .step p { max-width: 290px; margin: 0; color: #c6d7d1; font-size: 14px; line-height: 1.7; }
        .closing { display: grid; grid-template-columns: 1fr auto; align-items: center; gap: 36px; padding: 58px 0; }
        .closing h2 { margin: 0 0 10px; font: 38px/1.08 var(--serif); }
        .closing p { margin: 0; color: var(--ink-soft); }
        footer { padding: 28px 0; color: #e4efea; background: #102b29; font-size: 13px; }
        .footer-inner { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        footer p { margin: 0; }
        footer a { color: var(--lime); text-underline-offset: 3px; }
        .notice { margin: 18px auto 0; padding: 14px 18px; border: 1px solid #afcfc0; color: var(--ink); background: #e4f2e9; }
        .notice-error { border-color: #efb7c8; background: #fff0f4; }
        dialog { width: min(720px, calc(100% - 28px)); max-height: min(90vh, 820px); padding: 0; border: 1px solid var(--line); border-radius: 4px; color: var(--ink); background: var(--paper); box-shadow: 0 24px 80px #102b2933; }
        dialog::backdrop { background: #102b298c; backdrop-filter: blur(3px); }
        .dialog-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; padding: 24px 28px 18px; border-bottom: 1px solid var(--line); }
        .dialog-head h2 { margin: 0 0 6px; font: 32px/1.1 var(--serif); }
        .dialog-head p { margin: 0; color: var(--ink-soft); font-size: 13px; }
        .close-button { width: 38px; height: 38px; display: grid; place-items: center; border: 1px solid var(--line); border-radius: 3px; color: var(--ink); background: transparent; font-size: 22px; cursor: pointer; }
        .request-form { padding: 22px 28px 28px; }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        .field { min-width: 0; }
        .field-wide { grid-column: 1 / -1; }
        .field label { display: block; margin-bottom: 6px; font-size: 13px; font-weight: 700; }
        .field input, .field select, .field textarea { width: 100%; min-height: 46px; padding: 10px 12px; border: 1px solid #bfcfc8; border-radius: 3px; color: var(--ink); background: var(--white); }
        .field textarea { min-height: 84px; resize: vertical; }
        .field small { display: block; margin-top: 5px; color: var(--ink-soft); font-size: 11px; }
        .field-error { display: block; margin-top: 5px; color: #a01d45; font-size: 12px; }
        .form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 22px; }
        .form-actions .button-outline { min-height: 46px; }
        @keyframes rise-in { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
        .hero-copy > * { animation: rise-in .6s both; }
        .hero-copy > :nth-child(2) { animation-delay: .08s; }
        .hero-copy > :nth-child(3) { animation-delay: .16s; }
        .hero-visual { animation: rise-in .7s .12s both; }
        @media (max-width: 800px) {
            .wrap { width: min(100% - 36px, 620px); }
            .nav-inner { min-height: 68px; }
            .brand img { max-width: 148px; height: 50px; }
            .nav-links { gap: 16px; }
            .nav-links > a:not(.button) { display: none; }
            .nav-links .button { min-height: 42px; padding-inline: 14px; font-size: 13px; }
            .hero { padding-top: 28px; padding-bottom: 42px; }
            .hero-grid { grid-template-columns: 1fr; gap: 34px; }
            h1 { max-width: 570px; font-size: 50px; }
            .hero-visual { min-height: 340px; }
            .hero-photo { inset: 0 16px 22px 0; width: calc(100% - 16px); height: calc(100% - 22px); }
            .section { padding: 68px 0; }
            .section-heading h2 { font-size: 40px; }
        }
        @media (max-width: 560px) {
            .topline-inner { min-height: 32px; font-size: 10px; }
            .topline-inner p:last-child { display: none; }
            .wrap { width: calc(100% - 32px); }
            .nav-links .button { padding-inline: 11px; }
            h1 { margin-top: 14px; margin-bottom: 12px; font-size: 39px; }
            .hero-copy { font-size: 15px; line-height: 1.55; }
            .hero-grid { gap: 20px; }
            .hero-actions { align-items: stretch; flex-direction: column; gap: 8px; margin-top: 18px; }
            .hero-actions .button { width: 100%; }
            .hero-actions .button-outline { display: none; }
            .hero-note { margin-top: 12px; padding-top: 10px; font-size: 11px; }
            .hero-visual { min-height: 160px; }
            .hero-photo { object-position: center 34%; }
            .visual-caption { width: 190px; padding: 11px 13px; }
            .visual-caption strong { font-size: 17px; }
            .section-heading h2 { font-size: 35px; }
            .service-list, .steps { grid-template-columns: 1fr; }
            .service, .service + .service { padding: 22px 0; border-left: 0; }
            .service h3 { margin-top: 14px; }
            .step, .step + .step { padding: 22px 0; border-left: 0; border-bottom: 1px solid #54716c; }
            .step h3 { margin-top: 12px; }
            .closing { grid-template-columns: 1fr; gap: 22px; padding: 44px 0; }
            .closing h2 { font-size: 34px; }
            .closing .button { width: 100%; }
            .footer-inner { align-items: flex-start; flex-direction: column; }
            .dialog-head { padding: 20px 18px 16px; }
            .dialog-head h2 { font-size: 28px; }
            .request-form { padding: 18px; }
            .form-grid { grid-template-columns: 1fr; gap: 13px; }
            .field-wide { grid-column: auto; }
            .form-actions { flex-direction: column-reverse; }
            .form-actions .button { width: 100%; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <div class="topline">
        <div class="wrap topline-inner">
            <p>Atención cercana para cuidar tu sonrisa</p>
            <p>Lunes a sábado · 9:00 a 19:00</p>
        </div>
    </div>
    <nav class="nav" aria-label="Navegación principal">
        <div class="wrap nav-inner">
            <a class="brand" href="{{ route('home') }}" aria-label="Melissa López, inicio">
                <img src="{{ asset('img/logo.png') }}" alt="Melissa López N. Odontología Integral">
            </a>
            <div class="nav-links">
                <a href="#servicios">Tratamientos</a>
                <a href="#proceso">Tu primera visita</a>
                <a href="{{ route('login') }}">Acceso pacientes</a>
                <button class="button" type="button" data-open-request>Solicitar cita <span aria-hidden="true">↗</span></button>
            </div>
        </div>
    </nav>

    <main>
        @if(session('success'))
            <div class="wrap notice" role="status">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="wrap notice notice-error" role="alert">Revisa los datos marcados en el formulario para enviar tu solicitud.</div>
        @endif

        <section class="hero">
            <div class="wrap hero-grid">
                <div class="hero-copy">
                    <span class="eyebrow">Odontología integral</span>
                    <h1>Odontología integral, hecha para ti.</h1>
                    <p class="hero-copy">Atención dental profesional, explicada con claridad y pensada alrededor de ti. Cuéntanos qué necesitas y encontraremos un horario para conocernos.</p>
                    <div class="hero-actions">
                        <button class="button" type="button" data-open-request>Solicitar una cita <span aria-hidden="true">↗</span></button>
                        <a class="button button-outline" href="#servicios">Conocer tratamientos</a>
                    </div>
                    <div class="hero-note">
                        <span class="note-mark" aria-hidden="true">ML</span>
                        <span>Tu solicitud es revisada por nuestro equipo antes de confirmar el horario.</span>
                    </div>
                </div>
                <div class="hero-visual">
                    <img class="hero-photo" src="{{ asset('img/fondo2.png') }}" alt="Identidad de Melissa López N., odontología integral">
                    <div class="visual-caption"><span>Atención personal</span><strong>Salud y bienestar, en cada visita.</strong></div>
                </div>
            </div>
        </section>

        <section class="section services" id="servicios">
            <div class="wrap">
                <div class="section-heading">
                    <span class="eyebrow">Tratamientos</span>
                    <h2>Cuidado dental para cada etapa.</h2>
                    <p>Empieza con una valoración y recibe orientación para elegir el tratamiento que mejor se adapte a tu sonrisa.</p>
                </div>
                <div class="service-list">
                    <article class="service">
                        <span class="service-index">01 / PREVENCIÓN</span>
                        <h3>Revisión y limpieza</h3>
                        <p>Revisiones periódicas, limpieza profesional y recomendaciones para mantener una buena salud bucal.</p>
                    </article>
                    <article class="service">
                        <span class="service-index">02 / ORTODONCIA</span>
                        <h3>Alineación dental</h3>
                        <p>Opciones de ortodoncia para mejorar la mordida y acompañar el movimiento de tus dientes.</p>
                    </article>
                    <article class="service">
                        <span class="service-index">03 / ESTÉTICA</span>
                        <h3>Diseño de sonrisa</h3>
                        <p>Alternativas estéticas como blanqueamiento y restauraciones, con un plan a tu medida.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section process" id="proceso">
            <div class="wrap">
                <div class="section-heading">
                    <span class="eyebrow">Tu primera visita</span>
                    <h2>Un proceso claro desde el primer mensaje.</h2>
                    <p>El horario que propones queda pendiente hasta que el equipo revise y confirme la disponibilidad.</p>
                </div>
                <div class="steps">
                    <article class="step">
                        <span class="step-number">PASO 01</span>
                        <h3>Cuéntanos qué necesitas</h3>
                        <p>Comparte tus datos, el motivo de consulta y una fecha que te funcione.</p>
                    </article>
                    <article class="step">
                        <span class="step-number">PASO 02</span>
                        <h3>Revisamos el horario</h3>
                        <p>El equipo confirma por WhatsApp o se comunica contigo para buscar otra opción.</p>
                    </article>
                    <article class="step">
                        <span class="step-number">PASO 03</span>
                        <h3>Te esperamos</h3>
                        <p>Recibirás la confirmación de tu cita y los siguientes pasos para tu atención.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section services" id="ofertas-home">
            <div class="wrap">
                <div class="section-heading">
                    <span class="eyebrow">Ofertas</span>
                    <h2>Promociones pensadas para cuidar tu sonrisa.</h2>
                    <p>Accede a programas y paquetes para prevención, estética y cuidado continuo en tiempos que se adapten a tu rutina.</p>
                </div>

                @if($ofertas->isNotEmpty())
                    <div class="service-list">
                        @foreach($ofertas->take(3) as $oferta)
                            <article class="service">
                                <span class="service-index">{{ strtoupper($oferta->tag ?? 'PROMOCIÓN') }}</span>
                                <h3>{{ $oferta->titulo }}</h3>
                                <p>{{ $oferta->descripcion }}</p>
                                @if($oferta->precio)
                                    <div class="service-price" style="font-weight: 700; color: var(--rose-dark); margin-top: 16px;">
                                        ${{ number_format((float) $oferta->precio, 0) }}
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="service-list">
                        <article class="service">
                            <span class="service-index">PROMOCIÓN</span>
                            <h3>Sin ofertas activas</h3>
                            <p>Pronto se publicarán promociones para cuidar tu sonrisa.</p>
                        </article>
                    </div>
                @endif

                <div class="hero-actions" style="margin-top: 20px;">
                    <a class="button" href="{{ route('ofertas.public') }}">Ver ofertas</a>
                </div>
            </div>
        </section>

        <section class="section process" id="casos-home" style="background:#f7f8f5;color:var(--ink);border-top:1px solid var(--line);border-bottom:1px solid var(--line);">
            <div class="wrap">
                <div class="section-heading">
                    <span class="eyebrow" style="color:var(--rose-dark);">Casos</span>
                    <h2>Resultados reales, atención cercana y una experiencia cuidada.</h2>
                    <p style="color:var(--ink-soft);">Cada caso se trabaja con un plan claro, seguimiento y comunicación simple para que te sientas acompañada en todo momento.</p>
                </div>
                <div class="steps" style="border-top-color: var(--line);">
                    <article class="step">
                        <span class="step-number" style="color:var(--rose-dark);">CASO 01</span>
                        <h3>Sonrisa más equilibrada</h3>
                        <p style="color:var(--ink-soft);">Un tratamiento de estética y revisión para mejorar la armonía visual, la funcionalidad y la confianza.</p>
                    </article>
                    <article class="step">
                        <span class="step-number" style="color:var(--rose-dark);">CASO 02</span>
                        <h3>Seguimiento profesional</h3>
                        <p style="color:var(--ink-soft);">Control constante de progresos y ajustes para que cada etapa del tratamiento se entienda claramente.</p>
                    </article>
                    <article class="step">
                        <span class="step-number" style="color:var(--rose-dark);">CASO 03</span>
                        <h3>Salud bucal estable</h3>
                        <p style="color:var(--ink-soft);">Limpieza, prevención y recomendaciones para mantener una sonrisa sana en el tiempo.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="wrap closing" id="contacto">
            <div>
                <h2>¿Listo para dar el primer paso?</h2>
                <p>Solicita un horario y nuestro equipo se pondrá en contacto contigo.</p>
            </div>
            <button class="button" type="button" data-open-request>Solicitar cita <span aria-hidden="true">↗</span></button>
        </section>
    </main>

    <footer>
        <div class="wrap footer-inner">
            <p>© {{ date('Y') }} Melissa López N. · Odontología Integral</p>
            <p>Lunes a sábado, 9:00 a 19:00 · <a href="mailto:contacto@melissalopez.com">Contacto</a></p>
        </div>
    </footer>

    <dialog id="modalSolicitud" aria-labelledby="modalSolicitudTitulo">
        <div class="dialog-head">
            <div>
                <h2 id="modalSolicitudTitulo">Solicita tu cita</h2>
                <p>El horario quedará pendiente hasta que el equipo lo confirme.</p>
            </div>
            <button class="close-button" type="button" data-close-request aria-label="Cerrar">×</button>
        </div>
        <form class="request-form" action="{{ route('solicitud.storePublic') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label for="nombre">Nombre(s)</label>
                    <input id="nombre" name="nombre" value="{{ old('nombre') }}" maxlength="255" autocomplete="given-name" required>
                    @error('nombre')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="apellidos">Apellidos</label>
                    <input id="apellidos" name="apellidos" value="{{ old('apellidos') }}" maxlength="255" autocomplete="family-name" required>
                    @error('apellidos')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="telefono">WhatsApp con clave de país</label>
                    <input id="telefono" name="telefono" type="tel" value="{{ old('telefono') }}" placeholder="+52 55 1234 5678" maxlength="20" autocomplete="tel" required>
                    <small>Lo usaremos para confirmar tu solicitud.</small>
                    @error('telefono')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="email">Correo electrónico (opcional)</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="255" autocomplete="email">
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="fecha_nacimiento">Fecha de nacimiento</label>
                    <input id="fecha_nacimiento" name="fecha_nacimiento" type="date" value="{{ old('fecha_nacimiento') }}" max="{{ now()->toDateString() }}" required>
                    @error('fecha_nacimiento')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="fecha_propuesta">Fecha que prefieres</label>
                    <input id="fecha_propuesta" name="fecha_propuesta" type="date" value="{{ old('fecha_propuesta') }}" min="{{ now()->toDateString() }}" required>
                    @error('fecha_propuesta')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field field-wide">
                    <label for="hora_propuesta">Horario disponible</label>
                    <select id="hora_propuesta" name="hora_propuesta" required disabled>
                        <option value="">Selecciona una fecha primero</option>
                    </select>
                    @error('hora_propuesta')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field field-wide">
                    <label for="motivo_consulta">Motivo de consulta (opcional)</label>
                    <textarea id="motivo_consulta" name="motivo_consulta" maxlength="2000">{{ old('motivo_consulta') }}</textarea>
                    @error('motivo_consulta')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-actions">
                <button class="button button-outline" type="button" data-close-request>Cancelar</button>
                <button class="button" type="submit">Enviar solicitud <span aria-hidden="true">↗</span></button>
            </div>
        </form>
    </dialog>

    <script>
        const dialog = document.getElementById('modalSolicitud');
        const dateInput = document.getElementById('fecha_propuesta');
        const timeSelect = document.getElementById('hora_propuesta');
        const previousTime = @json(old('hora_propuesta'));

        document.querySelectorAll('[data-open-request]').forEach((button) => {
            button.addEventListener('click', () => dialog.showModal());
        });
        document.querySelectorAll('[data-close-request]').forEach((button) => {
            button.addEventListener('click', () => dialog.close());
        });
        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) dialog.close();
        });

        async function cargarHorariosPublicos() {
            if (!dateInput.value) return;
            timeSelect.disabled = true;
            timeSelect.replaceChildren(new Option('Cargando horarios...', ''));

            try {
                const query = new URLSearchParams({ fecha: dateInput.value });
                const response = await fetch(`{{ route('api.disponibilidad') }}?${query}`);
                if (!response.ok) throw new Error('No se pudo consultar disponibilidad');
                const data = await response.json();
                timeSelect.replaceChildren(new Option('Selecciona un horario', ''));

                data.horarios.forEach((hora) => {
                    const value = hora.slice(0, 5);
                    timeSelect.add(new Option(value, value, false, value === previousTime));
                });

                if (!data.horarios.length) timeSelect.replaceChildren(new Option('No hay horarios disponibles ese día', ''));
                timeSelect.disabled = data.horarios.length === 0;
            } catch (error) {
                timeSelect.replaceChildren(new Option('Error al cargar horarios. Intenta de nuevo.', ''));
            }
        }

        dateInput.addEventListener('change', cargarHorariosPublicos);
        @if(session('open_request_modal') || $errors->any())
            dialog.showModal();
        @endif
        if (dateInput.value) cargarHorariosPublicos();
    </script>
</body>
</html>