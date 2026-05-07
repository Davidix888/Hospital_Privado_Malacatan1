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
    th, td { border-bottom: 1px solid #e6edf6; text-align: center; padding: 9px 10px; font-size: 12.5px; }
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

    @media (max-width: 920px) {
        .farm-grid,
        .catalog-grid,
        .row-2 { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
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
@if ($errors->any())
    <div class="alert">{{ $errors->first() }}</div>
@endif

<div class="farm-nav" id="farmNav">
    <button type="button" data-target="sec-catalogo" class="active">Catálogo</button>
    <button type="button" data-target="sec-compras">Compras</button>
    <button type="button" data-target="sec-ventas">Ventas</button>
    <button type="button" data-target="sec-devoluciones">Devoluciones</button>
    <button type="button" data-target="sec-inventario">Inventario</button>
    <button type="button" data-target="sec-lotes">Lotes</button>
</div>

<section id="sec-catalogo" class="farm-section active">
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
                            </tr>
                        @empty
                            <tr><td colspan="6">No hay medicamentos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<section id="sec-compras" class="farm-section">
    <div class="card farm-card form-card">
        <h2>Registrar compra</h2>
        <p>Busca por ID, código o nombre. Puedes agregar múltiples medicamentos en una sola compra.</p>
        <form method="POST" action="{{ route('farmacia.compras.store') }}">
            @csrf
            <div id="compra-items">
                <div class="inline-box show compra-item" data-index="0" style="margin-bottom:10px;">
                    <div class="field">
                        <label>Buscar medicamento</label>
                        <input class="form-input js-med-search-inline-compra" placeholder="Ej: ID-00012, 12 o Aspirina">
                    </div>
                    <div class="field">
                        <label>Medicamento</label>
                        <select class="form-input js-med-select-compra" name="items[0][id_medicamento]" required>
                            <option value="">Seleccione...</option>
                            @foreach ($medicamentos as $medicamento)
                                <option value="{{ $medicamento->id_medicamento }}">[{{ $medicamento->id_medicamento }}] {{ $medicamento->codigo_interno ?? 'ID-s/c' }} - {{ $medicamento->nombre }} @if($medicamento->presentacion) - {{ $medicamento->presentacion }} @endif @if($medicamento->concentracion) ({{ $medicamento->concentracion }}) @endif</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row-2">
                        <div class="field">
                            <label>Cantidad</label>
                            <input class="form-input js-compra-cantidad" type="number" min="1" name="items[0][cantidad]" required>
                        </div>
                        <div class="field">
                            <label>Fecha de vencimiento</label>
                            <input class="form-input" type="date" name="items[0][fecha_vencimiento]" required>
                        </div>
                    </div>

                    <div class="row-2">
                        <div class="field">
                            <label>Precio de compra unitario</label>
                            <input class="form-input js-compra-precio" type="number" step="0.01" min="0" name="items[0][precio_compra]" required>
                            <p class="muted" style="font-size:12px;margin-top:6px;">Este valor es por unidad.</p>
                        </div>
                        <div class="field">
                            <label>Precio de venta unitario del lote</label>
                            <input class="form-input" type="number" step="0.01" min="0" name="items[0][precio_venta]" required>
                        </div>
                    </div>

                    <div class="field">
                        <label>Total línea (compra)</label>
                        <input class="form-input js-compra-total-linea" value="Q 0.00" disabled>
                    </div>
                </div>
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
                    <input class="form-input" type="date" name="fecha" value="{{ now()->toDateString() }}" required>
                </div>
                <div class="field">
                    <label>Total estimado de compra</label>
                    <input class="form-input" id="compra_total_general" value="Q 0.00" disabled>
                </div>
            </div>

            <button class="btn" type="submit">Guardar compra</button>
        </form>
    </div>
</section>

<section id="sec-ventas" class="farm-section">
    <div class="card farm-card form-card">
        <h2>Registrar venta</h2>
        <p>Busca por ID, código o nombre. Puedes agregar múltiples medicamentos en una sola venta.</p>
        <form method="POST" action="{{ route('farmacia.ventas.store') }}">
            @csrf
            <div id="venta-items">
                <div class="inline-box show sale-item" data-index="0" style="margin-bottom:10px;">
                    <div class="row-2">
                        <div class="field">
                            <label>Buscar medicamento</label>
                            <input class="form-input js-med-search-inline" placeholder="Ej: ID-00012, 12 o Aspirina">
                        </div>
                        <div class="field">
                            <label>Cantidad</label>
                            <input class="form-input" type="number" min="1" name="items[0][cantidad]" required>
                        </div>
                    </div>
                    <div class="field">
                        <label>Medicamento</label>
                        <select class="form-input js-med-select" name="items[0][id_medicamento]" required>
                            <option value="">Seleccione...</option>
                            @foreach ($medicamentos as $medicamento)
                                <option value="{{ $medicamento->id_medicamento }}">[{{ $medicamento->id_medicamento }}] {{ $medicamento->codigo_interno ?? 'ID-s/c' }} - {{ $medicamento->nombre }} @if($medicamento->presentacion) - {{ $medicamento->presentacion }} @endif @if($medicamento->concentracion) ({{ $medicamento->concentracion }}) @endif</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;margin:8px 0 14px;">
                <button class="btn btn-dark btn-sm" type="button" id="add-item-venta">+ Agregar otro medicamento</button>
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
                <input class="form-input" type="date" name="fecha" value="{{ now()->toDateString() }}" required>
            </div>

            <button class="btn" type="submit">Guardar venta</button>
        </form>
    </div>
</section>

<section id="sec-devoluciones" class="farm-section">
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

<section id="sec-inventario" class="farm-section">
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

<section id="sec-lotes" class="farm-section">
    <h2 class="section-title">Lotes con stock disponible</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID lote</th>
                    <th>Medicamento</th>
                    <th>Stock</th>
                    <th>Precio unitario</th>
                    <th>Vencimiento</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lotesActivos as $lote)
                    <tr>
                        <td>{{ $lote->id_lote }}</td>
                        <td>{{ $lote->medicamento }}</td>
                        <td>{{ $lote->stock }}</td>
                        <td>Q {{ number_format((float) $lote->precio_venta, 2) }}</td>
                        <td>{{ $lote->fecha_vencimiento ?? 'Sin fecha' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">No hay lotes disponibles actualmente.</td></tr>
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

    const selCategoria = document.getElementById('id_categoria');
    const boxCategoria = document.getElementById('box_nueva_categoria');
    if (selCategoria && boxCategoria) {
        const toggleCategoria = () => boxCategoria.classList.toggle('show', selCategoria.value === '__nueva__');
        selCategoria.addEventListener('change', toggleCategoria);
        toggleCategoria();
    }

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

        itemEl.querySelector('.js-compra-cantidad')?.addEventListener('input', recalcCompraTotals);
        itemEl.querySelector('.js-compra-precio')?.addEventListener('input', recalcCompraTotals);
    }

    const compraItems = document.getElementById('compra-items');
    if (compraItems) {
        const first = compraItems.querySelector('.compra-item');
        if (first) wireCompraItem(first);

        const addCompra = document.getElementById('add-item-compra');
        addCompra.addEventListener('click', () => {
            const base = compraItems.querySelector('.compra-item');
            const clone = base.cloneNode(true);
            const idx = compraItems.querySelectorAll('.compra-item').length;
            clone.dataset.index = idx;

            const search = clone.querySelector('.js-med-search-inline-compra');
            const select = clone.querySelector('.js-med-select-compra');
            const cantidad = clone.querySelector('.js-compra-cantidad');
            const precio = clone.querySelector('.js-compra-precio');
            const precioVenta = clone.querySelector('input[name^="items"][name$="[precio_venta]"]');
            const venc = clone.querySelector('input[name^="items"][name$="[fecha_vencimiento]"]');
            const totalLinea = clone.querySelector('.js-compra-total-linea');

            if (search) search.value = '';
            if (select) { select.name = `items[${idx}][id_medicamento]`; select.selectedIndex = 0; }
            if (cantidad) { cantidad.name = `items[${idx}][cantidad]`; cantidad.value = ''; }
            if (precio) { precio.name = `items[${idx}][precio_compra]`; precio.value = ''; }
            if (precioVenta) { precioVenta.name = `items[${idx}][precio_venta]`; precioVenta.value = ''; }
            if (venc) { venc.name = `items[${idx}][fecha_vencimiento]`; venc.value = ''; }
            if (totalLinea) totalLinea.value = 'Q 0.00';

            const removeWrap = document.createElement('div');
            removeWrap.style.display = 'flex';
            removeWrap.style.justifyContent = 'flex-end';
            removeWrap.style.marginTop = '6px';
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-sm';
            removeBtn.textContent = 'Quitar';
            removeBtn.addEventListener('click', () => {
                clone.remove();
                recalcCompraTotals();
            });
            removeWrap.appendChild(removeBtn);
            clone.appendChild(removeWrap);

            compraItems.appendChild(clone);
            wireCompraItem(clone);
            recalcCompraTotals();
        });
    }

    function wireVentaItem(itemEl) {
        const input = itemEl.querySelector('.js-med-search-inline');
        const select = itemEl.querySelector('.js-med-select');
        if (input && select) bindMedicineSearch(input, select);
    }

    const ventaItems = document.getElementById('venta-items');
    if (ventaItems) {
        const firstItem = ventaItems.querySelector('.sale-item');
        if (firstItem) wireVentaItem(firstItem);

        const addBtn = document.getElementById('add-item-venta');
        addBtn.addEventListener('click', () => {
            const first = ventaItems.querySelector('.sale-item');
            const clone = first.cloneNode(true);
            const idx = ventaItems.querySelectorAll('.sale-item').length;
            clone.dataset.index = idx;

            const qty = clone.querySelector('input[type="number"]');
            qty.name = `items[${idx}][cantidad]`;
            qty.value = '';

            const search = clone.querySelector('.js-med-search-inline');
            search.value = '';

            const select = clone.querySelector('.js-med-select');
            select.name = `items[${idx}][id_medicamento]`;
            select.selectedIndex = 0;

            const removeWrap = document.createElement('div');
            removeWrap.style.display = 'flex';
            removeWrap.style.justifyContent = 'flex-end';
            removeWrap.style.marginTop = '6px';
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-sm';
            removeBtn.textContent = 'Quitar';
            removeBtn.addEventListener('click', () => clone.remove());
            removeWrap.appendChild(removeBtn);
            clone.appendChild(removeWrap);

            ventaItems.appendChild(clone);
            wireVentaItem(clone);
        });
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
})();
</script>
@endsection
