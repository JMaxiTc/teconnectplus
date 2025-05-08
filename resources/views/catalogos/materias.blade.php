@extends("components.layout")
@section('content')
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent

<div class="container">
    <h1 class="mb-4">Gestión de Materias</h1>

    <!-- Formulario para agregar una nueva materia -->
    <div class="card mb-4">
        <div class="card-header">Agregar Materia</div>
        <div class="card-body">
            <form method="POST" action="{{ route('catalogos.materias.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Materia</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar</button>
            </form>
        </div>
    </div>

    <!-- Lista de materias existentes -->
    <div class="card">
        <div class="card-header">Materias Existentes</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materias as $materia)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $materia->nombre }}</td>
                        <td>
                            <!-- Botones de acción -->
                            <form method="POST" action="{{ route('catalogos.materias.destroy', $materia->id) }}" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection