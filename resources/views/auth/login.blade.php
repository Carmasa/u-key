@extends('layouts.app')

@section('title', 'Iniciar sesión - U-Key')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <h1 class="mb-2"><i class="bi bi-keyboard me-2"></i>U-KEY</h1>
                <p class="text-muted">Accede a tu cuenta para continuar</p>
            </div>

            <div class="card auth-card">
                <div class="card-header">
                    <h4><i class="bi bi-box-arrow-in-right me-2"></i>Iniciar sesión</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
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
                                   id="password" name="password" placeholder="••••••••" required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar sesión
                        </button>
                    </form>

                    <hr style="border-color: var(--border-color); margin: 2rem 0;">

                    <p class="text-center text-muted mb-0">
                        ¿No tienes cuenta? 
                        <a href="{{ route('register') }}" class="fw-bold">Regístrate aquí</a>
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