@extends("components.layout")
@section("content")


@auth
<div class="alert {{ Auth::user()->esAsesor() ? 'alert-primary' : (Auth::user()->esAdmin() ? 'alert-dark' : 'alert-success') }} mb-4 shadow-sm">
    <div class="d-flex align-items-center">
        <div class="flex-shrink-0">
            <i class="fas {{ Auth::user()->esAsesor() ? 'fa-chalkboard-teacher' : (Auth::user()->esAdmin() ? 'fa-user-shield' : 'fa-user-graduate') }} fa-3x me-3"></i>
        </div>
        <div class="flex-grow-1">
            <h4 class="alert-heading">
                @if(Auth::user()->esAsesor())
                    ¡Bienvenido Asesor!
                @elseif(Auth::user()->esAdmin())
                    ¡Bienvenido Administrador!
                @else
                    ¡Bienvenido Estudiante!
                @endif
            </h4>
            <p class="mb-0">
                <strong>{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</strong>
                @if(Auth::user()->esAsesor())
                    - Estás listo para compartir tu conocimiento y apoyar a otros estudiantes.
                @elseif(Auth::user()->esAdmin())
                    - Tienes acceso completo al sistema para gestionar usuarios y configuraciones.
                @else
                    - Explora todas las asesorías disponibles y encuentra el apoyo académico que necesitas.
                @endif
            </p>
        </div>
    </div>
</div>

@if(Auth::user()->esAsesor())
<div class="role-asesor-only mb-4">
    @php
        $disponibilidades = Auth::user()->disponibilidades()->count();
        $disponibilidadesActivas = Auth::user()->disponibilidades()->where('estado', 'ACTIVO')->count();
    @endphp

    @if($disponibilidades === 0 || $disponibilidadesActivas === 0)
    <div class="alert alert-warning mb-4 shadow-sm border-left-warning">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0 me-3">
                <i class="fas fa-exclamation-circle fa-2x text-warning"></i>
            </div>
            <div class="flex-grow-1">
                <h5 class="alert-heading">¡Completa tu perfil como asesor!</h5>
                <p class="mb-0">
                    @if($disponibilidades === 0)
                    Para que los estudiantes puedan agendar asesorías contigo, es necesario que registres tu disponibilidad de horario.
                    @else
                    No tienes horarios activos. Para que los estudiantes puedan agendar asesorías contigo, es necesario que actives al menos un horario de disponibilidad.
                    @endif
                </p>
                <div class="mt-2">
                    <a href="{{ route('perfil.show') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-clock me-1"></i> Configurar mi horario
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="card border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Panel de Asesor</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-calendar-check fa-2x text-primary mb-2"></i>
                        <h6>
                            Solicitudes Pendientes
                            @php
                                $pendientes = Auth::user()->solicitudesPendientes()->count();
                            @endphp
                            @if($pendientes > 0)
                                <span class="badge bg-danger rounded-pill">{{ $pendientes }}</span>
                            @endif
                        </h6>
                        <p class="small text-muted">Revisa tus solicitudes pendientes</p>
                        <a href="{{ route('asesoriasa.solicitudes.get') }}" class="btn btn-sm btn-outline-primary">Ver solicitudes</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-book fa-2x text-primary mb-2"></i>
                        <h6>Mis Materias</h6>
                        <p class="small text-muted">Gestiona las materias que puedes asesorar</p>
                        <a href="{{ route('misMateriasGet') }}" class="btn btn-sm btn-outline-primary">Gestionar</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-star fa-2x text-primary mb-2"></i>
                        <h6>Mis Calificaciones</h6>
                        <p class="small text-muted">Revisa tus calificaciones como asesor</p>
                        <a href="/mis-calificaciones" class="btn btn-sm btn-outline-primary">Ver calificaciones</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(Auth::user()->esEstudiante())
<div class="role-estudiante-only mb-4">
    @php
        $tipoAprendizaje = Auth::user()->tipo_aprendizaje;
    @endphp

    @if(empty($tipoAprendizaje))
    <div class="alert alert-warning mb-4 shadow-sm border-left-warning">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0 me-3">
                <i class="fas fa-exclamation-circle fa-2x text-warning"></i>
            </div>
            <div class="flex-grow-1">
                <h5 class="alert-heading">¡Completa tu perfil de estudiante!</h5>
                <p class="mb-0">
                    Para personalizar tu experiencia de aprendizaje, te recomendamos registrar tu estilo de aprendizaje preferido.
                </p>
                <div class="mt-2">
                    <a href="{{ route('perfil.show') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-brain me-1"></i> Definir mi estilo de aprendizaje
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="card border-success">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Panel de Estudiante</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-search fa-2x text-success mb-2"></i>
                        <h6>Buscar Asesoría</h6>
                        <p class="small text-muted">Encuentra asesorías para tus materias</p>
                        <a href="/asesorias/solicitar" class="btn btn-sm btn-outline-success">Buscar</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-calendar-alt fa-2x text-success mb-2"></i>
                        <h6>
                            Mis Asesorías
                            @php
                                $pendientesEstudiante = Auth::user()->solicitudesPendientesEstudiante()->count();
                            @endphp
                            @if($pendientesEstudiante > 0)
                                <span class="badge bg-danger rounded-pill">{{ $pendientesEstudiante }}</span>
                            @endif
                        </h6>
                        <p class="small text-muted">Gestiona tus asesorías y solicitudes</p>
                        <a href="/asesorias" class="btn btn-sm btn-outline-success">Ver asesorías</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-graduation-cap fa-2x text-success mb-2"></i>
                        <h6>Materiales de Estudio</h6>
                        <p class="small text-muted">Accede a materiales para tus materias</p>
                        <a href="/materiales" class="btn btn-sm btn-outline-success">Ver materiales</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(Auth::user()->esAdmin())
<div class="role-admin-only mb-4">
    <div class="card border-dark">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Panel de Administrador</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-users-cog fa-2x text-dark mb-2"></i>
                        <h6>Gestión de Usuarios</h6>
                        <p class="small text-muted">Agrega, edita o elimina usuarios del sistema</p>
                        <a href="/admin/usuarios" class="btn btn-sm btn-outline-dark">Administrar usuarios</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-chart-pie fa-2x text-dark mb-2"></i>
                        <h6>Reportes</h6>
                        <p class="small text-muted">Visualiza estadísticas y genera reportes del sistema</p>
                        <a href="/admin/reportes" class="btn btn-sm btn-outline-dark">Ver reportes</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-envelope fa-2x text-dark mb-2"></i>
                        <h6>Notificaciones</h6>
                        <p class="small text-muted">Envía notificaciones a los usuarios</p>
                        <a href="/admin/notificaciones" class="btn btn-sm btn-outline-dark">Gestionar notificaciones</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endauth

<style>
    .hero-section {
        background: linear-gradient(to right, #104b87, #1e90ff);
        color: white;
        padding: 60px 20px;
        text-align: center;
        border-radius: 8px;
        margin-bottom: 40px;
    }

    .border-left-warning {
        border-left: 4px solid #ffc107;
    }

    .hero-section h1 {
        font-size: 2.8rem;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .hero-section p {
        font-size: 1.2rem;
        max-width: 800px;
        margin: 0 auto;
    }

    .custom-container {
        background-color: #ffffff;
        padding: 2rem;
        border-radius: 8px;
        max-width: 1200px;
        margin: 0 auto 60px auto;
        position: relative;
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.05);
    }

    .section-title {
        text-align: center;
        color: #104b87;
        margin-bottom: 2rem;
        font-size: 1.8rem;
    }

    .carousel-inner {
        display: flex;
    }

    .carousel-item.active,
    .carousel-item-next,
    .carousel-item-prev {
        display: flex;
    }

    .carousel-item > div {
        flex: 0 0 33.3333%;
        max-width: 33.3333%;
        padding: 0 10px;
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.15);
    }

    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        text-align: center;
    }

    .card-body i {
        font-size: 2.5rem;
        color: #104b87;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 500;
    }

    .btn {
        font-size: 0.9rem;
        padding: 5px 12px;
        margin-top: auto;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 50px;
        height: 50px;
        background-color: rgba(0, 0, 0, 0.3);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
    }

    .carousel-control-prev {
        left: -40px;
    }

    .carousel-control-next {
        right: -40px;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        filter: invert(1);
    }

    .info-section {
        max-width: 1200px;
        margin: 0 auto 60px auto;
        padding: 60px 30px;
        background: linear-gradient(135deg, #f0f8ff, #ffffff);
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.05);
        text-align: center;
    }

    .info-section h2 {
        font-size: 2rem;
        color: #104b87;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .info-section h3 {
        color: #1e90ff;
        margin-bottom: 10px;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .info-section p {
        font-size: 1.1rem;
        color: #444;
        max-width: 900px;
        margin: 0 auto 20px auto;
    }

    .highlight-box {
        background-color: #eaf4fd;
        border-left: 5px solid #1e90ff;
        padding: 20px;
        margin: 30px auto;
        max-width: 800px;
        border-radius: 8px;
    }

    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 40px;
    }

    .benefit-card {
        background: #ffffff;
        border: 1px solid #dbe9f6;
        padding: 25px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .benefit-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
    }

    .benefit-icon {
        font-size: 2.5rem;
        color: #1e90ff;
        margin-bottom: 15px;
    }
</style>

{{-- Hero --}}
<div class="hero-section">
    <h1>Asesorías entre pares</h1>
    <p>
        Bienvenido a tu espacio de aprendizaje colaborativo. Aquí los alumnos se apoyan entre sí mediante materiales de estudio y sesiones de asesoría personalizadas.
    </p>
</div>

{{-- Carrusel de materias --}}
<div class="container-fluid custom-container">
    <h2 class="section-title">Explora las materias disponibles</h2>

    <div id="subjectsCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($materias->chunk(3) as $chunkIndex => $chunk)
                <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                    @foreach($chunk as $materia)
                        <div>
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <i class="{{ $getIconForMateria($materia->nombre) }} mb-3 fa-2x"></i>
                                    <h5 class="card-title">{{ $materia->nombre }}</h5>
                                    @auth
                                    <a href="{{ url('/materia/'.$materia->id_materia) }}" class="btn btn-primary mt-auto">Ir</a>
                                    @else
                                    <a href="{{ url('login', ['redirect' => '/materia/'.$materia->id_materia]) }}" class="btn btn-primary mt-auto">Ir</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#subjectsCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#subjectsCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
</div>

@guest
{{-- Info General --}}
<div class="info-section">
    <h2>¿Qué es esta plataforma?</h2>
    <p>
        Una herramienta académica creada por y para estudiantes. Aquí puedes consultar material educativo y conectar con asesores que dominan distintas materias para recibir orientación clara y práctica.
    </p>

    <div class="highlight-box">
        <h3>Objetivo principal</h3>
        <p>Facilitar el aprendizaje entre pares mediante una comunidad colaborativa, recursos de calidad y asesorías personalizadas.</p>
    </div>

    <h2 class="mt-5">Beneficios de usar la plataforma</h2>
    <div class="benefits-grid">
        <div class="benefit-card">
            <div class="benefit-icon"><i class="fas fa-user-friends"></i></div>
            <h4>Colaboración entre pares</h4>
            <p>Interactúa con otros alumnos que ya dominaron el tema y quieren ayudarte.</p>
        </div>
        <div class="benefit-card">
            <div class="benefit-icon"><i class="fas fa-lightbulb"></i></div>
            <h4>Aprendizaje personalizado</h4>
            <p>Recibe asesorías según tus dudas y tu propio ritmo de estudio.</p>
        </div>
        <div class="benefit-card">
            <div class="benefit-icon"><i class="fas fa-book"></i></div>
            <h4>Materiales organizados</h4>
            <p>Encuentra resúmenes, ejercicios y guías por materia y tema específico.</p>
        </div>
        <div class="benefit-card">
            <div class="benefit-icon"><i class="fas fa-clock"></i></div>
            <h4>Flexibilidad de horario</h4>
            <p>Agenda asesorías cuando tengas tiempo, sin limitarte a un horario fijo.</p>
        </div>
    </div>
</div>

{{-- Llamado a la acción --}}
<div class="cta-section mt-5 mb-5 py-5" style="background: linear-gradient(135deg, #f0faff, #ffffff); border-radius: 24px; box-shadow: 0 12px 40px rgba(0,0,0,0.05); text-align: center;">
    <div class="container">
        <h2 style="color: #0c3c73; font-weight: 700; font-size: 2.3rem; margin-bottom: 1.2rem;">¿Quieres recibir o brindar asesorías?</h2>
        <p style="max-width: 800px; margin: 0 auto 2rem auto; font-size: 1.15rem; color: #555;">
            Únete a nuestra comunidad académica: encuentra apoyo en tus materias o comparte tu conocimiento como asesor.
        </p>

        <div class="row justify-content-center gap-4 mt-4">
            <div class="col-md-5 mb-4">
                <div class="card h-100 border-0 shadow-lg" style="border-radius: 16px; background: linear-gradient(180deg, #ffffff, #f2f9ff); transition: all 0.3s ease;">
                    <div class="card-body text-center px-4 py-5">
                        <i class="fas fa-hand-holding-heart fa-3x mb-3" style="color: #1e90ff;"></i>
                        <h5 class="card-title mb-3" style="font-weight: 600; font-size: 1.25rem;">Quiero recibir asesoría</h5>
                        <p style="color: #444;">¿Tienes dudas o quieres reforzar una materia? Regístrate y encuentra el apoyo que necesitas.</p>
                        <a href="{{ route('register', ['rol' => 'ESTUDIANTE']) }}" class="btn btn-outline-primary mt-4 px-4 py-2" style="border-radius: 30px; font-weight: 500;">Registrarme como asesorado</a>
                    </div>
                </div>
            </div>
            <div class="col-md-5 mb-4">
                <div class="card h-100 border-0 shadow-lg" style="border-radius: 16px; background: linear-gradient(180deg, #ffffff, #e8f4ff); transition: all 0.3s ease;">
                    <div class="card-body text-center px-4 py-5">
                        <i class="fas fa-user-graduate fa-3x mb-3" style="color: #1e90ff;"></i>
                        <h5 class="card-title mb-3" style="font-weight: 600; font-size: 1.25rem;">Quiero ser asesor</h5>
                        <p style="color: #444;">¿Dominas alguna materia? Comparte tu experiencia y ayuda a otros estudiantes a tener éxito.</p>
                        <a href="{{ route('register', ['rol' => 'ASESOR']) }}" class="btn btn-primary mt-4 px-4 py-2" style="border-radius: 30px; font-weight: 500;">Registrarme como asesor</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endguest
@endsection
