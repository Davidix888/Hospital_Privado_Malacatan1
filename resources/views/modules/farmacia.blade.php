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
        background: linear-gradient(112deg, #0f2e53, #1f4f86);
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
    .table-wrap { overflow-x: auto; overflow-y: hidden; border: 1px solid #d3e0ef; border-radius: 12px; background: #fff; max-width: 100%; }
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
        top: 12px;
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

    @media (max-width: 920px) {
        .farm-grid,
        .catalog-grid,
        .ventas-grid,
        .row-2 { grid-template-columns: 1fr; }
        .lotes-filters { grid-template-columns: 1fr; }
        .lotes-filter-actions { justify-content: stretch; }
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
        <p class="muted">Registra catálogo, compras y ventas. El inventario se descuenta por lote con criterio FEFO. El precio de lote se maneja por unidad.</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-dark">Volver al panel</a>
</div>

@if (session('status'))
    <div class="alert ok">{{ session('status') }}</div>
@endif

<div class="farm-nav" id="farmNav" data-initial-section="{{ $activeSection }}">
    <button type="button" data-target="sec-catalogo" class="{{ $activeSection === 'sec-catalogo' ? 'active' : '' }}">Catálogo</button>
    <button type="button" data-target="sec-compras" class="{{ $activeSection === 'sec-compras' ? 'active' : '' }}">Compras</button>
    <button type="button" data-target="sec-ventas" class="{{ $activeSection === 'sec-ventas' ? 'active' : '' }}">Ventas</button>
    <button type="button" data-target="sec-devoluciones" class="{{ $activeSection === 'sec-devoluciones' ? 'active' : '' }}">Devoluciones</button>
    <button type="button" data-target="sec-inventario" class="{{ $activeSection === 'sec-inventario' ? 'active' : '' }}">Inventario</button>
    <button type="button" data-target="sec-lotes" class="{{ $activeSection === 'sec-lotes' ? 'active' : '' }}">Lotes</button>
</div>

<section id="sec-catalogo" class="farm-section {{ $activeSection === 'sec-catalogo' ? 'active' : '' }}">
    <div class="catalog-grid">
        <div class="card farm-card form-card">
                <h2>Registrar medicamento</h2>
                <p>Ingresa los datos clínicos y administrativos básicos del medicamento.</p>
                <form method="POST" action="{{ route('farmacia.medicamentos.store') }}">
                    @csrf

                    <div class="field">
                        <label>Nombre del medicamento</label>
                        <input class="form-input" name="nombre" required placeholder="Ej: Aspirina">
                    </div>

                    <div class="field">
                        <label>Categoría</label>
                        <select class="form-input" id="id_categoria" name="id_categoria">
                            <option value="">Sin categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre_categoria }}</option>
                            @endforeach
                            <option value="__nueva__">+ Registrar categoría nueva</option>
                        </select>
                        <div id="box_nueva_categoria" class="inline-box">
                            <div class="field" style="margin:0;">
                                <label>Nombre de categoría nueva</label>
                                <input class="form-input" name="nueva_categoria" placeholder="Ej: Antiinflamatorio">
                            </div>
                        </div>
                    </div>

                    <div class="row-2">
                        <div class="field">
                            <label>Presentación</label>
                            <input class="form-input" name="presentacion" placeholder="Tableta, jarabe, ampolla...">
                        </div>
                        <div class="field">
                            <label>Concentración</label>
                            <input class="form-input" name="concentracion" placeholder="500 mg, 250 mg/5 ml...">
                        </div>
                    </div>

                    <div class="field">
                        <label>Vía de administración</label>
                        <input class="form-input" name="via_administracion" placeholder="Oral, intramuscular, intravenosa...">
                    </div>

                    <div class="field">
                        <label>Código interno</label>
                        <input class="form-input" value="Se genera automáticamente: ID-00000" disabled>
                    </div>

                    <div class="field">
                        <label>Descripción (opcional)</label>
                        <input class="form-input" name="descripcion" placeholder="Observaciones relevantes del medicamento">
                    </div>

                    <button class="btn" type="submit">Guardar medicamento</button>
                </form>
        </div>

        <div>
            <h2 class="section-title" style="margin-top:0;">Medicamentos registrados</h2>
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
                    <tbody>
                        @forelse ($catalogoMedicamentos as $med)
                            <tr>
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
        </div>
    </div>
</section>

<section id="sec-compras" class="farm-section {{ $activeSection === 'sec-compras' ? 'active' : '' }}">
    <div class="ventas-grid">
        <div class="card farm-card form-card ventas-form-card">
            <h2>Registrar compra</h2>
            <p>Busca por ID, código o nombre. Puedes agregar múltiples medicamentos en una sola compra.</p>
            <form method="POST" action="{{ route('farmacia.compras.store') }}">
                @csrf
                <div id="compra-items">
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
                                    <div class="alert js-compra-error" style="margin-top:6px;">{{ $message }}</div>
                                @enderror
                                <p class="muted js-compra-med-warning" style="font-size:12px;margin-top:6px;"></p>
                            </div>
                            <div class="field">
                                <label>Cantidad</label>
                                <input class="form-input js-compra-cantidad" type="number" min="1" name="items[{{ $idx }}][cantidad]" value="{{ $oldItem['cantidad'] ?? '' }}" required>
                                @error("items.$idx.cantidad")
                                    <div class="alert js-compra-error" style="margin-top:6px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row-2">
                            <div class="field">
                                <label>Fecha de vencimiento</label>
                                <input class="form-input" type="date" name="items[{{ $idx }}][fecha_vencimiento]" value="{{ $oldItem['fecha_vencimiento'] ?? '' }}" required>
                                @error("items.$idx.fecha_vencimiento")
                                    <div class="alert js-compra-error" style="margin-top:6px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="field">
                                <label>Precio de compra unitario</label>
                                <input class="form-input js-compra-precio" type="number" step="0.01" min="0" name="items[{{ $idx }}][precio_compra]" value="{{ $oldItem['precio_compra'] ?? '' }}" required>
                                @error("items.$idx.precio_compra")
                                    <div class="alert js-compra-error" style="margin-top:6px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row-2">
                            <div class="field">
                                <label>Precio de venta unitario del lote</label>
                                <input class="form-input" type="number" step="0.01" min="0" name="items[{{ $idx }}][precio_venta]" value="{{ $oldItem['precio_venta'] ?? '' }}" required>
                                @error("items.$idx.precio_venta")
                                    <div class="alert js-compra-error" style="margin-top:6px;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="field">
                                <label>Total línea (compra)</label>
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
                            <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->nombre_empresa }}</option>
                        @endforeach
                        <option value="__nuevo__">+ Registrar proveedor nuevo</option>
                    </select>
                    <div id="box_nuevo_proveedor" class="inline-box">
                        <div class="row-2">
                            <div class="field">
                                <label>Nombre de empresa</label>
                                <input class="form-input" name="nuevo_proveedor_nombre" placeholder="Ej: Distribuidora Médica del Sur">
                            </div>
                            <div class="field">
                                <label>Teléfono</label>
                                <input class="form-input" name="nuevo_proveedor_telefono" placeholder="Ej: 5555-5555">
                            </div>
                        </div>
                        <div class="field">
                            <label>Correo</label>
                            <input class="form-input" type="email" name="nuevo_proveedor_correo" placeholder="correo@empresa.com">
                        </div>
                    </div>
                </div>

                <div class="row-2">
                    <div class="field">
                        <label>Fecha de compra</label>
                        <input class="form-input" type="date" name="fecha" value="{{ old('fecha', now()->toDateString()) }}" required>
                    </div>
                    <div class="field">
                        <label>Total estimado de compra</label>
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
            <p>Busca por ID, código o nombre. Puedes agregar múltiples medicamentos en una sola venta.</p>
            <form method="POST" action="{{ route('farmacia.ventas.store') }}">
                @csrf
                <input type="hidden" name="_active_section" value="sec-ventas">
                <div id="venta-items">
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
                                <p class="muted js-venta-med-warning" style="font-size:12px;margin-top:6px;"></p>
                            </div>
                            <div class="field">
                                <label>Cantidad</label>
                                <input class="form-input js-venta-cantidad" type="number" min="1" name="items[{{ $idx }}][cantidad]" value="{{ $oldItem['cantidad'] ?? '' }}" required>
                                @error("items.$idx.cantidad")
                                    <div class="alert js-venta-error" style="margin-top:6px;">{{ $message }}</div>
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
                    <label>Total estimado de venta</label>
                    <input class="form-input" id="venta_total_general" value="Q 0.00" disabled>
                </div>

                <div class="field">
                    <label>Paciente</label>
                    <select class="form-input" id="id_paciente" name="id_paciente">
                        <option value="">Consumidor general</option>
                        @foreach ($pacientes as $paciente)
                            <option value="{{ $paciente->id_paciente }}">{{ $paciente->nombre }} {{ $paciente->apellido }}</option>
                        @endforeach
                        <option value="__nuevo__">+ Registrar paciente nuevo</option>
                    </select>
                    <div id="box_nuevo_paciente" class="inline-box">
                        <div class="row-2">
                            <div class="field">
                                <label>Nombre</label>
                                <input class="form-input" name="nuevo_paciente_nombre" placeholder="Nombre">
                            </div>
                            <div class="field">
                                <label>Apellido</label>
                                <input class="form-input" name="nuevo_paciente_apellido" placeholder="Apellido">
                            </div>
                        </div>
                        <div class="row-2">
                            <div class="field">
                                <label>Teléfono</label>
                                <input class="form-input" name="nuevo_paciente_telefono" placeholder="Ej: 5555-5555">
                            </div>
                            <div class="field">
                                <label>Correo</label>
                                <input class="form-input" type="email" name="nuevo_paciente_correo" placeholder="correo@dominio.com">
                            </div>
                        </div>
                        <div class="row-2">
                            <div class="field">
                                <label>Fecha de nacimiento</label>
                                <input class="form-input" type="date" name="nuevo_paciente_fecha_nacimiento">
                            </div>
                            <div class="field">
                                <label>Género</label>
                                <select class="form-input" name="nuevo_paciente_genero">
                                    <option value="">Seleccione...</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="row-2">
                            <div class="field">
                                <label>NIT</label>
                                <input class="form-input" name="nuevo_paciente_nit" placeholder="Ej: CF o 1234567-8">
                            </div>
                            <div class="field">
                                <label>DPI</label>
                                <input class="form-input" name="nuevo_paciente_dpi" placeholder="Ej: 1234 56789 0101">
                            </div>
                        </div>
                        <div class="field">
                            <label>Dirección</label>
                            <input class="form-input" name="nuevo_paciente_direccion" placeholder="Dirección de residencia">
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label>Fecha de venta</label>
                    <input class="form-input" type="date" name="fecha" value="{{ old('fecha', now()->toDateString()) }}" required>
                </div>

                <button class="btn" type="submit">Guardar venta</button>
            </form>
        </div>

        <aside class="search-card">
            <h3 style="margin:0 0 8px;color:#163760;font-size:18px;">Búsqueda rápida</h3>
            <p class="muted" style="margin-bottom:10px;">Busca por nombre, código o ID. Te muestro 5 sugerencias.</p>
            <input id="venta-busqueda-global" class="form-input" placeholder="Ej: amoxi, ID-00003, 3">
            <ul id="venta-sugerencias" class="search-suggestions"></ul>
            <p id="venta-sugerencias-empty" class="search-empty">Sin resultados.</p>
        </aside>
    </div>
</section>

<section id="sec-devoluciones" class="farm-section {{ $activeSection === 'sec-devoluciones' ? 'active' : '' }}">
    <div class="catalog-grid">
        <div class="card farm-card form-card">
            <h2>Registrar devolución</h2>
            <p>Selecciona una línea vendida y registra la cantidad a devolver. El stock se reintegra al lote original.</p>
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
                </div>

                <input type="hidden" name="id_venta" id="devolucion_id_venta">
                <input type="hidden" name="id_lote" id="devolucion_id_lote">

                <div class="row-2">
                    <div class="field">
                        <label>Cantidad a devolver</label>
                        <input class="form-input" type="number" min="1" name="cantidad" id="devolucion_cantidad" required>
                    </div>
                    <div class="field">
                        <label>Disponible para devolver</label>
                        <input class="form-input" id="devolucion_disponible" value="0" disabled>
                    </div>
                </div>

                <div class="row-2">
                    <div class="field">
                        <label>Fecha de devolución</label>
                        <input class="form-input" type="date" name="fecha" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="field">
                        <label>Motivo</label>
                        <input class="form-input" name="motivo" placeholder="Producto en mal estado, error de despacho, etc.">
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
                    <tbody>
                        @forelse ($ventasParaDevolver as $linea)
                            <tr>
                                <td>{{ $linea->id_venta }}</td>
                                <td>{{ $linea->fecha }}</td>
                                <td>{{ $linea->id_lote }}</td>
                                <td>{{ $linea->medicamento }}</td>
                                <td>{{ trim(($linea->paciente_nombre ?? '').' '.($linea->paciente_apellido ?? '')) ?: 'Consumidor general' }}</td>
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
        </div>
    </div>
</section>

<section id="sec-inventario" class="farm-section {{ $activeSection === 'sec-inventario' ? 'active' : '' }}">
    <h2 class="section-title">Inventario por medicamento</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Medicamento</th>
                    <th>Stock total</th>
                    <th>Lotes activos</th>
                    <th>Próximo vencimiento</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inventario as $item)
                    @php
                        $vencimiento = $item->proximo_vencimiento ? \Illuminate\Support\Carbon::parse($item->proximo_vencimiento) : null;
                        $hoy = now()->startOfDay();
                        $dias = $vencimiento ? $hoy->diffInDays($vencimiento, false) : null;
                    @endphp
                    <tr>
                        <td>{{ $item->medicamento }}</td>
                        <td>{{ $item->stock_total }}</td>
                        <td>{{ (int) $item->lotes_activos }}</td>
                        <td>{{ $item->proximo_vencimiento ?? 'Sin fecha' }}</td>
                        <td>
                            @if ((int) $item->stock_total <= 0 || !$vencimiento)
                                <span class="pill pill-empty">Sin stock</span>
                            @elseif ($dias < 0)
                                <span class="pill pill-expired">Vencido</span>
                            @elseif ($dias <= 30)
                                <span class="pill pill-soon">Por vencer ({{ $dias }} días)</span>
                            @else
                                <span class="pill pill-ok">Vigente</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No hay información de inventario registrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<section id="sec-lotes" class="farm-section {{ $activeSection === 'sec-lotes' ? 'active' : '' }}">
    <h2 class="section-title">Lotes con stock disponible</h2>
    <div class="card farm-card" style="padding:12px;margin-bottom:10px;">
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
                        $isLowStock = (int) $lote->stock <= 10;
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
                            @if ($isLowStock)
                                <span class="pill pill-soon">Bajo stock ({{ (int) $lote->stock }})</span>
                            @endif
                            @if ($estadoLote !== 'por_vencer' && $estadoLote !== 'vencido' && !$isLowStock)
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
    const togglePaciente = () => boxPaciente.classList.toggle('show', selPaciente.value === '__nuevo__');
    selPaciente.addEventListener('change', togglePaciente);
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

    const fltLoteMed = document.getElementById('flt-lote-med');
    const fltLoteVDesde = document.getElementById('flt-lote-venc-desde');
    const fltLoteVHasta = document.getElementById('flt-lote-venc-hasta');
    const fltLoteStockMin = document.getElementById('flt-lote-stock-min');
    const fltLoteEstado = document.getElementById('flt-lote-estado');
    const fltLoteClear = document.getElementById('flt-lote-clear');
    const lotesTbody = document.getElementById('lotes-tbody');
    const lotesCount = document.getElementById('lotes-count');

    if (lotesTbody && lotesCount) {
        const rows = Array.from(lotesTbody.querySelectorAll('tr[data-med]'));
        const applyLotesFilters = () => {
            const qMed = (fltLoteMed?.value || '').trim().toLowerCase();
            const vDesde = fltLoteVDesde?.value || '';
            const vHasta = fltLoteVHasta?.value || '';
            const stockMin = parseInt(fltLoteStockMin?.value || '0', 10);
            const estado = fltLoteEstado?.value || '';
            let visible = 0;

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
                row.style.display = show ? '' : 'none';
                if (show) visible += 1;
            });

            lotesCount.textContent = `Mostrando ${visible} de ${rows.length} lotes.`;
        };

        [fltLoteMed, fltLoteVDesde, fltLoteVHasta, fltLoteStockMin, fltLoteEstado].forEach((el) => {
            el?.addEventListener('input', applyLotesFilters);
            el?.addEventListener('change', applyLotesFilters);
        });
        fltLoteClear?.addEventListener('click', () => {
            if (fltLoteMed) fltLoteMed.value = '';
            if (fltLoteVDesde) fltLoteVDesde.value = '';
            if (fltLoteVHasta) fltLoteVHasta.value = '';
            if (fltLoteStockMin) fltLoteStockMin.value = '';
            if (fltLoteEstado) fltLoteEstado.value = '';
            applyLotesFilters();
        });
        applyLotesFilters();
    }
})();
</script>
@endsection
