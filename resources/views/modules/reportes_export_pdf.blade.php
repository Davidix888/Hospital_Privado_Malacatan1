<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #1f2937;
            margin: 20px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        p { margin: 0; color: #4b5563; font-size: 12px; }
        .letterhead {
            border: 1px solid #c7d7ea;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 14px;
        }
        .letterhead-top {
            background: #12345a;
            color: #fff;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .letterhead-top img {
            width: 52px;
            height: 52px;
            border-radius: 8px;
            background: #fff;
            object-fit: cover;
            padding: 2px;
        }
        .letterhead-top .name { font-size: 18px; font-weight: 700; line-height: 1.1; }
        .letterhead-top .tag { font-size: 12px; opacity: .95; }
        .letterhead-bottom {
            padding: 8px 14px;
            background: #eef4fb;
            color: #234162;
            font-size: 11px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }
        .report-meta {
            margin: 8px 0 14px;
            border: 1px solid #d5e3f3;
            border-radius: 8px;
            background: #f8fbff;
            padding: 8px 10px;
        }
        .report-meta-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; }
        .report-meta-grid .item { border-right: 1px solid #dbe6f3; padding-right: 8px; }
        .report-meta-grid .item:last-child { border-right: 0; }
        .report-meta-grid small { display: block; color: #647892; font-size: 10px; margin-bottom: 2px; }
        .report-meta-grid strong { color: #163a61; font-size: 12px; }
        .summary { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin: 8px 0 14px; }
        .summary .box { border: 1px solid #d5e3f3; border-radius: 8px; padding: 8px; background: #f7fbff; }
        .summary .box small { display: block; color: #5f7390; font-size: 11px; margin-bottom: 4px; }
        .summary .box strong { color: #12345a; font-size: 15px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #cbd9ea; padding: 9px 8px; font-size: 12px; text-align: left; vertical-align: top; word-break: break-word; }
        thead th { background: #dce8f7; color: #1e3a5f; font-size: 11px; text-transform: uppercase; letter-spacing: .03em; }
        tbody tr:nth-child(even) td { background: #f7fbff; }
        @media print {
            .hint { display: none; }
            body { margin: 8mm; }
        }
    </style>
</head>
<body>
    <header class="letterhead">
        <div class="letterhead-top">
            <img src="{{ asset('Hospital_logo.jpeg') }}" alt="Logo Hospital">
            <div>
                <div class="name">Hospital Privado Malacatan</div>
                <div class="tag">Sistema de gestion clinica y operativa</div>
            </div>
        </div>
        <div class="letterhead-bottom">
            <span>Reporte interno</span>
        </div>
    </header>

    <div class="report-meta">
        <div class="report-meta-grid">
            <div class="item">
                <small>Tipo de documento</small>
                <strong>Informe de laboratorio</strong>
            </div>
            <div class="item">
                <small>Fecha de emision</small>
                <strong>{{ $fechaGeneracion }}</strong>
            </div>
            <div class="item">
                <small>Generado por</small>
                <strong>{{ $usuarioGenerador ?? 'No registrado' }}</strong>
            </div>
        </div>
    </div>

    @if (!empty($resumenExport))
        <div class="summary">
            @foreach ($resumenExport as $k => $v)
                <div class="box">
                    <small>{{ $k }}</small>
                    <strong>{{ $v }}</strong>
                </div>
            @endforeach
        </div>
    @endif

    <table>
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row['fecha'] ?? '' }}</td>
                    <td>{{ $row['solicitud'] ?? ($row['detalle'] ?? '') }}</td>
                    <td>{{ $row['paciente'] ?? '' }}</td>
                    <td>{{ $row['usuario'] ?? '' }}</td>
                    <td>{{ $row['dato'] ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Sin datos para exportar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
<script>
    window.addEventListener('load', function () {
        window.print();
    });
</script>
</html>
