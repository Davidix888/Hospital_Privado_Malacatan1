@extends('layouts.app', ['title' => 'Laboratorio'])

@push('styles')
<style>
    .container { max-width: min(96vw, 1700px); }
    .lab-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
        padding: 8px;
        border: 1px solid #d3e0ef;
        border-radius: 12px;
        background: #f4f8fd;
    }
    .lab-nav button {
        border: 1px solid #c8d8ea;
        background: #fff;
        color: #1f446f;
        padding: 8px 12px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 13px;
        cursor: default;
    }
    .lab-nav button.active {
        background: linear-gradient(112deg, #0f2e53, #1f4f86);
        color: #fff;
        border-color: transparent;
    }
    .header-row { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 14px; }
    .header-row .btn,
    .lab-section .btn,
    #sol-modal .btn,
    #dup-modal .btn {
        background: linear-gradient(112deg, #0f2e53, #1f4f86);
        color: #fff;
        border-color: transparent;
    }
    .header-row .btn:hover,
    .lab-section .btn:hover,
    #sol-modal .btn:hover,
    #dup-modal .btn:hover {
        filter: brightness(1.05);
    }
    .catalog-grid { display: grid; grid-template-columns: minmax(420px, 520px) minmax(0, 1fr); gap: 16px; align-items: start; }
    .farm-card { padding: 18px; }
    .farm-card h2 { margin: 0 0 8px; font-size: 24px; color: #163760; }
    .farm-card p { margin: 0 0 14px; color: #5b718d; font-size: 14px; }
    .field { margin-bottom: 10px; }
    .field label { display: block; margin-bottom: 5px; font-weight: 700; color: #1f446f; font-size: 13px; }
    .form-input {
        width: 100%; border: 1px solid #c8d8ea; border-radius: 10px; min-height: 40px;
        padding: 8px 10px; font-size: 14px; background: #fff; color: #17365c;
    }
    .form-input:focus { outline: none; border-color: #74aee9; box-shadow: 0 0 0 3px rgba(116, 174, 233, .2); }
    .field-error { margin-top: 4px; color: #b42318; font-size: 12px; font-weight: 600; }
    .table-wrap { overflow-x: auto; border: 1px solid #d3e0ef; border-radius: 12px; background: #fff; }
    table { width: 100%; border-collapse: collapse; min-width: 940px; }
    th, td { border-bottom: 1px solid #e6edf6; text-align: center; padding: 9px 10px; font-size: 12.5px; vertical-align: middle; }
    th { background: #f2f7fd; color: #234b79; font-weight: 800; text-transform: uppercase; letter-spacing: .3px; font-size: 12px; }
    .pill { display: inline-flex; align-items: center; border-radius: 999px; padding: 4px 10px; font-size: 12px; font-weight: 800; border: 1px solid transparent; }
    .status-on { background: #eaf8ef; color: #1e7b3f; border-color: #b9e7c8; }
    .status-off { background: #ffeded; color: #a92626; border-color: #f2bbbb; }
    .actions-cell { display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 6px; }
    .actions-cell form { margin: 0; }
    .icon-btn {
        width: 30px; height: 30px; padding: 0; display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px; border: 1px solid #c8d8ea; background: #fff; color: #1f446f; cursor: pointer;
    }
    .icon-btn svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; }
    .icon-btn-delete { color: #a92626; border-color: #f2bbbb; background: #fff5f5; }
    .icon-btn-toggle-off { color: #a65f08; border-color: #f4d4a5; background: #fff8ed; }
    .icon-btn-toggle-on { color: #1e7b3f; border-color: #b9e7c8; background: #effbf4; }
    .edit-row { display: none; background: #f8fbff; }
    .edit-row.show { display: table-row; }
    .edit-box { text-align: left; border: 1px solid #d3e0ef; border-radius: 10px; padding: 12px; background: #fff; }
    .edit-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
    .edit-grid .full { grid-column: 1 / -1; }
    .filter-panel { padding: 14px; margin-bottom: 12px; }
    .filters { display: grid; grid-template-columns: .9fr 1.4fr .8fr .8fr auto; gap: 12px; align-items: end; }
    .filters .field { margin-bottom: 0; }
    .filters .field label { min-height: 18px; }
    .filters .field .btn { min-height: 40px; display: inline-flex; align-items: center; justify-content: center; }
    .table-pagination-frame { margin-top: 10px; padding: 10px 12px; border: 1px solid #d3e0ef; border-radius: 12px; background: #f7fbff; }
    .pagination { display: flex; flex-wrap: wrap; gap: 6px; justify-content: center; }
    .page-btn { border: 1px solid #c8d8ea; background: #fff; color: #1f446f; padding: 6px 10px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; }
    .page-btn.active { background: #1f4f86; color: #fff; border-color: #1f4f86; }
    .count { font-size: 12px; color: #5e7591; margin: 16px 0 6px; text-align: center; }
    .exam-item { border: 1px dashed #c8d8ea; border-radius: 10px; padding: 10px; margin-bottom: 10px; background: #f9fcff; }
    .exam-item-head { display:flex; justify-content:space-between; align-items:center; gap:8px; margin-bottom:8px; }
    .exam-detail-card { margin-top: 10px; border:1px solid #d3e0ef; border-radius:12px; overflow:hidden; }
    .exam-detail-head { background:#f2f7fd; color:#234b79; font-weight:800; padding:8px 10px; font-size:12px; text-transform:uppercase; }
    .exam-detail-row { display:grid; grid-template-columns: 1.1fr .9fr 2fr .8fr auto; gap:8px; padding:10px; border-top:1px solid #e6edf6; align-items:center; font-size:13px; }
    .exam-detail-total { display:flex; justify-content:flex-end; gap:8px; padding:10px; border-top:1px solid #e6edf6; font-weight:800; color:#163760; background:#fbfdff; }
    .paciente-grid { display:grid; grid-template-columns: minmax(680px, 1fr) minmax(320px, 420px); gap:16px; align-items:start; }
    .search-card { border: 1px solid #d3e0ef; border-radius: 12px; background: #fff; padding: 12px; margin-bottom: 10px; position: sticky; top: 86px; z-index: 5; }
    .search-suggestions { list-style: none; margin: 10px 0 0; padding: 0; border: 1px solid #d3e0ef; border-radius: 10px; overflow: hidden; background: #fff; }
    .search-suggestions li { padding: 9px 10px; border-bottom: 1px solid #e6edf6; cursor: pointer; font-size: 13px; color: #23405f; }
    .search-suggestions li:last-child { border-bottom: 0; }
    .search-suggestions li:hover { background: #f2f7fd; }
    .search-empty { margin-top: 8px; font-size: 12px; color: #5e7591; }
    .modal-backdrop {
        position: fixed; inset: 0; background: rgba(8, 24, 44, .45);
        display: none; align-items: center; justify-content: center; z-index: 1200;
        padding: 16px;
    }
    .modal-backdrop.show { display: flex; }
    .modal-card {
        width: min(760px, 96vw); max-height: 88vh; overflow: auto;
        border: 1px solid #c8d8ea; border-radius: 12px; background: #fff; padding: 14px;
        box-shadow: 0 18px 40px rgba(8, 24, 44, .22);
    }
    .modal-head { display:flex; justify-content:space-between; align-items:center; gap:10px; margin-bottom:8px; }
    .modal-head h3 { margin:0; color:#163760; font-size:22px; }
    .modal-close { border:1px solid #c8d8ea; background:#fff; color:#1f446f; border-radius:8px; width:32px; height:32px; cursor:pointer; }
    .exam-list { display: grid; gap: 10px; margin: 10px 0; }
    .exam-list-item { border: 1px solid #d3e0ef; border-radius: 10px; padding: 10px; background: #f9fcff; }
    .exam-list-top { display:flex; justify-content:space-between; gap:8px; flex-wrap:wrap; margin-bottom:8px; }
    .exam-list-name { font-weight: 800; color: #163760; }
    .exam-list-meta { font-size: 12px; color:#4f6785; display:flex; gap:10px; flex-wrap:wrap; }
    .warn-modal-card { width: min(520px, 96vw); }
    @media (max-width: 920px) {
        .catalog-grid, .filters, .edit-grid { grid-template-columns: 1fr; }
        .paciente-grid { grid-template-columns: 1fr; }
        .exam-detail-row { grid-template-columns: 1fr; }
        .search-card { position: static; }
    }
</style>
@endpush

@section('content')
<div class="header-row">
    <h1 class="title" style="font-size:34px;margin:0;">M&oacute;dulo Laboratorio</h1>
    <a href="{{ route('dashboard') }}" class="btn btn-dark">Volver al panel</a>
</div>

@if (session('status'))
    <div class="alert ok">{{ session('status') }}</div>
@endif
@if ($errors->any())
    <div class="alert">{{ $errors->first() }}</div>
@endif
@if (!$hasCatalogTable)
    <div class="alert">No existe la tabla del cat&aacute;logo de ex&aacute;menes. Ejecuta migraciones para habilitar esta secci&oacute;n.</div>
@endif

<div class="lab-nav" id="labNav">
    <button type="button" class="active" data-target="sec-catalogo">Cat&aacute;logo</button>
    <button type="button" data-target="sec-paciente">Ingreso de paciente</button>
    <button type="button" data-target="sec-examenes">Ex&aacute;menes</button>
</div>

<section id="sec-catalogo" class="lab-section active">
    <div class="catalog-grid">
        <div class="card farm-card">
            <h2>Registrar examen</h2>
            <p>Registro r&aacute;pido de ex&aacute;menes de laboratorio.</p>
            <form method="POST" action="{{ route('laboratorio.examenes.store') }}">
                @csrf
                <div class="field">
                    <label>Nombre del examen</label>
                    <input class="form-input" name="nombre_examen" value="{{ old('nombre_examen') }}" required pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s'.-]+$" title="Solo letras y espacios" placeholder="Ej: Hemograma completo">
                    @error('nombre_examen') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label>Costo</label>
                    <input class="form-input" type="number" name="costo" min="0" step="0.01" value="{{ old('costo') }}" required placeholder="Ej: 120.00">
                    @error('costo') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label>Tipo de muestra</label>
                    <input class="form-input" name="tipo_muestra" value="{{ old('tipo_muestra') }}" required placeholder="Ej: Sangre, orina, saliva">
                    @error('tipo_muestra') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label>C&oacute;digo interno</label>
                    <input class="form-input" value="Se genera autom&aacute;ticamente: EXA-00000" disabled>
                </div>
                <div class="field">
                    <label>Informaci&oacute;n (opcional)</label>
                    <textarea class="form-input" name="informacion" rows="4" placeholder="Preparaci&oacute;n, observaciones y detalles del examen">{{ old('informacion') }}</textarea>
                    @error('informacion') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <button class="btn" type="submit" {{ !$hasCatalogTable ? 'disabled' : '' }}>Guardar examen</button>
            </form>
        </div>

        <div>
            <div class="card farm-card filter-panel">
                <div class="filters">
                    <div class="field">
                        <label>C&oacute;digo</label>
                        <input id="flt-codigo" class="form-input" placeholder="Ej: EXA-00012">
                    </div>
                    <div class="field">
                        <label>Examen</label>
                        <input id="flt-nombre" class="form-input" placeholder="Buscar por nombre">
                    </div>
                    <div class="field">
                        <label>Costo m&iacute;n.</label>
                        <input id="flt-costo" class="form-input" type="number" min="0" step="0.01" placeholder="0">
                    </div>
                    <div class="field">
                        <label>Estado</label>
                        <select id="flt-estado" class="form-input">
                            <option value="">Todos</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                    <div class="field">
                        <button type="button" class="btn btn-dark btn-sm" id="flt-clear">Limpiar</button>
                    </div>
                </div>
                <p id="exa-count" class="count"></p>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>C&oacute;digo</th>
                            <th>Examen</th>
                            <th>Costo</th>
                            <th>Tipo de muestra</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="exa-tbody">
                        @forelse ($examenes as $exa)
                            <tr
                                data-codigo="{{ mb_strtolower($exa->codigo_examen ?? '') }}"
                                data-nombre="{{ mb_strtolower($exa->nombre_examen ?? '') }}"
                                data-costo="{{ (float) $exa->costo }}"
                                data-estado="{{ (int) ($exa->activo ?? 1) === 1 ? 'activo' : 'inactivo' }}"
                                data-edit-target="edit-exa-{{ $exa->id_examen }}"
                            >
                                <td>{{ $exa->codigo_examen ?? ('EXA-'.str_pad((string) $exa->id_examen, 5, '0', STR_PAD_LEFT)) }}</td>
                                <td>{{ $exa->nombre_examen }}</td>
                                <td>Q {{ number_format((float) $exa->costo, 2) }}</td>
                                <td>{{ $exa->tipo_muestra ?: 'No definido' }}</td>
                                <td>
                                    @if ((int) ($exa->activo ?? 1) === 1)
                                        <span class="pill status-on">Activo</span>
                                    @else
                                        <span class="pill status-off">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        <button type="button" class="icon-btn js-toggle-edit" data-target="edit-exa-{{ $exa->id_examen }}" title="Editar examen" aria-label="Editar examen">
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4 11.5-11.5z"/></svg>
                                        </button>
                                        <form method="POST" action="{{ route('laboratorio.examenes.toggle', $exa->id_examen) }}">
                                            @csrf
                                            <button type="submit" class="icon-btn {{ (int) ($exa->activo ?? 1) === 1 ? 'icon-btn-toggle-off' : 'icon-btn-toggle-on' }}" title="{{ (int) ($exa->activo ?? 1) === 1 ? 'Desactivar examen' : 'Activar examen' }}" aria-label="{{ (int) ($exa->activo ?? 1) === 1 ? 'Desactivar examen' : 'Activar examen' }}">
                                                @if ((int) ($exa->activo ?? 1) === 1)
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
                                                @else
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12l5 5L20 7"/></svg>
                                                @endif
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('laboratorio.examenes.destroy', $exa->id_examen) }}" onsubmit="return confirm('&iquest;Seguro que deseas eliminar este examen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="icon-btn icon-btn-delete" title="Eliminar examen" aria-label="Eliminar examen">
                                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr id="edit-exa-{{ $exa->id_examen }}" class="edit-row">
                                <td colspan="6">
                                    <div class="edit-box">
                                        <form method="POST" action="{{ route('laboratorio.examenes.update', $exa->id_examen) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="edit-grid">
                                                <div class="field">
                                                    <label>Nombre del examen</label>
                                                    <input class="form-input" name="nombre_examen" value="{{ $exa->nombre_examen }}" required pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s'.-]+$" title="Solo letras y espacios">
                                                </div>
                                                <div class="field">
                                                    <label>Costo</label>
                                                    <input class="form-input" type="number" min="0" step="0.01" name="costo" value="{{ number_format((float) $exa->costo, 2, '.', '') }}" required>
                                                </div>
                                                <div class="field">
                                                    <label>Tipo de muestra</label>
                                                    <input class="form-input" name="tipo_muestra" value="{{ $exa->tipo_muestra }}" required placeholder="Ej: Sangre, orina, saliva">
                                                </div>
                                                <div class="field full">
                                                    <label>Informaci&oacute;n</label>
                                                    <textarea class="form-input" name="informacion" rows="3">{{ $exa->informacion }}</textarea>
                                                </div>
                                            </div>
                                            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                                <button class="btn btn-sm" type="submit">Guardar cambios</button>
                                                <button type="button" class="btn btn-dark btn-sm js-toggle-edit" data-target="edit-exa-{{ $exa->id_examen }}">Cancelar</button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6">No hay ex&aacute;menes registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-pagination-frame">
                <div id="exa-pagination" class="pagination"></div>
            </div>
        </div>
    </div>
</section>

<section id="sec-examenes" class="lab-section" style="display:none;">
    <div class="card farm-card">
        <h2>Ex&aacute;menes registrados</h2>
        <p>Seguimiento por paciente en fases: ingresado, en proceso, finalizado o cancelado.</p>

            <div class="card farm-card filter-panel" style="padding:14px;">
            <div class="filters" style="grid-template-columns:1fr 1fr 1fr 1fr auto;">
                <div class="field">
                    <label>ID solicitud</label>
                    <input id="sol-f-id" class="form-input" placeholder="Ej: SOL-000123">
                </div>
                <div class="field">
                    <label>Paciente</label>
                    <input id="sol-f-paciente" class="form-input" placeholder="Nombre o apellido">
                </div>
                <div class="field">
                    <label>Examen</label>
                    <input id="sol-f-examen" class="form-input" placeholder="Nombre o c&oacute;digo">
                </div>
                <div class="field">
                    <label>Estado</label>
                    <select id="sol-f-estado" class="form-input">
                        <option value="">Todos</option>
                        <option value="ingresada">Ingresada</option>
                        <option value="en_proceso">En proceso</option>
                        <option value="parcial">Parcial</option>
                        <option value="finalizada">Finalizada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
                <div class="field">
                    <button type="button" class="btn btn-dark btn-sm" id="sol-f-clear">Limpiar</button>
                </div>
            </div>
            <p id="sol-count" class="count" style="margin-top:14px;"></p>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID solicitud</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>DPI</th>
                        <th>Precio total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="sol-tbody">
                    @forelse (($solicitudesAgrupadas ?? collect()) as $g)
                        @php
                            $estado = in_array(($g['estado_general'] ?? 'ingresada'), ['ingresada', 'en_proceso', 'parcial', 'finalizada', 'cancelada'], true) ? $g['estado_general'] : 'ingresada';
                            $paciente = $g['paciente'] ?? '';
                        @endphp
                        <tr
                            data-solicitud="{{ mb_strtolower((string) (($g['codigo_solicitud'] ?? '') !== '' ? $g['codigo_solicitud'] : 'LEGACY')) }}"
                            data-paciente="{{ mb_strtolower($paciente) }}"
                            data-examen="{{ mb_strtolower(collect($g['examenes'] ?? [])->map(fn($x) => (($x['codigo'] ?? '').' '.($x['nombre'] ?? '')))->join(' ')) }}"
                            data-estado="{{ $estado }}"
                        >
                            <td>{{ ($g['codigo_solicitud'] ?? '') !== '' ? $g['codigo_solicitud'] : 'LEGACY' }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($g['fecha'])->format('d/m/Y H:i') }}</td>
                            <td>{{ $paciente !== '' ? $paciente : 'Sin nombre' }}</td>
                            <td>{{ ($g['dpi'] ?? '') !== '' ? $g['dpi'] : 'Sin DPI' }}</td>
                            <td>Q {{ number_format((float) ($g['total'] ?? 0), 2) }}</td>
                            <td>
                                @if ($estado === 'finalizada')
                                    <span class="pill status-on">Finalizada</span>
                                @elseif ($estado === 'parcial')
                                    <span class="pill" style="background:#efe9ff;color:#5b2ea6;border-color:#d7c6ff;">Parcial</span>
                                @elseif ($estado === 'en_proceso')
                                    <span class="pill icon-btn-toggle-off" style="display:inline-flex;border-radius:999px;padding:4px 10px;">En proceso</span>
                                @elseif ($estado === 'cancelada')
                                    <span class="pill status-off">Cancelada</span>
                                @else
                                    <span class="pill" style="background:#e9f2ff;color:#215089;border-color:#b9d0ee;">Ingresada</span>
                                @endif
                            </td>
                            <td>
                                <button
                                    type="button"
                                    class="icon-btn js-open-sol-modal"
                                    title="Ver y actualizar solicitud"
                                    aria-label="Ver y actualizar solicitud"
                                    data-paciente-id="{{ $g['id_paciente'] }}"
                                    data-paciente="{{ $paciente !== '' ? $paciente : 'Sin nombre' }}"
                                    data-dpi="{{ ($g['dpi'] ?? '') !== '' ? $g['dpi'] : 'Sin DPI' }}"
                                    data-fecha="{{ \Illuminate\Support\Carbon::parse($g['fecha'])->format('d/m/Y H:i') }}"
                                    data-total="{{ number_format((float) ($g['total'] ?? 0), 2, '.', '') }}"
                                    data-examenes='@json($g['examenes'])'
                                    data-codigo-solicitud="{{ ($g['codigo_solicitud'] ?? '') !== '' ? $g['codigo_solicitud'] : ($g['grupo'] ?? '') }}"
                                    data-estado="{{ $estado }}"
                                >
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4 11.5-11.5z"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">No hay ex&aacute;menes registrados con pacientes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-pagination-frame">
            <div id="sol-pagination" class="pagination"></div>
        </div>
    </div>
</section>
<div id="sol-modal" class="modal-backdrop" aria-hidden="true">
    <div class="modal-card">
        <div class="modal-head">
            <h3>Solicitud de ex&aacute;menes</h3>
            <button type="button" class="modal-close" id="sol-modal-close" aria-label="Cerrar">✕</button>
        </div>
        <div class="edit-grid">
            <div class="field">
                <label>ID paciente</label>
                <input class="form-input" id="sol-m-paciente-id" disabled>
            </div>
            <div class="field">
                <label>Paciente</label>
                <input class="form-input" id="sol-m-paciente" disabled>
            </div>
            <div class="field">
                <label>DPI</label>
                <input class="form-input" id="sol-m-dpi" disabled>
            </div>
            <div class="field">
                <label>Fecha</label>
                <input class="form-input" id="sol-m-fecha" disabled>
            </div>
            <div class="field">
                <label>Total</label>
                <input class="form-input" id="sol-m-total" disabled>
            </div>
        </div>
        <div id="sol-m-examenes-body" class="exam-list"></div>
        <div style="display:flex;justify-content:flex-end;">
            <button type="button" class="btn btn-dark btn-sm" id="sol-m-cancel">Cerrar</button>
        </div>
    </div>
</div>

<div id="dup-modal" class="modal-backdrop" aria-hidden="true">
    <div class="modal-card warn-modal-card">
        <div class="modal-head">
            <h3>Solicitud abierta detectada</h3>
            <button type="button" class="modal-close" id="dup-modal-close" aria-label="Cerrar">X</button>
        </div>
        <p id="dup-message" style="margin:6px 0 14px;color:#355375;">
            Ya existe una solicitud abierta para este paciente y examen.
        </p>
        <div style="display:flex;gap:8px;justify-content:flex-end;flex-wrap:wrap;">
            <button type="button" class="btn btn-dark btn-sm" id="dup-cancel">Cancelar</button>
            <button type="button" class="btn btn-sm" id="dup-confirm">Crear nueva solicitud</button>
        </div>
    </div>
</div>

<section id="sec-paciente" class="lab-section" style="display:none;">
    <div class="paciente-grid">
        <div class="card farm-card">
            <h2>Ingreso de paciente</h2>
            <p>Registra los datos del paciente y selecciona el examen solicitado.</p>
            <form method="POST" action="{{ route('laboratorio.pacientes.store') }}" id="paciente-form">
                @csrf
                <input type="hidden" name="confirmar_duplicado" id="confirmar_duplicado" value="{{ old('confirmar_duplicado') ? '1' : '0' }}">
                <div class="edit-grid">
                <div class="field">
                    <label>Nombre</label>
                    <input class="form-input" name="nombre" value="{{ old('nombre') }}" required pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s'.-]+$" title="Solo letras y espacios">
                    @error('nombre') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label>Apellido</label>
                    <input class="form-input" name="apellido" value="{{ old('apellido') }}" required pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s'.-]+$" title="Solo letras y espacios">
                    @error('apellido') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label>Tel&eacute;fono</label>
                    <input class="form-input" name="telefono" value="{{ old('telefono') }}" required inputmode="numeric" pattern="^\d{8}$" maxlength="8" placeholder="Ej: 12345678">
                    @error('telefono') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label>Correo</label>
                    <input class="form-input" type="email" name="correo" value="{{ old('correo') }}" required placeholder="correo@dominio.com">
                    @error('correo') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label>NIT</label>
                    <input class="form-input" name="nit" value="{{ old('nit') }}" placeholder="Ej: CF o 1234567-8">
                    @error('nit') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label>DPI</label>
                    <input class="form-input" name="dpi" value="{{ old('dpi') }}" required inputmode="numeric" pattern="^\d{13}$" maxlength="13" placeholder="Ej: 1234567890123">
                    @error('dpi') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <input type="hidden" name="id_paciente_existente" id="id_paciente_existente" value="{{ old('id_paciente_existente') }}">
                <div class="field">
                    <label>G&eacute;nero</label>
                    <select class="form-input" name="genero" required>
                        <option value="">Seleccionar...</option>
                        <option value="Masculino" {{ old('genero') === 'Masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="Femenino" {{ old('genero') === 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        <option value="Otro" {{ old('genero') === 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('genero') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label>Fecha de nacimiento</label>
                    <input class="form-input" type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" max="{{ now()->toDateString() }}" required>
                    @error('fecha_nacimiento') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field full">
                    <label>Direcci&oacute;n</label>
                    <input class="form-input" name="direccion" value="{{ old('direccion') }}" placeholder="Direcci&oacute;n completa (opcional)">
                    @error('direccion') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="field full">
                    <label>Ex&aacute;menes solicitados</label>
                    <div id="exam-items"></div>
                    <button type="button" class="btn btn-sm" id="btn-add-exam">+ Agregar examen</button>
                </div>
                <div class="field full">
                    <div class="exam-detail-card">
                        <div class="exam-detail-head">Detalle de solicitud</div>
                        <div id="exam-detail-body"></div>
                        <div class="exam-detail-total">
                            <span>Total:</span>
                            <span id="exam-total">Q 0.00</span>
                        </div>
                    </div>
                </div>
                </div>
                <button class="btn" type="submit">Guardar paciente y asignar examen</button>
            </form>
        </div>

        <aside class="search-card">
            <h3 style="margin:14px 0 8px;color:#163760;font-size:18px;">Paciente por DPI</h3>
            <p class="count" style="text-align:left;margin:0;">Busca por DPI para autocompletar datos previos.</p>
            <input id="paciente-dpi-search" class="form-input" placeholder="Ej: 1234567890101" style="margin-top:10px;">
            <ul id="paciente-dpi-suggestions" class="search-suggestions"></ul>
            <p id="paciente-dpi-empty" class="search-empty" style="display:none;">Sin coincidencias por DPI.</p>

            <h3 style="margin:14px 0 8px;color:#163760;font-size:18px;">B&uacute;squeda r&aacute;pida de examen</h3>
            <p class="count" style="text-align:left;margin:0;">Busca por nombre, c&oacute;digo o ID. Te muestro 5 sugerencias.</p>
            <input id="exam-search" class="form-input" placeholder="Ej: hemograma, EXA-00012, 12" style="margin-top:10px;">
            <ul id="exam-suggestions" class="search-suggestions"></ul>
            <p id="exam-search-empty" class="search-empty" style="display:none;">No hay coincidencias.</p>
        </aside>
    </div>
</section>

<script>
(() => {
    const navButtons = Array.from(document.querySelectorAll('#labNav button[data-target]'));
    const sections = Array.from(document.querySelectorAll('.lab-section'));
    const activateSection = (target) => {
        navButtons.forEach((btn) => btn.classList.toggle('active', btn.dataset.target === target));
        sections.forEach((sec) => {
            const show = sec.id === target;
            sec.classList.toggle('active', show);
            sec.style.display = show ? '' : 'none';
        });
    };
    navButtons.forEach((btn) => btn.addEventListener('click', () => activateSection(btn.dataset.target)));

    const fltCodigo = document.getElementById('flt-codigo');
    const fltNombre = document.getElementById('flt-nombre');
    const fltCosto = document.getElementById('flt-costo');
    const fltEstado = document.getElementById('flt-estado');
    const fltClear = document.getElementById('flt-clear');
    const tbody = document.getElementById('exa-tbody');
    const count = document.getElementById('exa-count');
    const pagination = document.getElementById('exa-pagination');

    if (tbody && count && pagination) {
        const rows = Array.from(tbody.querySelectorAll('tr[data-nombre]'));
        const pageSize = 8;
        let currentPage = 1;

        const renderPagination = (totalPages) => {
            pagination.innerHTML = '';
            if (totalPages <= 1) return;
            for (let page = 1; page <= totalPages; page += 1) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `page-btn${page === currentPage ? ' active' : ''}`;
                btn.textContent = String(page);
                btn.addEventListener('click', () => { currentPage = page; applyFilters(); });
                pagination.appendChild(btn);
            }
        };

        const applyFilters = () => {
            const qCodigo = (fltCodigo?.value || '').trim().toLowerCase();
            const qNombre = (fltNombre?.value || '').trim().toLowerCase();
            const costoMin = parseFloat(fltCosto?.value || '0');
            const estado = fltEstado?.value || '';
            const filteredRows = [];

            rows.forEach((row) => {
                const codigo = row.dataset.codigo || '';
                const nombre = row.dataset.nombre || '';
                const costo = parseFloat(row.dataset.costo || '0');
                const rowEstado = row.dataset.estado || '';
                const editTarget = row.dataset.editTarget || '';
                const editRow = editTarget ? document.getElementById(editTarget) : null;

                const okCodigo = qCodigo === '' || codigo.includes(qCodigo);
                const okNombre = qNombre === '' || nombre.includes(qNombre);
                const okCosto = !Number.isFinite(costoMin) || costo >= costoMin;
                const okEstado = estado === '' || rowEstado === estado;
                const show = okCodigo && okNombre && okCosto && okEstado;

                if (show) filteredRows.push(row);
                row.style.display = 'none';
                if (editRow) editRow.classList.remove('show');
            });

            const totalFiltered = filteredRows.length;
            const totalPages = Math.max(1, Math.ceil(totalFiltered / pageSize));
            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const start = (currentPage - 1) * pageSize;
            const end = start + pageSize;
            filteredRows.slice(start, end).forEach((row) => {
                row.style.display = '';
            });

            const showing = totalFiltered === 0 ? 0 : Math.min(pageSize, totalFiltered - start);
            count.textContent = `Mostrando ${showing} de ${totalFiltered} ex\u00E1menes filtrados.`;
            renderPagination(totalPages);
        };

        [fltCodigo, fltNombre, fltCosto, fltEstado].forEach((el) => {
            el?.addEventListener('input', () => { currentPage = 1; applyFilters(); });
            el?.addEventListener('change', () => { currentPage = 1; applyFilters(); });
        });

        fltClear?.addEventListener('click', () => {
            if (fltCodigo) fltCodigo.value = '';
            if (fltNombre) fltNombre.value = '';
            if (fltCosto) fltCosto.value = '';
            if (fltEstado) fltEstado.value = '';
            currentPage = 1;
            applyFilters();
        });

        applyFilters();
    }

    const solId = document.getElementById('sol-f-id');
    const solPaciente = document.getElementById('sol-f-paciente');
    const solExamen = document.getElementById('sol-f-examen');
    const solEstado = document.getElementById('sol-f-estado');
    const solClear = document.getElementById('sol-f-clear');
    const solBody = document.getElementById('sol-tbody');
    const solCount = document.getElementById('sol-count');
    const solPagination = document.getElementById('sol-pagination');

    if (solBody && solCount && solPagination) {
        const rows = Array.from(solBody.querySelectorAll('tr[data-paciente]'));
        const pageSize = 8;
        let currentPage = 1;

        const renderPages = (totalPages) => {
            solPagination.innerHTML = '';
            if (totalPages <= 1) return;
            for (let page = 1; page <= totalPages; page += 1) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `page-btn${page === currentPage ? ' active' : ''}`;
                btn.textContent = String(page);
                btn.addEventListener('click', () => { currentPage = page; applySolicitudFilters(); });
                solPagination.appendChild(btn);
            }
        };

        const applySolicitudFilters = () => {
            const qPaciente = (solPaciente?.value || '').trim().toLowerCase();
            const qId = (solId?.value || '').trim().toLowerCase();
            const qExamen = (solExamen?.value || '').trim().toLowerCase();
            const qEstado = (solEstado?.value || '').trim().toLowerCase();
            const filteredRows = [];

            rows.forEach((row) => {
                const solicitud = row.dataset.solicitud || '';
                const paciente = row.dataset.paciente || '';
                const examen = row.dataset.examen || '';
                const estado = row.dataset.estado || '';
                const show = (qId === '' || solicitud.includes(qId))
                    && (qPaciente === '' || paciente.includes(qPaciente))
                    && (qExamen === '' || examen.includes(qExamen))
                    && (qEstado === '' || estado === qEstado);
                row.style.display = show ? '' : 'none';
                if (show) filteredRows.push(row);
            });

            const total = filteredRows.length;
            const totalPages = Math.max(1, Math.ceil(total / pageSize));
            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            rows.forEach((row) => { row.style.display = 'none'; });
            const start = (currentPage - 1) * pageSize;
            const end = start + pageSize;
            filteredRows.slice(start, end).forEach((row) => { row.style.display = ''; });

            const showing = total === 0 ? 0 : Math.min(pageSize, total - start);
            solCount.textContent = `Mostrando ${showing} de ${total} solicitudes.`;
            renderPages(totalPages);
        };

        [solId, solPaciente, solExamen, solEstado].forEach((el) => {
            el?.addEventListener('input', () => { currentPage = 1; applySolicitudFilters(); });
            el?.addEventListener('change', () => { currentPage = 1; applySolicitudFilters(); });
        });

        solClear?.addEventListener('click', () => {
            if (solId) solId.value = '';
            if (solPaciente) solPaciente.value = '';
            if (solExamen) solExamen.value = '';
            if (solEstado) solEstado.value = '';
            currentPage = 1;
            applySolicitudFilters();
        });

        applySolicitudFilters();
    }

    document.querySelectorAll('.js-toggle-edit').forEach((btn) => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.target || '';
            if (!target) return;
            const row = document.getElementById(target);
            if (!row) return;
            row.classList.toggle('show');
        });
    });

    const solModal = document.getElementById('sol-modal');
    const solModalClose = document.getElementById('sol-modal-close');
    const solModalCancel = document.getElementById('sol-m-cancel');
    const solMPacienteId = document.getElementById('sol-m-paciente-id');
    const solMPaciente = document.getElementById('sol-m-paciente');
    const solMDpi = document.getElementById('sol-m-dpi');
    const solMFecha = document.getElementById('sol-m-fecha');
    const solMTotal = document.getElementById('sol-m-total');
    const solMExamenesBody = document.getElementById('sol-m-examenes-body');
    const dupModal = document.getElementById('dup-modal');
    const dupModalClose = document.getElementById('dup-modal-close');
    const dupCancel = document.getElementById('dup-cancel');
    const dupConfirm = document.getElementById('dup-confirm');
    const dupMessage = document.getElementById('dup-message');
    const pacienteForm = document.getElementById('paciente-form');
    const confirmarDuplicadoInput = document.getElementById('confirmar_duplicado');
    const phaseRank = { ingresado: 1, en_proceso: 2, finalizado: 3, cancelado: 3 };

    const closeSolModal = () => {
        if (!solModal) return;
        solModal.classList.remove('show');
        solModal.setAttribute('aria-hidden', 'true');
    };

    document.querySelectorAll('.js-open-sol-modal').forEach((btn) => {
        btn.addEventListener('click', () => {
            if (!solModal) return;
            if (solMPacienteId) solMPacienteId.value = btn.dataset.pacienteId || '';
            if (solMPaciente) solMPaciente.value = btn.dataset.paciente || '';
            if (solMDpi) solMDpi.value = btn.dataset.dpi || '';
            if (solMFecha) solMFecha.value = btn.dataset.fecha || '';
            if (solMTotal) solMTotal.value = `Q ${btn.dataset.total || '0.00'}`;
            if (solMExamenesBody) {
                let examenes = [];
                try { examenes = JSON.parse(btn.dataset.examenes || '[]'); } catch (e) { examenes = []; }
                solMExamenesBody.innerHTML = '';
                examenes.forEach((exa) => {
                    const currentState = exa.estado || 'ingresado';
                    const currentRank = phaseRank[currentState] || 1;
                    const item = document.createElement('div');
                    item.className = 'exam-list-item';
                    item.innerHTML = `
                        <div class="exam-list-top">
                            <div class="exam-list-name">${exa.codigo || ''} - ${exa.nombre || ''}</div>
                            <div class="pill ${currentState === 'finalizado' ? 'status-on' : (currentState === 'cancelado' ? 'status-off' : '')}" style="${currentState === 'ingresado' ? 'background:#e9f2ff;color:#215089;border-color:#b9d0ee;' : (currentState === 'en_proceso' ? 'color:#a65f08;border-color:#f4d4a5;background:#fff8ed;' : '')}">
                                ${currentState === 'en_proceso' ? 'En proceso' : (currentState === 'finalizado' ? 'Finalizado' : (currentState === 'cancelado' ? 'Cancelado' : 'Ingresado'))}
                            </div>
                        </div>
                        <div class="exam-list-meta">
                            <span><strong>Muestra:</strong> ${exa.tipo_muestra || 'No definido'}</span>
                            <span><strong>Costo:</strong> Q ${Number(exa.costo || 0).toFixed(2)}</span>
                        </div>
                        <div class="exam-list-meta" style="margin-top:4px;"><strong>Detalle:</strong> ${exa.informacion || 'Sin información adicional.'}</div>
                        <form method="POST" action="{{ url('/laboratorio/solicitudes') }}/${exa.id_paciente_examen}/estado" style="display:flex;gap:8px;align-items:center;justify-content:flex-start;flex-wrap:wrap;margin-top:8px;">
                            @csrf
                            <input type="hidden" name="active_tab" value="sec-examenes">
                            <input type="hidden" name="reopen_codigo_solicitud" value="${btn.dataset.codigoSolicitud || ''}">
                            <select class="form-input js-item-estado" name="estado" style="min-width:145px;max-width:180px;min-height:34px;padding:6px 8px;">
                                <option value="ingresado">Ingresado</option>
                                <option value="en_proceso">En proceso</option>
                                <option value="finalizado">Finalizado</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                            <button class="btn btn-sm" type="submit" ${currentState === 'finalizado' ? 'disabled title="Este examen ya está finalizado"' : ''}>Guardar examen</button>
                        </form>
                    `;
                    solMExamenesBody.appendChild(item);
                    const selItem = item.querySelector('.js-item-estado');
                    if (selItem) {
                        Array.from(selItem.options).forEach((opt) => {
                            const optValue = opt.value;
                            const optRank = phaseRank[optValue] || 0;
                            opt.disabled = false;
                            if (currentState === 'finalizado' || currentState === 'cancelado') {
                                opt.disabled = optValue !== currentState;
                            } else if (optRank < currentRank) {
                                opt.disabled = true;
                            } else if (currentState === 'ingresado' && optValue === 'finalizado') {
                                opt.disabled = true;
                            }
                        });
                        selItem.value = currentState;
                    }
                });
            }
            solModal.classList.add('show');
            solModal.setAttribute('aria-hidden', 'false');
        });
    });

    solModalClose?.addEventListener('click', closeSolModal);
    solModalCancel?.addEventListener('click', closeSolModal);
    solModal?.addEventListener('click', (e) => { if (e.target === solModal) closeSolModal(); });

    const preferredTab = @json(session('active_tab'));
    const reopenCodigoSolicitud = @json(session('reopen_codigo_solicitud'));
    const hasOldPacienteInput = {{ ($errors->any() && (old('nombre') || old('apellido') || old('id_examen'))) ? 'true' : 'false' }};
    if (preferredTab && ['sec-catalogo', 'sec-paciente', 'sec-examenes'].includes(preferredTab)) {
        activateSection(preferredTab);
    } else if (hasOldPacienteInput) {
        activateSection('sec-paciente');
    } else {
        activateSection('sec-catalogo');
    }

    const examItemsWrap = document.getElementById('exam-items');
    const btnAddExam = document.getElementById('btn-add-exam');
    const examDetailBody = document.getElementById('exam-detail-body');
    const examTotal = document.getElementById('exam-total');
    const examSearch = document.getElementById('exam-search');
    const examSuggestions = document.getElementById('exam-suggestions');
    const examSearchEmpty = document.getElementById('exam-search-empty');
    const pacienteDpiSearch = document.getElementById('paciente-dpi-search');
    const pacienteDpiSuggestions = document.getElementById('paciente-dpi-suggestions');
    const pacienteDpiEmpty = document.getElementById('paciente-dpi-empty');
    const pacienteIdExistente = document.getElementById('id_paciente_existente');
    const pacientesDataset = @json($pacientesJs ?? []);
    const examsDataset = (@json($examenesJs) || []).filter((e) => Number(e.activo ?? 1) === 1);

    const examById = new Map(examsDataset.map((x) => [String(x.id), x]));
    const oldExams = @json(old('examenes', []));

    const buildOptions = (selected = '') => {
        const opts = ['<option value="">Seleccionar examen...</option>'];
        examsDataset.forEach((e) => {
            const isSel = String(selected) === String(e.id) ? 'selected' : '';
            opts.push(`<option value="${e.id}" ${isSel}>${e.codigo} - ${e.nombre}</option>`);
        });
        return opts.join('');
    };

    const renderDetail = () => {
        if (!examItemsWrap || !examDetailBody || !examTotal) return;
        const selects = Array.from(examItemsWrap.querySelectorAll('select[name="examenes[]"]'));
        let html = '';
        let total = 0;
        selects.forEach((sel, idx) => {
            const exam = examById.get(String(sel.value || ''));
            if (!exam) return;
            total += Number(exam.costo || 0);
            const info = exam.info && exam.info.trim() !== '' ? exam.info : 'Sin informaci&oacute;n';
            const tipo = exam.tipo && exam.tipo.trim() !== '' ? exam.tipo : 'No definido';
            html += `<div class="exam-detail-row">
                <div><strong>#${idx + 1}</strong> ${exam.codigo} - ${exam.nombre}</div>
                <div><strong>Muestra:</strong> ${tipo}</div>
                <div><strong>Info:</strong> ${info}</div>
                <div><strong>Costo:</strong> Q ${Number(exam.costo || 0).toFixed(2)}</div>
                <div></div>
            </div>`;
        });
        if (html === '') {
            html = '<div class="exam-detail-row"><div>No hay ex&aacute;menes seleccionados.</div></div>';
        }
        examDetailBody.innerHTML = html;
        examTotal.textContent = `Q ${total.toFixed(2)}`;
    };

    const addExamItem = (selected = '') => {
        if (!examItemsWrap) return;
        const item = document.createElement('div');
        item.className = 'exam-item';
        item.innerHTML = `
            <div class="exam-item-head">
                <strong>Examen</strong>
                <button type="button" class="btn btn-dark btn-sm js-remove-exam">Quitar</button>
            </div>
            <select class="form-input" name="examenes[]" required>${buildOptions(selected)}</select>
        `;
        const removeBtn = item.querySelector('.js-remove-exam');
        const select = item.querySelector('select[name="examenes[]"]');
        removeBtn?.addEventListener('click', () => {
            item.remove();
            if (examItemsWrap.querySelectorAll('.exam-item').length === 0) addExamItem('');
            renderDetail();
        });
        select?.addEventListener('change', renderDetail);
        examItemsWrap.appendChild(item);
        renderDetail();
    };

    const selectedExamIds = () => {
        if (!examItemsWrap) return [];
        return Array.from(examItemsWrap.querySelectorAll('select[name="examenes[]"]'))
            .map((s) => String(s.value || ''))
            .filter((v) => v !== '');
    };

    const applySuggestion = (exam) => {
        const selected = selectedExamIds();
        if (selected.includes(String(exam.id))) {
            if (examSearch) examSearch.value = '';
            renderSuggestions();
            return;
        }
        const emptySelect = Array.from(examItemsWrap?.querySelectorAll('select[name="examenes[]"]') || [])
            .find((s) => String(s.value || '') === '');
        if (emptySelect) {
            emptySelect.value = String(exam.id);
            emptySelect.dispatchEvent(new Event('change', { bubbles: true }));
        } else {
            addExamItem(String(exam.id));
        }
        if (examSearch) examSearch.value = '';
        renderSuggestions();
    };

    const renderSuggestions = () => {
        if (!examSuggestions || !examSearch || !examSearchEmpty) return;
        const q = (examSearch.value || '').trim().toLowerCase();
        const selected = selectedExamIds();
        const results = examsDataset
            .filter((e) => {
                const inSelected = selected.includes(String(e.id));
                if (inSelected) return false;
                const hay = `${e.id} ${e.codigo} ${e.nombre}`.toLowerCase();
                return q === '' || hay.includes(q);
            })
            .slice(0, 5);

        examSuggestions.innerHTML = '';
        results.forEach((e) => {
            const li = document.createElement('li');
            li.textContent = `[${e.id}] ${e.codigo} - ${e.nombre}`;
            li.addEventListener('click', () => applySuggestion(e));
            examSuggestions.appendChild(li);
        });
        examSearchEmpty.style.display = results.length ? 'none' : 'block';
    };

    if (examItemsWrap && btnAddExam) {
        if (Array.isArray(oldExams) && oldExams.length > 0) {
            oldExams.forEach((id) => addExamItem(id));
        } else {
            addExamItem('');
        }
        btnAddExam.addEventListener('click', () => addExamItem(''));
        examSearch?.addEventListener('input', renderSuggestions);
        renderSuggestions();
    }

    const setField = (name, value) => {
        const el = document.querySelector(`[name="${name}"]`);
        if (el) el.value = value || '';
    };

    const applyPacienteSuggestion = (pac) => {
        if (pacienteIdExistente) pacienteIdExistente.value = String(pac.id || '');
        setField('nombre', pac.nombre || '');
        setField('apellido', pac.apellido || '');
        setField('telefono', pac.telefono || '');
        setField('correo', pac.correo || '');
        setField('direccion', pac.direccion || '');
        setField('nit', pac.nit || '');
        setField('dpi', pac.dpi || '');
        setField('genero', pac.genero || '');
        setField('fecha_nacimiento', pac.fecha_nacimiento || '');
        if (pacienteDpiSearch) pacienteDpiSearch.value = pac.dpi || '';
        renderPacienteSuggestions();
    };

    const renderPacienteSuggestions = () => {
        if (!pacienteDpiSearch || !pacienteDpiSuggestions || !pacienteDpiEmpty) return;
        const qRaw = (pacienteDpiSearch.value || '').trim();
        const q = qRaw.toLowerCase();
        const results = pacientesDataset
            .filter((p) => {
                const dpi = String(p.dpi || '').toLowerCase();
                return q === '' ? false : dpi.includes(q);
            })
            .slice(0, 5);

        pacienteDpiSuggestions.innerHTML = '';
        results.forEach((p) => {
            const li = document.createElement('li');
            li.textContent = `${p.dpi || 'Sin DPI'} - ${p.nombre || ''} ${p.apellido || ''}`.trim();
            li.addEventListener('click', () => applyPacienteSuggestion(p));
            pacienteDpiSuggestions.appendChild(li);
        });

        if (pacienteIdExistente && qRaw === '') {
            pacienteIdExistente.value = '';
        }
        pacienteDpiEmpty.style.display = results.length ? 'none' : 'block';
    };

    pacienteDpiSearch?.addEventListener('input', renderPacienteSuggestions);
    renderPacienteSuggestions();

    if (preferredTab === 'sec-examenes' && reopenCodigoSolicitud) {
        const btn = document.querySelector(`.js-open-sol-modal[data-codigo-solicitud="${reopenCodigoSolicitud}"]`);
        btn?.click();
    }

    const duplicateWarning = @json(session('duplicate_warning'));
    if (duplicateWarning) {
        activateSection('sec-paciente');
        if (dupMessage) dupMessage.textContent = `${duplicateWarning} ¿Deseas crear una nueva solicitud de todos modos?`;
        dupModal?.classList.add('show');
        dupModal?.setAttribute('aria-hidden', 'false');
    }

    const closeDupModal = () => {
        dupModal?.classList.remove('show');
        dupModal?.setAttribute('aria-hidden', 'true');
    };
    dupModalClose?.addEventListener('click', closeDupModal);
    dupCancel?.addEventListener('click', closeDupModal);
    dupModal?.addEventListener('click', (e) => { if (e.target === dupModal) closeDupModal(); });
    dupConfirm?.addEventListener('click', () => {
        if (confirmarDuplicadoInput) confirmarDuplicadoInput.value = '1';
        pacienteForm?.submit();
    });
})();
</script>
@endsection
