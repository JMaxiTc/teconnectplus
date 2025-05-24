@extends('components.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Iniciar sesión</h4>
                </div>
                <div class="card-body">
                    @if(session('cuenta_desactivada'))
                        <div class="alert alert-danger mb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-user-lock fa-2x"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="alert-heading">¡Cuenta desactivada!</h5>
                                    <p class="mb-0">{{ session('error') ?? 'Tu cuenta ha sido desactivada por un administrador. Si crees que esto es un error, por favor contacta al soporte técnico.' }}</p>
                                </div>
                            </div>
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger mb-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        @if(isset($redirect))
                            <input type="hidden" name="redirect" value="{{ $redirect }}">
                        @endif

                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <input id="correo" type="email" class="form-control @error('correo') is-invalid @enderror" name="correo" value="{{ old('correo') }}" required autocomplete="email" autofocus>
                            @error('correo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Recordarme
                                </label>
                            </div>
                        </div>

                        <div class="mb-0">
                            <button type="submit" class="btn btn-primary">
                                Iniciar sesión
                            </button>
                            <a href="{{ route('register') }}" class="btn btn-link">
                                ¿No tienes cuenta? Regístrate
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection