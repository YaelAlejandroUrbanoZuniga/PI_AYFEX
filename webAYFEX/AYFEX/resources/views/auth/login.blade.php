@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #fafafa; 
    }
    .login-container {
        max-width: 420px;
        width: 100%;
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.05); 
    }
    .logo-img {
        width: 90px;
        height: auto;
        margin-bottom: 5px;
    }
    .brand-title {
        font-weight: 800;
        letter-spacing: 1px;
        color: #111;
        font-size: 1.5rem;
    }
    .form-control-custom {
        border-radius: 12px;
        padding: 12px 16px;
        border: 1px solid #e0e0e0;
        background-color: #ffffff; 
    }
    .form-control-custom:focus {
        border-color: #ff5722;
        box-shadow: 0 0 0 0.2rem rgba(255, 87, 34, 0.25);
    }
    .btn-orange {
        background-color: #ff5722; 
        border-color: #ff5722;
        color: white;
        border-radius: 12px;
        font-weight: bold;
        font-size: 1.1rem;
        transition: all 0.3s;
    }
    .btn-orange:hover {
        background-color: #e64a19;
        border-color: #e64a19;
        color: white;
        transform: translateY(-1px);
    }
    .text-orange {
        color: #ff7043 !important;
    }
    .social-icon {
        font-size: 1.8rem;
        margin: 0 10px;
        text-decoration: none;
        transition: transform 0.2s;
    }
    .social-icon:hover {
        transform: scale(1.1);
    }
    .icon-fb { color: #1877f2; }
    .icon-wa { color: #25d366; }
    .icon-google { color: #db4437;  }
</style>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="login-container p-5">
        
        <div class="text-center mb-4">
            <img src="{{ asset('AYFEXLOGO-Photoroom.png') }}" alt="AYFEX Logo" class="logo-img">
            <h1 class="brand-title mb-1">AYFEX</h1>
            <p class="text-muted" style="font-size: 0.9rem;">Gestión de Transporte de Paquetería</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <input type="email" name="email" class="form-control form-control-custom shadow-sm" placeholder="Correo Electrónico" required autofocus>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control form-control-custom shadow-sm" placeholder="Contraseña" required>
            </div>

            <div class="text-center mb-4 mt-2">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-orange text-decoration-none" style="font-size: 0.85rem;">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn btn-orange w-100 py-2 mb-4 shadow">
                Iniciar Sesión
            </button>
        </form>

        <div class="text-center mb-4">
            <span class="text-muted" style="font-size: 0.9rem;">¿No tienes cuenta?</span>
            <a href="{{ route('register') }}" class="text-orange text-decoration-none" style="font-size: 0.9rem;">Regístrate aquí</a>
        </div>

        <div class="text-center">
            <p class="text-muted mb-3" style="font-size: 0.9rem;">Otras formas de iniciar sesión</p>
            <div class="d-flex justify-content-center">
                <a href="#" class="social-icon icon-fb" title="Facebook"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-icon icon-wa" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                <a href="#" class="social-icon icon-google" title="Google"><i class="fab fa-google"></i></a>
            </div>
        </div>

    </div>
</div>
@endsection