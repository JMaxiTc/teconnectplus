@extends('components.layout')
@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Notificaciones</h1>
    <p>Envía notificaciones a los usuarios.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.notificaciones') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                </div>
                <div class="mb-3">
                    <label for="mensaje" class="form-label">Mensaje</label>
                    <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Enviar Notificación</button>
            </form>
        </div>
    </div>
</div>
@endsection
