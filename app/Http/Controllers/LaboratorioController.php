<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class LaboratorioController extends Controller
{
    public function index(): View
    {
        $hasTable = Schema::hasTable('examen_laboratorio');
        $hasTipoMuestra = $hasTable && Schema::hasColumn('examen_laboratorio', 'tipo_muestra');

        $examenes = collect();
        $examenesJs = collect();
        $pacientesJs = collect();
        $solicitudes = collect();
        $solicitudesAgrupadas = collect();

        if ($hasTable) {
            $query = DB::table('examen_laboratorio')
                ->select('id_examen', 'codigo_examen', 'nombre_examen', 'costo', 'informacion', 'activo');

            if ($hasTipoMuestra) {
                $query->addSelect('tipo_muestra');
            } else {
                $query->addSelect(DB::raw('NULL as tipo_muestra'));
            }

            $examenes = $query
                ->orderBy('nombre_examen')
                ->limit(500)
                ->get();

            $examenesJs = $examenes->map(function ($e) {
                return [
                    'id' => (int) $e->id_examen,
                    'codigo' => (string) ($e->codigo_examen ?? ('EXA-'.str_pad((string) $e->id_examen, 5, '0', STR_PAD_LEFT))),
                    'nombre' => (string) ($e->nombre_examen ?? ''),
                    'tipo' => (string) ($e->tipo_muestra ?? ''),
                    'info' => (string) ($e->informacion ?? ''),
                    'costo' => (float) ($e->costo ?? 0),
                ];
            })->values();
        }

        if (Schema::hasTable('paciente')) {
            $cols = Schema::getColumnListing('paciente');
            $select = [];
            foreach (['id_paciente', 'nombre', 'apellido', 'telefono', 'correo', 'direccion', 'nit', 'dpi', 'genero', 'fecha_nacimiento'] as $c) {
                if (in_array($c, $cols, true)) {
                    $select[] = $c;
                }
            }

            if (!empty($select)) {
                $pacientes = DB::table('paciente')
                    ->select($select)
                    ->orderByDesc('id_paciente')
                    ->limit(800)
                    ->get();

                $pacientesJs = $pacientes->map(function ($p) {
                    return [
                        'id' => (int) ($p->id_paciente ?? 0),
                        'nombre' => (string) ($p->nombre ?? ''),
                        'apellido' => (string) ($p->apellido ?? ''),
                        'telefono' => (string) ($p->telefono ?? ''),
                        'correo' => (string) ($p->correo ?? ''),
                        'direccion' => (string) ($p->direccion ?? ''),
                        'nit' => (string) ($p->nit ?? ''),
                        'dpi' => (string) ($p->dpi ?? ''),
                        'genero' => (string) ($p->genero ?? ''),
                        'fecha_nacimiento' => (string) ($p->fecha_nacimiento ?? ''),
                    ];
                })->values();
            }
        }

        if (
            Schema::hasTable('paciente_examen_laboratorio') &&
            Schema::hasTable('paciente') &&
            Schema::hasTable('examen_laboratorio')
        ) {
            $tieneEstado = Schema::hasColumn('paciente_examen_laboratorio', 'estado');
            $tieneCodigoSolicitud = Schema::hasColumn('paciente_examen_laboratorio', 'codigo_solicitud');
            $estadoSelect = $tieneEstado ? 'pel.estado' : DB::raw("'ingresado' as estado");
            $codigoSolicitudSelect = $tieneCodigoSolicitud ? 'pel.codigo_solicitud' : DB::raw('NULL as codigo_solicitud');

            $solicitudes = DB::table('paciente_examen_laboratorio as pel')
                ->join('paciente as p', 'p.id_paciente', '=', 'pel.id_paciente')
                ->join('examen_laboratorio as e', 'e.id_examen', '=', 'pel.id_examen')
                ->select(
                    'pel.id_paciente_examen',
                    'pel.created_at',
                    'p.id_paciente',
                    'p.nombre',
                    'p.apellido',
                    'p.dpi',
                    $codigoSolicitudSelect,
                    'e.id_examen',
                    'e.codigo_examen',
                    'e.nombre_examen',
                    'e.tipo_muestra',
                    'e.informacion',
                    'e.costo',
                    $estadoSelect
                )
                ->orderByDesc('pel.id_paciente_examen')
                ->limit(1200)
                ->get();

            $solicitudesAgrupadas = $solicitudes
                ->groupBy(function ($r) {
                    $codigoSolicitud = trim((string) ($r->codigo_solicitud ?? ''));
                    if ($codigoSolicitud !== '') {
                        return 'sol|'.$codigoSolicitud;
                    }

                    // Fallback para registros antiguos sin codigo_solicitud:
                    // agrupa por paciente y minuto para no separar examenes de una misma captura.
                    $fechaKey = Carbon::parse($r->created_at)->format('Y-m-d H:i');
                    return 'legacy|'.$r->id_paciente.'|'.$fechaKey;
                })
                ->map(function ($rows, $groupKey) {
                    $first = $rows->first();
                    $estados = $rows->pluck('estado')->map(fn ($s) => (string) $s)->unique()->values();
                    $total = $rows->count();
                    $cIngresado = $rows->where('estado', 'ingresado')->count();
                    $cProceso = $rows->where('estado', 'en_proceso')->count();
                    $cFinalizado = $rows->where('estado', 'finalizado')->count();
                    $cCancelado = $rows->where('estado', 'cancelado')->count();

                    $estadoGeneral = 'ingresada';
                    if ($total > 0 && $cCancelado === $total) {
                        $estadoGeneral = 'cancelada';
                    } elseif ($total > 0 && $cFinalizado === $total) {
                        $estadoGeneral = 'finalizada';
                    } elseif ($total > 0 && ($cFinalizado + $cCancelado) === $total && $cFinalizado > 0) {
                        // Si la combinacion es finalizado + cancelado (sin pendientes),
                        // la solicitud se considera finalizada.
                        $estadoGeneral = 'finalizada';
                    } elseif ($cProceso > 0) {
                        $estadoGeneral = 'en_proceso';
                    } elseif (($cFinalizado + $cCancelado) > 0) {
                        $estadoGeneral = 'parcial';
                    } elseif ($cIngresado === $total) {
                        $estadoGeneral = 'ingresada';
                    }

                    return [
                        'grupo' => $groupKey,
                        'codigo_solicitud' => (string) ($first->codigo_solicitud ?? ''),
                        'fecha' => $first->created_at,
                        'id_paciente' => $first->id_paciente,
                        'paciente' => trim(($first->nombre ?? '').' '.($first->apellido ?? '')),
                        'dpi' => (string) ($first->dpi ?? ''),
                        'total' => (float) $rows->sum(fn ($x) => (float) ($x->costo ?? 0)),
                        'estado_general' => $estadoGeneral,
                        'examenes' => $rows->map(function ($x) {
                            return [
                                'id_paciente_examen' => (int) $x->id_paciente_examen,
                                'codigo' => (string) ($x->codigo_examen ?? ('EXA-'.str_pad((string) $x->id_examen, 5, '0', STR_PAD_LEFT))),
                                'nombre' => (string) ($x->nombre_examen ?? ''),
                                'tipo_muestra' => (string) ($x->tipo_muestra ?? ''),
                                'informacion' => (string) ($x->informacion ?? ''),
                                'costo' => (float) ($x->costo ?? 0),
                                'estado' => (string) ($x->estado ?? 'ingresado'),
                            ];
                        })->values(),
                    ];
                })
                ->values();
        }

        return view('modules.laboratorio', [
            'hasCatalogTable' => $hasTable,
            'examenes' => $examenes,
            'examenesJs' => $examenesJs,
            'pacientesJs' => $pacientesJs,
            'solicitudes' => $solicitudes,
            'solicitudesAgrupadas' => $solicitudesAgrupadas,
        ]);
    }

    public function storeExamen(Request $request): RedirectResponse
    {
        $this->ensureTable();
        $hasTipoMuestra = Schema::hasColumn('examen_laboratorio', 'tipo_muestra');

        $data = $request->validate([
            'nombre_examen' => ['required', 'string', 'max:180', 'regex:/^[\pL\s\'\.-]+$/u', 'unique:examen_laboratorio,nombre_examen'],
            'costo' => ['required', 'numeric', 'min:0'],
            'tipo_muestra' => ['required', 'string', 'max:120'],
            'informacion' => ['nullable', 'string', 'max:1200'],
        ], $this->messages(), $this->attributes());

        $payload = [
            'codigo_examen' => null,
            'nombre_examen' => trim((string) $data['nombre_examen']),
            'costo' => (float) $data['costo'],
            'informacion' => trim((string) ($data['informacion'] ?? '')) ?: null,
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($hasTipoMuestra) {
            $payload['tipo_muestra'] = trim((string) ($data['tipo_muestra'] ?? '')) ?: null;
        }

        $id = DB::table('examen_laboratorio')->insertGetId($payload, 'id_examen');

        DB::table('examen_laboratorio')
            ->where('id_examen', $id)
            ->update([
                'codigo_examen' => 'EXA-'.str_pad((string) $id, 5, '0', STR_PAD_LEFT),
                'updated_at' => now(),
            ]);

        return back()->with('status', 'Examen registrado correctamente.');
    }

    public function updateExamen(Request $request, int $id): RedirectResponse
    {
        $this->ensureTable();
        $hasTipoMuestra = Schema::hasColumn('examen_laboratorio', 'tipo_muestra');

        $exists = DB::table('examen_laboratorio')->where('id_examen', $id)->exists();
        if (!$exists) {
            return back()->withErrors(['examen' => 'El examen no existe.']);
        }

        $data = $request->validate([
            'nombre_examen' => ['required', 'string', 'max:180', 'regex:/^[\pL\s\'\.-]+$/u', 'unique:examen_laboratorio,nombre_examen,'.$id.',id_examen'],
            'costo' => ['required', 'numeric', 'min:0'],
            'tipo_muestra' => ['required', 'string', 'max:120'],
            'informacion' => ['nullable', 'string', 'max:1200'],
        ], $this->messages(), $this->attributes());

        $payload = [
            'nombre_examen' => trim((string) $data['nombre_examen']),
            'costo' => (float) $data['costo'],
            'informacion' => trim((string) ($data['informacion'] ?? '')) ?: null,
            'updated_at' => now(),
        ];

        if ($hasTipoMuestra) {
            $payload['tipo_muestra'] = trim((string) ($data['tipo_muestra'] ?? '')) ?: null;
        }

        DB::table('examen_laboratorio')
            ->where('id_examen', $id)
            ->update($payload);

        return back()->with('status', 'Examen actualizado correctamente.');
    }

    public function toggleExamen(int $id): RedirectResponse
    {
        $this->ensureTable();

        $exam = DB::table('examen_laboratorio')->where('id_examen', $id)->first();
        if (!$exam) {
            return back()->withErrors(['examen' => 'El examen no existe.']);
        }

        DB::table('examen_laboratorio')->where('id_examen', $id)->update([
            'activo' => ((int) $exam->activo === 1) ? 0 : 1,
            'updated_at' => now(),
        ]);

        return back()->with('status', 'Estado del examen actualizado.');
    }

    public function destroyExamen(int $id): RedirectResponse
    {
        $this->ensureTable();
        DB::table('examen_laboratorio')->where('id_examen', $id)->delete();
        return back()->with('status', 'Examen eliminado correctamente.');
    }

    public function storePaciente(Request $request): RedirectResponse
    {
        if (!Schema::hasTable('paciente')) {
            return back()->withErrors(['paciente' => 'No existe la tabla de pacientes.']);
        }

        $data = $request->validate([
            'id_paciente_existente' => ['nullable', 'integer'],
            'nombre' => ['required', 'string', 'max:120', 'regex:/^[\pL\s\'\.-]+$/u'],
            'apellido' => ['required', 'string', 'max:120', 'regex:/^[\pL\s\'\.-]+$/u'],
            'telefono' => ['required', 'regex:/^\d{8}$/'],
            'correo' => ['required', 'email', 'max:120'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'nit' => ['nullable', 'string', 'max:30'],
            'dpi' => ['required', 'regex:/^\d{13}$/'],
            'genero' => ['required', 'string', 'max:20'],
            'fecha_nacimiento' => ['required', 'date', 'before_or_equal:today'],
            'examenes' => ['required', 'array', 'min:1'],
            'examenes.*' => ['required', 'integer', 'distinct', 'exists:examen_laboratorio,id_examen'],
            'confirmar_duplicado' => ['nullable', 'boolean'],
        ], $this->messages(), array_merge($this->attributes(), [
            'id_paciente_existente' => 'paciente existente',
            'nombre' => 'nombre',
            'apellido' => 'apellido',
            'telefono' => 'telefono',
            'correo' => 'correo',
            'direccion' => 'direccion',
            'nit' => 'NIT',
            'dpi' => 'DPI',
            'genero' => 'genero',
            'fecha_nacimiento' => 'fecha de nacimiento',
            'examenes' => 'examenes',
            'examenes.*' => 'examen',
            'confirmar_duplicado' => 'confirmacion de duplicado',
        ]));

        $confirmarDuplicado = (int) ($data['confirmar_duplicado'] ?? 0) === 1;

        // Prevencion de duplicados accidentales:
        // si el paciente ya tiene el mismo examen en estado abierto, pedimos confirmacion explicita.
        if (Schema::hasTable('paciente_examen_laboratorio') && Schema::hasTable('paciente')) {
            $idPacienteRef = 0;
            $idExistente = (int) ($data['id_paciente_existente'] ?? 0);
            if ($idExistente > 0 && DB::table('paciente')->where('id_paciente', $idExistente)->exists()) {
                $idPacienteRef = $idExistente;
            } else {
                $dpi = trim((string) ($data['dpi'] ?? ''));
                if ($dpi !== '' && Schema::hasColumn('paciente', 'dpi')) {
                    $idPacienteRef = (int) (DB::table('paciente')->where('dpi', $dpi)->value('id_paciente') ?? 0);
                }
            }

            if ($idPacienteRef > 0) {
                $abiertos = DB::table('paciente_examen_laboratorio')
                    ->where('id_paciente', $idPacienteRef)
                    ->whereIn('id_examen', array_map('intval', $data['examenes']))
                    ->whereIn('estado', ['ingresado', 'en_proceso'])
                    ->count();

                if ($abiertos > 0 && !$confirmarDuplicado) {
                    return back()
                        ->with('duplicate_warning', 'Este paciente ya tiene '.$abiertos.' examen(es) abierto(s) del mismo tipo.')
                        ->withInput()
                        ->with('active_tab', 'sec-paciente');
                }
            }
        }

        $columns = Schema::getColumnListing('paciente');
        $payload = [];

        foreach (['nombre', 'apellido', 'telefono', 'correo', 'direccion', 'nit', 'dpi', 'genero', 'fecha_nacimiento'] as $col) {
            if (in_array($col, $columns, true)) {
                $value = $data[$col] ?? null;
                if ($value !== null && $value !== '') {
                    $payload[$col] = is_string($value) ? trim($value) : $value;
                }
            }
        }

        if (in_array('nit', $columns, true) && empty(trim((string) ($payload['nit'] ?? '')))) {
            $payload['nit'] = 'CF';
        }

        if (empty($payload)) {
            return back()->withErrors(['paciente' => 'No se pudieron mapear columnas para guardar paciente.']);
        }

        DB::transaction(function () use ($payload, $data, $columns): void {
            $idExistente = (int) ($data['id_paciente_existente'] ?? 0);

            if ($idExistente > 0 && DB::table('paciente')->where('id_paciente', $idExistente)->exists()) {
                DB::table('paciente')->where('id_paciente', $idExistente)->update($payload);
                $idPaciente = $idExistente;
            } else {
                $idPaciente = DB::table('paciente')->insertGetId($payload, 'id_paciente');
            }

            $firstExam = (int) $data['examenes'][0];
            foreach (['id_examen', 'examen_id'] as $examCol) {
                if (in_array($examCol, $columns, true)) {
                    DB::table('paciente')->where('id_paciente', $idPaciente)->update([$examCol => $firstExam]);
                    break;
                }
            }

            if (Schema::hasTable('paciente_examen_laboratorio')) {
                $hasEstado = Schema::hasColumn('paciente_examen_laboratorio', 'estado');
                $hasCodigoSolicitud = Schema::hasColumn('paciente_examen_laboratorio', 'codigo_solicitud');
                $codigoSolicitud = null;
                foreach ($data['examenes'] as $idExamen) {
                    // Cada ingreso genera una nueva solicitud, aunque el paciente
                    // ya tenga el mismo examen registrado previamente.
                    $payloadRel = [
                        'id_paciente' => $idPaciente,
                        'id_examen' => (int) $idExamen,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    if ($hasEstado) {
                        $payloadRel['estado'] = 'ingresado';
                    }
                    if ($hasCodigoSolicitud && $codigoSolicitud !== null) {
                        $payloadRel['codigo_solicitud'] = $codigoSolicitud;
                    }

                    $idRel = DB::table('paciente_examen_laboratorio')->insertGetId($payloadRel, 'id_paciente_examen');

                    if ($hasCodigoSolicitud && $codigoSolicitud === null) {
                        $codigoSolicitud = 'SOL-'.str_pad((string) $idRel, 6, '0', STR_PAD_LEFT);
                        DB::table('paciente_examen_laboratorio')
                            ->where('id_paciente_examen', $idRel)
                            ->update(['codigo_solicitud' => $codigoSolicitud]);
                    }
                }
            }
        });

        $cantidad = count($data['examenes']);
        return back()->with('status', 'Paciente registrado con '.$cantidad.' examen(es) asignado(s).');
    }

    public function updateSolicitudEstado(Request $request, int $id): RedirectResponse
    {
        if (!Schema::hasTable('paciente_examen_laboratorio')) {
            return back()->withErrors(['solicitud' => 'No existe la tabla de solicitudes de laboratorio.']);
        }
        if (!Schema::hasColumn('paciente_examen_laboratorio', 'estado')) {
            return back()->withErrors(['solicitud' => 'No existe la columna estado. Ejecuta migraciones.']);
        }

        $data = $request->validate([
            'estado' => ['required', 'in:ingresado,en_proceso,finalizado,cancelado'],
            'active_tab' => ['nullable', 'string', 'in:sec-catalogo,sec-paciente,sec-examenes'],
            'reopen_codigo_solicitud' => ['nullable', 'string', 'max:80'],
        ], $this->messages(), ['estado' => 'estado']);

        $solicitud = DB::table('paciente_examen_laboratorio')
            ->where('id_paciente_examen', $id)
            ->first(['id_paciente_examen', 'estado']);

        if (!$solicitud) {
            return back()->withErrors(['solicitud' => 'La solicitud no existe.']);
        }

        $estadoActual = in_array((string) $solicitud->estado, ['ingresado', 'en_proceso', 'finalizado', 'cancelado'], true)
            ? (string) $solicitud->estado
            : 'ingresado';
        $estadoNuevo = (string) $data['estado'];

        if (!$this->isTransitionAllowed($estadoActual, $estadoNuevo)) {
            return back()
                ->withErrors(['estado' => 'Transicion de fase no permitida para este examen.'])
                ->with('active_tab', $data['active_tab'] ?? 'sec-examenes');
        }

        $updated = DB::table('paciente_examen_laboratorio')
            ->where('id_paciente_examen', $id)
            ->update([
                'estado' => $data['estado'],
                'updated_at' => now(),
            ]);

        return back()
            ->with('status', 'Estado del examen actualizado correctamente.')
            ->with('active_tab', $data['active_tab'] ?? 'sec-examenes')
            ->with('reopen_codigo_solicitud', (string) ($data['reopen_codigo_solicitud'] ?? ''));
    }

    public function updateSolicitudEstadoGeneral(Request $request): RedirectResponse
    {
        if (!Schema::hasTable('paciente_examen_laboratorio')) {
            return back()->withErrors(['solicitud' => 'No existe la tabla de solicitudes de laboratorio.']);
        }
        if (!Schema::hasColumn('paciente_examen_laboratorio', 'estado')) {
            return back()->withErrors(['solicitud' => 'No existe la columna estado. Ejecuta migraciones.']);
        }

        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer'],
            'estado' => ['required', 'in:ingresado,en_proceso,finalizado,cancelado'],
            'active_tab' => ['nullable', 'string', 'in:sec-catalogo,sec-paciente,sec-examenes'],
        ], $this->messages(), [
            'ids' => 'examenes de la solicitud',
            'ids.*' => 'id de examen',
            'estado' => 'estado',
        ]);

        $ids = collect($data['ids'])->map(fn ($x) => (int) $x)->unique()->values();
        $rows = DB::table('paciente_examen_laboratorio')
            ->whereIn('id_paciente_examen', $ids->all())
            ->get(['id_paciente_examen', 'estado']);

        if ($rows->count() !== $ids->count()) {
            return back()
                ->withErrors(['solicitud' => 'Algunos examenes de la solicitud no existen.'])
                ->with('active_tab', $data['active_tab'] ?? 'sec-examenes');
        }

        $estadoNuevo = (string) $data['estado'];
        $invalidas = 0;
        foreach ($rows as $row) {
            if (!$this->isTransitionAllowed((string) ($row->estado ?? 'ingresado'), $estadoNuevo)) {
                $invalidas++;
            }
        }
        if ($invalidas > 0) {
            return back()
                ->withErrors(['estado' => 'No se pudo aplicar el cambio general porque '.$invalidas.' examen(es) no permiten esa transicion de fase.'])
                ->with('active_tab', $data['active_tab'] ?? 'sec-examenes');
        }

        DB::table('paciente_examen_laboratorio')
            ->whereIn('id_paciente_examen', $ids->all())
            ->update([
                'estado' => $estadoNuevo,
                'updated_at' => now(),
            ]);

        return back()
            ->with('status', 'Estado general actualizado para toda la solicitud.')
            ->with('active_tab', $data['active_tab'] ?? 'sec-examenes');
    }

    private function ensureTable(): void
    {
        if (!Schema::hasTable('examen_laboratorio')) {
            abort(500, 'No existe la tabla de catalogo de examenes. Ejecuta migraciones.');
        }
    }

    private function isTransitionAllowed(string $estadoActualRaw, string $estadoNuevo): bool
    {
        $estadoActual = in_array($estadoActualRaw, ['ingresado', 'en_proceso', 'finalizado', 'cancelado'], true)
            ? $estadoActualRaw
            : 'ingresado';
        $map = [
            'ingresado' => 1,
            'en_proceso' => 2,
            'finalizado' => 3,
            'cancelado' => 3,
        ];

        if (in_array($estadoActual, ['finalizado', 'cancelado'], true) && $estadoNuevo !== $estadoActual) {
            return false;
        }

        // Regla de negocio: no se permite saltar de ingresado a finalizado.
        // Si se cancela desde ingresado, sí es válido.
        if ($estadoActual === 'ingresado' && $estadoNuevo === 'finalizado') {
            return false;
        }

        return ($map[$estadoNuevo] ?? 0) >= ($map[$estadoActual] ?? 0);
    }

    private function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser texto valido.',
            'numeric' => 'El campo :attribute debe ser numerico.',
            'integer' => 'El campo :attribute debe ser un numero entero.',
            'array' => 'El campo :attribute debe ser una lista valida.',
            'distinct' => 'No puedes repetir el mismo examen.',
            'exists' => 'El :attribute seleccionado no existe.',
            'min' => 'El campo :attribute debe ser como minimo :min.',
            'max' => 'El campo :attribute no puede exceder :max caracteres.',
            'unique' => 'Ya existe un examen con ese :attribute.',
            'email' => 'El campo :attribute debe ser un correo valido.',
            'date' => 'El campo :attribute debe tener una fecha valida.',
            'before_or_equal' => 'El campo :attribute no puede ser una fecha futura.',
            'regex' => 'El campo :attribute solo puede contener letras.',
            'nombre_examen.regex' => 'El nombre del examen no puede contener numeros.',
            'telefono.regex' => 'El telefono debe tener exactamente 8 digitos.',
            'dpi.regex' => 'El DPI debe tener exactamente 13 digitos.',
            'in' => 'El campo :attribute seleccionado no es valido.',
        ];
    }

    private function attributes(): array
    {
        return [
            'nombre_examen' => 'nombre del examen',
            'costo' => 'costo',
            'tipo_muestra' => 'tipo de muestra',
            'informacion' => 'informacion',
        ];
    }
}
