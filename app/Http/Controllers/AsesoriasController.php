<?php

namespace App\Http\Controllers;

use App\Models\Asesoria;
use App\Models\Usuario;
use App\Models\Materia;
use App\Models\DisponibilidadAsesor;
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
        try {
            // Verificar que exista la materia
            $materia = Materia::find($id);
            
            if (!$materia) {
                return response()->json(['error' => 'Materia no encontrada'], 404);
            }
            
            // Corregir la consulta para usar el nombre correcto de la tabla y columna
            $asesores = Usuario::whereHas('materias', function ($q) use ($id) {
                $q->where('asesor_materia.fk_id_materia', $id);
            })->where('rol', 'ASESOR')->get();
            
            // Registrar información para depuración
            \Illuminate\Support\Facades\Log::info("Asesores encontrados para materia $id: " . $asesores->count());
            
            return response()->json($asesores);
        } catch (\Exception $e) {
            // Registrar el error para diagnóstico
            \Illuminate\Support\Facades\Log::error("Error al obtener asesores por materia: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Obtiene las disponibilidades de un asesor para una fecha específica
     */
    public function disponibilidadesAsesor($id_asesor, $fecha)
    {
        // Obtener el día de la semana de la fecha como nombre (Lunes, Martes, etc.)
        $diasSemana = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        $diaSemanaNumero = date('N', strtotime($fecha));
        $diaSemana = $diasSemana[$diaSemanaNumero];
        
        // Registrar información de depuración
        \Illuminate\Support\Facades\Log::info("Consultando disponibilidad para asesor $id_asesor en fecha $fecha (día $diaSemana)");
        
        // Buscar disponibilidades del asesor para ese día de la semana y con estado ACTIVO
        $disponibilidades = DisponibilidadAsesor::getDisponibilidadesPorDia($id_asesor, $diaSemana);
        
        // Registrar cuántas disponibilidades se encontraron
        \Illuminate\Support\Facades\Log::info("Se encontraron " . $disponibilidades->count() . " disponibilidades para el asesor $id_asesor en día $diaSemana");
        
        // Verificar si hay asesorías programadas para esa fecha que puedan afectar la disponibilidad
        $asesoriasOcupadas = Asesoria::where('fk_id_asesor', $id_asesor)
            ->whereDate('fecha', $fecha)
            ->whereIn('estado', ['PENDIENTE', 'CONFIRMADA', 'PROCESO'])
            ->get(['fecha', 'duracion']);
        
        // Si no hay disponibilidades, mostrar un mensaje de depuración y devolver array vacío
        if ($disponibilidades->isEmpty()) {
            // Para propósitos de prueba, si estamos en ambiente local, generemos algunos horarios ficticios
            if (app()->environment('local')) {
                \Illuminate\Support\Facades\Log::info("Generando horarios ficticios para pruebas");
                
                $horariosDisponibles = [];
                // Generar horarios ficticios para pruebas (de 9am a 5pm)
                for ($hora = 9; $hora <= 16; $hora++) {
                    $horariosDisponibles[] = sprintf('%02d:00', $hora);
                    $horariosDisponibles[] = sprintf('%02d:30', $hora);
                }
                
                return response()->json($horariosDisponibles);
            }
            
            return response()->json([]);
        }
        
        // Preparar los horarios disponibles
        $horariosDisponibles = [];
        
        foreach ($disponibilidades as $disponibilidad) {
            // Registrar información de cada disponibilidad
            \Illuminate\Support\Facades\Log::info("Procesando disponibilidad: día={$disponibilidad->dia_semana}, inicio={$disponibilidad->hora_inicio}, fin={$disponibilidad->hora_fin}");
            
            // Convertir horas de inicio y fin a minutos para facilitar cálculos
            $inicioMinutos = $this->horaAMinutos($disponibilidad->hora_inicio);
            $finMinutos = $this->horaAMinutos($disponibilidad->hora_fin);
            
            \Illuminate\Support\Facades\Log::info("Rango en minutos: inicio={$inicioMinutos}, fin={$finMinutos}");
            
            // Generar slots de 30 minutos dentro del rango de disponibilidad
            for ($minutos = $inicioMinutos; $minutos < $finMinutos; $minutos += 30) {
                $horaSlot = $this->minutosAHora($minutos);
                
                // Verificar que el slot no esté ocupado por una asesoría
                $slotDisponible = true;
                foreach ($asesoriasOcupadas as $asesoria) {
                    $horaInicio = date('H:i', strtotime($asesoria->fecha));
                    $duracionMinutos = $this->duracionAMinutos($asesoria->duracion);
                    $horaFinAsesoria = $this->minutosAHora($this->horaAMinutos($horaInicio) + $duracionMinutos);
                    
                    // Si el slot actual está dentro del rango de una asesoría, no está disponible
                    if ($horaSlot >= $horaInicio && $horaSlot < $horaFinAsesoria) {
                        \Illuminate\Support\Facades\Log::info("Slot {$horaSlot} ocupado por asesoría ({$horaInicio} - {$horaFinAsesoria})");
                        $slotDisponible = false;
                        break;
                    }
                }
                
                if ($slotDisponible) {
                    \Illuminate\Support\Facades\Log::info("Agregando slot disponible: {$horaSlot}");
                    $horariosDisponibles[] = $horaSlot;
                }
            }
        }
        
        \Illuminate\Support\Facades\Log::info("Total de horarios disponibles: " . count($horariosDisponibles) . " slots. Horarios: " . implode(', ', $horariosDisponibles));
        return response()->json($horariosDisponibles);
    }
    
    /**
     * Convierte una hora en formato HH:MM a minutos desde el inicio del día
     */
    private function horaAMinutos($hora) 
    {
        list($horas, $minutos) = explode(':', $hora);
        return ($horas * 60) + $minutos;
    }
    
    /**
     * Convierte minutos desde el inicio del día a formato HH:MM
     */
    private function minutosAHora($minutos) 
    {
        $horas = floor($minutos / 60);
        $mins = $minutos % 60;
        return sprintf('%02d:%02d', $horas, $mins);
    }
    
    /**
     * Convierte una duración en formato HH:MM:SS a minutos
     */
    private function duracionAMinutos($duracion)
    {
        list($horas, $minutos, $segundos) = explode(':', $duracion);
        return ($horas * 60) + $minutos;
    }

    public function estudianteSolicitarPost(Request $request)
    {
        $request->validate([
            'materia' => 'required|exists:materia,id_materia',
            'asesor' => 'required|exists:usuario,id_usuario',
            'tema' => 'required|string|max:255',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'duracion' => 'required|in:60,120',
        ]);

        // Validar que la hora solicitada esté disponible para el asesor
        // Obtener el día de la semana de la fecha como nombre (Lunes, Martes, etc.)
        $diasSemana = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        $diaSemanaNumero = date('N', strtotime($request->fecha));
        $diaSemana = $diasSemana[$diaSemanaNumero];
        
        \Illuminate\Support\Facades\Log::info("Validando hora {$request->hora} para asesor {$request->asesor} en día {$diaSemana}");
        
        // Verificar que el asesor tenga disponibilidad para ese día y hora
        $disponible = DisponibilidadAsesor::where('id_asesor', $request->asesor)
                        ->where('dia_semana', $diaSemana)
                        ->where('estado', 'ACTIVO')
                        ->where('hora_inicio', '<=', $request->hora)
                        ->whereRaw('TIME(?) < hora_fin', [$request->hora])
                        ->exists();
        
        if (!$disponible) {
            return back()->withErrors(['hora' => 'La hora seleccionada no está disponible para este asesor.'])->withInput();
        }
        
        // Verificar que no haya otra asesoría en el mismo horario
        $horaInicio = $request->fecha . ' ' . $request->hora;
        $horaFin = date('Y-m-d H:i:s', strtotime($horaInicio) + ($request->duracion * 60));
        
        $conflicto = Asesoria::where('fk_id_asesor', $request->asesor)
                    ->whereIn('estado', ['PENDIENTE', 'CONFIRMADA', 'PROCESO'])
                    ->where(function($q) use ($horaInicio, $horaFin) {
                        // La nueva asesoría comienza durante una existente
                        $q->where(function($query) use ($horaInicio) {
                            $query->where('fecha', '<=', $horaInicio)
                                  ->whereRaw('DATE_ADD(fecha, INTERVAL TIME_TO_SEC(duracion) SECOND) > ?', [$horaInicio]);
                        })
                        // La nueva asesoría termina durante una existente
                        ->orWhere(function($query) use ($horaFin, $horaInicio) {
                            $query->where('fecha', '<', $horaFin)
                                  ->whereRaw('fecha > ?', [$horaInicio]);
                        });
                    })
                    ->exists();
        
        if ($conflicto) {
            return back()->withErrors(['hora' => 'El asesor ya tiene una asesoría programada en ese horario.'])->withInput();
        }
        
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
        return redirect('/asesorias/pendientes');
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

        try {
            $request->validate([
                'estado' => 'required|in:PENDIENTE,CONFIRMADA,PROCESO,FINALIZADA,CANCELADA',
                'observaciones' => $request->estado === 'CANCELADA' ? 'required|string|min:10' : 'nullable',
            ]);

            $estado_anterior = $asesoria->estado;
            $asesoria->estado = $request->estado;
            
            // Guardar observaciones cuando se cancela una asesoría
            if ($request->estado === 'CANCELADA' && $request->has('observaciones')) {
                $asesoria->observaciones = $request->observaciones;
                \Illuminate\Support\Facades\Log::info("Cancelando asesoría ID: {$id} con observación: {$request->observaciones}");
            }
            
            $result = $asesoria->save();
            
            if (!$result) {
                \Illuminate\Support\Facades\Log::error("Error al guardar la asesoría ID: {$id}. No se pudo actualizar.");
                session()->flash('tipo', 'error');
                session()->flash('mensaje', 'No se pudo actualizar el estado de la asesoría. Por favor, inténtalo de nuevo.');
                return back();
            }

            \Illuminate\Support\Facades\Log::info("Asesoría ID: {$id} actualizada correctamente al estado: {$request->estado}");
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
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error en actualizarEstado: {$e->getMessage()}");
            session()->flash('tipo', 'error');
            session()->flash('mensaje', 'Error al procesar la solicitud: ' . $e->getMessage());
            return back();
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


