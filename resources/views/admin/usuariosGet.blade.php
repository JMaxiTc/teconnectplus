@extends("components.layout")
@section('content')
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent
<div class="container">
    <h2 class="mb-4">Gestión de Usuarios</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ url('/admin/usuarios/agregar') }}" class="btn btn-primary mb-3">Crear Nuevo Usuario</a>

    <div class="card">
        <div class="card-header">
            <h5>Usuarios Registrados</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Nacimiento</th>
                        <th>Genero</th>
                        <th>Rol</th>
                        <th>Semestre</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id_usuario }}</td>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->apellido }}</td>
                        <td>{{ $usuario->fechaNacimiento }}</td>
                        <td>{{ $usuario->genero->genero }}</td>
                        <td>{{ $usuario->rol }}</td>
                        <td>{{ $usuario->semestre }}</td>
                        <td>{{ $usuario->correo }}</td>
                        <td>
                            <a href="{{ url('/admin/usuarios/' . $usuario->id_usuario . '/actualizar') }}" class="btn btn-sm btn-warning">Editar</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
