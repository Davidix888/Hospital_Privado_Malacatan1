<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Hospital Privado Malacatán' }}</title>
    <style>
        :root {
            --bg: #f4f9fc;
            --bg-2: #e6f1f8;
            --card: #ffffff;
            --primary: #11233d;
            --primary-soft: #1a3558;
            --text: #0f243f;
            --muted: #5a6e86;
            --border: #d5e1ef;
            --success: #16a34a;
            --danger: #7f1d1d;
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
            padding-top: 74px;
        }
        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 20;
            background: linear-gradient(90deg, var(--primary), var(--primary-soft));
            color: #fff;
            border-bottom: 1px solid rgba(255, 255, 255, .15);
        }
        .topbar-inner { max-width: 1120px; margin: 0 auto; padding: 10px 16px; display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap; text-align: center; }
        .logo { width: 52px; height: 52px; border-radius: 10px; object-fit: cover; background: #fff; padding: 3px; }
        .brand-title { font-size: 20px; font-weight: 800; margin: 0; line-height: 1.1; }
        .brand-subtitle { margin: 0; opacity: .9; font-size: 13px; }
        .page-main { flex: 1 0 auto; width: 100%; display: flex; flex-direction: column; }
        .container { max-width: 1120px; margin: 18px auto 24px; padding: 0 16px; width: 100%; }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 14px; box-shadow: 0 6px 20px rgba(15, 23, 42, .08); }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0 16px;
            border: 0;
            border-radius: 10px;
            color: #fff;
            background: linear-gradient(90deg, var(--primary), var(--primary-soft));
            font-weight: 700;
            font-size: 14px;
            line-height: 1;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
        }
        .btn-sm { min-height: 34px; padding: 0 12px; font-size: 13px; border-radius: 8px; }
        .btn-block { width: 100%; }
        .btn-dark { background: #1f3c63; }
        .btn-success { background: var(--success); }
        .btn-danger { background: var(--danger); }

        .input, .select { width: 100%; border: 1px solid rgba(255,255,255,.5); border-radius: 0; border-top: 0; border-left: 0; border-right: 0; padding: 10px 6px; font-size: 17px; outline: none; background: transparent; color: #e9fbff; }
        .input::placeholder { color: rgba(233, 251, 255, .7); }
        .input:focus, .select:focus { border-color: #d3f3ff; box-shadow: none; }
        .title { font-size: clamp(28px, 4vw, 38px); margin: 0 0 8px; }
        .subtitle { margin: 0; color: var(--muted); font-size: 20px; }
        .alert { margin: 12px 0; border-radius: 10px; padding: 10px 12px; background: #ffe9e9; border: 1px solid #fecaca; color: #b91c1c; }
        .ok { background: #e7f9ee; border-color: #86efac; color: #166534; }

        .main-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            background: #e7effa;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 8px;
        }
        .main-nav a {
            min-height: 36px;
            padding: 0 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--primary);
            border-radius: 8px;
            border: 1px solid transparent;
            font-weight: 600;
            font-size: 14px;
        }
        .main-nav a.active {
            background: linear-gradient(90deg, var(--primary), var(--primary-soft));
            color: #fff;
        }
        .panel-grid {
            margin-top: 22px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }
        .module-card {
            padding: 20px;
            min-height: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            justify-content: space-between;
        }
        .module-card h3 {
            font-size: 30px;
            margin: 0 0 8px;
        }
        .module-card p {
            color: #596a83;
            font-size: 18px;
            margin: 0 0 16px;
        }

        .footer {
            margin-top: 0;
            background: linear-gradient(90deg, #11233d, #1a3558);
            color: #f4f7ff;
            border-top: 1px solid rgba(255,255,255,.1);
            min-height: 16.67vh;
            display: flex;
            align-items: center;
            flex-shrink: 0;
            width: 100vw;
            max-width: 100vw;
            margin-left: calc(50% - 50vw);
        }
        .footer-inner { max-width: 1120px; width: 100%; margin: 0 auto; padding: 14px 16px; text-align: center; font-size: 13px; line-height: 1.8; opacity: .95; }
        .footer-links { display: flex; justify-content: center; gap: 18px; flex-wrap: wrap; font-size: 12px; opacity: .9; }

        @media (max-width: 1024px) { .container { margin-top: 18px; } }
        @media (max-width: 768px) {
            .topbar-inner { padding: 10px 12px; }
            .logo { width: 44px; height: 44px; }
            .brand-title { font-size: 16px; }
            .brand-subtitle { font-size: 12px; }
            body { padding-top: 70px; }
            .container { padding: 0 12px; margin-top: 14px; }
            .footer { min-height: 20dvh; }
            .footer-inner { line-height: 1.6; font-size: 12px; }
            .footer-links { gap: 10px; font-size: 11px; }
        }
        @media (max-width: 420px) { .footer { min-height: 22dvh; } }
    </style>
    @stack('styles')
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <img class="logo" src="{{ asset('Hospital_logo.jpeg') }}" alt="Logo Hospital">
            <div>
                <p class="brand-title">Hospital Privado Malacatán</p>
                <p class="brand-subtitle">Sistema de gestión clínica y operativa</p>
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
            <div style="margin-bottom:8px;">Hospital Privado Malacatán</div>
            <div class="footer-links">
                <span>Diseñado para gestión hospitalaria</span>
                <span>Aviso legal</span>
                <span>Política de privacidad</span>
            </div>
            <div style="margin-top:8px;">&copy; {{ now()->year }} Todos los derechos reservados</div>
        </div>
    </footer>
</body>
</html>
