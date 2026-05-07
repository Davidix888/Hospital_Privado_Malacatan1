<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FarmaciaController extends Controller
{
    public function index(): View
    {
        $medicamentos = DB::table('medicamento as m')
            ->leftJoin('categoria_medicamento as c', 'c.id_categoria', '=', 'm.id_categoria')
            ->select(
                'm.id_medicamento',
                'm.nombre',
                'm.presentacion',
                'm.concentracion',
                'm.via_administracion',
                'm.unidad_medida',
                'm.codigo_interno',
                'c.nombre_categoria'
            )
            ->orderBy('m.nombre')
            ->get();

        $categorias = DB::table('categoria_medicamento')
            ->select('id_categoria', 'nombre_categoria')
            ->orderBy('nombre_categoria')
            ->get();

        $catalogoMedicamentos = DB::table('medicamento as m')
            ->leftJoin('categoria_medicamento as c', 'c.id_categoria', '=', 'm.id_categoria')
            ->select(
                'm.id_medicamento',
                'm.nombre',
                'm.presentacion',
                'm.concentracion',
                'm.via_administracion',
                'm.unidad_medida',
                'm.codigo_interno',
                'c.nombre_categoria'
            )
            ->orderBy('m.nombre')
            ->limit(500)
            ->get();

        $proveedores = DB::table('proveedor')
            ->select('id_proveedor', 'nombre_empresa')
            ->orderBy('nombre_empresa')
            ->get();

        $pacientes = DB::table('paciente')
            ->select('id_paciente', 'nombre', 'apellido')
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->limit(200)
            ->get();

        $inventario = DB::table('lote as l')
            ->join('medicamento as m', 'm.id_medicamento', '=', 'l.id_medicamento')
            ->select(
                'm.id_medicamento',
                'm.nombre as medicamento',
                DB::raw('COALESCE(SUM(l.stock), 0) as stock_total'),
                DB::raw('MIN(CASE WHEN l.stock > 0 THEN l.fecha_vencimiento END) as proximo_vencimiento'),
                DB::raw('SUM(CASE WHEN l.stock > 0 THEN 1 ELSE 0 END) as lotes_activos')
            )
            ->groupBy('m.id_medicamento', 'm.nombre')
            ->orderBy('m.nombre')
            ->get();

        $lotesActivos = DB::table('lote as l')
            ->join('medicamento as m', 'm.id_medicamento', '=', 'l.id_medicamento')
            ->select('l.id_lote', 'm.nombre as medicamento', 'l.stock', 'l.precio_venta', 'l.fecha_vencimiento')
            ->where('l.stock', '>', 0)
            ->orderBy('l.fecha_vencimiento')
            ->orderBy('l.id_lote')
            ->limit(250)
            ->get();

        $devueltos = DB::table('detalle_devolucion as dd')
            ->join('devolucion as d', 'd.id_devolucion', '=', 'dd.id_devolucion')
            ->select('d.id_venta', 'dd.id_lote', DB::raw('SUM(dd.cantidad) as cantidad_devuelta'))
            ->groupBy('d.id_venta', 'dd.id_lote')
            ->get()
            ->keyBy(fn ($row) => $row->id_venta.'|'.$row->id_lote);

        $ventasParaDevolver = DB::table('detalle_venta as dv')
            ->join('venta_farmacia as v', 'v.id_venta', '=', 'dv.id_venta')
            ->join('lote as l', 'l.id_lote', '=', 'dv.id_lote')
            ->join('medicamento as m', 'm.id_medicamento', '=', 'l.id_medicamento')
            ->leftJoin('paciente as p', 'p.id_paciente', '=', 'v.id_paciente')
            ->select(
                'v.id_venta',
                'v.fecha',
                'dv.id_lote',
                'm.nombre as medicamento',
                'dv.cantidad as cantidad_vendida',
                'p.nombre as paciente_nombre',
                'p.apellido as paciente_apellido'
            )
            ->orderByDesc('v.id_venta')
            ->limit(500)
            ->get()
            ->map(function ($row) use ($devueltos) {
                $key = $row->id_venta.'|'.$row->id_lote;
                $devuelto = (int) ($devueltos[$key]->cantidad_devuelta ?? 0);
                $row->cantidad_devuelta = $devuelto;
                $row->cantidad_disponible = max(0, (int) $row->cantidad_vendida - $devuelto);
                return $row;
            })
            ->filter(fn ($row) => $row->cantidad_disponible > 0)
            ->values();

        return view('modules.farmacia', [
            'medicamentos' => $medicamentos,
            'categorias' => $categorias,
            'catalogoMedicamentos' => $catalogoMedicamentos,
            'proveedores' => $proveedores,
            'pacientes' => $pacientes,
            'inventario' => $inventario,
            'lotesActivos' => $lotesActivos,
            'ventasParaDevolver' => $ventasParaDevolver,
        ]);
    }

    public function storeMedicamento(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:180'],
            'id_categoria' => ['nullable'],
            'nueva_categoria' => ['nullable', 'string', 'max:120'],
            'presentacion' => ['nullable', 'string', 'max:100'],
            'concentracion' => ['nullable', 'string', 'max:100'],
            'via_administracion' => ['nullable', 'string', 'max:80'],
            'descripcion' => ['nullable', 'string', 'max:400'],
        ], $this->validationMessages(), $this->validationAttributes());

        DB::transaction(function () use ($data): void {
            $idCategoria = null;
            if (($data['id_categoria'] ?? null) === '__nueva__') {
                $categoriaNombre = trim((string) ($data['nueva_categoria'] ?? ''));
                if ($categoriaNombre === '') {
                    throw ValidationException::withMessages([
                        'nueva_categoria' => 'Debes ingresar el nombre de la categoría.',
                    ]);
                }

                $categoria = DB::table('categoria_medicamento')
                    ->whereRaw('LOWER(nombre_categoria) = ?', [mb_strtolower($categoriaNombre)])
                    ->first();

                if ($categoria) {
                    $idCategoria = (int) $categoria->id_categoria;
                } else {
                    $idCategoria = DB::table('categoria_medicamento')->insertGetId([
                        'nombre_categoria' => $categoriaNombre,
                    ], 'id_categoria');
                }
            } elseif (!empty($data['id_categoria'])) {
                $idCategoria = (int) $data['id_categoria'];
            }

            $idMedicamento = DB::table('medicamento')->insertGetId([
                'nombre' => trim((string) $data['nombre']),
                'id_categoria' => $idCategoria,
                'presentacion' => trim((string) ($data['presentacion'] ?? '')) ?: null,
                'concentracion' => trim((string) ($data['concentracion'] ?? '')) ?: null,
                'via_administracion' => trim((string) ($data['via_administracion'] ?? '')) ?: null,
                'unidad_medida' => trim((string) ($data['unidad_medida'] ?? '')) ?: null,
                'codigo_interno' => null,
                'descripcion' => trim((string) ($data['descripcion'] ?? '')) ?: null,
            ], 'id_medicamento');

            DB::table('medicamento')
                ->where('id_medicamento', $idMedicamento)
                ->update([
                    'codigo_interno' => 'ID-'.str_pad((string) $idMedicamento, 5, '0', STR_PAD_LEFT),
                ]);
        });

        return back()->with('status', 'Medicamento registrado correctamente.');
    }

    public function storeCompra(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_proveedor' => ['nullable'],
            'nuevo_proveedor_nombre' => ['nullable', 'string', 'max:160'],
            'nuevo_proveedor_telefono' => ['nullable', 'string', 'max:50'],
            'nuevo_proveedor_correo' => ['nullable', 'email', 'max:150'],
            'fecha' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_medicamento' => ['required', 'integer', 'exists:medicamento,id_medicamento'],
            'items.*.fecha_vencimiento' => ['required', 'date'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
            'items.*.precio_compra' => ['required', 'numeric', 'min:0'],
            'items.*.precio_venta' => ['required', 'numeric', 'min:0'],
        ], $this->validationMessages(), $this->validationAttributes());

        $idUsuario = (int) Session::get('auth_usuario_id');

        DB::transaction(function () use ($data, $idUsuario): void {
            $idProveedor = null;
            if (($data['id_proveedor'] ?? null) === '__nuevo__') {
                $nombre = trim((string) ($data['nuevo_proveedor_nombre'] ?? ''));
                if ($nombre === '') {
                    throw ValidationException::withMessages([
                        'nuevo_proveedor_nombre' => 'Debes ingresar el nombre del nuevo proveedor.',
                    ]);
                }

                $idProveedor = DB::table('proveedor')->insertGetId([
                    'nombre_empresa' => $nombre,
                    'telefono' => trim((string) ($data['nuevo_proveedor_telefono'] ?? '')) ?: null,
                    'correo' => trim((string) ($data['nuevo_proveedor_correo'] ?? '')) ?: null,
                ], 'id_proveedor');
            } elseif (!empty($data['id_proveedor'])) {
                $idProveedor = (int) $data['id_proveedor'];
                $exists = DB::table('proveedor')->where('id_proveedor', $idProveedor)->exists();
                if (!$exists) {
                    throw ValidationException::withMessages([
                        'id_proveedor' => 'El proveedor seleccionado no existe.',
                    ]);
                }
            }

            $idCompra = DB::table('compra_abastecimiento')->insertGetId([
                'fecha' => $data['fecha'],
                'id_proveedor' => $idProveedor,
                'id_usuario' => $idUsuario,
            ], 'id_compra_abastecimiento');

            foreach ($data['items'] as $item) {
                if ($item['fecha_vencimiento'] < $data['fecha']) {
                    throw ValidationException::withMessages([
                        'items' => 'La fecha de vencimiento no puede ser menor que la fecha de compra.',
                    ]);
                }

                $idLote = DB::table('lote')->insertGetId([
                    'stock' => (int) $item['cantidad'],
                    'fecha_vencimiento' => $item['fecha_vencimiento'],
                    'precio_venta' => $item['precio_venta'],
                    'id_medicamento' => (int) $item['id_medicamento'],
                ], 'id_lote');

                DB::table('detalle_compra')->insert([
                    'cantidad' => (int) $item['cantidad'],
                    'precio_compra' => $item['precio_compra'],
                    'id_lote' => $idLote,
                    'id_compra_abastecimiento' => $idCompra,
                ]);
            }
        });

        return back()->with('status', 'Compra registrada con múltiples medicamentos y lotes ingresados al inventario.');
    }

    public function storeVenta(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_paciente' => ['nullable'],
            'nuevo_paciente_nombre' => ['nullable', 'string', 'max:120'],
            'nuevo_paciente_apellido' => ['nullable', 'string', 'max:120'],
            'nuevo_paciente_telefono' => ['nullable', 'string', 'max:50'],
            'nuevo_paciente_direccion' => ['nullable', 'string', 'max:220'],
            'nuevo_paciente_correo' => ['nullable', 'email', 'max:150'],
            'nuevo_paciente_fecha_nacimiento' => ['nullable', 'date'],
            'nuevo_paciente_nit' => ['nullable', 'string', 'max:30'],
            'nuevo_paciente_genero' => ['nullable', 'string', 'max:20'],
            'nuevo_paciente_dpi' => ['nullable', 'string', 'max:30'],
            'fecha' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_medicamento' => ['required', 'integer', 'exists:medicamento,id_medicamento'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
        ], $this->validationMessages(), $this->validationAttributes());

        $idUsuario = (int) Session::get('auth_usuario_id');

        DB::transaction(function () use ($data, $idUsuario): void {
            $idPaciente = null;
            if (($data['id_paciente'] ?? null) === '__nuevo__') {
                $nombre = trim((string) ($data['nuevo_paciente_nombre'] ?? ''));
                $apellido = trim((string) ($data['nuevo_paciente_apellido'] ?? ''));
                if ($nombre === '' || $apellido === '') {
                    throw ValidationException::withMessages([
                        'nuevo_paciente_nombre' => 'Debes ingresar nombre y apellido del paciente nuevo.',
                    ]);
                }

                $idPaciente = DB::table('paciente')->insertGetId([
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'telefono' => trim((string) ($data['nuevo_paciente_telefono'] ?? '')) ?: null,
                    'direccion' => trim((string) ($data['nuevo_paciente_direccion'] ?? '')) ?: null,
                    'correo' => trim((string) ($data['nuevo_paciente_correo'] ?? '')) ?: null,
                ], 'id_paciente');
            } elseif (!empty($data['id_paciente'])) {
                $idPaciente = (int) $data['id_paciente'];
                $exists = DB::table('paciente')->where('id_paciente', $idPaciente)->exists();
                if (!$exists) {
                    throw ValidationException::withMessages([
                        'id_paciente' => 'El paciente seleccionado no existe.',
                    ]);
                }
            }

            $idVenta = DB::table('venta_farmacia')->insertGetId([
                'fecha' => $data['fecha'],
                'id_paciente' => $idPaciente,
                'id_usuario' => $idUsuario,
            ], 'id_venta');

            foreach ($data['items'] as $item) {
                $idMedicamento = (int) $item['id_medicamento'];
                $cantidadSolicitada = (int) $item['cantidad'];

                $lotes = DB::table('lote')
                    ->where('id_medicamento', $idMedicamento)
                    ->where('stock', '>', 0)
                    ->where(function ($query) use ($data): void {
                        $query->whereNull('fecha_vencimiento')
                            ->orWhere('fecha_vencimiento', '>=', $data['fecha']);
                    })
                    ->orderByRaw('fecha_vencimiento IS NULL, fecha_vencimiento ASC')
                    ->orderBy('id_lote')
                    ->lockForUpdate()
                    ->get(['id_lote', 'stock', 'precio_venta']);

                $stockDisponible = (int) $lotes->sum('stock');
                if ($stockDisponible < $cantidadSolicitada) {
                    $nombreMed = (string) DB::table('medicamento')
                        ->where('id_medicamento', $idMedicamento)
                        ->value('nombre');

                    throw ValidationException::withMessages([
                        'items' => 'Stock insuficiente para '.$nombreMed.'. Disponible: '.$stockDisponible,
                    ]);
                }

                $restante = $cantidadSolicitada;
                foreach ($lotes as $lote) {
                    if ($restante <= 0) {
                        break;
                    }

                    $consumir = min($restante, (int) $lote->stock);
                    $nuevoStock = (int) $lote->stock - $consumir;
                    $precioVenta = (float) ($lote->precio_venta ?? 0);

                    DB::table('lote')
                        ->where('id_lote', $lote->id_lote)
                        ->update(['stock' => $nuevoStock]);

                    DB::table('detalle_venta')->insert([
                        'id_venta' => $idVenta,
                        'id_lote' => $lote->id_lote,
                        'cantidad' => $consumir,
                        'subtotal' => round($consumir * $precioVenta, 2),
                    ]);

                    $restante -= $consumir;
                }
            }
        });

        return back()->with('status', 'Venta registrada con múltiples medicamentos y stock descontado por lote (FEFO).');
    }

    public function storeDevolucion(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_venta' => ['required', 'integer', 'exists:venta_farmacia,id_venta'],
            'id_lote' => ['required', 'integer', 'exists:lote,id_lote'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'fecha' => ['required', 'date'],
            'motivo' => ['nullable', 'string', 'max:180'],
        ], $this->validationMessages(), $this->validationAttributes());

        DB::transaction(function () use ($data): void {
            $cantidadVendida = (int) DB::table('detalle_venta')
                ->where('id_venta', (int) $data['id_venta'])
                ->where('id_lote', (int) $data['id_lote'])
                ->sum('cantidad');

            if ($cantidadVendida <= 0) {
                throw ValidationException::withMessages([
                    'id_lote' => 'Ese lote no pertenece a la venta seleccionada.',
                ]);
            }

            $cantidadDevuelta = (int) DB::table('detalle_devolucion as dd')
                ->join('devolucion as d', 'd.id_devolucion', '=', 'dd.id_devolucion')
                ->where('d.id_venta', (int) $data['id_venta'])
                ->where('dd.id_lote', (int) $data['id_lote'])
                ->sum('dd.cantidad');

            $disponible = $cantidadVendida - $cantidadDevuelta;
            if ((int) $data['cantidad'] > $disponible) {
                throw ValidationException::withMessages([
                    'cantidad' => 'No puedes devolver más de lo disponible. Disponible: '.$disponible,
                ]);
            }

            $idDevolucion = DB::table('devolucion')->insertGetId([
                'fecha' => $data['fecha'],
                'motivo' => trim((string) ($data['motivo'] ?? '')) ?: null,
                'id_venta' => (int) $data['id_venta'],
            ], 'id_devolucion');

            DB::table('detalle_devolucion')->insert([
                'id_devolucion' => $idDevolucion,
                'id_lote' => (int) $data['id_lote'],
                'cantidad' => (int) $data['cantidad'],
            ]);

            DB::table('lote')
                ->where('id_lote', (int) $data['id_lote'])
                ->update([
                    'stock' => DB::raw('stock + '.(int) $data['cantidad']),
                ]);
        });

        return back()->with('status', 'Devolución registrada y stock reintegrado al lote.');
    }

    private function validationMessages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser texto válido.',
            'integer' => 'El campo :attribute debe ser un número entero.',
            'numeric' => 'El campo :attribute debe ser numérico.',
            'email' => 'El campo :attribute debe ser un correo válido.',
            'date' => 'El campo :attribute debe ser una fecha válida.',
            'exists' => 'El valor seleccionado en :attribute no existe.',
            'min' => 'El campo :attribute debe ser como mínimo :min.',
            'max' => 'El campo :attribute no puede exceder :max caracteres.',
            'array' => 'El campo :attribute debe ser una lista válida.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'id_medicamento' => 'medicamento',
            'id_proveedor' => 'proveedor',
            'id_paciente' => 'paciente',
            'id_venta' => 'venta',
            'id_lote' => 'lote',
            'fecha' => 'fecha',
            'fecha_vencimiento' => 'fecha de vencimiento',
            'cantidad' => 'cantidad',
            'precio_compra' => 'precio de compra',
            'precio_venta' => 'precio de venta',
            'nombre' => 'nombre',
            'presentacion' => 'presentación',
            'concentracion' => 'concentración',
            'via_administracion' => 'vía de administración',
            'descripcion' => 'descripción',
            'nueva_categoria' => 'categoría nueva',
            'nuevo_proveedor_nombre' => 'nombre del proveedor',
            'nuevo_proveedor_telefono' => 'teléfono del proveedor',
            'nuevo_proveedor_correo' => 'correo del proveedor',
            'nuevo_paciente_nombre' => 'nombre del paciente',
            'nuevo_paciente_apellido' => 'apellido del paciente',
            'nuevo_paciente_telefono' => 'teléfono del paciente',
            'nuevo_paciente_direccion' => 'dirección del paciente',
            'nuevo_paciente_correo' => 'correo del paciente',
            'nuevo_paciente_fecha_nacimiento' => 'fecha de nacimiento',
            'nuevo_paciente_nit' => 'NIT',
            'nuevo_paciente_genero' => 'género',
            'nuevo_paciente_dpi' => 'DPI',
            'items' => 'líneas de venta',
            'items.*.id_medicamento' => 'medicamento en la línea',
            'items.*.cantidad' => 'cantidad en la línea',
            'items.*.fecha_vencimiento' => 'fecha de vencimiento en la línea',
            'items.*.precio_compra' => 'precio de compra en la línea',
            'items.*.precio_venta' => 'precio de venta en la línea',
            'motivo' => 'motivo',
        ];
    }
}
