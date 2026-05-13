<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Hospital Privado Malacatán' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
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
            --font-ui: "Manrope", "Segoe UI", "Tahoma", sans-serif;
            --font-display: "Sora", "Manrope", "Segoe UI", sans-serif;
        }
        * { box-sizing: border-box; }
        html, body { min-height: 100%; }
        body {
            margin: 0;
            font-family: var(--font-ui);
            background:
                radial-gradient(circle at 8% 8%, rgba(17, 35, 61, .06), transparent 42%),
                radial-gradient(circle at 92% 18%, rgba(26, 53, 88, .08), transparent 38%),
                linear-gradient(180deg, #eff6fb 0%, var(--bg) 42%, var(--bg-2) 100%);
            background-size: 120% 120%, 120% 120%, 100% 100%;
            animation: ambientShift 18s ease-in-out infinite alternate;
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
        .topbar-inner { max-width: min(96vw, 1700px); margin: 0 auto; padding: 10px 16px; display: flex; align-items: center; justify-content: space-between; gap: 12px; text-align: left; }
        .topbar-brand { display: inline-flex; align-items: center; gap: 12px; min-width: 0; }
        .logo { width: 52px; height: 52px; border-radius: 12px; object-fit: cover; background: #fff; padding: 3px; box-shadow: 0 5px 16px rgba(0, 0, 0, .2); }
        .brand-title {
            font-family: var(--font-display);
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            line-height: 1.1;
            letter-spacing: -.35px;
        }
        .brand-subtitle { margin: 0; opacity: .9; font-size: 13px; letter-spacing: .15px; }
        .topbar-actions {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            align-self: flex-start;
            margin-top: 2px;
        }
        .topbar-actions .btn { min-height: 38px; padding: 0 14px; border-radius: 10px; box-shadow: 0 6px 16px rgba(10, 24, 43, .25); }
        .page-main { flex: 1 0 auto; width: 100%; display: flex; flex-direction: column; }
        .container { max-width: 1120px; margin: 22px auto 30px; padding: 0 16px; width: 100%; }
        .card {
            background: var(--card);
            border: 1px solid rgba(174, 196, 220, .7);
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, .08);
            backdrop-filter: blur(2px);
            position: relative;
            overflow: hidden;
        }
        .card::before {
            content: "";
            position: absolute;
            inset: 0 0 auto 0;
            height: 3px;
            background: linear-gradient(90deg, #11233d 0%, #1a3558 45%, #2d5c95 100%);
            opacity: .85;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0 16px;
            border: 0;
            border-radius: 12px;
            color: #fff;
            background: linear-gradient(90deg, var(--primary), var(--primary-soft));
            font-weight: 700;
            font-size: 14px;
            font-family: var(--font-ui);
            line-height: 1;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
            transition: transform .16s ease, box-shadow .16s ease, opacity .16s ease;
            box-shadow: 0 8px 18px rgba(17, 35, 61, .2);
        }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 12px 24px rgba(17, 35, 61, .22); }
        .btn:active { transform: translateY(0); }
        .btn-sm { min-height: 34px; padding: 0 12px; font-size: 13px; border-radius: 8px; }
        .btn-block { width: 100%; }
        .btn-dark { background: #1f3c63; }
        .btn-success { background: var(--success); }
        .btn-danger { background: var(--danger); }

        .input, .select { width: 100%; border: 1px solid rgba(255,255,255,.5); border-radius: 0; border-top: 0; border-left: 0; border-right: 0; padding: 10px 6px; font-size: 17px; outline: none; background: transparent; color: #e9fbff; }
        .input::placeholder { color: rgba(233, 251, 255, .7); }
        .input:focus, .select:focus { border-color: #d3f3ff; box-shadow: none; }
        .title {
            font-family: var(--font-display);
            font-size: clamp(28px, 4vw, 38px);
            font-weight: 700;
            margin: 0 0 8px;
            letter-spacing: -.7px;
            color: #0f2b4d;
            text-wrap: balance;
        }
        .subtitle { margin: 0; color: var(--muted); font-size: 20px; font-weight: 600; }
        .alert { margin: 12px 0; border-radius: 10px; padding: 10px 12px; background: #ffe9e9; border: 1px solid #fecaca; color: #b91c1c; }
        .ok { background: #e7f9ee; border-color: #86efac; color: #166534; }

        .main-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            background: #e7effa;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, .04) inset;
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
            font-weight: 700;
            font-size: 14px;
            letter-spacing: .1px;
            transition: all .14s ease;
        }
        .main-nav a:hover {
            background: rgba(17, 35, 61, .07);
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
            font-family: var(--font-display);
            font-size: 30px;
            margin: 0 0 10px;
            font-weight: 700;
            letter-spacing: -.3px;
            color: #0f2b4d;
        }
        .module-card p {
            color: #596a83;
            font-size: 18px;
            margin: 0 0 16px;
            font-weight: 600;
        }
        .module-card {
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }
        .module-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgba(17, 35, 61, .14);
            border-color: rgba(45, 92, 149, .35);
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }
        .page-header-center { flex: 1; text-align: center; }
        .soft-panel {
            padding: 14px 18px;
            background: #e7effa;
            text-align: center;
            border-radius: 14px;
            border: 1px solid rgba(174, 196, 220, .7);
        }
        .table-shell { overflow: auto; margin-top: 12px; border: 1px solid var(--border); border-radius: 14px; background: #fff; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead tr { background: #eef3fa; }
        .data-table th {
            text-align: center;
            padding: 10px;
            vertical-align: middle;
            font-size: 13px;
            color: #1e3f67;
            font-weight: 800;
            letter-spacing: .2px;
        }
        .data-table td { padding: 10px; text-align: center; vertical-align: middle; border-top: 1px solid #dbe3ef; font-weight: 600; }
        .data-table tbody tr:hover { background: #f8fbff; }
        .form-shell {
            width: min(780px, 100%);
            margin: 24px auto;
            padding: clamp(18px, 3vw, 28px);
        }
        .form-shell-sm { width: min(720px, 100%); }
        .form-grid { margin-top: 16px; display: grid; gap: 14px; }
        .form-field { display: grid; gap: 6px; }
        .form-label { font-size: 14px; font-weight: 700; color: #1f446f; letter-spacing: .1px; }
        .form-control {
            width: 100%;
            min-height: 44px;
            border: 1px solid #c8d8ea;
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 15px;
            font-family: var(--font-ui);
            color: #0f243f;
            background: #fff;
            transition: border-color .14s ease, box-shadow .14s ease, background .14s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: #74aee9;
            box-shadow: 0 0 0 3px rgba(116, 174, 233, .18);
        }
        .form-control:disabled { background: #f8fafc; color: #44546a; }
        .checkbox-row {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #1f446f;
        }
        .checkbox-row input[type="checkbox"] { width: 16px; height: 16px; accent-color: #1f4f86; }
        .form-hint { color: #60738d; font-size: 13px; line-height: 1.45; }
        .form-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            margin-top: 4px;
        }
        .security-kicker {
            color: #b45309;
            font-weight: 800;
            letter-spacing: .35px;
            margin: 0 0 6px;
            font-size: 13px;
        }
        .module-shell {
            padding: clamp(20px, 3vw, 30px);
            text-align: center;
        }
        .module-shell .subtitle {
            font-size: clamp(17px, 2vw, 20px);
            margin: 0 auto;
            max-width: 680px;
        }
        .module-actions {
            margin-top: 16px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        @keyframes ambientShift {
            0% {
                background-position: 0% 0%, 100% 0%, 0% 0%;
            }
            100% {
                background-position: 14% 10%, 88% 16%, 0% 0%;
            }
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
            .topbar-brand { flex: 1; min-width: 0; }
            .logo { width: 44px; height: 44px; }
            .brand-title { font-size: 16px; }
            .brand-subtitle { font-size: 12px; }
            .topbar-actions .btn { min-height: 34px; padding: 0 10px; font-size: 12px; }
            .topbar-actions { align-self: center; margin-top: 0; }
            body { padding-top: 70px; }
            .container { padding: 0 12px; margin-top: 14px; }
            .page-header-center { text-align: left; }
            .form-shell { margin: 18px auto; }
            .form-actions > * { width: 100%; }
            body { animation: none; }
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
            <div class="topbar-brand">
                <img class="logo" src="{{ asset('Hospital_logo.jpeg') }}" alt="Logo Hospital">
                <div>
                    <p class="brand-title">Hospital Privado Malacatán</p>
                    <p class="brand-subtitle">Sistema de gestión clínica y operativa</p>
                </div>
            </div>
            <div class="topbar-actions">
                @yield('topbar_actions')
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
