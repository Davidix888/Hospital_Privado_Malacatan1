@extends('layouts.app', ['title' => 'Reportes Laboratorio'])

@php
    $labMax = max(1, collect($chartData['labMensual'] ?? [])->max('solicitudes') ?? 1);
    $topExMax = max(1, collect($chartData['topExamenes'] ?? [])->max('total') ?? 1);
    $totalEstados = max(1, array_sum($chartData['labEstados'] ?? []));
    $finalizadosPct = (int) round(((int) ($chartData['labEstados']['finalizado'] ?? 0) * 100) / $totalEstados);
    $ingresadoPct = ((int) ($chartData['labEstados']['ingresado'] ?? 0) * 100) / $totalEstados;
    $enProcesoPct = ((int) ($chartData['labEstados']['en_proceso'] ?? 0) * 100) / $totalEstados;
    $finalizadoPct = ((int) ($chartData['labEstados']['finalizado'] ?? 0) * 100) / $totalEstados;
    $canceladoPct = ((int) ($chartData['labEstados']['cancelado'] ?? 0) * 100) / $totalEstados;
    $cut1 = $ingresadoPct;
    $cut2 = $ingresadoPct + $enProcesoPct;
    $cut3 = $ingresadoPct + $enProcesoPct + $finalizadoPct;
@endphp

@section('topbar_actions')
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="btn btn-dark" type="submit">Cerrar sesion</button>
</form>
@endsection

@section('content')
<section class="reports-shell">
    <div class="reports-head card">
        <h1>Dashboard de Reportes - Laboratorio</h1>
        <div class="head-actions">
            <span>{{ now()->format('d/m/Y') }}</span>
            <a class="btn btn-sm btn-primary" href="{{ route('dashboard') }}">Volver al panel</a>
        </div>
    </div>

    <div class="kpi-grid">
        <article class="card kpi-card kpi-main">
            <small>Examenes activos</small>
            <strong>{{ number_format((int) ($resumenLab['examenes_activos'] ?? 0)) }}</strong>
        </article>
        <article class="card kpi-card">
            <small>Solicitudes del mes</small>
            <strong>{{ number_format((int) ($resumenLab['solicitudes_mes'] ?? 0)) }}</strong>
        </article>
        <article class="card kpi-card">
            <small>Pendientes</small>
            <strong>{{ number_format((int) ($resumenLab['pendientes'] ?? 0)) }}</strong>
        </article>
        <article class="card kpi-card">
            <small>Finalizados del mes</small>
            <strong>{{ number_format((int) ($resumenLab['finalizados_mes'] ?? 0)) }}</strong>
        </article>
    </div>

    <div class="extra-grid">
        <article class="card panel">
            <div class="panel-head">
                <h3>Alertas criticas</h3>
                <small>Prioridad operativa</small>
            </div>
            <div class="alerts-list">
                @foreach (($alertas ?? []) as $alerta)
                    <div class="alert-chip {{ $alerta['nivel'] === 'media' ? 'media' : 'ok' }}">
                        <strong>{{ $alerta['titulo'] }}</strong>
                        <span>{{ $alerta['detalle'] }}</span>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="card panel">
            <div class="panel-head">
                <h3>Comparativo mensual</h3>
                <small>Vs mes anterior</small>
            </div>
            <div class="compare-grid">
                @foreach (($comparativoMensual ?? []) as $item)
                    <div class="compare-card">
                        <small>{{ $item['label'] }}</small>
                        <strong>{{ $item['valor'] }}</strong>
                        <span class="{{ str_starts_with($item['delta'], '-') ? 'down' : 'up' }}">{{ $item['delta'] }}</span>
                    </div>
                @endforeach
            </div>
        </article>
    </div>

    <div class="viz-grid">
        <article class="card panel">
            <div class="panel-head">
                <h3>Solicitudes por mes (ultimos 6 meses)</h3>
            </div>
            <div class="bars">
                @foreach (($chartData['labMensual'] ?? []) as $mes)
                    <div class="bar-col">
                        <span class="bar" style="height: {{ max(8, (int) round(($mes['solicitudes'] * 100) / $labMax)) }}%;"></span>
                        <b>{{ $mes['solicitudes'] }}</b>
                        <small>{{ $mes['label'] }}</small>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="card panel panel-side">
            <div class="panel-head">
                <h3>Estado de examenes</h3>
            </div>
            <div class="ring" style="--cut1: {{ $cut1 }}; --cut2: {{ $cut2 }}; --cut3: {{ $cut3 }};">
                <div class="ring-inner">{{ $finalizadosPct }}%</div>
            </div>
            <ul class="status-list">
                <li><span>Ingresado</span><strong>{{ (int) ($chartData['labEstados']['ingresado'] ?? 0) }}</strong></li>
                <li><span>En proceso</span><strong>{{ (int) ($chartData['labEstados']['en_proceso'] ?? 0) }}</strong></li>
                <li><span>Finalizado</span><strong>{{ (int) ($chartData['labEstados']['finalizado'] ?? 0) }}</strong></li>
                <li><span>Cancelado</span><strong>{{ (int) ($chartData['labEstados']['cancelado'] ?? 0) }}</strong></li>
            </ul>
        </article>
    </div>

    <div class="viz-grid second">
        <article class="card panel">
            <div class="panel-head">
                <h3>Top examenes solicitados</h3>
            </div>
            <div class="h-bars">
                @forelse (($chartData['topExamenes'] ?? []) as $item)
                    <div class="h-row">
                        <span class="name">{{ $item['nombre'] }}</span>
                        <div class="track"><span class="fill" style="width: {{ max(6, (int) round(($item['total'] * 100) / $topExMax)) }}%;"></span></div>
                        <b>{{ $item['total'] }}</b>
                    </div>
                @empty
                    <p class="empty-text">Aun no hay registros para este ranking.</p>
                @endforelse
            </div>
        </article>

        <article class="card panel">
            <div class="panel-head">
                <h3>Tecnicos con mas ingresos registrados</h3>
            </div>
            <div class="tech-list">
                @forelse (($chartData['tecnicosRendimiento'] ?? []) as $item)
                    <div class="tech-row">
                        <span class="tech-name">{{ $item['tecnico'] }}</span>
                        <strong class="tech-total">{{ $item['total'] }}</strong>
                    </div>
                @empty
                    <p class="empty-text">Aun no hay ingresos con tecnico asignado.</p>
                @endforelse
            </div>
        </article>
    </div>

    <article class="card panel">
        <div class="panel-head">
            <h3>Actividad reciente de laboratorio</h3>
            <div class="export-actions">
                <a class="btn btn-sm btn-primary" href="{{ route('reportes.export', ['modulo' => 'laboratorio', 'formato' => 'pdf']) }}" onclick="window.open(this.href, '_blank', 'noopener,noreferrer'); return false;">PDF</a>
            </div>
        </div>
        <div class="table-shell">
            <table class="data-table">
                <thead>
                <tr><th>Fecha</th><th>Solicitud</th><th>Paciente</th><th>Usuario</th><th>Estado</th></tr>
                </thead>
                <tbody>
                @forelse (($actividadLaboratorio ?? []) as $item)
                    <tr><td>{{ $item['fecha'] }}</td><td>{{ $item['solicitud'] }}</td><td>{{ $item['paciente'] }}</td><td>{{ $item['usuario'] }}</td><td>{{ $item['dato'] }}</td></tr>
                @empty
                    <tr><td colspan="5">Sin actividad reciente de laboratorio.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </article>
</section>

@push('styles')
<style>
    .reports-shell { display: grid; gap: 14px; }
    .reports-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 16px; }
    .reports-head h1 { margin: 0; color: #12345a; font-size: 32px; font-family: var(--font-display); }
    .head-actions { display: inline-flex; align-items: center; gap: 10px; color: #5f7390; font-weight: 700; }
    .kpi-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; }
    .kpi-card { padding: 14px; }
    .kpi-card small { color: #5f7390; font-size: 11px; text-transform: uppercase; font-weight: 800; }
    .kpi-card strong { display: block; margin-top: 8px; font-size: 34px; color: #11355d; }
    .kpi-main { background: linear-gradient(135deg, #11355d, #1f4f86); }
    .kpi-main small, .kpi-main strong { color: #fff; }
    .extra-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 12px; }
    .viz-grid { display: grid; grid-template-columns: minmax(0, 1fr) 300px; gap: 12px; }
    .viz-grid.second { grid-template-columns: 1fr 360px; }
    .panel { padding: 14px; }
    .panel-head { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 10px; }
    .panel-head h3 { margin: 0; color: #173b62; font-size: 18px; }
    .panel-head small { color: #647892; font-weight: 700; }
    .alerts-list { display: grid; gap: 8px; }
    .alert-chip { border-radius: 10px; border: 1px solid #d5e3f3; background: #f5f9fe; padding: 10px 12px; display: grid; gap: 3px; }
    .alert-chip strong { color: #15385f; font-size: 13px; }
    .alert-chip span { color: #5f7390; font-size: 12px; font-weight: 600; }
    .alert-chip.media { border-color: #fde68a; background: #fff9ea; }
    .alert-chip.ok { border-color: #bbf7d0; background: #eefcf3; }
    .compare-grid { display: grid; grid-template-columns: 1fr; gap: 8px; }
    .compare-card { border: 1px solid #d5e3f3; border-radius: 10px; padding: 10px; background: #f8fbff; display: grid; gap: 3px; }
    .compare-card small { color: #60738d; font-weight: 700; font-size: 11px; text-transform: uppercase; }
    .compare-card strong { color: #13375d; font-size: 20px; line-height: 1.1; }
    .compare-card span { font-size: 12px; font-weight: 800; }
    .compare-card .up { color: #0f766e; }
    .compare-card .down { color: #b91c1c; }
    .bars { height: 220px; display: grid; grid-template-columns: repeat(6, 1fr); align-items: end; gap: 10px; padding: 8px 4px 0; }
    .bar-col { text-align: center; display: grid; align-content: end; gap: 4px; justify-items: center; height: 100%; }
    .bar { width: 30px; border-radius: 8px 8px 0 0; background: linear-gradient(180deg, #1f4f86 0%, #12345a 100%); min-height: 8px; }
    .bar-col b { font-size: 12px; color: #173b62; }
    .bar-col small { font-size: 11px; color: #60738d; }
    .panel-side { display: grid; align-content: start; gap: 12px; }
    .ring {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        margin: 0 auto;
        background: conic-gradient(
            #2d5c95 0 calc(var(--cut1) * 1%),
            #3f7f9f calc(var(--cut1) * 1%) calc(var(--cut2) * 1%),
            #1f4f86 calc(var(--cut2) * 1%) calc(var(--cut3) * 1%),
            #d6a24a calc(var(--cut3) * 1%) 100%
        );
        display: grid;
        place-items: center;
    }
    .ring-inner { width: 102px; height: 102px; border-radius: 50%; background: #fff; display: grid; place-items: center; color: #12345a; font-weight: 800; }
    .status-list { list-style: none; margin: 0; padding: 0; display: grid; gap: 8px; }
    .status-list li { display: flex; justify-content: space-between; color: #50657f; font-weight: 700; }
    .h-bars { display: grid; gap: 10px; }
    .h-row { display: grid; grid-template-columns: 220px 1fr 44px; gap: 10px; align-items: center; }
    .h-row .name { color: #1a395d; font-weight: 700; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .track { height: 12px; background: #e6eef8; border-radius: 999px; overflow: hidden; }
    .fill { height: 100%; display: block; background: linear-gradient(90deg, #f5b942 0%, #f59e0b 100%); border-radius: 999px; }
    .h-row b { color: #173b62; font-size: 12px; text-align: right; }
    .empty-text { margin: 0; color: #60738d; font-weight: 600; }
    .tech-list { display: grid; gap: 8px; }
    .tech-row {
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        gap: 10px;
        border: 1px solid #d5e3f3;
        border-radius: 10px;
        padding: 10px 12px;
        background: #f8fbff;
    }
    .tech-name {
        color: #173b62;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .tech-total {
        color: #0f5b96;
        font-size: 16px;
        font-weight: 800;
    }
    .table-shell {
        border: 1px solid #d3e1f0;
        border-radius: 12px;
        overflow: hidden;
        background: #ffffff;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    .data-table thead th {
        background: linear-gradient(180deg, #eaf2fb 0%, #dce8f7 100%);
        color: #123a63;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .02em;
        padding: 11px 10px;
        border-bottom: 1px solid #c5d8ee;
    }
    .data-table tbody td {
        padding: 10px;
        font-size: 13px;
        color: #23405f;
        border-bottom: 1px solid #e6eef8;
        vertical-align: top;
        word-break: break-word;
    }
    .data-table tbody tr:nth-child(even) td {
        background: #f8fbff;
    }
    .data-table tbody tr:hover td {
        background: #eef5ff;
    }
    .export-actions { display: flex; gap: 8px; justify-content: flex-end; flex-wrap: wrap; }
    @media (max-width: 1100px) {
        .kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .extra-grid { grid-template-columns: 1fr; }
        .viz-grid { grid-template-columns: 1fr; }
        .viz-grid.second { grid-template-columns: 1fr; }
    }
    @media (max-width: 700px) {
        .reports-head { flex-direction: column; align-items: flex-start; }
        .kpi-grid { grid-template-columns: 1fr; }
        .h-row { grid-template-columns: 1fr; }
    }
</style>
@endpush
@endsection
