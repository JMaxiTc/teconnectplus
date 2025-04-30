@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent

<style>
    .hero-section {
        background: linear-gradient(to right, #104b87, #1e90ff);
        color: white;
        padding: 60px 20px;
        text-align: center;
        border-radius: 8px;
        margin-bottom: 40px;
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

    @php
        $subjects = [
            ["name" => "Programación orientada a objetos", "icon" => "fas fa-code", "route" => "/programacion-orientada-a-objetos"],
            ["name" => "Contabilidad financiera", "icon" => "fas fa-calculator", "route" => "/contabilidad-financiera"],
            ["name" => "Psicología general", "icon" => "fas fa-brain", "route" => "/psicologia-general"],
            ["name" => "Anatomía básica", "icon" => "fas fa-user", "route" => "/anatomia-basica"],
            ["name" => "Diseño arquitectónico", "icon" => "fas fa-drafting-compass", "route" => "/diseno-arquitectonico"],
            ["name" => "Bases de datos", "icon" => "fas fa-database", "route" => "/bases-de-datos"],
            ["name" => "Macroeconomía", "icon" => "fas fa-chart-line", "route" => "/macroeconomia"],
            ["name" => "Neurociencia", "icon" => "fas fa-brain", "route" => "/neurociencia"],
            ["name" => "Cálculo diferencial", "icon" => "fas fa-square-root-alt", "route" => "/calculo-diferencial"],
            ["name" => "Historia del arte", "icon" => "fas fa-palette", "route" => "/historia-del-arte"],
        ];
    @endphp

    <div id="subjectsCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach(array_chunk($subjects, 3) as $chunkIndex => $subjectChunk)
                <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                    @foreach($subjectChunk as $subject)
                        <div>
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    @if($subject['icon'])
                                        <i class="{{ $subject['icon'] }} mb-3"></i>
                                    @endif
                                    <h5 class="card-title">{{ $subject['name'] }}</h5>
                                    <a href="{{ $subject['route'] }}" class="btn btn-primary mt-auto">Ir</a>
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
                        <a href="#" class="btn btn-outline-primary mt-4 px-4 py-2" style="border-radius: 30px; font-weight: 500;">Registrarme como asesorado</a>
                    </div>
                </div>
            </div>
            <div class="col-md-5 mb-4">
                <div class="card h-100 border-0 shadow-lg" style="border-radius: 16px; background: linear-gradient(180deg, #ffffff, #e8f4ff); transition: all 0.3s ease;">
                    <div class="card-body text-center px-4 py-5">
                        <i class="fas fa-user-graduate fa-3x mb-3" style="color: #1e90ff;"></i>
                        <h5 class="card-title mb-3" style="font-weight: 600; font-size: 1.25rem;">Quiero ser asesor</h5>
                        <p style="color: #444;">¿Dominas alguna materia? Comparte tu experiencia y ayuda a otros estudiantes a tener éxito.</p>
                        <a href="#" class="btn btn-primary mt-4 px-4 py-2" style="border-radius: 30px; font-weight: 500;">Registrarme como asesor</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
