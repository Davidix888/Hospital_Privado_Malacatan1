<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use ZipArchive;

class ReportesController extends Controller
{
    public function index(): View
    {
        $usuario = Usuario::with('rol')->findOrFail(Session::get('auth_usuario_id'));
        $rol = Rol::normalizeRoleName((string) optional($usuario->rol)->nombre_rol);
        $inicioMes = Carbon::now()->startOfMonth()->toDateString();

        return view('modules.reportes', [
            'usuario' => $usuario,
            'rol' => $rol,
            'resumenLab' => $this->buildLaboratorioResumen($inicioMes),
            'chartData' => [
                'labEstados' => $this->buildLabEstados(),
                'labMensual' => $this->buildLabMensual(),
                'topExamenes' => $this->buildTopExamenes(),
                'tecnicosRendimiento' => $this->buildTecnicosRendimiento(),
            ],
            'alertas' => $this->buildAlertasLaboratorio(),
            'comparativoMensual' => $this->buildComparativoLaboratorio(),
            'actividadLaboratorio' => $this->buildActividadLaboratorio(),
        ]);
    }

    public function export(Request $request, string $modulo, string $formato): Response|\Symfony\Component\HttpFoundation\StreamedResponse|View
    {
        $modulo = strtolower($modulo);
        $formato = strtolower($formato);

        if ($modulo !== 'laboratorio' || !in_array($formato, ['excel', 'csv', 'pdf'], true)) {
            abort(404);
        }

        $rows = $this->buildActividadLaboratorio();
        $headers = ['Fecha', 'Solicitud', 'Paciente', 'Usuario', 'Estado'];
        $usuarioGenerador = Usuario::find(Session::get('auth_usuario_id'));
        $nombreGenerador = trim((string) (($usuarioGenerador->nombres ?? '').' '.($usuarioGenerador->apellidos ?? '')));
        if ($nombreGenerador === '') {
            $nombreGenerador = (string) ($usuarioGenerador->nombre_usuario ?? 'Usuario no registrado');
        }

        $resumen = $this->buildResumenExportLaboratorio($rows);
        $resumen['Generado por'] = $nombreGenerador;
        $filenameBase = 'Reporte_Laboratorio_'.now()->format('Ymd_His');

        if ($formato === 'excel') {
            $xml = $this->buildExcelXml($headers, $rows, $resumen);
            return response($xml, 200, [
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="'.$filenameBase.'.xls"',
                'Cache-Control' => 'max-age=0, no-cache, no-store, must-revalidate',
                'Pragma' => 'public',
            ]);
        }

        if ($formato === 'csv') {
            $csv = $this->buildLaboratorioCsv($headers, $rows, $resumen);
            return response($csv, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="'.$filenameBase.'.csv"',
                'Cache-Control' => 'max-age=0, no-cache, no-store, must-revalidate',
                'Pragma' => 'public',
            ]);
        }

        return view('modules.reportes_export_pdf', [
            'titulo' => 'Reporte Laboratorio',
            'modulo' => 'laboratorio',
            'headers' => $headers,
            'rows' => $rows,
            'resumenExport' => $resumen,
            'fechaGeneracion' => now()->format('d/m/Y H:i'),
            'usuarioGenerador' => $nombreGenerador,
        ]);
    }

    private function buildLaboratorioResumen(string $inicioMes): array
    {
        $examenesActivos = Schema::hasTable('examen_laboratorio')
            ? DB::table('examen_laboratorio')->where('activo', 1)->count()
            : 0;

        if (!Schema::hasTable('paciente_examen_laboratorio')) {
            return ['examenes_activos' => $examenesActivos, 'solicitudes_mes' => 0, 'pendientes' => 0, 'finalizados_mes' => 0];
        }

        $hasCodigo = Schema::hasColumn('paciente_examen_laboratorio', 'codigo_solicitud');
        $hasEstado = Schema::hasColumn('paciente_examen_laboratorio', 'estado');
        $solicitudesMes = $hasCodigo
            ? DB::table('paciente_examen_laboratorio')->whereDate('created_at', '>=', $inicioMes)->whereNotNull('codigo_solicitud')->distinct('codigo_solicitud')->count('codigo_solicitud')
            : DB::table('paciente_examen_laboratorio')->whereDate('created_at', '>=', $inicioMes)->count();

        return [
            'examenes_activos' => $examenesActivos,
            'solicitudes_mes' => $solicitudesMes,
            'pendientes' => $hasEstado ? DB::table('paciente_examen_laboratorio')->whereIn('estado', ['ingresado', 'en_proceso'])->count() : 0,
            'finalizados_mes' => $hasEstado ? DB::table('paciente_examen_laboratorio')->whereDate('updated_at', '>=', $inicioMes)->where('estado', 'finalizado')->count() : 0,
        ];
    }

    private function buildLabEstados(): array
    {
        $base = ['ingresado' => 0, 'en_proceso' => 0, 'finalizado' => 0, 'cancelado' => 0];
        if (!Schema::hasTable('paciente_examen_laboratorio') || !Schema::hasColumn('paciente_examen_laboratorio', 'estado')) {
            return $base;
        }
        $rows = DB::table('paciente_examen_laboratorio')->select('estado', DB::raw('COUNT(*) as total'))->groupBy('estado')->get();
        foreach ($rows as $row) {
            if (array_key_exists((string) $row->estado, $base)) {
                $base[(string) $row->estado] = (int) $row->total;
            }
        }
        return $base;
    }

    private function buildLabMensual(): array
    {
        $meses = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::now()->startOfMonth()->subMonths($i);
            $meses[$m->format('Y-m')] = ['label' => strtoupper($m->translatedFormat('M y')), 'solicitudes' => 0];
        }
        if (!Schema::hasTable('paciente_examen_laboratorio')) {
            return array_values($meses);
        }
        $raw = DB::table('paciente_examen_laboratorio')
            ->selectRaw("TO_CHAR(created_at, 'YYYY-MM') as mes")
            ->selectRaw('COUNT(*) as total')
            ->whereDate('created_at', '>=', Carbon::now()->startOfMonth()->subMonths(5)->toDateString())
            ->groupByRaw("TO_CHAR(created_at, 'YYYY-MM')")
            ->get();
        foreach ($raw as $row) {
            $key = (string) $row->mes;
            if (isset($meses[$key])) {
                $meses[$key]['solicitudes'] = (int) $row->total;
            }
        }
        return array_values($meses);
    }

    private function buildTopExamenes(): array
    {
        if (!Schema::hasTable('paciente_examen_laboratorio') || !Schema::hasTable('examen_laboratorio')) {
            return [];
        }
        return DB::table('paciente_examen_laboratorio as pel')
            ->join('examen_laboratorio as e', 'e.id_examen', '=', 'pel.id_examen')
            ->select('e.nombre_examen')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('e.nombre_examen')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($r) => ['nombre' => (string) $r->nombre_examen, 'total' => (int) $r->total])
            ->values()
            ->all();
    }

    private function buildTecnicosRendimiento(): array
    {
        if (!Schema::hasTable('paciente_examen_laboratorio') || !Schema::hasColumn('paciente_examen_laboratorio', 'id_usuario')) {
            return [];
        }

        $query = DB::table('paciente_examen_laboratorio as pel')
            ->leftJoin('usuario as u', 'u.id_usuario', '=', 'pel.id_usuario')
            ->select(
                'pel.id_usuario',
                DB::raw("COALESCE(NULLIF(TRIM(u.nombres || ' ' || u.apellidos), ''), u.nombre_usuario, 'Usuario # ' || pel.id_usuario) as tecnico"),
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('pel.id_usuario');

        return $query
            ->groupBy('pel.id_usuario', 'u.nombres', 'u.apellidos', 'u.nombre_usuario')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(fn ($r) => [
                'tecnico' => (string) $r->tecnico,
                'total' => (int) $r->total,
            ])
            ->values()
            ->all();
    }

    private function buildActividadLaboratorio(): array
    {
        if (!Schema::hasTable('paciente_examen_laboratorio')) {
            return [];
        }
        $hasCodigo = Schema::hasColumn('paciente_examen_laboratorio', 'codigo_solicitud');
        $hasEstado = Schema::hasColumn('paciente_examen_laboratorio', 'estado');
        $hasUsuario = Schema::hasColumn('paciente_examen_laboratorio', 'id_usuario');

        $query = DB::table('paciente_examen_laboratorio as pel')
            ->leftJoin('paciente as p', 'p.id_paciente', '=', 'pel.id_paciente')
            ->select(
                'pel.id_paciente_examen',
                'pel.created_at',
                DB::raw("COALESCE(p.nombre || ' ' || p.apellido, 'Paciente no registrado') as referencia"),
                DB::raw("'No registrado' as usuario")
            )
            ->orderByDesc('pel.id_paciente_examen')
            ->limit(30);

        if ($hasCodigo) $query->addSelect('pel.codigo_solicitud');
        if ($hasEstado) $query->addSelect('pel.estado');
        if ($hasUsuario) {
            $query->leftJoin('usuario as u', 'u.id_usuario', '=', 'pel.id_usuario')
                ->addSelect(DB::raw("COALESCE(u.nombres || ' ' || u.apellidos, u.nombre_usuario, 'No registrado') as usuario"));
        }

        return $query->get()->map(fn ($r) => [
            'fecha' => Carbon::parse($r->created_at)->toDateString(),
            'solicitud' => (($r->codigo_solicitud ?? null) ?: 'SOL-'.$r->id_paciente_examen),
            'paciente' => (string) $r->referencia,
            'usuario' => (string) ($r->usuario ?? 'No registrado'),
            'dato' => ucfirst((string) ($r->estado ?? 'ingresado')),
        ])->values()->all();
    }

    private function buildAlertasLaboratorio(): array
    {
        $alertas = [];
        if (Schema::hasTable('paciente_examen_laboratorio') && Schema::hasColumn('paciente_examen_laboratorio', 'estado')) {
            $atrasadas = DB::table('paciente_examen_laboratorio')
                ->whereIn('estado', ['ingresado', 'en_proceso'])
                ->whereDate('created_at', '<=', Carbon::now()->subDays(2)->toDateString())
                ->count();
            if ($atrasadas > 0) {
                $alertas[] = ['nivel' => 'media', 'titulo' => 'Solicitudes atrasadas', 'detalle' => $atrasadas.' solicitud(es) superan 48 horas.'];
            }
        }
        if (empty($alertas)) {
            $alertas[] = ['nivel' => 'ok', 'titulo' => 'Sin alertas criticas', 'detalle' => 'No se detectaron riesgos operativos inmediatos.'];
        }
        return $alertas;
    }

    private function buildComparativoLaboratorio(): array
    {
        $inicioActual = Carbon::now()->startOfMonth()->toDateString();
        $inicioSig = Carbon::now()->startOfMonth()->addMonth()->toDateString();
        $inicioAnt = Carbon::now()->startOfMonth()->subMonth()->toDateString();
        $finAnt = Carbon::now()->startOfMonth()->toDateString();

        $actual = Schema::hasTable('paciente_examen_laboratorio')
            ? DB::table('paciente_examen_laboratorio')->whereDate('created_at', '>=', $inicioActual)->whereDate('created_at', '<', $inicioSig)->count()
            : 0;
        $anterior = Schema::hasTable('paciente_examen_laboratorio')
            ? DB::table('paciente_examen_laboratorio')->whereDate('created_at', '>=', $inicioAnt)->whereDate('created_at', '<', $finAnt)->count()
            : 0;

        return [[
            'label' => 'Solicitudes de laboratorio',
            'valor' => number_format($actual),
            'delta' => $this->calcDelta($actual, $anterior),
        ]];
    }

    private function calcDelta(float|int $actual, float|int $anterior): string
    {
        if ((float) $anterior === 0.0) return ((float) $actual === 0.0) ? '0%' : '+100%';
        $pct = (($actual - $anterior) / $anterior) * 100;
        return ($pct > 0 ? '+' : '').number_format($pct, 1).'%';
    }

    private function buildResumenExportLaboratorio(array $rows): array
    {
        $total = count($rows);
        $finalizadas = collect($rows)->filter(fn ($r) => mb_strtolower((string) $r['dato']) === 'finalizado')->count();
        $abiertas = collect($rows)->filter(fn ($r) => in_array(mb_strtolower((string) $r['dato']), ['ingresado', 'en proceso'], true))->count();
        return [
            'Total de solicitudes' => number_format($total),
            'Finalizadas' => number_format($finalizadas),
            'Abiertas' => number_format($abiertas),
        ];
    }

    private function buildLaboratorioCsv(array $headers, array $rows, array $resumen): string
    {
        $fp = fopen('php://temp', 'r+');
        if ($fp === false) {
            return '';
        }

        fwrite($fp, "\xEF\xBB\xBF");
        fwrite($fp, "sep=;\r\n");
        fputcsv($fp, ['Reporte', 'Laboratorio'], ';');
        fputcsv($fp, ['Hospital', 'Hospital Privado Malacatan'], ';');
        fputcsv($fp, ['Sistema', 'Gestion clinica y operativa'], ';');
        fputcsv($fp, []);

        fputcsv($fp, ['RESUMEN', 'VALOR'], ';');
        foreach ($resumen as $k => $v) {
            fputcsv($fp, [$k, $v], ';');
        }
        fputcsv($fp, []);

        fputcsv($fp, ['DETALLE DE SOLICITUDES'], ';');
        fputcsv($fp, $headers, ';');
        foreach ($rows as $row) {
            fputcsv($fp, [
                (string) ($row['fecha'] ?? ''),
                (string) ($row['solicitud'] ?? ''),
                (string) ($row['paciente'] ?? ''),
                (string) ($row['usuario'] ?? ''),
                (string) ($row['dato'] ?? ''),
            ], ';');
        }

        rewind($fp);
        $csv = stream_get_contents($fp) ?: '';
        fclose($fp);
        return $csv;
    }
    private function xmlCell(string $value, string $styleId = 'text'): string
    {
        return '<Cell ss:StyleID="'.$styleId.'"><Data ss:Type="String">'.e($value).'</Data></Cell>';
    }

    private function buildExcelXml(array $headers, array $rows, array $resumen): string
    {
        $sheetRows = [];
        $sheetRows[] = '<Row><Cell ss:StyleID="summaryLabel"><Data ss:Type="String">Reporte</Data></Cell><Cell ss:StyleID="summaryValue"><Data ss:Type="String">Laboratorio</Data></Cell></Row>';
        $sheetRows[] = '<Row><Cell ss:StyleID="summaryLabel"><Data ss:Type="String">Hospital</Data></Cell><Cell ss:StyleID="summaryValue"><Data ss:Type="String">Hospital Privado Malacatan</Data></Cell></Row>';
        $sheetRows[] = '<Row><Cell ss:StyleID="summaryLabel"><Data ss:Type="String">Sistema</Data></Cell><Cell ss:StyleID="summaryValue"><Data ss:Type="String">Gestion clinica y operativa</Data></Cell></Row>';
        $sheetRows[] = '<Row/>';
        $sheetRows[] = '<Row><Cell ss:StyleID="section"><Data ss:Type="String">RESUMEN</Data></Cell><Cell ss:StyleID="section"><Data ss:Type="String">VALOR</Data></Cell></Row>';
        foreach ($resumen as $k => $v) {
            $sheetRows[] = '<Row>'.$this->xmlCell((string) $k, 'summaryLabel').$this->xmlCell((string) $v, 'summaryValue').'</Row>';
        }
        $sheetRows[] = '<Row/>';
        $sheetRows[] = '<Row><Cell ss:MergeAcross="4" ss:StyleID="title"><Data ss:Type="String">DETALLE DE SOLICITUDES</Data></Cell></Row>';
        $sheetRows[] = '<Row>'
            .$this->xmlCell((string) $headers[0], 'header')
            .$this->xmlCell((string) $headers[1], 'header')
            .$this->xmlCell((string) $headers[2], 'header')
            .$this->xmlCell((string) $headers[3], 'header')
            .$this->xmlCell((string) $headers[4], 'header')
            .'</Row>';

        if (empty($rows)) {
            $sheetRows[] = '<Row><Cell ss:MergeAcross="4" ss:StyleID="empty"><Data ss:Type="String">Sin datos disponibles</Data></Cell></Row>';
        } else {
            foreach ($rows as $row) {
                $sheetRows[] = '<Row>'
                    .$this->xmlCell((string) $row['fecha'])
                    .$this->xmlCell((string) $row['solicitud'])
                    .$this->xmlCell((string) $row['paciente'])
                    .$this->xmlCell((string) $row['usuario'])
                    .$this->xmlCell((string) $row['dato'])
                    .'</Row>';
            }
        }

        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<?mso-application progid="Excel.Sheet"?>'
            .'<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'
            .'<Styles>'
            .'<Style ss:ID="title"><Font ss:Bold="1" ss:Size="12" ss:Color="#12345A"/><Interior ss:Color="#DCE8F7" ss:Pattern="Solid"/></Style>'
            .'<Style ss:ID="section"><Font ss:Bold="1" ss:Color="#12345A"/><Interior ss:Color="#EAF1FB" ss:Pattern="Solid"/></Style>'
            .'<Style ss:ID="summaryLabel"><Font ss:Bold="1" ss:Color="#1E3A5F"/><Interior ss:Color="#F3F8FF" ss:Pattern="Solid"/></Style>'
            .'<Style ss:ID="summaryValue"><Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/></Style>'
            .'<Style ss:ID="header"><Font ss:Bold="1" ss:Color="#1E3A5F"/><Interior ss:Color="#DCE8F7" ss:Pattern="Solid"/></Style>'
            .'<Style ss:ID="text"><Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/></Style>'
            .'<Style ss:ID="empty"><Font ss:Italic="1" ss:Color="#64748B"/></Style>'
            .'</Styles>'
            .'<Worksheet ss:Name="Reporte Laboratorio"><Table>'
            .'<Column ss:AutoFitWidth="0" ss:Width="90"/>'
            .'<Column ss:AutoFitWidth="0" ss:Width="120"/>'
            .'<Column ss:AutoFitWidth="0" ss:Width="250"/>'
            .'<Column ss:AutoFitWidth="0" ss:Width="170"/>'
            .'<Column ss:AutoFitWidth="0" ss:Width="110"/>'
            .implode('', $sheetRows)
            .'</Table></Worksheet></Workbook>';
    }

    private function xlsxEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private function colLetter(int $index): string
    {
        $index++;
        $letter = '';
        while ($index > 0) {
            $mod = ($index - 1) % 26;
            $letter = chr(65 + $mod).$letter;
            $index = intdiv($index - 1, 26);
        }
        return $letter;
    }

    private function buildExcelXlsx(array $headers, array $rows, array $resumen): string
    {
        $shared = [];
        $sharedMap = [];
        $si = function (string $text) use (&$shared, &$sharedMap): int {
            if (isset($sharedMap[$text])) return $sharedMap[$text];
            $idx = count($shared);
            $shared[] = $text;
            $sharedMap[$text] = $idx;
            return $idx;
        };

        $sheetRows = [];
        $rowNum = 1;
        $sheetRows[] = '<row r="'.$rowNum.'"><c r="A'.$rowNum.'" t="s" s="1"><v>'.$si('Reporte Laboratorio').'</v></c></row>';
        $rowNum++;
        $sheetRows[] = '<row r="'.$rowNum.'"><c r="A'.$rowNum.'" t="s" s="2"><v>'.$si('Generado: '.now()->format('d/m/Y H:i')).'</v></c></row>';
        $rowNum += 2;

        foreach ($resumen as $k => $v) {
            $sheetRows[] = '<row r="'.$rowNum.'">'
                .'<c r="A'.$rowNum.'" t="s" s="3"><v>'.$si((string) $k).'</v></c>'
                .'<c r="B'.$rowNum.'" t="s" s="4"><v>'.$si((string) $v).'</v></c>'
                .'</row>';
            $rowNum++;
        }
        $rowNum++;

        $headerCells = '';
        foreach ($headers as $i => $h) {
            $cell = $this->colLetter($i).$rowNum;
            $headerCells .= '<c r="'.$cell.'" t="s" s="5"><v>'.$si((string) $h).'</v></c>';
        }
        $sheetRows[] = '<row r="'.$rowNum.'">'.$headerCells.'</row>';
        $rowNum++;

        if (empty($rows)) {
            $sheetRows[] = '<row r="'.$rowNum.'"><c r="A'.$rowNum.'" t="s" s="6"><v>'.$si('Sin datos disponibles').'</v></c></row>';
        } else {
            foreach ($rows as $r) {
                $vals = [(string) $r['fecha'], (string) $r['solicitud'], (string) $r['paciente'], (string) $r['usuario'], (string) $r['dato']];
                $cells = '';
                foreach ($vals as $i => $v) {
                    $cell = $this->colLetter($i).$rowNum;
                    $cells .= '<c r="'.$cell.'" t="s" s="0"><v>'.$si($v).'</v></c>';
                }
                $sheetRows[] = '<row r="'.$rowNum.'">'.$cells.'</row>';
                $rowNum++;
            }
        }

        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>'
            .implode('', $sheetRows)
            .'</sheetData></worksheet>';

        $sharedItems = '';
        foreach ($shared as $txt) {
            $sharedItems .= '<si><t>'.$this->xlsxEscape($txt).'</t></si>';
        }
        $sharedXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="'.count($shared).'" uniqueCount="'.count($shared).'">'
            .$sharedItems
            .'</sst>';

        $stylesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<fonts count="4"><font><sz val="10"/><name val="Calibri"/></font><font><b/><sz val="14"/><color rgb="FF12345A"/><name val="Calibri"/></font><font><sz val="10"/><color rgb="FF1E3A5F"/><name val="Calibri"/></font><font><b/><sz val="10"/><color rgb="FF1E3A5F"/><name val="Calibri"/></font></fonts>'
            .'<fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FFEAF1FB"/><bgColor indexed="64"/></patternFill></fill></fills>'
            .'<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            .'<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            .'<cellXfs count="7">'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            .'<xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0"/>'
            .'<xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0"/>'
            .'<xf numFmtId="0" fontId="3" fillId="2" borderId="0" xfId="0"/>'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            .'<xf numFmtId="0" fontId="3" fillId="2" borderId="0" xfId="0"/>'
            .'<xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0"/>'
            .'</cellXfs><cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles></styleSheet>';

        $workbookXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets><sheet name="Reporte Laboratorio" sheetId="1" r:id="rId1"/></sheets></workbook>';
        $relsRootXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>';
        $relsWorkbookXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/><Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/></Relationships>';
        $typesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/><Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/></Types>';

        $tmpBase = tempnam(sys_get_temp_dir(), 'xlsx_');
        if ($tmpBase === false) {
            throw new \RuntimeException('No se pudo crear archivo temporal para Excel.');
        }
        @unlink($tmpBase);
        $tmp = $tmpBase.'.xlsx';
        $zip = new ZipArchive();
        $openResult = $zip->open($tmp, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($openResult !== true) {
            throw new \RuntimeException('No se pudo inicializar el paquete XLSX.');
        }
        $zip->addFromString('[Content_Types].xml', $typesXml);
        $zip->addFromString('_rels/.rels', $relsRootXml);
        $zip->addFromString('xl/workbook.xml', $workbookXml);
        $zip->addFromString('xl/_rels/workbook.xml.rels', $relsWorkbookXml);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
        $zip->addFromString('xl/styles.xml', $stylesXml);
        $zip->addFromString('xl/sharedStrings.xml', $sharedXml);
        if (!$zip->close()) {
            @unlink($tmp);
            throw new \RuntimeException('No se pudo finalizar el archivo XLSX.');
        }
        $binary = (string) file_get_contents($tmp);
        @unlink($tmp);
        if ($binary === '') {
            throw new \RuntimeException('El archivo XLSX generado está vacío.');
        }
        return $binary;
    }
}
