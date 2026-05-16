@extends('layouts.app', ['title' => 'Farmacia'])

@push('styles')
<style>
    .container {
        max-width: min(96vw, 1700px);
    }
    .farm-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
        padding: 8px;
        border: 1px solid #d3e0ef;
        border-radius: 12px;
        background: #f4f8fd;
    }
    .farm-nav button {
        border: 1px solid #c8d8ea;
        background: #fff;
        color: #1f446f;
        padding: 8px 12px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 13px;
        cursor: pointer;
    }
    .farm-nav button.active {
        background: #0f2e53;
        color: #fff;
        border-color: transparent;
    }
    .farm-section { display: none; }
    .farm-section.active { display: block; }

    .farm-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }
    .catalog-grid {
        display: grid;
        grid-template-columns: minmax(420px, 520px) minmax(0, 1fr);
        gap: 16px;
        align-items: start;
    }
    .farm-card { padding: 18px; }
    .form-card {
        max-width: 860px;
        margin: 0 auto;
    }
    .catalog-form-card {
        margin: 0;
        max-width: 520px;
    }
    .devolucion-form-card {
        margin: 0;
        max-width: 520px;
    }
    .catalog-form {
        display: grid;
        gap: 10px;
    }
    .catalog-form .field {
        margin-bottom: 0;
    }
    .catalog-form .row-2 {
        align-items: end;
    }
    .catalog-form .btn {
        justify-self: start;
        min-width: 190px;
    }
    .farm-card h2 { margin: 0 0 8px; font-size: 24px; color: #163760; }
    .farm-card p { margin: 0 0 14px; color: #5b718d; font-size: 14px; }
    .field { margin-bottom: 10px; }
    .field label { display: block; margin-bottom: 5px; font-weight: 700; color: #1f446f; font-size: 13px; }
    .form-input {
        width: 100%;
        border: 1px solid #c8d8ea;
        border-radius: 10px;
        min-height: 40px;
        padding: 8px 10px;
        font-size: 14px;
        background: #fff;
        color: #17365c;
    }
    .form-input:focus { outline: none; border-color: #74aee9; box-shadow: 0 0 0 3px rgba(116, 174, 233, .2); }
    .field-error { margin-top: 4px; color: #b42318; font-size: 12px; font-weight: 600; }
    .row-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
    .inline-box {
        margin-top: 8px;
        padding: 10px;
        border-radius: 10px;
        border: 1px dashed #bed1e7;
        background: #f7fbff;
        display: none;
    }
    .inline-box.show { display: block; }
    .table-wrap {
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        touch-action: pan-x pan-y;
        border: 1px solid #d3e0ef;
        border-radius: 12px;
        background: #fff;
        max-width: 100%;
    }
    table { width: 100%; border-collapse: collapse; min-width: 900px; }
    th, td { border-bottom: 1px solid #e6edf6; text-align: center; padding: 9px 10px; font-size: 12.5px; vertical-align: middle; }
    th { background: #f2f7fd; color: #234b79; font-weight: 800; text-transform: uppercase; letter-spacing: .3px; font-size: 12px; text-align: center; }
    td { color: #23405f; }
    .section-title { margin: 18px 0 8px; font-size: 20px; color: #15345a; }
    .header-row { display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 14px; }
    .muted { color: #5e7591; margin: 0; }
    .pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .2px;
        border: 1px solid transparent;
    }
    .pill-ok { background: #eaf8ef; color: #1e7b3f; border-color: #b9e7c8; }
    .pill-soon { background: #fff6e8; color: #a65f08; border-color: #f4d4a5; }
    .pill-expired { background: #ffeded; color: #a92626; border-color: #f2bbbb; }
    .pill-empty { background: #eef4fb; color: #5b718d; border-color: #cfdeef; }
    .status-on { background: #eaf8ef; color: #1e7b3f; border-color: #b9e7c8; }
    .status-off { background: #ffeded; color: #a92626; border-color: #f2bbbb; }
    .actions-cell {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        min-height: 32px;
    }
    .actions-cell form {
        margin: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-xs {
        padding: 5px 8px;
        border-radius: 8px;
        font-size: 11px;
        min-height: auto;
    }
    .icon-btn {
        width: 30px;
        height: 30px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        vertical-align: middle;
        border-radius: 8px;
        border: 1px solid #c8d8ea;
        background: #fff;
        color: #1f446f;
        cursor: pointer;
    }
    .icon-btn:hover { background: #f3f8ff; }
    .icon-btn svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; }
    .icon-btn-delete { color: #a92626; border-color: #f2bbbb; background: #fff5f5; }
    .icon-btn-delete:hover { background: #ffecec; }
    .icon-btn-toggle-off { color: #a65f08; border-color: #f4d4a5; background: #fff8ed; }
    .icon-btn-toggle-off:hover { background: #fff1db; }
    .icon-btn-toggle-on { color: #1e7b3f; border-color: #b9e7c8; background: #effbf4; }
    .icon-btn-toggle-on:hover { background: #e4f7ec; }
    .edit-row { display: none; background: #f8fbff; }
    .edit-row.show { display: table-row; }
    .edit-box {
        text-align: left;
        border: 1px solid #d3e0ef;
        border-radius: 10px;
        padding: 12px;
        background: #fff;
    }
    .ventas-grid {
        display: grid;
        grid-template-columns: minmax(720px, 1fr) minmax(380px, 500px);
        gap: 18px;
        align-items: start;
    }
    .ventas-form-card {
        max-width: none;
        margin: 0;
    }
    .search-card {
        border: 1px solid #d3e0ef;
        border-radius: 12px;
        background: #fff;
        padding: 14px;
        position: sticky;
        top: 86px;
        z-index: 5;
    }
    .search-suggestions {
        list-style: none;
        margin: 10px 0 0;
        padding: 0;
        border: 1px solid #d3e0ef;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
    }
    .search-suggestions li {
        padding: 9px 10px;
        border-bottom: 1px solid #e6edf6;
        cursor: pointer;
        font-size: 13px;
        color: #23405f;
    }
    .search-suggestions li:last-child { border-bottom: 0; }
    .search-suggestions li:hover { background: #f2f7fd; }
    .search-empty {
        margin-top: 8px;
        font-size: 12px;
        color: #5e7591;
    }
    .lotes-filters {
        display: grid;
        grid-template-columns: 1.8fr 1fr 1fr 0.8fr 1fr auto;
        gap: 10px;
        margin-bottom: 10px;
        align-items: end;
    }
    .lotes-filters .field {
        margin-bottom: 0;
    }
    .lotes-filter-actions {
        display: flex;
        align-items: end;
        justify-content: flex-end;
    }
    .lotes-count {
        font-size: 12px;
        color: #5e7591;
        margin: 0 0 8px;
    }
    .lotes-pagination {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 6px;
    }
    .table-pagination-frame {
        margin-top: 10px;
        padding: 10px 12px;
        border: 1px solid #d3e0ef;
        border-radius: 12px;
        background: #f7fbff;
    }
    .table-pagination-frame .lotes-pagination {
        justify-content: center;
        margin-top: 0;
    }
    .meds-filter-panel .lotes-filters {
        grid-template-columns: repeat(4, minmax(170px, 1fr)) auto;
        align-items: end;
    }
    .meds-filter-scroll {
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 4px;
    }
    .meds-filter-panel .lotes-filter-actions {
        justify-content: center;
        align-items: flex-end;
    }
    .meds-filter-panel .lotes-filter-actions .btn {
        width: 100%;
        min-height: 40px;
    }
    .meds-filter-panel .btn,
    .meds-filter-panel .lotes-page-btn {
        margin-left: auto;
        margin-right: auto;
    }
    .meds-filter-panel .lotes-count {
        text-align: center;
    }
    .lotes-filter-panel .lotes-filters {
        grid-template-columns: repeat(5, minmax(150px, 1fr)) auto;
        align-items: end;
    }
    .lotes-filter-panel .lotes-filter-actions {
        justify-content: center;
        align-items: flex-end;
    }
    .lotes-filter-panel .lotes-filter-actions .btn {
        width: 100%;
        min-height: 40px;
    }
    .lotes-filter-panel .lotes-count {
        text-align: center;
    }
    .lotes-page-btn {
        border: 1px solid #c8d8ea;
        background: #fff;
        color: #1f446f;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }
    .lotes-page-btn.active {
        background: #1f4f86;
        color: #fff;
        border-color: #1f4f86;
    }

    @media (max-width: 920px) {
        .farm-grid,
        .catalog-grid,
        .ventas-grid,
        .row-2 { grid-template-columns: 1fr; }
        .catalog-form-card {
            margin-top: 0;
            max-width: none;
        }
        .devolucion-form-card {
            max-width: none;
        }
        .lotes-filters { grid-template-columns: 1fr; }
        .lotes-filter-actions { justify-content: stretch; }
        .meds-filter-panel .lotes-filters {
            grid-template-columns: 1fr;
        }
        .meds-filter-panel .lotes-filter-actions {
            justify-content: center;
        }
        .meds-filter-panel .lotes-filter-actions .btn {
            width: 100%;
        }
        .lotes-filter-panel .lotes-filters {
            grid-template-columns: 1fr;
        }
        .lotes-filter-panel .lotes-filter-actions {
            justify-content: center;
        }
        .lotes-filter-panel .lotes-filter-actions .btn {
            width: 100%;
        }
        .search-card {
            position: static;
        }
    }
</style>
@endpush

@section('content')
@php
    $activeSection = old('_active_section', session('active_section', 'sec-catalogo'));
    $oldCompraItems = $activeSection === 'sec-compras' ? old('items') : null;
    if (!is_array($oldCompraItems) || count($oldCompraItems) === 0) {
        $oldCompraItems = [[]];
    }
    $oldVentaItems = $activeSection === 'sec-ventas' ? old('items') : null;
    if (!is_array($oldVentaItems) || count($oldVentaItems) === 0) {
        $oldVentaItems = [[]];
    }
@endphp
<div class="header-row">
    <div>
        <h1 class="title" style="font-size:34px;margin:0;">Módulo Farmacia</h1>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-dark">Volver al panel</a>
</div>

@if (session('status'))
    <div class="alert ok">{{ session('status') }}</div>
@endif

<div class="farm-nav" id="farmNav" data-initial-section="{{ $activeSection }}">
    <button type="button" data-target="sec-catalogo" class="{{ $activeSection === 'sec-catalogo' ? 'active' : '' }}">Medicamento</button>
    <button type="button" data-target="sec-compras" class="{{ $activeSection === 'sec-compras' ? 'active' : '' }}">Compras</button>
    <button type="button" data-target="sec-ventas" class="{{ $activeSection === 'sec-ventas' ? 'active' : '' }}">Ventas</button>
    <button type="button" data-target="sec-devoluciones-compras" class="{{ $activeSection === 'sec-devoluciones-compras' ? 'active' : '' }}">Dev. Compras</button>
    <button type="button" data-target="sec-devoluciones" class="{{ $activeSection === 'sec-devoluciones' ? 'active' : '' }}">Dev. Ventas</button>
    <button type="button" data-target="sec-inventario" class="{{ $activeSection === 'sec-inventario' ? 'active' : '' }}">Inventario</button>
    <button type="button" data-target="sec-lotes" class="{{ $activeSection === 'sec-lotes' ? 'active' : '' }}">Lotes</button>
</div>

<section id="sec-catalogo" class="farm-section {{ $activeSection === 'sec-catalogo' ? 'active' : '' }}">
    <div class="catalog-grid">
        <div class="card farm-card form-card catalog-form-card">
                <h2>Registrar medicamento</h2>
                <p>Registro rápido de medicamentos</p>
                <form method="POST" action="{{ route('farmacia.medicamentos.store') }}" class="catalog-form">
                    @csrf

                    <div class="field">
                        <label>Nombre del medicamento</label>
                        <input class="form-input" name="nombre" value="{{ old('nombre') }}" required pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s'.-]+$" title="Solo letras y espacios" placeholder="Ej: Aspirina">
                        @error('nombre') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label>Categoría</label>
                        <select class="form-input" id="id_categoria" name="id_categoria" required>
                            <option value="">Seleccionar categoría...</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id_categoria }}" @selected((string) old('id_categoria') === (string) $categoria->id_categoria)>{{ $categoria->nombre_categoria }}</option>
                            @endforeach
                            <option value="__nueva__" @selected(old('id_categoria') === '__nueva__')>+ Registrar categoría nueva</option>
                        </select>
                        @error('id_categoria') <div class="field-error">{{ $message }}</div> @enderror
                        <div id="box_nueva_categoria" class="inline-box">
                            <div class="field" style="margin:0;">
                                <label>Nombre de categoría nueva</label>
                                <input class="form-input" name="nueva_categoria" value="{{ old('nueva_categoria') }}" pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s'.-]+$" title="Solo letras y espacios" placeholder="Ej: Antiinflamatorio">
                                @error('nueva_categoria') <div class="field-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row-2">
                        <div class="field">
                            <label>Presentación</label>
                            <input class="form-input" name="presentacion" value="{{ old('presentacion') }}" required pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s'.-]+$" title="Solo letras y espacios" placeholder="Tableta, jarabe, ampolla...">
                            @error('presentacion') <div class="field-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="field">
                            <label>Concentración</label>
                            <input class="form-input" name="concentracion" value="{{ old('concentracion') }}" required placeholder="500 mg, 250 mg/5 ml...">
                            @error('concentracion') <div class="field-error">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="field">
                        <label>Vía de administración</label>
                        <input class="form-input" name="via_administracion" value="{{ old('via_administracion') }}" required pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s'.-]+$" title="Solo letras y espacios" placeholder="Oral, intramuscular, intravenosa...">
                        @error('via_administracion') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label>Código interno</label>
                        <input class="form-input" value="Se genera automáticamente: ID-00000" disabled>
                    </div>

                    <div class="field">
                        <label>Descripción (opcional)</label>
                        <input class="form-input" name="descripcion" value="{{ old('descripcion') }}" placeholder="Observaciones relevantes del medicamento">
                        @error('descripcion') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                <button class="btn" type="submit">Guardar medicamento</button>
            </form>

        </div>

        <div>
            <div class="card farm-card meds-filter-panel" style="padding:12px;margin-bottom:10px;">
                <div class="meds-filter-scroll">
                <div class="lotes-filters">
                    <div class="field">
                        <label>Código</label>
                        <input id="flt-med-codigo" class="form-input" placeholder="Ej: ID-00012">
                    </div>
                    <div class="field">
                        <label>Medicamento</label>
                        <input id="flt-med-nombre" class="form-input" placeholder="Buscar por nombre">
                    </div>
                    <div class="field">
                        <label>Categoría</label>
                        <input id="flt-med-categoria" class="form-input" placeholder="Buscar por categoría">
                    </div>
                    <div class="field">
                        <label>Estado</label>
                        <select id="flt-med-estado" class="form-input">
                            <option value="">Todos</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                    <div class="lotes-filter-actions">
                        <button type="button" class="btn btn-dark btn-sm" id="flt-med-clear">Limpiar</button>
                    </div>
                </div>
                </div>
                <p id="meds-count" class="lotes-count"></p>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Medicamento</th>
                            <th>Categoría</th>
                            <th>Presentación</th>
                            <th>Concentración</th>
                            <th>Vía</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="meds-tbody">
                        @forelse ($catalogoMedicamentos as $med)
                            <tr
                                data-codigo="{{ mb_strtolower($med->codigo_interno ?? '') }}"
                                data-nombre="{{ mb_strtolower($med->nombre ?? '') }}"
                                data-categoria="{{ mb_strtolower($med->nombre_categoria ?? '') }}"
                                data-estado="{{ (int) ($med->activo ?? 1) === 1 ? 'activo' : 'inactivo' }}"
                                data-edit-target="edit-med-{{ $med->id_medicamento }}"
                            >
                                <td>{{ $med->codigo_interno ?? '-' }}</td>
                                <td>{{ $med->nombre }}</td>
                                <td>{{ $med->nombre_categoria ?? 'Sin categoría' }}</td>
                                <td>{{ $med->presentacion ?? '-' }}</td>
                                <td>{{ $med->concentracion ?? '-' }}</td>
                                <td>{{ $med->via_administracion ?? '-' }}</td>
                                <td>
                                    @if ((int) ($med->activo ?? 1) === 1)
                                        <span class="pill status-on">Activo</span>
                                    @else
                                        <span class="pill status-off">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        <button
                                            type="button"
                                            class="icon-btn js-toggle-edit"
                                            data-target="edit-med-{{ $med->id_medicamento }}"
                                            title="Editar medicamento"
                                            aria-label="Editar medicamento"
                                        >
                                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                            </svg>
                                        </button>

                                        <form method="POST" action="{{ route('farmacia.medicamentos.toggle', $med->id_medicamento) }}">
                                            @csrf
                                            <button
                                                class="icon-btn {{ (int) ($med->activo ?? 1) === 1 ? 'icon-btn-toggle-off' : 'icon-btn-toggle-on' }}"
                                                type="submit"
                                                title="{{ (int) ($med->activo ?? 1) === 1 ? 'Desactivar medicamento' : 'Activar medicamento' }}"
                                                aria-label="{{ (int) ($med->activo ?? 1) === 1 ? 'Desactivar medicamento' : 'Activar medicamento' }}"
                                            >
                                                @if ((int) ($med->activo ?? 1) === 1)
                                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                                        <path d="M18 6L6 18"></path>
                                                        <path d="M6 6l12 12"></path>
                                                    </svg>
                                                @else
                                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                                        <path d="M20 6L9 17l-5-5"></path>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('farmacia.medicamentos.destroy', $med->id_medicamento) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este medicamento?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="icon-btn icon-btn-delete" type="submit" title="Eliminar medicamento" aria-label="Eliminar medicamento">
                                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M8 6V4h8v2"></path>
                                                    <path d="M19 6l-1 14H6L5 6"></path>
                                                    <path d="M10 11v6"></path>
                                                    <path d="M14 11v6"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr id="edit-med-{{ $med->id_medicamento }}" class="edit-row">
                                <td colspan="8">
                                    <div class="edit-box">
                                        <form method="POST" action="{{ route('farmacia.medicamentos.update', $med->id_medicamento) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="row-2">
                                                <div class="field">
                                                    <label>Nombre</label>
                                                    <input class="form-input" name="nombre" value="{{ $med->nombre }}" required>
                                                </div>
                                                <div class="field">
                                                    <label>Categoría</label>
                                                    <select class="form-input" name="id_categoria">
                                                        <option value="">Sin categoría</option>
                                                        @foreach ($categorias as $categoria)
                                                            <option value="{{ $categoria->id_categoria }}" @selected((int) ($med->id_categoria ?? 0) === (int) $categoria->id_categoria)>
                                                                {{ $categoria->nombre_categoria }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row-2">
                                                <div class="field">
                                                    <label>Presentación</label>
                                                    <input class="form-input" name="presentacion" value="{{ $med->presentacion }}">
                                                </div>
                                                <div class="field">
                                                    <label>Concentración</label>
                                                    <input class="form-input" name="concentracion" value="{{ $med->concentracion }}">
                                                </div>
                                            </div>
                                            <div class="row-2">
                                                <div class="field">
                                                    <label>Vía de administración</label>
                                                    <input class="form-input" name="via_administracion" value="{{ $med->via_administracion }}">
                                                </div>
                                                <div class="field">
                                                    <label>Descripción</label>
                                                    <input class="form-input" name="descripcion" value="{{ $med->descripcion ?? '' }}">
                                                </div>
                                            </div>
                                            <div style="display:flex;gap:8px;justify-content:flex-end;">
                                                <button type="button" class="btn btn-dark btn-xs js-toggle-edit" data-target="edit-med-{{ $med->id_medicamento }}">Cancelar</button>
                                                <button class="btn btn-xs" type="submit">Guardar cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8">No hay medicamentos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="table-pagination-frame">
                <div id="meds-pagination" class="lotes-pagination"></div>
            </div>
        </div>
    </div>
</section>

<section id="sec-compras" class="farm-section {{ $activeSection === 'sec-compras' ? 'active' : '' }}">
    <div class="ventas-grid">
        <div class="card farm-card form-card ventas-form-card">
            <h2>Registrar compra</h2>
            <p>Ingrese los datos para registrar la compra</p>
            <form method="POST" action="{{ route('farmacia.compras.store') }}">
                @csrf
                <input type="hidden" name="_active_section" value="sec-compras">
                <div id="compra-items">
                    @error('items')
                        <div class="field-error" style="margin-bottom:8px;">{{ $message }}</div>
                    @enderror
                    @foreach ($oldCompraItems as $idx => $oldItem)
                    <div class="inline-box show compra-item" data-index="{{ $idx }}" style="margin-bottom:10px;">
                        <div class="row-2">
                            <div class="field">
                                <label>Medicamento</label>
                                <select class="form-input js-med-select-compra" name="items[{{ $idx }}][id_medicamento]" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($medicamentos as $medicamento)
                                        <option
                                            value="{{ $medicamento->id_medicamento }}"
                                            data-stock="{{ (int) ($medicamento->stock_disponible ?? 0) }}"
                                            data-vencimiento="{{ $medicamento->proximo_vencimiento_stock ?? '' }}"
                                            @selected((string) ($oldItem['id_medicamento'] ?? '') === (string) $medicamento->id_medicamento)
                                        >[{{ $medicamento->id_medicamento }}] {{ $medicamento->codigo_interno ?? 'ID-s/c' }} - {{ $medicamento->nombre }} @if($medicamento->presentacion) - {{ $medicamento->presentacion }} @endif @if($medicamento->concentracion) ({{ $medicamento->concentracion }}) @endif</option>
                                    @endforeach
                                </select>
                                @error("items.$idx.id_medicamento")
                                    <div class="field-error js-compra-error">{{ $message }}</div>
                                @enderror
                                <p class="muted js-compra-med-warning" style="font-size:12px;margin-top:6px;"></p>
                            </div>
                            <div class="field">
                                <label>Cantidad</label>
                                <input class="form-input js-compra-cantidad" type="number" min="1" name="items[{{ $idx }}][cantidad]" value="{{ $oldItem['cantidad'] ?? '' }}" required>
                                @error("items.$idx.cantidad")
                                    <div class="field-error js-compra-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row-2">
                            <div class="field">
                                <label>Fecha de vencimiento</label>
                                <input class="form-input" type="date" name="items[{{ $idx }}][fecha_vencimiento]" value="{{ $oldItem['fecha_vencimiento'] ?? '' }}" required>
                                @error("items.$idx.fecha_vencimiento")
                                    <div class="field-error js-compra-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="field">
                                <label>Precio de compra unitario</label>
                                <input class="form-input js-compra-precio" type="number" step="0.01" min="0.01" name="items[{{ $idx }}][precio_compra]" value="{{ $oldItem['precio_compra'] ?? '' }}" required>
                                @error("items.$idx.precio_compra")
                                    <div class="field-error js-compra-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row-2">
                            <div class="field">
                                <label>Precio de venta unitario del lote</label>
                                <input class="form-input" type="number" step="0.01" min="0.01" name="items[{{ $idx }}][precio_venta]" value="{{ $oldItem['precio_venta'] ?? '' }}" required>
                                @error("items.$idx.precio_venta")
                                    <div class="field-error js-compra-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="field">
                                <label>Total del próducto</label>
                                <input class="form-input js-compra-total-linea" value="Q 0.00" disabled>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div style="display:flex;justify-content:flex-end;margin:8px 0 14px;">
                    <button class="btn btn-dark btn-sm" type="button" id="add-item-compra">+ Agregar otro medicamento</button>
                </div>

                <div class="field">
                    <label>Proveedor</label>
                    <select class="form-input" id="id_proveedor" name="id_proveedor">
                        <option value="">Sin proveedor</option>
                        @foreach ($proveedores as $proveedor)
                            <option value="{{ $proveedor->id_proveedor }}" @selected((string) old('id_proveedor') === (string) $proveedor->id_proveedor)>{{ $proveedor->nombre_empresa }}</option>
                        @endforeach
                        <option value="__nuevo__" @selected(old('id_proveedor') === '__nuevo__')>+ Registrar proveedor nuevo</option>
                    </select>
                    @error('id_proveedor') <div class="field-error">{{ $message }}</div> @enderror
                    <div id="box_nuevo_proveedor" class="inline-box">
                        <div class="row-2">
                            <div class="field">
                                <label>Nombre de empresa</label>
                                <input class="form-input" name="nuevo_proveedor_nombre" value="{{ old('nuevo_proveedor_nombre') }}" placeholder="Ej: Distribuidora Médica del Sur">
                                @error('nuevo_proveedor_nombre') <div class="field-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="field">
                                <label>Teléfono</label>
                                <input class="form-input" name="nuevo_proveedor_telefono" value="{{ old('nuevo_proveedor_telefono') }}" placeholder="Ej: 5555-5555">
                                @error('nuevo_proveedor_telefono') <div class="field-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="field">
                            <label>Correo</label>
                            <input class="form-input" type="email" name="nuevo_proveedor_correo" value="{{ old('nuevo_proveedor_correo') }}" placeholder="correo@empresa.com">
                            @error('nuevo_proveedor_correo') <div class="field-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="row-2">
                    <div class="field">
                        <label>Fecha de compra</label>
                        <input class="form-input" type="date" name="fecha" max="{{ now()->toDateString() }}" value="{{ old('fecha', now()->toDateString()) }}" required>
                        @error('fecha') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="field">
                        <label>Total general</label>
                        <input class="form-input" id="compra_total_general" value="Q 0.00" disabled>
                    </div>
                </div>
                <button class="btn" type="submit">Guardar compra</button>
            </form>
        </div>

        <aside class="search-card">
            <h3 style="margin:0 0 8px;color:#163760;font-size:18px;">Búsqueda rápida</h3>
            <p class="muted" style="margin-bottom:10px;">Busca por nombre, código o ID. Te muestro 5 sugerencias.</p>
            <input id="compra-busqueda-global" class="form-input" placeholder="Ej: amoxi, ID-00003, 3">
            <ul id="compra-sugerencias" class="search-suggestions"></ul>
            <p id="compra-sugerencias-empty" class="search-empty">Sin resultados.</p>
        </aside>
    </div>
</section>

<section id="sec-ventas" class="farm-section {{ $activeSection === 'sec-ventas' ? 'active' : '' }}">
    <div class="ventas-grid">
        <div class="card farm-card form-card ventas-form-card">
            <h2>Registrar venta</h2>
            <p>Ingrese los datos para registrar la venta</p>
            <form method="POST" action="{{ route('farmacia.ventas.store') }}" id="venta-form">
                @csrf
                <input type="hidden" name="_active_section" value="sec-ventas">
                @if ($activeSection === 'sec-ventas' && $errors->any())
                    <div class="alert warn" style="margin-bottom:10px;">
                        <strong>No se pudo registrar la venta.</strong>
                        <ul style="margin:8px 0 0 18px;">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div id="venta-alerta-cliente" class="alert warn" style="display:none;margin-bottom:10px;"></div>
                <div id="venta-items">
                    @error('items')
                        <div class="field-error" style="margin-bottom:8px;">{{ $message }}</div>
                    @enderror
                    @foreach ($oldVentaItems as $idx => $oldItem)
                    <div class="inline-box show sale-item" data-index="{{ $idx }}" style="margin-bottom:10px;">
                        <div class="row-2">
                            <div class="field">
                                <label>Medicamento</label>
                                <select class="form-input js-med-select" name="items[{{ $idx }}][id_medicamento]" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($medicamentos as $medicamento)
                                        <option
                                            value="{{ $medicamento->id_medicamento }}"
                                            data-precio="{{ (float) ($medicamento->precio_referencia ?? 0) }}"
                                            data-stock="{{ (int) ($medicamento->stock_disponible ?? 0) }}"
                                            data-vencimiento="{{ $medicamento->proximo_vencimiento_stock ?? '' }}"
                                            @selected((string) ($oldItem['id_medicamento'] ?? '') === (string) $medicamento->id_medicamento)
                                        >
                                            [{{ $medicamento->id_medicamento }}] {{ $medicamento->codigo_interno ?? 'ID-s/c' }} - {{ $medicamento->nombre }} @if($medicamento->presentacion) - {{ $medicamento->presentacion }} @endif @if($medicamento->concentracion) ({{ $medicamento->concentracion }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error("items.$idx.id_medicamento")
                                    <div class="field-error js-venta-error">{{ $message }}</div>
                                @enderror
                                <p class="muted js-venta-med-warning" style="font-size:12px;margin-top:6px;"></p>
                            </div>
                            <div class="field">
                                <label>Cantidad</label>
                                <input class="form-input js-venta-cantidad" type="number" min="1" name="items[{{ $idx }}][cantidad]" value="{{ $oldItem['cantidad'] ?? '' }}" required>
                                @error("items.$idx.cantidad")
                                    <div class="field-error js-venta-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="field">
                            <label>Subtotal línea (estimado)</label>
                            <input class="form-input js-venta-total-linea" value="Q 0.00" disabled>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div style="display:flex;justify-content:flex-end;margin:8px 0 14px;">
                    <button class="btn btn-dark btn-sm" type="button" id="add-item-venta">+ Agregar otro medicamento</button>
                </div>

                <div class="field">
                    <label>Total general</label>
                    <input class="form-input" id="venta_total_general" value="Q 0.00" disabled>
                </div>

                <div class="field">
                    <label>Paciente</label>
                    <input type="hidden" id="consumidor_final_hidden" name="consumidor_final" value="{{ old('consumidor_final', 0) ? 1 : 0 }}">
                    <button
                        class="form-input"
                        style="text-align:left;cursor:pointer;"
                        type="button"
                        id="toggle-paciente-box"
                    >Paciente</button>
                    @error('id_paciente') <div class="field-error">{{ $message }}</div> @enderror
                    <div id="box_nuevo_paciente" class="inline-box {{ old('id_paciente') || old('nuevo_paciente_nit') || old('nuevo_paciente_dpi') || old('nuevo_paciente_nombre') || old('nuevo_paciente_apellido') || !old('consumidor_final', true) ? 'show' : '' }}">
                        <div class="field" style="margin-bottom:10px;">
                            <label style="display:flex;align-items:center;gap:8px;">
                                <input type="checkbox" id="consumidor_final" value="1" @checked(old('consumidor_final', false))>
                                Consumidor final (no guardar datos del paciente)
                            </label>
                            @error('consumidor_final') <div class="field-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="field">
                            <label>Paciente existente</label>
                            <select class="form-input" id="id_paciente" name="id_paciente">
                                <option value="">Seleccione...</option>
                                @foreach ($pacientes as $paciente)
                                    <option
                                        value="{{ $paciente->id_paciente }}"
                                        data-nombre="{{ $paciente->nombre }}"
                                        data-apellido="{{ $paciente->apellido }}"
                                        data-telefono="{{ $paciente->telefono ?? '' }}"
                                        data-correo="{{ $paciente->correo ?? '' }}"
                                        data-fecha-nacimiento="{{ $paciente->fecha_nacimiento ?? '' }}"
                                        data-genero="{{ $paciente->genero ?? '' }}"
                                        data-nit="{{ $paciente->nit ?? '' }}"
                                        data-dpi="{{ $paciente->dpi ?? '' }}"
                                        data-direccion="{{ $paciente->direccion ?? '' }}"
                                        @selected((string) old('id_paciente') === (string) $paciente->id_paciente)
                                    >{{ $paciente->nombre }} {{ $paciente->apellido }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row-2">
                            <div class="field">
                                <label>Nombre</label>
                                <input class="form-input" name="nuevo_paciente_nombre" value="{{ old('nuevo_paciente_nombre') }}" placeholder="Nombre">
                                @error('nuevo_paciente_nombre') <div class="field-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="field">
                                <label>Apellido</label>
                                <input class="form-input" name="nuevo_paciente_apellido" value="{{ old('nuevo_paciente_apellido') }}" placeholder="Apellido">
                                @error('nuevo_paciente_apellido') <div class="field-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row-2">
                            <div class="field">
                                <label>Teléfono</label>
                                <input class="form-input" name="nuevo_paciente_telefono" value="{{ old('nuevo_paciente_telefono') }}" placeholder="Ej: 5555-5555">
                                @error('nuevo_paciente_telefono') <div class="field-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="field">
                                <label>Correo</label>
                                <input class="form-input" type="email" name="nuevo_paciente_correo" value="{{ old('nuevo_paciente_correo') }}" placeholder="correo@dominio.com">
                                @error('nuevo_paciente_correo') <div class="field-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row-2">
                            <div class="field">
                                <label>Fecha de nacimiento</label>
                                <input class="form-input" type="date" name="nuevo_paciente_fecha_nacimiento" value="{{ old('nuevo_paciente_fecha_nacimiento') }}">
                                @error('nuevo_paciente_fecha_nacimiento') <div class="field-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="field">
                                <label>Género</label>
                                <select class="form-input" name="nuevo_paciente_genero">
                                    <option value="">Seleccione...</option>
                                    <option value="Femenino" @selected(old('nuevo_paciente_genero') === 'Femenino')>Femenino</option>
                                    <option value="Masculino" @selected(old('nuevo_paciente_genero') === 'Masculino')>Masculino</option>
                                    <option value="Otro" @selected(old('nuevo_paciente_genero') === 'Otro')>Otro</option>
                                </select>
                                @error('nuevo_paciente_genero') <div class="field-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row-2">
                            <div class="field">
                                <label>NIT</label>
                                <input class="form-input" id="nuevo_paciente_nit" name="nuevo_paciente_nit" value="{{ old('nuevo_paciente_nit') }}" placeholder="Ej: CF o 1234567-8">
                                @error('nuevo_paciente_nit') <div class="field-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="field">
                                <label>DPI</label>
                                <input class="form-input" name="nuevo_paciente_dpi" value="{{ old('nuevo_paciente_dpi') }}" placeholder="Ej: 1234 56789 0101">
                                @error('nuevo_paciente_dpi') <div class="field-error">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="field">
                            <label>Dirección</label>
                            <input class="form-input" name="nuevo_paciente_direccion" value="{{ old('nuevo_paciente_direccion') }}" placeholder="Dirección de residencia">
                            @error('nuevo_paciente_direccion') <div class="field-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label>Fecha de venta</label>
                    <input class="form-input" type="date" name="fecha" max="{{ now()->toDateString() }}" value="{{ old('fecha', now()->toDateString()) }}" required>
                    @error('fecha') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <button class="btn" type="submit">Guardar venta</button>
            </form>
        </div>

        <aside class="search-card">
            <h3 style="margin:0 0 8px;color:#163760;font-size:18px;">Búsqueda rápida</h3>
            <p class="muted" style="margin-bottom:10px;">Ingrese el nombre o código del medicamento que desea buscar</p>
            <input id="venta-busqueda-global" class="form-input" placeholder="Ej: amoxi, ID-00003, 3">
            <ul id="venta-sugerencias" class="search-suggestions"></ul>
            <p id="venta-sugerencias-empty" class="search-empty">Sin resultados.</p>
        </aside>
    </div>
</section>

<section id="sec-devoluciones" class="farm-section {{ $activeSection === 'sec-devoluciones' ? 'active' : '' }}">
    <div class="catalog-grid">
        <div class="card farm-card form-card devolucion-form-card">
            <h2>Registrar devolución</h2>
            <p>Ingrese los datos solicitados para realizar la devolución</p>
            <form method="POST" action="{{ route('farmacia.devoluciones.store') }}">
                @csrf

                <div class="field">
                    <label>Línea de venta</label>
                    <select class="form-input" id="devolucion_linea">
                        <option value="">Seleccione...</option>
                        @foreach ($ventasParaDevolver as $linea)
                            <option
                                value="{{ $linea->id_venta }}|{{ $linea->id_lote }}"
                                data-disponible="{{ $linea->cantidad_disponible }}"
                            >
                                Venta #{{ $linea->id_venta }} | Lote #{{ $linea->id_lote }} | {{ $linea->medicamento }} | Disp: {{ $linea->cantidad_disponible }} | Fecha: {{ $linea->fecha }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_venta') <div class="field-error">{{ $message }}</div> @enderror
                    @error('id_lote') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <input type="hidden" name="id_venta" id="devolucion_id_venta">
                <input type="hidden" name="id_lote" id="devolucion_id_lote">

                <div class="row-2">
                    <div class="field">
                        <label>Cantidad a devolver</label>
                        <input class="form-input" type="number" min="1" name="cantidad" id="devolucion_cantidad" required>
                        @error('cantidad') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="field">
                        <label>Disponible para devolver</label>
                        <input class="form-input" id="devolucion_disponible" value="0" disabled>
                    </div>
                </div>

                <div class="row-2">
                    <div class="field">
                        <label>Fecha de devolución</label>
                        <input class="form-input" type="date" name="fecha" max="{{ now()->toDateString() }}" value="{{ old('fecha', now()->toDateString()) }}" required>
                        @error('fecha') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="field">
                        <label>Motivo</label>
                        <input class="form-input" name="motivo" value="{{ old('motivo') }}" placeholder="Producto en mal estado, error de despacho, etc.">
                        @error('motivo') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="field" style="margin-top:4px;">
                    <label style="display:flex;align-items:center;gap:8px;font-weight:700;color:#1f446f;">
                        <input type="checkbox" name="reingresable" value="1" {{ old('reingresable') ? 'checked' : '' }}>
                        Reingresable a stock
                    </label>
                    <p class="muted" style="font-size:12px;margin-top:4px;">Marca esta opción solo si el producto puede volver al inventario.</p>
                </div>

                <button class="btn" type="submit">Guardar devolución</button>
            </form>
        </div>

        <div>
            <h2 class="section-title" style="margin-top:0;">Ventas con saldo para devolución</h2>
            <div class="card farm-card lotes-filter-panel" style="padding:12px;margin-bottom:10px;">
                <div class="meds-filter-scroll">
                <div class="lotes-filters">
                    <div class="field">
                        <label>ID venta</label>
                        <input id="flt-dev-venta" class="form-input" placeholder="Ej: 1024">
                    </div>
                    <div class="field">
                        <label>Paciente</label>
                        <input id="flt-dev-pac" class="form-input" placeholder="Buscar por paciente">
                    </div>
                    <div class="field">
                        <label>Fecha desde</label>
                        <input id="flt-dev-fecha-desde" class="form-input" type="date">
                    </div>
                    <div class="field">
                        <label>Fecha hasta</label>
                        <input id="flt-dev-fecha-hasta" class="form-input" type="date">
                    </div>
                    <div class="lotes-filter-actions">
                        <button type="button" class="btn btn-dark btn-sm" id="flt-dev-clear">Limpiar</button>
                    </div>
                </div>
                </div>
                <p id="dev-count" class="lotes-count"></p>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID venta</th>
                            <th>Fecha</th>
                            <th>Lote</th>
                            <th>Medicamento</th>
                            <th>Paciente</th>
                            <th>Vendido</th>
                            <th>Devuelto</th>
                            <th>Disponible</th>
                        </tr>
                    </thead>
                    <tbody id="dev-tbody">
                        @forelse ($ventasParaDevolver as $linea)
                            @php
                                $pacienteDev = trim(($linea->paciente_nombre ?? '').' '.($linea->paciente_apellido ?? '')) ?: 'CF';
                            @endphp
                            <tr
                                data-venta="{{ (string) $linea->id_venta }}"
                                data-pac="{{ mb_strtolower((string) $pacienteDev) }}"
                                data-fecha="{{ (string) ($linea->fecha ?? '') }}"
                            >
                                <td>{{ $linea->id_venta }}</td>
                                <td>{{ $linea->fecha }}</td>
                                <td>{{ $linea->id_lote }}</td>
                                <td>{{ $linea->medicamento }}</td>
                                <td>{{ $pacienteDev }}</td>
                                <td>{{ $linea->cantidad_vendida }}</td>
                                <td>{{ $linea->cantidad_devuelta }}</td>
                                <td>{{ $linea->cantidad_disponible }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="8">No hay líneas de venta disponibles para devolución.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="table-pagination-frame">
                <div id="dev-pagination" class="lotes-pagination"></div>
            </div>
        </div>
    </div>
</section>

<section id="sec-devoluciones-compras" class="farm-section {{ $activeSection === 'sec-devoluciones-compras' ? 'active' : '' }}">
    <div class="catalog-grid">
        <div class="card farm-card form-card devolucion-form-card">
            <h2>Registrar devolución de compra</h2>
            <p>Ingrese los datos solicitados para devolver una compra</p>
            <form method="POST" action="{{ route('farmacia.devoluciones_compras.store') }}">
                @csrf
                <input type="hidden" name="_active_section" value="sec-devoluciones-compras">

                <div class="field">
                    <label>Línea de compra</label>
                    <select class="form-input" id="devolucion_compra_linea">
                        <option value="">Seleccione...</option>
                        @foreach ($comprasParaDevolver as $linea)
                            <option
                                value="{{ $linea->id_compra_abastecimiento }}|{{ $linea->id_lote }}"
                                data-disponible="{{ $linea->cantidad_disponible }}"
                            >
                                Compra #{{ $linea->id_compra_abastecimiento }} | Lote #{{ $linea->id_lote }} | {{ $linea->medicamento }} | Disp: {{ $linea->cantidad_disponible }} | Fecha: {{ $linea->fecha }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_compra_abastecimiento') <div class="field-error">{{ $message }}</div> @enderror
                    @error('id_lote') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <input type="hidden" name="id_compra_abastecimiento" id="devolucion_compra_id_compra">
                <input type="hidden" name="id_lote" id="devolucion_compra_id_lote">

                <div class="row-2">
                    <div class="field">
                        <label>Cantidad a devolver</label>
                        <input class="form-input" type="number" min="1" name="cantidad" id="devolucion_compra_cantidad" required>
                        @error('cantidad') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="field">
                        <label>Disponible para devolver</label>
                        <input class="form-input" id="devolucion_compra_disponible" value="0" disabled>
                    </div>
                </div>

                <div class="row-2">
                    <div class="field">
                        <label>Fecha de devolución</label>
                        <input class="form-input" type="date" name="fecha" max="{{ now()->toDateString() }}" value="{{ old('fecha', now()->toDateString()) }}" required>
                        @error('fecha') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="field">
                        <label>Motivo</label>
                        <input class="form-input" name="motivo" value="{{ old('motivo') }}" placeholder="Producto dañado, devolución a proveedor, etc.">
                        @error('motivo') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <button class="btn" type="submit">Guardar devolución de compra</button>
            </form>
        </div>

        <div>
            <h2 class="section-title" style="margin-top:0;">Compras con saldo para devolución</h2>
            <div class="card farm-card lotes-filter-panel" style="padding:12px;margin-bottom:10px;">
                <div class="meds-filter-scroll">
                <div class="lotes-filters">
                    <div class="field">
                        <label>ID compra</label>
                        <input id="flt-devc-compra" class="form-input" placeholder="Ej: 2048">
                    </div>
                    <div class="field">
                        <label>Proveedor</label>
                        <input id="flt-devc-prov" class="form-input" placeholder="Buscar por proveedor">
                    </div>
                    <div class="field">
                        <label>Fecha desde</label>
                        <input id="flt-devc-fecha-desde" class="form-input" type="date">
                    </div>
                    <div class="field">
                        <label>Fecha hasta</label>
                        <input id="flt-devc-fecha-hasta" class="form-input" type="date">
                    </div>
                    <div class="lotes-filter-actions">
                        <button type="button" class="btn btn-dark btn-sm" id="flt-devc-clear">Limpiar</button>
                    </div>
                </div>
                </div>
                <p id="devc-count" class="lotes-count"></p>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID compra</th>
                            <th>Fecha</th>
                            <th>Lote</th>
                            <th>Medicamento</th>
                            <th>Proveedor</th>
                            <th>Comprado</th>
                            <th>Devuelto</th>
                            <th>Disponible</th>
                        </tr>
                    </thead>
                    <tbody id="devc-tbody">
                        @forelse ($comprasParaDevolver as $linea)
                            @php
                                $proveedorDevCompra = (string) ($linea->proveedor_nombre ?? 'N/A');
                            @endphp
                            <tr
                                data-compra="{{ (string) $linea->id_compra_abastecimiento }}"
                                data-prov="{{ mb_strtolower($proveedorDevCompra) }}"
                                data-fecha="{{ (string) ($linea->fecha ?? '') }}"
                            >
                                <td>{{ $linea->id_compra_abastecimiento }}</td>
                                <td>{{ $linea->fecha }}</td>
                                <td>{{ $linea->id_lote }}</td>
                                <td>{{ $linea->medicamento }}</td>
                                <td>{{ $proveedorDevCompra }}</td>
                                <td>{{ (int) $linea->cantidad_comprada }}</td>
                                <td>{{ (int) $linea->cantidad_devuelta }}</td>
                                <td>{{ (int) $linea->cantidad_disponible }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="8">No hay líneas de compra disponibles para devolución.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="table-pagination-frame">
                <div id="devc-pagination" class="lotes-pagination"></div>
            </div>
        </div>
    </div>
</section>

<section id="sec-inventario" class="farm-section {{ $activeSection === 'sec-inventario' ? 'active' : '' }}">
    <h2 class="section-title">Inventario por medicamento</h2>
    <div class="card farm-card lotes-filter-panel" style="padding:12px;margin-bottom:10px;">
        <div class="meds-filter-scroll">
        <div class="lotes-filters">
            <div class="field">
                <label>ID medicamento</label>
                <input id="flt-inv-id" class="form-input" placeholder="Ej: 12">
            </div>
            <div class="field">
                <label>Medicamento</label>
                <input id="flt-inv-med" class="form-input" placeholder="Buscar por medicamento">
            </div>
            <div class="field">
                <label>Lotes activos mín.</label>
                <input id="flt-inv-lotes-min" class="form-input" type="number" min="0" placeholder="0">
            </div>
            <div class="field">
                <label>Stock mín.</label>
                <input id="flt-inv-stock-min" class="form-input" type="number" min="0" placeholder="0">
            </div>
            <div class="lotes-filter-actions">
                <button type="button" class="btn btn-dark btn-sm" id="flt-inv-clear">Limpiar</button>
            </div>
        </div>
        </div>
    </div>
    <p id="inv-count" class="lotes-count"></p>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Medicamento</th>
                    <th>Stock total</th>
                    <th>Lotes activos</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody id="inv-tbody">
                @forelse ($inventario as $item)
                    @php
                        $isLowStockInventario = (int) $item->stock_total > 0 && (int) $item->stock_total <= 20;
                    @endphp
                    <tr
                        data-id="{{ (string) ($item->id_medicamento ?? '') }}"
                        data-med="{{ mb_strtolower((string) ($item->medicamento ?? '')) }}"
                        data-stock="{{ (int) ($item->stock_total ?? 0) }}"
                        data-lotes="{{ (int) ($item->lotes_activos ?? 0) }}"
                    >
                        <td>{{ $item->id_medicamento }}</td>
                        <td>{{ $item->medicamento }}</td>
                        <td>{{ $item->stock_total }}</td>
                        <td>{{ (int) $item->lotes_activos }}</td>
                        <td>
                            @if ((int) $item->stock_total <= 0)
                                <span class="pill pill-empty">Sin stock</span>
                            @elseif ($isLowStockInventario)
                                <span class="pill pill-soon">Bajo stock total ({{ (int) $item->stock_total }})</span>
                            @else
                                <span class="pill pill-ok">Stock suficiente</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No hay información de inventario registrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-pagination-frame">
        <div id="inv-pagination" class="lotes-pagination"></div>
    </div>
</section>

<section id="sec-lotes" class="farm-section {{ $activeSection === 'sec-lotes' ? 'active' : '' }}">
    <h2 class="section-title">Lotes con stock disponible</h2>
    <div class="card farm-card lotes-filter-panel" style="padding:12px;margin-bottom:10px;">
        <div class="meds-filter-scroll">
        <div class="lotes-filters">
            <div class="field">
                <label>Medicamento</label>
                <input id="flt-lote-med" class="form-input" placeholder="Buscar por medicamento">
            </div>
            <div class="field">
                <label>Vence desde</label>
                <input id="flt-lote-venc-desde" class="form-input" type="date">
            </div>
            <div class="field">
                <label>Vence hasta</label>
                <input id="flt-lote-venc-hasta" class="form-input" type="date">
            </div>
            <div class="field">
                <label>Stock mín.</label>
                <input id="flt-lote-stock-min" class="form-input" type="number" min="0" placeholder="0">
            </div>
            <div class="field">
                <label>Estado</label>
                <select id="flt-lote-estado" class="form-input">
                    <option value="">Todos</option>
                    <option value="vigente">Vigente</option>
                    <option value="vencido">Vencido</option>
                    <option value="sin_fecha">Sin fecha</option>
                </select>
            </div>
            <div class="lotes-filter-actions">
                <button type="button" class="btn btn-dark btn-sm" id="flt-lote-clear">Limpiar</button>
            </div>
        </div>
        </div>
        <p id="lotes-count" class="lotes-count"></p>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID lote</th>
                    <th>Medicamento</th>
                    <th>Stock</th>
                    <th>Precio unitario</th>
                    <th>Vencimiento</th>
                    <th>Estado</th>
                    <th>Alerta</th>
                </tr>
            </thead>
            <tbody id="lotes-tbody">
                @forelse ($lotesActivos as $lote)
                    @php
                        $venc = $lote->fecha_vencimiento ? \Illuminate\Support\Carbon::parse($lote->fecha_vencimiento)->startOfDay() : null;
                        $hoyLote = now()->startOfDay();
                        $diasLote = $venc ? $hoyLote->diffInDays($venc, false) : null;
                        $estadoLote = !$venc ? 'sin_fecha' : ($diasLote < 0 ? 'vencido' : ($diasLote <= 30 ? 'por_vencer' : 'vigente'));
                    @endphp
                    <tr
                        data-med="{{ mb_strtolower($lote->medicamento) }}"
                        data-venc="{{ $lote->fecha_vencimiento ?? '' }}"
                        data-stock="{{ (int) $lote->stock }}"
                        data-estado="{{ $estadoLote }}"
                        data-alerta="{{ $estadoLote === 'por_vencer' || $estadoLote === 'vencido' ? '1' : '0' }}"
                    >
                        <td>{{ $lote->id_lote }}</td>
                        <td>{{ $lote->medicamento }}</td>
                        <td>{{ $lote->stock }}</td>
                        <td>Q {{ number_format((float) $lote->precio_venta, 2) }}</td>
                        <td>{{ $lote->fecha_vencimiento ?? 'Sin fecha' }}</td>
                        <td>
                            @if ($estadoLote === 'vigente')
                                <span class="pill pill-ok">Vigente</span>
                            @elseif ($estadoLote === 'vencido')
                                <span class="pill pill-expired">Vencido</span>
                            @elseif ($estadoLote === 'por_vencer')
                                <span class="pill pill-soon">Por vencer</span>
                            @else
                                <span class="pill pill-empty">Sin fecha</span>
                            @endif
                        </td>
                        <td>
                            @if ($estadoLote === 'vencido')
                                <span class="pill pill-expired">Vencido</span>
                            @elseif ($estadoLote === 'por_vencer')
                                <span class="pill pill-soon">Por vencer ({{ $diasLote }} días)</span>
                            @endif
                            @if ($estadoLote !== 'por_vencer' && $estadoLote !== 'vencido')
                                <span class="pill pill-empty">Sin alerta</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">No hay lotes disponibles actualmente.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-pagination-frame">
        <div id="lotes-pagination" class="lotes-pagination"></div>
    </div>
</section>

<script>
(() => {
    const nav = document.getElementById('farmNav');
    const buttons = Array.from(nav.querySelectorAll('button[data-target]'));
    const sections = Array.from(document.querySelectorAll('.farm-section'));

    function activate(targetId) {
        buttons.forEach((btn) => btn.classList.toggle('active', btn.dataset.target === targetId));
        sections.forEach((sec) => sec.classList.toggle('active', sec.id === targetId));
    }

    buttons.forEach((btn) => {
        btn.addEventListener('click', () => activate(btn.dataset.target));
    });
    activate(nav.dataset.initialSection || 'sec-catalogo');

    document.querySelectorAll('.farm-section form').forEach((form) => {
        form.addEventListener('submit', () => {
            const active = document.querySelector('.farm-section.active');
            if (!active) return;
            let hidden = form.querySelector('input[name="_active_section"]');
            if (!hidden) {
                hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = '_active_section';
                form.appendChild(hidden);
            }
            hidden.value = active.id;
        });
    });

    const selCategoria = document.getElementById('id_categoria');
    const boxCategoria = document.getElementById('box_nueva_categoria');
    if (selCategoria && boxCategoria) {
        const toggleCategoria = () => boxCategoria.classList.toggle('show', selCategoria.value === '__nueva__');
        selCategoria.addEventListener('change', toggleCategoria);
        toggleCategoria();
    }

    document.querySelectorAll('.js-toggle-edit').forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.dataset.target;
            const row = targetId ? document.getElementById(targetId) : null;
            if (!row) return;
            row.classList.toggle('show');
        });
    });

    const selProveedor = document.getElementById('id_proveedor');
    const boxProveedor = document.getElementById('box_nuevo_proveedor');
    const toggleProveedor = () => boxProveedor.classList.toggle('show', selProveedor.value === '__nuevo__');
    selProveedor.addEventListener('change', toggleProveedor);
    toggleProveedor();

    const selPaciente = document.getElementById('id_paciente');
    const boxPaciente = document.getElementById('box_nuevo_paciente');
    const btnTogglePaciente = document.getElementById('toggle-paciente-box');
    const chkConsumidorFinal = document.getElementById('consumidor_final');
    const inConsumidorFinalHidden = document.getElementById('consumidor_final_hidden');
    const inPacNombre = document.querySelector('input[name="nuevo_paciente_nombre"]');
    const inPacApellido = document.querySelector('input[name="nuevo_paciente_apellido"]');
    const inPacTelefono = document.querySelector('input[name="nuevo_paciente_telefono"]');
    const inPacCorreo = document.querySelector('input[name="nuevo_paciente_correo"]');
    const inPacFechaNac = document.querySelector('input[name="nuevo_paciente_fecha_nacimiento"]');
    const inPacGenero = document.querySelector('select[name="nuevo_paciente_genero"]');
    const inPacNit = document.getElementById('nuevo_paciente_nit');
    const inPacDpi = document.querySelector('input[name="nuevo_paciente_dpi"]');
    const inPacDireccion = document.querySelector('input[name="nuevo_paciente_direccion"]');

    const clearPacienteFields = () => {
        if (inPacNombre) inPacNombre.value = '';
        if (inPacApellido) inPacApellido.value = '';
        if (inPacTelefono) inPacTelefono.value = '';
        if (inPacCorreo) inPacCorreo.value = '';
        if (inPacFechaNac) inPacFechaNac.value = '';
        if (inPacGenero) inPacGenero.value = '';
        if (inPacNit) inPacNit.value = '';
        if (inPacDpi) inPacDpi.value = '';
        if (inPacDireccion) inPacDireccion.value = '';
    };

    const fillPacienteFieldsFromOption = (opt) => {
        if (!opt) return;
        if (inPacNombre) inPacNombre.value = opt.dataset.nombre || '';
        if (inPacApellido) inPacApellido.value = opt.dataset.apellido || '';
        if (inPacTelefono) inPacTelefono.value = opt.dataset.telefono || '';
        if (inPacCorreo) inPacCorreo.value = opt.dataset.correo || '';
        if (inPacFechaNac) inPacFechaNac.value = opt.dataset.fechaNacimiento || '';
        if (inPacGenero) inPacGenero.value = opt.dataset.genero || '';
        if (inPacNit) inPacNit.value = opt.dataset.nit || '';
        if (inPacDpi) inPacDpi.value = opt.dataset.dpi || '';
        if (inPacDireccion) inPacDireccion.value = opt.dataset.direccion || '';
    };

    const findPacienteOptionByNitOrDpi = () => {
        const nit = ((inPacNit?.value || '').trim().toUpperCase()).replace(/[ ._]/g, '');
        const dpi = ((inPacDpi?.value || '').trim()).replace(/\s+/g, '');
        if (nit === '' && dpi === '') return null;
        return Array.from(selPaciente?.options || []).find((opt) => {
            if (!opt.value) return false;
            const optNit = String(opt.dataset.nit || '').trim().toUpperCase().replace(/[ ._]/g, '');
            const optDpi = String(opt.dataset.dpi || '').trim().replace(/\s+/g, '');
            return (nit !== '' && optNit !== '' && optNit === nit) || (dpi !== '' && optDpi !== '' && optDpi === dpi);
        }) || null;
    };

    const togglePaciente = () => {
        const esCF = !!chkConsumidorFinal?.checked;

        [inPacNombre, inPacApellido, inPacTelefono, inPacCorreo, inPacFechaNac, inPacGenero, inPacNit, inPacDpi, inPacDireccion].forEach((input) => {
            if (!input) return;
            input.disabled = esCF;
            input.required = !esCF && !selPaciente?.value && (input === inPacNombre || input === inPacApellido || input === inPacNit);
        });
        if (inConsumidorFinalHidden) {
            inConsumidorFinalHidden.value = esCF ? '1' : '0';
        }

        if (esCF) {
            if (selPaciente) selPaciente.value = '';
        }
    };
    btnTogglePaciente?.addEventListener('click', () => {
        boxPaciente.classList.toggle('show');
    });
    selPaciente.addEventListener('change', () => {
        if (selPaciente.value && chkConsumidorFinal) {
            chkConsumidorFinal.checked = false;
        }
        const opt = selPaciente.options[selPaciente.selectedIndex];
        if (opt && opt.value) {
            fillPacienteFieldsFromOption(opt);
        } else {
            clearPacienteFields();
        }
        togglePaciente();
    });
    inPacNit?.addEventListener('blur', () => {
        const opt = findPacienteOptionByNitOrDpi();
        if (opt && selPaciente) {
            selPaciente.value = opt.value;
            fillPacienteFieldsFromOption(opt);
        }
    });
    inPacDpi?.addEventListener('blur', () => {
        const opt = findPacienteOptionByNitOrDpi();
        if (opt && selPaciente) {
            selPaciente.value = opt.value;
            fillPacienteFieldsFromOption(opt);
        }
    });
    chkConsumidorFinal?.addEventListener('change', togglePaciente);
    togglePaciente();

    function bindMedicineSearch(input, select) {
        const allOptions = Array.from(select.options).map((opt) => ({ value: opt.value, text: opt.text }));
        input.addEventListener('input', () => {
            const q = input.value.trim().toLowerCase();
            const selectedValue = select.value;
            const filtered = allOptions.filter((opt, idx) => {
                if (idx === 0) return true;
                const byText = opt.text.toLowerCase().includes(q);
                const byId = opt.value.toLowerCase().includes(q);
                return q === '' || byText || byId;
            });

            select.innerHTML = '';
            filtered.forEach((opt) => {
                const option = document.createElement('option');
                option.value = opt.value;
                option.text = opt.text;
                if (opt.value === selectedValue) option.selected = true;
                select.appendChild(option);
            });

            if (select.value === '' && filtered.length > 1) {
                select.selectedIndex = 1;
            }
        });
    }

    function computeDaysTo(dateStr) {
        if (!dateStr) return null;
        const ref = new Date();
        ref.setHours(0, 0, 0, 0);
        const d = new Date(`${dateStr}T00:00:00`);
        if (Number.isNaN(d.getTime())) return null;
        return Math.floor((d.getTime() - ref.getTime()) / 86400000);
    }

    function buildMedWarning(select) {
        if (!select || !select.value) return '';
        const opt = select.options[select.selectedIndex];
        const stock = parseInt(opt?.dataset?.stock || '0', 10);
        const venc = opt?.dataset?.vencimiento || '';
        const days = computeDaysTo(venc);
        if (stock <= 0) return 'Sin stock disponible en inventario.';
        if (days !== null && days < 0) return `Stock: ${stock}. Lote próximo vencido.`;
        if (days !== null && days <= 30) return `Stock: ${stock}. Próximo vencimiento en ${days} día(s).`;
        return `Stock disponible: ${stock}.`;
    }

    function recalcCompraTotals() {
        const lineas = Array.from(document.querySelectorAll('.compra-item'));
        let total = 0;
        lineas.forEach((linea) => {
            const cantidad = parseFloat(linea.querySelector('.js-compra-cantidad')?.value || '0');
            const precio = parseFloat(linea.querySelector('.js-compra-precio')?.value || '0');
            const subtotal = (isNaN(cantidad) ? 0 : cantidad) * (isNaN(precio) ? 0 : precio);
            const out = linea.querySelector('.js-compra-total-linea');
            if (out) out.value = `Q ${subtotal.toFixed(2)}`;
            total += subtotal;
        });
        const totalGeneral = document.getElementById('compra_total_general');
        if (totalGeneral) totalGeneral.value = `Q ${total.toFixed(2)}`;
    }

    function wireCompraItem(itemEl) {
        const input = itemEl.querySelector('.js-med-search-inline-compra');
        const select = itemEl.querySelector('.js-med-select-compra');
        if (input && select) bindMedicineSearch(input, select);
        const warning = itemEl.querySelector('.js-compra-med-warning');
        const syncWarning = () => { if (warning) warning.textContent = buildMedWarning(select); };
        select?.addEventListener('change', syncWarning);
        syncWarning();

        itemEl.querySelector('.js-compra-cantidad')?.addEventListener('input', recalcCompraTotals);
        itemEl.querySelector('.js-compra-precio')?.addEventListener('input', recalcCompraTotals);
    }

    function reindexCompraItems() {
        const items = Array.from(document.querySelectorAll('.compra-item'));
        items.forEach((item, idx) => {
            item.dataset.index = String(idx);
            const select = item.querySelector('.js-med-select-compra');
            const cantidad = item.querySelector('.js-compra-cantidad');
            const precioCompra = item.querySelector('.js-compra-precio');
            const precioVenta = item.querySelector('input[name*="[precio_venta]"]');
            const venc = item.querySelector('input[name*="[fecha_vencimiento]"]');
            if (select) select.name = `items[${idx}][id_medicamento]`;
            if (cantidad) cantidad.name = `items[${idx}][cantidad]`;
            if (precioCompra) precioCompra.name = `items[${idx}][precio_compra]`;
            if (precioVenta) precioVenta.name = `items[${idx}][precio_venta]`;
            if (venc) venc.name = `items[${idx}][fecha_vencimiento]`;
        });
    }

    function appendCompraItem(prefill = null) {
        const compraItems = document.getElementById('compra-items');
        const first = compraItems?.querySelector('.compra-item');
        if (!compraItems || !first) return null;

        const clone = first.cloneNode(true);
        clone.querySelectorAll('.js-compra-error').forEach((el) => el.remove());
        const select = clone.querySelector('.js-med-select-compra');
        const cantidad = clone.querySelector('.js-compra-cantidad');
        const precioCompra = clone.querySelector('.js-compra-precio');
        const precioVenta = clone.querySelector('input[name*="[precio_venta]"]');
        const venc = clone.querySelector('input[name*="[fecha_vencimiento]"]');
        const totalLinea = clone.querySelector('.js-compra-total-linea');

        if (select) {
            select.selectedIndex = 0;
            if (prefill?.value) select.value = prefill.value;
        }
        if (cantidad) cantidad.value = prefill?.cantidad ? String(prefill.cantidad) : '';
        if (precioCompra) precioCompra.value = '';
        if (precioVenta) precioVenta.value = '';
        if (venc) venc.value = '';
        if (totalLinea) totalLinea.value = 'Q 0.00';

        const oldRemove = clone.querySelector('.js-compra-remove-wrap');
        if (oldRemove) oldRemove.remove();

        const removeWrap = document.createElement('div');
        removeWrap.className = 'js-compra-remove-wrap';
        removeWrap.style.display = 'flex';
        removeWrap.style.justifyContent = 'flex-end';
        removeWrap.style.marginTop = '6px';
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-sm';
        removeBtn.textContent = 'Quitar';
        removeBtn.addEventListener('click', () => {
            clone.remove();
            reindexCompraItems();
            recalcCompraTotals();
        });
        removeWrap.appendChild(removeBtn);
        clone.appendChild(removeWrap);

        compraItems.appendChild(clone);
        wireCompraItem(clone);
        reindexCompraItems();
        recalcCompraTotals();
        return clone;
    }

    const compraItems = document.getElementById('compra-items');
    if (compraItems) {
        const initialCompraItems = Array.from(compraItems.querySelectorAll('.compra-item'));
        initialCompraItems.forEach((item) => wireCompraItem(item));

        const addCompra = document.getElementById('add-item-compra');
        addCompra.addEventListener('click', () => {
            appendCompraItem();
        });
    }

    const compraSearchInput = document.getElementById('compra-busqueda-global');
    const compraSuggestions = document.getElementById('compra-sugerencias');
    const compraEmpty = document.getElementById('compra-sugerencias-empty');
    if (compraSearchInput && compraSuggestions && compraEmpty) {
        const firstSelectCompra = document.querySelector('.js-med-select-compra');
        const medDatasetCompra = firstSelectCompra
            ? Array.from(firstSelectCompra.options)
                .filter((opt, idx) => idx > 0 && opt.value)
                .map((opt) => ({ value: opt.value, text: opt.text }))
            : [];

        const applyCompraSuggestion = (item) => {
            const firstEmpty = Array.from(document.querySelectorAll('.compra-item .js-med-select-compra'))
                .find((sel) => !sel.value);
            if (firstEmpty) {
                firstEmpty.value = item.value;
            } else {
                appendCompraItem({ value: item.value });
            }
            compraSearchInput.value = '';
            renderCompraSuggestions();
        };

        const renderCompraSuggestions = () => {
            const q = (compraSearchInput.value || '').trim().toLowerCase();
            const results = medDatasetCompra
                .filter((item) => q === '' || item.text.toLowerCase().includes(q) || item.value.toLowerCase().includes(q))
                .slice(0, 5);

            compraSuggestions.innerHTML = '';
            results.forEach((item) => {
                const li = document.createElement('li');
                li.textContent = item.text;
                li.addEventListener('click', () => applyCompraSuggestion(item));
                compraSuggestions.appendChild(li);
            });
            compraEmpty.style.display = results.length ? 'none' : 'block';
        };

        compraSearchInput.addEventListener('input', renderCompraSuggestions);
        renderCompraSuggestions();
    }

    function recalcVentaTotals() {
        const lineas = Array.from(document.querySelectorAll('.sale-item'));
        let total = 0;
        lineas.forEach((linea) => {
            const select = linea.querySelector('.js-med-select');
            const cantidad = parseFloat(linea.querySelector('.js-venta-cantidad')?.value || '0');
            const selectedOption = select?.options?.[select.selectedIndex];
            const precio = parseFloat(selectedOption?.dataset?.precio || '0');
            const subtotal = (isNaN(cantidad) ? 0 : cantidad) * (isNaN(precio) ? 0 : precio);
            const out = linea.querySelector('.js-venta-total-linea');
            if (out) out.value = `Q ${subtotal.toFixed(2)}`;
            total += subtotal;
        });
        const totalGeneral = document.getElementById('venta_total_general');
        if (totalGeneral) totalGeneral.value = `Q ${total.toFixed(2)}`;
    }

    function wireVentaItem(itemEl) {
        const select = itemEl.querySelector('.js-med-select');
        const cantidad = itemEl.querySelector('.js-venta-cantidad');
        const warning = itemEl.querySelector('.js-venta-med-warning');
        const syncWarning = () => { if (warning) warning.textContent = buildMedWarning(select); };
        if (select) {
            select.addEventListener('change', recalcVentaTotals);
            select.addEventListener('change', syncWarning);
        }
        if (cantidad) {
            cantidad.addEventListener('input', recalcVentaTotals);
        }
        syncWarning();
    }

    function reindexVentaItems() {
        const items = Array.from(document.querySelectorAll('.sale-item'));
        items.forEach((item, idx) => {
            item.dataset.index = String(idx);
            const select = item.querySelector('.js-med-select');
            const qty = item.querySelector('.js-venta-cantidad');
            if (select) select.name = `items[${idx}][id_medicamento]`;
            if (qty) qty.name = `items[${idx}][cantidad]`;
        });
    }

    function ensureVentaRemoveButton(itemEl) {
        if (!itemEl) return;
        let removeWrap = itemEl.querySelector('.js-venta-remove-wrap');
        if (!removeWrap) {
            removeWrap = document.createElement('div');
            removeWrap.className = 'js-venta-remove-wrap';
            removeWrap.style.display = 'flex';
            removeWrap.style.justifyContent = 'flex-end';
            removeWrap.style.marginTop = '6px';
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-sm';
            removeBtn.textContent = 'Quitar';
            removeBtn.addEventListener('click', () => {
                itemEl.remove();
                if (document.querySelectorAll('.sale-item').length === 0) {
                    appendVentaItem();
                }
                reindexVentaItems();
                recalcVentaTotals();
            });
            removeWrap.appendChild(removeBtn);
            itemEl.appendChild(removeWrap);
        }
    }

    let ventaItemTemplate = null;

    function appendVentaItem(prefill = null) {
        const ventaItems = document.getElementById('venta-items');
        const first = ventaItems?.querySelector('.sale-item');
        if (!ventaItems) return null;
        if (!first && !ventaItemTemplate) return null;

        const clone = first ? first.cloneNode(true) : ventaItemTemplate.cloneNode(true);
        const select = clone.querySelector('.js-med-select');
        const qty = clone.querySelector('.js-venta-cantidad');
        const subtotalOut = clone.querySelector('.js-venta-total-linea');
        clone.querySelectorAll('.js-venta-error').forEach((el) => el.remove());

        if (select) {
            select.selectedIndex = 0;
            if (prefill?.value) select.value = prefill.value;
        }
        if (qty) qty.value = prefill?.cantidad ? String(prefill.cantidad) : '1';
        if (subtotalOut) subtotalOut.value = 'Q 0.00';

        const oldRemove = clone.querySelector('.js-venta-remove-wrap');
        if (oldRemove) oldRemove.remove();

        ensureVentaRemoveButton(clone);

        ventaItems.appendChild(clone);
        wireVentaItem(clone);
        reindexVentaItems();
        recalcVentaTotals();
        return clone;
    }

    const ventaItems = document.getElementById('venta-items');
    if (ventaItems) {
        const initialItems = Array.from(ventaItems.querySelectorAll('.sale-item'));
        if (initialItems.length > 0) {
            ventaItemTemplate = initialItems[0].cloneNode(true);
        }
        initialItems.forEach((item) => {
            wireVentaItem(item);
            ensureVentaRemoveButton(item);
        });

        const addBtn = document.getElementById('add-item-venta');
        addBtn.addEventListener('click', () => {
            appendVentaItem();
        });
        recalcVentaTotals();
    }

    const ventaForm = document.getElementById('venta-form');
    const ventaClientAlert = document.getElementById('venta-alerta-cliente');
    if (ventaForm) {
        ventaForm.addEventListener('submit', (event) => {
            const errores = [];
            const lineasVenta = Array.from(document.querySelectorAll('.sale-item'));

            if (lineasVenta.length === 0) {
                errores.push('Debe agregar al menos un medicamento.');
            }

            let hayMedicamentoValido = false;
            lineasVenta.forEach((linea, idx) => {
                const select = linea.querySelector('.js-med-select');
                const qty = linea.querySelector('.js-venta-cantidad');
                const selected = (select?.value || '').trim();
                const cantidad = parseInt(qty?.value || '0', 10);

                if (selected !== '') {
                    hayMedicamentoValido = true;
                } else {
                    errores.push(`L\u00ednea ${idx + 1}: seleccione un medicamento.`);
                }

                if (!Number.isInteger(cantidad) || cantidad < 1) {
                    errores.push(`L\u00ednea ${idx + 1}: la cantidad debe ser mayor o igual a 1.`);
                }
            });

            if (!hayMedicamentoValido) {
                errores.push('Debe seleccionar al menos un medicamento v\u00e1lido.');
            }

            const fechaVenta = ventaForm.querySelector('input[name="fecha"]');
            if (!fechaVenta?.value) {
                errores.push('Debe ingresar la fecha de venta.');
            }

            const esCF = !!chkConsumidorFinal?.checked;
            if (!esCF && !selPaciente?.value) {
                const nombre = (inPacNombre?.value || '').trim();
                const apellido = (inPacApellido?.value || '').trim();
                let nit = (inPacNit?.value || '').trim().toUpperCase();
                nit = nit.replace(/[ ._]/g, '');
                if (nit === 'C/F') nit = 'CF';
                if (inPacNit) inPacNit.value = nit;
                if (nit === '') errores.push('Paciente: el NIT es obligatorio.');
                if (nit !== 'CF') {
                    if (nombre === '') errores.push('Paciente: el nombre es obligatorio.');
                    if (apellido === '') errores.push('Paciente: el apellido es obligatorio.');
                }
            }

            if (errores.length > 0) {
                event.preventDefault();
                if (ventaClientAlert) {
                    ventaClientAlert.style.display = 'block';
                    ventaClientAlert.innerHTML = `<strong>Revisa los datos del formulario:</strong><ul style="margin:8px 0 0 18px;">${errores.map((e) => `<li>${e}</li>`).join('')}</ul>`;
                }
                window.alert(`No se pudo guardar la venta:\n- ${errores.join('\n- ')}`);
            } else if (ventaClientAlert) {
                ventaClientAlert.style.display = 'none';
                ventaClientAlert.innerHTML = '';
            }
        });
    }

    const globalSearchInput = document.getElementById('venta-busqueda-global');
    const globalSuggestions = document.getElementById('venta-sugerencias');
    const globalEmpty = document.getElementById('venta-sugerencias-empty');
    if (globalSearchInput && globalSuggestions && globalEmpty) {
        const firstSelect = document.querySelector('.js-med-select');
        const medDataset = firstSelect
            ? Array.from(firstSelect.options)
                .filter((opt, idx) => idx > 0 && opt.value)
                .map((opt) => ({ value: opt.value, text: opt.text }))
            : [];

        const applySuggestion = (item) => {
            const firstEmpty = Array.from(document.querySelectorAll('.sale-item .js-med-select'))
                .find((sel) => !sel.value);

            let select = null;
            if (firstEmpty) {
                firstEmpty.value = item.value;
                const row = firstEmpty.closest('.sale-item');
                const qty = row?.querySelector('.js-venta-cantidad');
                if (qty && (!qty.value || qty.value === '0')) qty.value = '1';
                select = firstEmpty;
            } else {
                const nuevaLinea = appendVentaItem({ value: item.value, cantidad: 1 });
                select = nuevaLinea?.querySelector('.js-med-select') || null;
            }

            if (select) select.dispatchEvent(new Event('change', { bubbles: true }));
            globalSearchInput.value = '';
            renderSuggestions();
        };

        const renderSuggestions = () => {
            const q = (globalSearchInput.value || '').trim().toLowerCase();
            const results = medDataset
                .filter((item) => q === '' || item.text.toLowerCase().includes(q) || item.value.toLowerCase().includes(q))
                .slice(0, 5);

            globalSuggestions.innerHTML = '';
            results.forEach((item) => {
                const li = document.createElement('li');
                li.textContent = item.text;
                li.addEventListener('click', () => applySuggestion(item));
                globalSuggestions.appendChild(li);
            });
            globalEmpty.style.display = results.length ? 'none' : 'block';
        };

        globalSearchInput.addEventListener('input', renderSuggestions);
        renderSuggestions();
    }

    recalcCompraTotals();

    const selDevolucionLinea = document.getElementById('devolucion_linea');
    const inVenta = document.getElementById('devolucion_id_venta');
    const inLote = document.getElementById('devolucion_id_lote');
    const inDisponible = document.getElementById('devolucion_disponible');
    const inCantidad = document.getElementById('devolucion_cantidad');

    if (selDevolucionLinea && inVenta && inLote && inDisponible && inCantidad) {
        const syncDevolucion = () => {
            const val = selDevolucionLinea.value || '';
            if (!val.includes('|')) {
                inVenta.value = '';
                inLote.value = '';
                inDisponible.value = '0';
                inCantidad.removeAttribute('max');
                return;
            }

            const [venta, lote] = val.split('|');
            inVenta.value = venta;
            inLote.value = lote;
            const opt = selDevolucionLinea.options[selDevolucionLinea.selectedIndex];
            const disponible = parseInt(opt?.dataset?.disponible || '0', 10);
            inDisponible.value = String(disponible);
            inCantidad.max = String(Math.max(1, disponible));
            if (parseInt(inCantidad.value || '0', 10) > disponible) {
                inCantidad.value = String(disponible);
            }
        };

        selDevolucionLinea.addEventListener('change', syncDevolucion);
        syncDevolucion();
    }

    const selDevolucionCompraLinea = document.getElementById('devolucion_compra_linea');
    const inCompra = document.getElementById('devolucion_compra_id_compra');
    const inLoteCompra = document.getElementById('devolucion_compra_id_lote');
    const inDisponibleCompra = document.getElementById('devolucion_compra_disponible');
    const inCantidadCompra = document.getElementById('devolucion_compra_cantidad');

    if (selDevolucionCompraLinea && inCompra && inLoteCompra && inDisponibleCompra && inCantidadCompra) {
        const syncDevolucionCompra = () => {
            const val = selDevolucionCompraLinea.value || '';
            if (!val.includes('|')) {
                inCompra.value = '';
                inLoteCompra.value = '';
                inDisponibleCompra.value = '0';
                inCantidadCompra.max = '';
                return;
            }

            const [compra, lote] = val.split('|');
            inCompra.value = compra;
            inLoteCompra.value = lote;
            const opt = selDevolucionCompraLinea.options[selDevolucionCompraLinea.selectedIndex];
            const disponible = parseInt(opt?.dataset?.disponible || '0', 10);
            inDisponibleCompra.value = String(disponible);
            inCantidadCompra.max = String(Math.max(1, disponible));
            if (!inCantidadCompra.value || parseInt(inCantidadCompra.value, 10) < 1) {
                inCantidadCompra.value = '1';
            }
        };

        selDevolucionCompraLinea.addEventListener('change', syncDevolucionCompra);
        syncDevolucionCompra();
    }

    const fltLoteMed = document.getElementById('flt-lote-med');
    const fltLoteVDesde = document.getElementById('flt-lote-venc-desde');
    const fltLoteVHasta = document.getElementById('flt-lote-venc-hasta');
    const fltLoteStockMin = document.getElementById('flt-lote-stock-min');
    const fltLoteEstado = document.getElementById('flt-lote-estado');
    const fltLoteClear = document.getElementById('flt-lote-clear');
    const lotesTbody = document.getElementById('lotes-tbody');
    const lotesCount = document.getElementById('lotes-count');
    const lotesPagination = document.getElementById('lotes-pagination');
    const fltMedCodigo = document.getElementById('flt-med-codigo');
    const fltMedNombre = document.getElementById('flt-med-nombre');
    const fltMedCategoria = document.getElementById('flt-med-categoria');
    const fltMedEstado = document.getElementById('flt-med-estado');
    const fltMedClear = document.getElementById('flt-med-clear');
    const medsTbody = document.getElementById('meds-tbody');
    const medsCount = document.getElementById('meds-count');
    const medsPagination = document.getElementById('meds-pagination');
    const invTbody = document.getElementById('inv-tbody');
    const invCount = document.getElementById('inv-count');
    const invPagination = document.getElementById('inv-pagination');
    const fltInvId = document.getElementById('flt-inv-id');
    const fltInvMed = document.getElementById('flt-inv-med');
    const fltInvLotesMin = document.getElementById('flt-inv-lotes-min');
    const fltInvStockMin = document.getElementById('flt-inv-stock-min');
    const fltInvClear = document.getElementById('flt-inv-clear');
    const fltDevVenta = document.getElementById('flt-dev-venta');
    const fltDevPac = document.getElementById('flt-dev-pac');
    const fltDevFechaDesde = document.getElementById('flt-dev-fecha-desde');
    const fltDevFechaHasta = document.getElementById('flt-dev-fecha-hasta');
    const fltDevClear = document.getElementById('flt-dev-clear');
    const devTbody = document.getElementById('dev-tbody');
    const devCount = document.getElementById('dev-count');
    const devPagination = document.getElementById('dev-pagination');
    const fltDevcCompra = document.getElementById('flt-devc-compra');
    const fltDevcProv = document.getElementById('flt-devc-prov');
    const fltDevcFechaDesde = document.getElementById('flt-devc-fecha-desde');
    const fltDevcFechaHasta = document.getElementById('flt-devc-fecha-hasta');
    const fltDevcClear = document.getElementById('flt-devc-clear');
    const devcTbody = document.getElementById('devc-tbody');
    const devcCount = document.getElementById('devc-count');
    const devcPagination = document.getElementById('devc-pagination');

    if (medsTbody && medsCount && medsPagination) {
        const rows = Array.from(medsTbody.querySelectorAll('tr[data-nombre]'));
        const pageSize = 8;
        let currentPage = 1;

        const renderPagination = (totalPages) => {
            medsPagination.innerHTML = '';
            if (totalPages <= 1) return;
            for (let page = 1; page <= totalPages; page += 1) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `lotes-page-btn${page === currentPage ? ' active' : ''}`;
                btn.textContent = String(page);
                btn.addEventListener('click', () => {
                    currentPage = page;
                    applyMedsFilters();
                });
                medsPagination.appendChild(btn);
            }
        };

        const applyMedsFilters = () => {
            const qCodigo = (fltMedCodigo?.value || '').trim().toLowerCase();
            const qNombre = (fltMedNombre?.value || '').trim().toLowerCase();
            const qCategoria = (fltMedCategoria?.value || '').trim().toLowerCase();
            const estado = fltMedEstado?.value || '';
            const filteredRows = [];

            rows.forEach((row) => {
                const codigo = row.dataset.codigo || '';
                const nombre = row.dataset.nombre || '';
                const categoria = row.dataset.categoria || '';
                const rowEstado = row.dataset.estado || '';
                const editTarget = row.dataset.editTarget || '';
                const editRow = editTarget ? document.getElementById(editTarget) : null;

                const okCodigo = qCodigo === '' || codigo.includes(qCodigo);
                const okNombre = qNombre === '' || nombre.includes(qNombre);
                const okCategoria = qCategoria === '' || categoria.includes(qCategoria);
                const okEstado = estado === '' || rowEstado === estado;
                const show = okCodigo && okNombre && okCategoria && okEstado;

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
            medsCount.textContent = `Mostrando ${showing} de ${totalFiltered} medicamentos filtrados.`;
            renderPagination(totalPages);
        };

        [fltMedCodigo, fltMedNombre, fltMedCategoria, fltMedEstado].forEach((el) => {
            el?.addEventListener('input', () => {
                currentPage = 1;
                applyMedsFilters();
            });
            el?.addEventListener('change', () => {
                currentPage = 1;
                applyMedsFilters();
            });
        });
        fltMedClear?.addEventListener('click', () => {
            if (fltMedCodigo) fltMedCodigo.value = '';
            if (fltMedNombre) fltMedNombre.value = '';
            if (fltMedCategoria) fltMedCategoria.value = '';
            if (fltMedEstado) fltMedEstado.value = '';
            currentPage = 1;
            applyMedsFilters();
        });
        applyMedsFilters();
    }

    if (lotesTbody && lotesCount && lotesPagination) {
        const rows = Array.from(lotesTbody.querySelectorAll('tr[data-med]'));
        const pageSize = 8;
        let currentPage = 1;

        const renderPagination = (totalPages) => {
            lotesPagination.innerHTML = '';
            if (totalPages <= 1) return;

            for (let page = 1; page <= totalPages; page += 1) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `lotes-page-btn${page === currentPage ? ' active' : ''}`;
                btn.textContent = String(page);
                btn.addEventListener('click', () => {
                    currentPage = page;
                    applyLotesFilters(false);
                });
                lotesPagination.appendChild(btn);
            }
        };

        const applyLotesFilters = () => {
            const qMed = (fltLoteMed?.value || '').trim().toLowerCase();
            const vDesde = fltLoteVDesde?.value || '';
            const vHasta = fltLoteVHasta?.value || '';
            const stockMin = parseInt(fltLoteStockMin?.value || '0', 10);
            const estado = fltLoteEstado?.value || '';
            const filteredRows = [];

            rows.forEach((row) => {
                const med = row.dataset.med || '';
                const venc = row.dataset.venc || '';
                const stock = parseInt(row.dataset.stock || '0', 10);
                const est = row.dataset.estado || '';

                const okMed = qMed === '' || med.includes(qMed);
                const okDesde = vDesde === '' || (venc !== '' && venc >= vDesde);
                const okHasta = vHasta === '' || (venc !== '' && venc <= vHasta);
                const okStock = !Number.isFinite(stockMin) || stock >= stockMin;
                const okEstado = estado === '' || est === estado;

                const show = okMed && okDesde && okHasta && okStock && okEstado;
                if (show) filteredRows.push(row);
                row.style.display = 'none';
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
            lotesCount.textContent = `Mostrando ${showing} de ${totalFiltered} lotes filtrados.`;
            renderPagination(totalPages);
        };

        [fltLoteMed, fltLoteVDesde, fltLoteVHasta, fltLoteStockMin, fltLoteEstado].forEach((el) => {
            el?.addEventListener('input', () => {
                currentPage = 1;
                applyLotesFilters();
            });
            el?.addEventListener('change', () => {
                currentPage = 1;
                applyLotesFilters();
            });
        });
        fltLoteClear?.addEventListener('click', () => {
            if (fltLoteMed) fltLoteMed.value = '';
            if (fltLoteVDesde) fltLoteVDesde.value = '';
            if (fltLoteVHasta) fltLoteVHasta.value = '';
            if (fltLoteStockMin) fltLoteStockMin.value = '';
            if (fltLoteEstado) fltLoteEstado.value = '';
            currentPage = 1;
            applyLotesFilters();
        });
        applyLotesFilters();
    }

    if (devTbody && devCount && devPagination) {
        const rows = Array.from(devTbody.querySelectorAll('tr[data-venta]'));
        const pageSize = 8;
        let currentPage = 1;

        const renderPagination = (totalPages) => {
            devPagination.innerHTML = '';
            if (totalPages <= 1) return;

            for (let page = 1; page <= totalPages; page += 1) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `lotes-page-btn${page === currentPage ? ' active' : ''}`;
                btn.textContent = String(page);
                btn.addEventListener('click', () => {
                    currentPage = page;
                    applyDevFilters();
                });
                devPagination.appendChild(btn);
            }
        };

        const applyDevFilters = () => {
            const qVenta = (fltDevVenta?.value || '').trim().toLowerCase();
            const qPac = (fltDevPac?.value || '').trim().toLowerCase();
            const fDesde = fltDevFechaDesde?.value || '';
            const fHasta = fltDevFechaHasta?.value || '';
            const filteredRows = [];

            rows.forEach((row) => {
                const venta = (row.dataset.venta || '').toLowerCase();
                const pac = row.dataset.pac || '';
                const fecha = row.dataset.fecha || '';

                const okVenta = qVenta === '' || venta.includes(qVenta);
                const okPac = qPac === '' || pac.includes(qPac);
                const okDesde = fDesde === '' || (fecha !== '' && fecha >= fDesde);
                const okHasta = fHasta === '' || (fecha !== '' && fecha <= fHasta);
                const show = okVenta && okPac && okDesde && okHasta;

                if (show) filteredRows.push(row);
                row.style.display = 'none';
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
            devCount.textContent = `Mostrando ${showing} de ${totalFiltered} líneas de devolución filtradas.`;
            renderPagination(totalPages);
        };

        [fltDevVenta, fltDevPac, fltDevFechaDesde, fltDevFechaHasta].forEach((el) => {
            el?.addEventListener('input', () => {
                currentPage = 1;
                applyDevFilters();
            });
            el?.addEventListener('change', () => {
                currentPage = 1;
                applyDevFilters();
            });
        });

        fltDevClear?.addEventListener('click', () => {
            if (fltDevVenta) fltDevVenta.value = '';
            if (fltDevPac) fltDevPac.value = '';
            if (fltDevFechaDesde) fltDevFechaDesde.value = '';
            if (fltDevFechaHasta) fltDevFechaHasta.value = '';
            currentPage = 1;
            applyDevFilters();
        });

        applyDevFilters();
    }

    if (devcTbody && devcCount && devcPagination) {
        const rows = Array.from(devcTbody.querySelectorAll('tr[data-compra]'));
        const pageSize = 8;
        let currentPage = 1;

        const renderPagination = (totalPages) => {
            devcPagination.innerHTML = '';
            if (totalPages <= 1) return;

            for (let page = 1; page <= totalPages; page += 1) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `lotes-page-btn${page === currentPage ? ' active' : ''}`;
                btn.textContent = String(page);
                btn.addEventListener('click', () => {
                    currentPage = page;
                    applyDevcFilters();
                });
                devcPagination.appendChild(btn);
            }
        };

        const applyDevcFilters = () => {
            const qCompra = (fltDevcCompra?.value || '').trim().toLowerCase();
            const qProv = (fltDevcProv?.value || '').trim().toLowerCase();
            const fDesde = fltDevcFechaDesde?.value || '';
            const fHasta = fltDevcFechaHasta?.value || '';
            const filteredRows = [];

            rows.forEach((row) => {
                const compra = (row.dataset.compra || '').toLowerCase();
                const prov = row.dataset.prov || '';
                const fecha = row.dataset.fecha || '';

                const okCompra = qCompra === '' || compra.includes(qCompra);
                const okProv = qProv === '' || prov.includes(qProv);
                const okDesde = fDesde === '' || (fecha !== '' && fecha >= fDesde);
                const okHasta = fHasta === '' || (fecha !== '' && fecha <= fHasta);
                const show = okCompra && okProv && okDesde && okHasta;

                if (show) filteredRows.push(row);
                row.style.display = 'none';
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
            devcCount.textContent = `Mostrando ${showing} de ${totalFiltered} líneas de devolución filtradas.`;
            renderPagination(totalPages);
        };

        [fltDevcCompra, fltDevcProv, fltDevcFechaDesde, fltDevcFechaHasta].forEach((el) => {
            el?.addEventListener('input', () => {
                currentPage = 1;
                applyDevcFilters();
            });
            el?.addEventListener('change', () => {
                currentPage = 1;
                applyDevcFilters();
            });
        });

        fltDevcClear?.addEventListener('click', () => {
            if (fltDevcCompra) fltDevcCompra.value = '';
            if (fltDevcProv) fltDevcProv.value = '';
            if (fltDevcFechaDesde) fltDevcFechaDesde.value = '';
            if (fltDevcFechaHasta) fltDevcFechaHasta.value = '';
            currentPage = 1;
            applyDevcFilters();
        });

        applyDevcFilters();
    }

    if (invTbody && invCount && invPagination) {
        const rows = Array.from(invTbody.querySelectorAll('tr[data-med]'));
        const pageSize = 8;
        let currentPage = 1;

        const renderPagination = (totalPages) => {
            invPagination.innerHTML = '';
            if (totalPages <= 1) return;

            for (let page = 1; page <= totalPages; page += 1) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `lotes-page-btn${page === currentPage ? ' active' : ''}`;
                btn.textContent = String(page);
                btn.addEventListener('click', () => {
                    currentPage = page;
                    applyInventarioPagination();
                });
                invPagination.appendChild(btn);
            }
        };

        const applyInventarioPagination = () => {
            const qId = (fltInvId?.value || '').trim().toLowerCase();
            const qMed = (fltInvMed?.value || '').trim().toLowerCase();
            const lotesMin = parseInt(fltInvLotesMin?.value || '0', 10);
            const stockMin = parseInt(fltInvStockMin?.value || '0', 10);
            const filteredRows = [];

            rows.forEach((row) => {
                const id = (row.dataset.id || '').toLowerCase();
                const med = row.dataset.med || '';
                const lotes = parseInt(row.dataset.lotes || '0', 10);
                const stock = parseInt(row.dataset.stock || '0', 10);

                const okId = qId === '' || id.includes(qId);
                const okMed = qMed === '' || med.includes(qMed);
                const okLotes = !Number.isFinite(lotesMin) || lotes >= lotesMin;
                const okStock = !Number.isFinite(stockMin) || stock >= stockMin;
                const show = okId && okMed && okLotes && okStock;

                if (show) filteredRows.push(row);
                row.style.display = 'none';
            });

            const totalRows = filteredRows.length;
            const totalPages = Math.max(1, Math.ceil(totalRows / pageSize));
            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const start = (currentPage - 1) * pageSize;
            const end = start + pageSize;
            filteredRows.slice(start, end).forEach((row) => {
                row.style.display = '';
            });

            const showing = totalRows === 0 ? 0 : Math.min(pageSize, totalRows - start);
            invCount.textContent = `Mostrando ${showing} de ${totalRows} medicamentos.`;
            renderPagination(totalPages);
        };

        [fltInvId, fltInvMed, fltInvLotesMin, fltInvStockMin].forEach((el) => {
            el?.addEventListener('input', () => {
                currentPage = 1;
                applyInventarioPagination();
            });
            el?.addEventListener('change', () => {
                currentPage = 1;
                applyInventarioPagination();
            });
        });

        fltInvClear?.addEventListener('click', () => {
            if (fltInvId) fltInvId.value = '';
            if (fltInvMed) fltInvMed.value = '';
            if (fltInvLotesMin) fltInvLotesMin.value = '';
            if (fltInvStockMin) fltInvStockMin.value = '';
            currentPage = 1;
            applyInventarioPagination();
        });

        applyInventarioPagination();
    }

    const lowercaseSelectors = [
        'input[name="concentracion_unidad_otra"]',
        'input[name="via_administracion_otra"]',
        'input[name^="concentracion_unidad_otra"]',
        'input[name^="via_administracion_otra"]',
    ];

    lowercaseSelectors.forEach((selector) => {
        document.querySelectorAll(selector).forEach((input) => {
            input.addEventListener('input', () => {
                input.value = (input.value || '').toLowerCase();
            });
        });
    });
})();
</script>
@endsection
