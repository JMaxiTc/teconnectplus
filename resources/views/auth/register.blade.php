@extends('components.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Registro de usuario</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required autocomplete="nombre" autofocus>
                                @error('nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input id="apellido" type="text" class="form-control @error('apellido') is-invalid @enderror" name="apellido" value="{{ old('apellido') }}" required autocomplete="apellido">
                                @error('apellido')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <input id="correo" type="email" class="form-control @error('correo') is-invalid @enderror" name="correo" value="{{ old('correo') }}" required autocomplete="email">
                            @error('correo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Contraseña</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password-confirm" class="form-label">Confirmar contraseña</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="fechaNacimiento" class="form-label">Fecha de nacimiento</label>
                            <input id="fechaNacimiento" type="date" class="form-control @error('fechaNacimiento') is-invalid @enderror" name="fechaNacimiento" value="{{ old('fechaNacimiento') }}" required>
                            @error('fechaNacimiento')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="id_genero" class="form-label">Género</label>
                            <select id="id_genero" class="form-select @error('id_genero') is-invalid @enderror" name="id_genero">
                                <option value="">Seleccionar género</option>
                                @foreach($generos as $genero)
                                    <option value="{{ $genero->id_genero }}" {{ old('id_genero') == $genero->id_genero ? 'selected' : '' }}>
                                        {{ $genero->genero }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_genero')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="carrera" class="form-label">Carrera</label>
                            <input id="carrera" type="text" class="form-control @error('carrera') is-invalid @enderror" name="carrera" value="{{ old('carrera') }}" required>
                            <small class="form-text text-muted">Ejemplo: Ingeniería en Sistemas, Licenciatura en Derecho</small>
                            @error('carrera')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol</label>
                            <select id="rol" class="form-select @error('rol') is-invalid @enderror" name="rol" required>
                                <option value="">Seleccionar rol</option>
                                <option value="ESTUDIANTE" {{ (old('rol') == 'ESTUDIANTE' || (isset($selectedRole) && $selectedRole == 'ESTUDIANTE')) ? 'selected' : '' }}>Estudiante</option>
                                <option value="ASESOR" {{ (old('rol') == 'ASESOR' || (isset($selectedRole) && $selectedRole == 'ASESOR')) ? 'selected' : '' }}>Asesor</option>
                            </select>
                            @error('rol')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="semestre" class="form-label">Semestre</label>
                            <select id="semestre" class="form-select @error('semestre') is-invalid @enderror" name="semestre" required>
                                <option value="">Seleccionar semestre</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('semestre') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('semestre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <button type="submit" class="btn btn-primary">
                                Registrarse
                            </button>
                            <a href="{{ route('login') }}" class="btn btn-link">
                                ¿Ya tienes cuenta? Inicia sesión
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection