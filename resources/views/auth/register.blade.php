@extends('layouts.app')

@section('title', 'Registrarse - U-Key')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <h1 class="mb-2"><i class="bi bi-keyboard me-2"></i>U-KEY</h1>
                <p class="text-muted">Crea tu cuenta y empieza a comprar</p>
            </div>

            <div class="card auth-card">
                <div class="card-header">
                    <h4><i class="bi bi-person-plus me-2"></i>Crear cuenta</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="nombre" class="form-label">
                                <i class="bi bi-person me-1"></i>Nombre
                            </label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" value="{{ old('nombre') }}" 
                                   placeholder="Tu nombre" required>
                            @error('nombre')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>Email
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="tu@email.com" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock me-1"></i>Contraseña
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="Mínimo 8 caracteres" required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-lock-fill me-1"></i>Confirmar contraseña
                            </label>
                            <input type="password" class="form-control" id="password_confirmation" 
                                   name="password_confirmation" placeholder="Repite la contraseña" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="bi bi-person-plus me-2"></i>Crear cuenta
                        </button>
                    </form>

                    <hr style="border-color: var(--border-color); margin: 2rem 0;">

                    <p class="text-center text-muted mb-0">
                        ¿Ya tienes cuenta? 
                        <a href="{{ route('login') }}" class="fw-bold">Inicia sesión aquí</a>
                    </p>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('catalogo.index') }}" class="text-muted">
                    <i class="bi bi-arrow-left me-1"></i>Volver a la tienda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection