<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Hospital Privado Malacatan' }}</title>
    <style>
        :root {
            --bg: #f4f9fc;
            --bg-2: #e6f1f8;
            --card: #ffffff;
            --card-deep: #eaf4fb;
            --primary: #11233d;
            --primary-soft: #1a3558;
            --accent: #2f8f7f;
            --text: #0f243f;
            --muted: #5a6e86;
            --border: #d5e1ef;
            --success: #16a34a;
        }
        * { box-sizing: border-box; }
        html, body { min-height: 100%; }
        body {
            margin: 0;
            font-family: "Segoe UI", "Tahoma", sans-serif;
            background: radial-gradient(circle at 50% 35%, #d7eaf5, var(--bg) 46%, var(--bg-2) 100%);
            color: var(--text);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar {
            background: linear-gradient(90deg, var(--primary), var(--primary-soft));
            color: #fff;
            border-bottom: 1px solid rgba(255, 255, 255, .15);
        }
        .topbar-inner {
            max-width: 1120px;
            margin: 0 auto;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            text-align: center;
        }
        .logo {
            width: 52px;
            height: 52px;
            border-radius: 10px;
            object-fit: cover;
            background: #fff;
            padding: 3px;
        }
        .brand-title { font-size: 20px; font-weight: 800; margin: 0; line-height: 1.1; }
        .brand-subtitle { margin: 0; opacity: .9; font-size: 13px; }

        .page-main {
            flex: 1 0 auto;
            width: 100%;
            display: flex;
            flex-direction: column;
        }
        .container { max-width: 1120px; margin: 24px auto; padding: 0 16px; width: 100%; }

        .card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(15, 23, 42, .08);
        }
        .btn {
            border: 0;
            border-radius: 8px;
            padding: 12px 18px;
            color: #fff;
            background: linear-gradient(90deg, var(--primary), var(--primary-soft));
            font-weight: 700;
            cursor: pointer;
        }
        .btn-dark { background: #1f3c63; }
        .btn-success { background: var(--success); }

        .input, .select {
            width: 100%;
            border: 1px solid rgba(255,255,255,.5);
            border-radius: 0;
            border-top: 0;
            border-left: 0;
            border-right: 0;
            padding: 10px 6px;
            font-size: 17px;
            outline: none;
            background: transparent;
            color: #e9fbff;
        }
        .input::placeholder { color: rgba(233, 251, 255, .7); }
        .input:focus, .select:focus { border-color: #d3f3ff; box-shadow: none; }
        .title { font-size: clamp(28px, 4vw, 38px); margin: 0 0 8px; }
        .subtitle { margin: 0; color: var(--muted); font-size: 20px; }
        .alert {
            margin: 12px 0;
            border-radius: 10px;
            padding: 10px 12px;
            background: #ffe9e9;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }
        .ok { background: #e7f9ee; border-color: #86efac; color: #166534; }

        .footer {
            margin-top: 0;
            background: linear-gradient(90deg, #11233d, #1a3558);
            color: #f4f7ff;
            border-top: 1px solid rgba(255,255,255,.1);
            min-height: 16.67vh;
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }
        .footer-inner {
            max-width: 1120px;
            width: 100%;
            margin: 0 auto;
            padding: 14px 16px;
            text-align: center;
            font-size: 13px;
            line-height: 1.8;
            opacity: .95;
        }
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 18px;
            flex-wrap: wrap;
            font-size: 12px;
            opacity: .9;
        }
        @media (max-width: 1024px) {
            .container { margin-top: 18px; }
        }
        @media (max-width: 768px) {
            .topbar-inner { padding: 10px 12px; }
            .logo { width: 44px; height: 44px; }
            .brand-title { font-size: 16px; }
            .brand-subtitle { font-size: 12px; }
            .container { padding: 0 12px; margin-top: 14px; }
            .footer { min-height: 20dvh; }
            .footer-inner { line-height: 1.6; font-size: 12px; }
            .footer-links { gap: 10px; font-size: 11px; }
        }
        @media (max-width: 420px) {
            .footer { min-height: 22dvh; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <img class="logo" src="{{ asset('Hospital_logo.jpeg') }}" alt="Logo Hospital">
            <div>
                <p class="brand-title">Hospital Privado Malacatan</p>
                <p class="brand-subtitle">Sistema de gestion clinica y operativa</p>
            </div>
        </div>
    </header>

    <main class="page-main">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <div class="footer-inner">
            <div style="margin-bottom:8px;">Hospital Privado Malacatan</div>
            <div class="footer-links">
                <span>Disenado para gestion hospitalaria</span>
                <span>Aviso legal</span>
                <span>Politica de privacidad</span>
            </div>
            <div style="margin-top:8px;">&copy; {{ now()->year }} Todos los derechos reservados</div>
        </div>
    </footer>
</body>
</html>
