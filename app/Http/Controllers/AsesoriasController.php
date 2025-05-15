<?php

namespace App\Http\Controllers;

use App\Models\Asesoria;
use App\Models\Usuario;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsesoriasController extends Controller
{    
    public function estudianteSolicitarGet()
    {
        $materias = Materia::all();
        return view('asesorias.solicitar', [
            'materias' => $materias,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Asesorías' => url('/asesorias'),
                'Solicitar ' => url('/asesorias/solicitar')
            ]
        ]);
    }

    public function asesoresPorMateria($id)
    {
        $asesores = Usuario::whereHas('materias', function ($q) use ($id) {
            $q->where('materia.id_materia', $id);
        })->get();

        return response()->json($asesores);
    }

    public function estudianteSolicitarPost(Request $request)
    {
        $request->validate([
            'materia' => 'required|exists:materia,id_materia',
            'asesor' => 'required|exists:usuario,id_usuario',
            'tema' => 'required|string|max:255',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'duracion' => 'required|in:30,45,60,90',
        ]);

        // Convertimos los minutos a formato TIME (HH:MM:SS)
        $horas = floor($request->duracion / 60);
        $minutos = $request->duracion % 60;
        $duracionFormatted = sprintf('%02d:%02d:00', $horas, $minutos);
        
        Asesoria::create([
            'tema' => $request->tema,
            'fecha' => $request->fecha . ' ' . $request->hora,
            'duracion' => $duracionFormatted,
            'fk_id_materia' => $request->materia,
            'fk_id_asesor' => $request->asesor,
            'fk_id_estudiante' => Auth::user()->id_usuario,
            'estado' => 'PENDIENTE',
        ]);
        session()->flash('tipo', 'success');  // Tipo de mensaje: 'success', 'error', etc.
        session()->flash('mensaje', '¡Asesoria solicitada correctamente!'); // Mensaje a mostrar
        return redirect('/asesorias');
    }

    public function asesorSolicitudesGet()
    {
        $asesorias = Asesoria::with(['estudiante', 'materia'])
            ->where('fk_id_asesor', Auth::user()->id_usuario)
            ->whereIn('estado', ['PENDIENTE', 'CONFIRMADA', 'PROCESO', 'FINALIZADA', 'CANCELADA']) // Incluir pendientes y confirmadas
            ->whereRaw("ADDTIME(fecha, duracion) >= ?", [now()]) // Filtrar por fecha y duración
            ->orderBy('id_asesoria', 'desc')
            ->get();

        return view('asesorias.solicitudes', [
            'asesorias' => $asesorias,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Asesorías' => url('/asesoriasa'),
                'Solicitudes' => url('/asesoriasa/solicitudes')
            ]
        ]);
    }

    public function solicitudesPendientesGet()
    {
        $asesorias = Asesoria::with(['estudiante', 'materia'])
            ->where('fk_id_asesor', Auth::user()->id_usuario)
            ->where('estado', 'PENDIENTE')
            ->orderBy('fecha', 'asc')
            ->get();

        return view('asesorias.solicitudes', [
            'asesorias' => $asesorias,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Asesorías' => url('/asesoriasa'),
                'Solicitudes' => url('/asesoriasa/solicitudes')
            ]
        ]);
    }

    public function actualizarEstado(Request $request, $id)
    {
        $asesoria = Asesoria::findOrFail($id);

        if ($asesoria->fk_id_asesor != Auth::user()->id_usuario) {
            abort(403);
        }

        $request->validate([
            'estado' => 'required|in:PENDIENTE,CONFIRMADA,PROCESO,FINALIZADA,CANCELADA',
            'observaciones' => $request->estado === 'CANCELADA' ? 'required|string|min:10' : 'nullable',
        ]);

        $estado_anterior = $asesoria->estado;
        $asesoria->estado = $request->estado;
        
        // Guardar observaciones cuando se cancela una asesoría
        if ($request->estado === 'CANCELADA' && $request->has('observaciones')) {
            $asesoria->observaciones = $request->observaciones;
        }
        
        $asesoria->save();

        session()->flash('tipo', 'success');
        session()->flash('mensaje', '¡Estado actualizado correctamente!');

        // Redireccionar según el estado anterior y el nuevo
        if ($request->estado === 'CONFIRMADA') {
            return redirect()->route('asesoriasa.activas.get');
        } elseif ($request->estado === 'PROCESO') {
            // Mantener al usuario en la vista de detalle al iniciar la asesoría
            return redirect()->route('asesoriasa.detalle.get', $id);
        } elseif (in_array($request->estado, ['FINALIZADA', 'CANCELADA'])) {
            return redirect()->route('asesoriasa.historial.get');
        } elseif ($estado_anterior === 'PENDIENTE') {
            return redirect()->route('asesoriasa.solicitudes.get');
        } else {
            return redirect()->route('asesoriasa.activas.get');
        }
    }

    public function misAsesoriasGet()
    {
        $asesorias = Asesoria::with(['materia', 'asesor'])
            ->where('fk_id_estudiante', Auth::id())
            ->whereIn('estado', ['PENDIENTE', 'CONFIRMADA', 'PROCESO', 'FINALIZADA', 'CANCELADA']) // Incluir pendientes y confirmadas
            ->where('fecha', '>=', now()) // Filtrar por fecha futura o actual
            ->orderBy('fecha', 'asc')
            ->get();

        return view('asesorias.mis-asesorias', [
            'asesorias' => $asesorias,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Asesorías' => url('/asesorias'),
            ]
        ]);
    }

    public function todasAsesoriasGet()
    {
        $asesorias = Asesoria::with(['estudiante', 'materia'])
            ->where('fk_id_asesor', Auth::user()->id_usuario)
            ->whereIn('estado', ['CANCELADA', 'FINALIZADA'])
            ->orderBy('fecha', 'desc')
            ->get();

        return view('asesorias.todas-asesorias', [
            'asesorias' => $asesorias,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Asesorías' => url('/asesoriasa'),
                'Historial' => url('/asesoriasa/historial')
            ]
        ]);
    }

    public function countPendingSolicitudes()
    {
        $count = Asesoria::where('fk_id_asesor', Auth::user()->id_usuario)
            ->where('estado', 'PENDIENTE')
            ->count();

        return response()->json(['count' => $count]);
    }
    
    /**
     * Cuenta el número de asesorías activas para el asesor autenticado
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function countActiveAsesorias()
    {
        $count = Asesoria::where('fk_id_asesor', Auth::user()->id_usuario)
            ->whereIn('estado', ['CONFIRMADA', 'PROCESO'])
            ->count();

        return response()->json(['count' => $count]);
    }

    public function asesoriasActivasGet()
    {
        $asesorias = Asesoria::with(['estudiante', 'materia'])
            ->where('fk_id_asesor', Auth::user()->id_usuario)
            ->whereIn('estado', ['CONFIRMADA', 'PROCESO'])
            ->orderBy('fecha', 'asc')
            ->get();

        return view('asesorias.asesorias-activas', [
            'asesorias' => $asesorias,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Asesorías' => url('/asesoriasa')
            ]
        ]);
    }

    public function detalleAsesoriaGet($id)
    {
        $asesoria = Asesoria::with(['estudiante', 'materia'])
            ->where('id_asesoria', $id)
            ->where('fk_id_asesor', Auth::user()->id_usuario)
            ->firstOrFail();

        // Determinar la vista anterior basada en el estado de la asesoría
        $vistaAnterior = '';
        $nombreVistaAnterior = '';
        
        if (in_array($asesoria->estado, ['CONFIRMADA', 'PROCESO'])) {
            $vistaAnterior = url('/asesoriasa/activas');
            $nombreVistaAnterior = 'Activas';
        } elseif ($asesoria->estado === 'PENDIENTE') {
            $vistaAnterior = url('/asesoriasa/solicitudes');
            $nombreVistaAnterior = 'Solicitudes';
        } elseif (in_array($asesoria->estado, ['FINALIZADA', 'CANCELADA'])) {
            $vistaAnterior = url('/asesoriasa/historial');
            $nombreVistaAnterior = 'Historial';
        }

        // Generamos o recuperamos la URL de videoconferencia única para esta asesoría
        $videoconferenciaUrl = $this->generarUrlVideoconferencia($id);
        
        return view('asesorias.detalle-asesoria', [
            'asesoria' => $asesoria,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Asesorías' => url('/asesoriasa'),
                $nombreVistaAnterior => $vistaAnterior,
                'Detalle' => url('/asesoriasa/detalle/' . $id)
            ],
            'videoconferenciaUrl' => $videoconferenciaUrl,
            'meetCode' => $asesoria->meet_code // Código en formato xxx-yyyy-zzz
        ]);
    }

    // Métodos para estudiantes
    
    public function estudianteAsesoriasActivasGet()
    {
        $asesorias = Asesoria::with(['materia', 'asesor'])
            ->where('fk_id_estudiante', Auth::id())
            ->whereIn('estado', ['CONFIRMADA', 'PROCESO'])
            ->orderBy('fecha', 'asc')
            ->get();

        return view('asesorias.estudiante.asesorias-activas', [
            'asesorias' => $asesorias,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Asesorías' => url('/asesorias')
            ]
        ]);
    }

    public function estudianteSolicitudesPendientesGet()
    {
        $asesorias = Asesoria::with(['materia', 'asesor'])
            ->where('fk_id_estudiante', Auth::id())
            ->where('estado', 'PENDIENTE')
            ->orderBy('fecha', 'asc')
            ->get();

        return view('asesorias.estudiante.solicitudes-pendientes', [
            'asesorias' => $asesorias,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Asesorías' => url('/asesorias'),
                'Solicitudes' => url('/asesorias/pendientes')
            ]
        ]);
    }

    public function estudianteHistorialGet()
    {
        $asesorias = Asesoria::with(['materia', 'asesor'])
            ->where('fk_id_estudiante', Auth::id())
            ->whereIn('estado', ['FINALIZADA', 'CANCELADA'])
            ->orderBy('fecha', 'desc')
            ->get();

        return view('asesorias.estudiante.historial', [
            'asesorias' => $asesorias,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Asesorías' => url('/asesorias'),
                'Historial' => url('/asesorias/historial')
            ]
        ]);
    }

    public function estudianteDetalleAsesoriaGet($id)
    {
        $asesoria = Asesoria::with(['materia', 'asesor'])
            ->where('id_asesoria', $id)
            ->where('fk_id_estudiante', Auth::id())
            ->firstOrFail();

        // Determinar la vista anterior basada en el estado de la asesoría
        $vistaAnterior = '';
        $nombreVistaAnterior = '';
        
        if (in_array($asesoria->estado, ['CONFIRMADA', 'PROCESO'])) {
            $vistaAnterior = url('/asesorias');
            $nombreVistaAnterior = 'Activas';
        } elseif ($asesoria->estado === 'PENDIENTE') {
            $vistaAnterior = url('/asesorias/pendientes');
            $nombreVistaAnterior = 'Solicitudes';
        } elseif (in_array($asesoria->estado, ['FINALIZADA', 'CANCELADA'])) {
            $vistaAnterior = url('/asesorias/historial');
            $nombreVistaAnterior = 'Historial';
        }

        // Generamos o recuperamos la URL de videoconferencia única para esta asesoría
        $videoconferenciaUrl = $this->generarUrlVideoconferencia($id);
        
        return view('asesorias.estudiante.detalle-asesoria', [
            'asesoria' => $asesoria,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Asesorías' => url('/asesorias'),
                $nombreVistaAnterior => $vistaAnterior,
                'Detalle' => url('/asesorias/detalle/' . $id)
            ],
            'videoconferenciaUrl' => $videoconferenciaUrl,
            'meetCode' => $asesoria->meet_code // Código en formato xxx-yyyy-zzz
        ]);
    }

    /**
     * Obtiene la URL de videoconferencia para una asesoría
     * 
     * @param int $asesoriaId El ID de la asesoría
     * @return string La URL de la videoconferencia o URL para crearla
     */
    private function generarUrlVideoconferencia($asesoriaId)
    {
        // Buscar la asesoría
        $asesoria = Asesoria::findOrFail($asesoriaId);
        
        // Si ya tiene una URL de videoconferencia válida, la devolvemos
        if (!empty($asesoria->videoconference_url) && strpos($asesoria->videoconference_url, 'meet.google.com/') !== false) {
            return $asesoria->videoconference_url;
        }
        
        // Si no tiene URL, devolvemos la URL para crear una nueva en Google Meet
        return 'https://meet.google.com/new';
    }
    
    /**
     * Guarda la URL de videoconferencia para una asesoría
     * 
     * @param Request $request La solicitud con los datos
     * @return \Illuminate\Http\RedirectResponse
     */
    public function guardarEnlaceMeet(Request $request)
    {
        $request->validate([
            'id_asesoria' => 'required|exists:asesoria,id_asesoria',
            'enlace_meet' => 'required|url|starts_with:https://meet.google.com/',
        ]);

        $asesoria = Asesoria::findOrFail($request->id_asesoria);
        
        // Verificar permisos (solo el asesor puede actualizar el enlace)
        if ($asesoria->fk_id_asesor != Auth::id()) {
            abort(403, 'No tienes permisos para actualizar esta asesoría');
        }

        // Extraer el código de reunión del enlace
        $meetCode = str_replace('https://meet.google.com/', '', $request->enlace_meet);
        
        // Guardar el enlace en la base de datos
        $asesoria->videoconference_url = $request->enlace_meet;
        $asesoria->meet_code = $meetCode;
        $asesoria->save();
        
        session()->flash('tipo', 'success');
        session()->flash('mensaje', '¡Enlace de reunión guardado correctamente!');
        
        return redirect()->route('asesoriasa.detalle.get', $request->id_asesoria);
    }

    /**
     * Cuenta las solicitudes pendientes para un estudiante
     */
    public function countPendingSolicitudesEstudiante()
    {
        $count = Asesoria::where('fk_id_estudiante', Auth::user()->id_usuario)
            ->where('estado', 'PENDIENTE')
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Cuenta las asesorías activas para un estudiante
     */
    public function countActiveAsesoriasEstudiante()
    {
        $count = Asesoria::where('fk_id_estudiante', Auth::user()->id_usuario)
            ->whereIn('estado', ['CONFIRMADA', 'PROCESO'])
            ->count();

        return response()->json(['count' => $count]);
    }
}


