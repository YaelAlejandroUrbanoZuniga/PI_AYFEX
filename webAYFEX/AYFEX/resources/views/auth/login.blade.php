@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #fafafa;
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    .login-wrapper {
        max-width: 460px;
        width: 100%;
        margin: 0 auto;
    }
    .header-logo-container {
        text-align: center;
        margin-bottom: 2rem;
    }
    .logo-img {
        width: 65px;
        height: auto;
        margin-bottom: 12px;
        border-radius: 12px;
    }
    .brand-title {
        font-weight: 800;
        letter-spacing: 0.5px;
        color: #0f172a;
        font-size: 1.75rem;
        margin-bottom: 5px;
    }
    .brand-subtitle {
        color: #64748b;
        font-size: 0.95rem;
    }
    .login-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        padding: 2.5rem 2rem;
    }
    .badge-admin {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #c2410c; /* Naranja oscuro */
        background-color: #fffaf5;
        border: 1px solid #fed7aa;
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .divider {
        border-top: 1px solid #f1f5f9;
        margin: 1.5rem 0;
    }
    .form-title {
        font-weight: 600;
        font-size: 1.25rem;
        color: #0f172a;
        margin-bottom: 1.5rem;
    }
    .form-label {
        font-size: 0.85rem;
        color: #334155;
        font-weight: 500;
        margin-bottom: 8px;
    }
    .input-group-custom {
        display: flex;
        align-items: center;
        background-color: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 8px;
        padding: 0 14px;
        transition: all 0.3s ease;
    }
    .input-group-custom:focus-within {
        border-color: #ff5722;
        box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1);
    }
    .input-group-custom i {
        color: #94a3b8;
        font-size: 1.1rem;
    }
    .input-group-custom input {
        border: none;
        background: transparent;
        padding: 12px 10px;
        width: 100%;
        color: #334155;
        outline: none;
    }
    .input-group-custom input::placeholder {
        color: #94a3b8;
    }
    .btn-orange {
        background-color: #ff4726; 
        border: none;
        color: white;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        padding: 12px;
        transition: background-color 0.3s;
    }
    .btn-orange:hover {
        background-color: #e63919;
        color: white;
    }
    .text-orange {
        color: #ff4726 !important;
        font-weight: 500;
        font-size: 0.9rem;
    }
    .text-orange:hover {
        color: #e63919 !important;
        text-decoration: underline !important;
    }
    .test-access-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
        font-size: 0.8rem;
        color: #475569;
        margin-top: 1.5rem;
    }
    .footer-text {
        color: #94a3b8;
        font-size: 0.85rem;
        text-align: center;
        margin-top: 2rem;
    }
</style>

<div class="container d-flex flex-column justify-content-center align-items-center min-vh-100 py-4">
    
    <div class="login-wrapper">
        <div class="header-logo-container">
            <img src="{{ asset('AYFEXLOGO-Photoroom.png') }}" alt="AYFEX Logo" class="logo-img">
            <h1 class="brand-title">AYFEX</h1>
            <p class="brand-subtitle">Sistema de Gestión de Transporte de Paquetería</p>
        </div>

        <div class="login-card">
            
            <div class="text-center">
                <span class="badge-admin">
                    <i class="fas fa-shield-alt"></i> Panel Administrativo
                </span>
            </div>
            
            <div class="divider"></div>

            <h3 class="form-title">Iniciar Sesión</h3>

            <div id="errorMensaje" class="alert alert-danger" style="display: none; background-color: #fee2e2; color: #dc2626; border-radius: 8px; padding: 10px; font-size: 0.9rem; text-align: center; margin-bottom: 1rem; border: 1px solid #fca5a5;">
                Usuario o contraseña incorrectos.
            </div>

            <form id="formLogin">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <div class="input-group-custom">
                        <i class="far fa-envelope"></i>
                        <input type="email" id="inputUsuario" name="email" placeholder="ejemplo@ayfex.com" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Contraseña</label>
                    <div class="input-group-custom">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="inputPassword" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" id="btnSubmit" class="btn btn-orange w-100 mb-3">
                    Ingresar al Sistema
                </button>

                <div class="text-center mb-2">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-orange text-decoration-none" style="font-size: 0.85rem;">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <div class="text-center mt-3">
                    <span style="color: #64748b; font-size: 0.9rem;">¿No tienes cuenta?</span> 
                    <a href="{{ route('registro') }}" class="text-orange text-decoration-none ms-1">
                        Regístrate aquí
                    </a>
                </div>

                <div class="test-access-box">
                    <strong>¿No sabes tus credenciales?</strong><br>
                    Regístrate para crear un nuevo usuario.
                </div>

            </form>
        </div>

        <div class="footer-text">
            © 2026 AYFEX. Todos los derechos reservados.
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const formLogin = document.getElementById("formLogin");
    const errorMensaje = document.getElementById("errorMensaje");
    const btnSubmit = document.getElementById("btnSubmit");

    // Limpiamos credenciales anteriores si la persona regresó al login
    localStorage.removeItem("authCredentials");

    formLogin.addEventListener("submit", function(event) {
        event.preventDefault();

        // 1. Obtener valores ingresados (Cambiamos el nombre para que coincida abajo)
        const email = document.getElementById("inputUsuario").value; // Antes era 'usuario'
        const password = document.getElementById("inputPassword").value;

        // 2. Crear el token Basic Auth (Base64)
        // Usamos 'email' que acabamos de definir arriba
        const tokenBase64 = btoa(`${email}:${password}`);

        btnSubmit.disabled = true;
        btnSubmit.innerText = "Verificando...";
        errorMensaje.style.display = "none";

        // 3. Hacer petición a FastAPI
        fetch("http://127.0.0.1:5000/v1/login/", {
            method: 'POST',
            headers: {
                // CORRECCIÓN: Usamos 'tokenBase64' que es el nombre de la variable arriba
                'Authorization': `Basic ${tokenBase64}`, 
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.status === 401 || response.status === 404) {
                throw new Error("Credenciales inválidas");
            }
            if (!response.ok) {
                throw new Error("Error en el servidor");
            }
            return response.json(); 
        })
        .then(data => {
            // 4. ÉXITO: Guardar token
            localStorage.setItem("authCredentials", tokenBase64);
            window.location.href = "/dashboard"; 
        })
        .catch(error => {
            console.error("Error:", error);
            errorMensaje.style.display = "block";
            btnSubmit.disabled = false;
            btnSubmit.innerText = "Ingresar al Sistema";
        });
    });
});
</script>
@endsection