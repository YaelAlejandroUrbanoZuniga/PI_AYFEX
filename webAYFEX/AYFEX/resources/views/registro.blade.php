
@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #fafafa;
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    .login-wrapper {
        max-width: 500px; /* Un poco más ancho que el login para acomodar más campos */
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
    .form-title {
        font-weight: 600;
        font-size: 1.25rem;
        color: #0f172a;
        margin-bottom: 1.5rem;
        text-align: center;
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
        margin-bottom: 1rem;
    }
    .input-group-custom:focus-within {
        border-color: #ff5722;
        box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1);
    }
    .input-group-custom i {
        color: #94a3b8;
        font-size: 1.1rem;
        width: 20px;
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
        margin-top: 1rem;
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
    .alert-custom {
        border-radius: 8px;
        padding: 10px;
        font-size: 0.9rem;
        text-align: center;
        margin-bottom: 1rem;
        display: none;
    }
    .alert-error {
        background-color: #fee2e2;
        color: #dc2626;
        border: 1px solid #fca5a5;
    }
    .alert-success {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }
</style>

<div class="container d-flex flex-column justify-content-center align-items-center min-vh-100 py-4">
    
    <div class="login-wrapper">
        <div class="header-logo-container">
            <img src="{{ asset('AYFEXLOGO-Photoroom.png') }}" alt="AYFEX Logo" class="logo-img">
            <h1 class="brand-title">AYFEX</h1>
            <p class="brand-subtitle">Únete a nuestra plataforma de logística</p>
        </div>

        <div class="login-card">
            <h3 class="form-title">Crear una Cuenta</h3>

            <div id="alertaError" class="alert-custom alert-error"></div>
            <div id="alertaExito" class="alert-custom alert-success">¡Registro exitoso! Redirigiendo...</div>

            <form id="formRegistro">
                @csrf

                <div>
                    <label class="form-label">Nombre Completo</label>
                    <div class="input-group-custom">
                        <i class="far fa-user"></i>
                        <input type="text" id="inputNombre" placeholder="Ej. Juan Pérez" required>
                    </div>
                </div>

                <div>
                    <label class="form-label">Correo Electrónico</label>
                    <div class="input-group-custom">
                        <i class="far fa-envelope"></i>
                        <input type="email" id="inputEmail" placeholder="correo@ejemplo.com" required>
                    </div>
                </div>

                <div>
                    <label class="form-label">Teléfono</label>
                    <div class="input-group-custom">
                        <i class="fas fa-phone-alt"></i>
                        <input type="tel" id="inputTelefono" placeholder="55 1234 5678" required>
                    </div>
                </div>

                <div>
                    <label class="form-label">Contraseña</label>
                    <div class="input-group-custom">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="inputPassword" placeholder="Crea una contraseña" minlength="6" required>
                    </div>
                </div>

                <div>
                    <label class="form-label">Confirmar Contraseña</label>
                    <div class="input-group-custom">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="inputPasswordConfirm" placeholder="Repite tu contraseña" minlength="6" required>
                    </div>
                </div>

                <button type="submit" id="btnSubmit" class="btn btn-orange w-100 mb-4">
                    Registrarme
                </button>

                <div class="text-center">
                    <span style="color: #64748b; font-size: 0.9rem;">¿Ya tienes cuenta?</span> 
                    <a href="{{ route('login') }}" class="text-orange text-decoration-none ms-1">
                        Inicia Sesión aquí
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const formRegistro = document.getElementById("formRegistro");
    const alertaError = document.getElementById("alertaError");
    const alertaExito = document.getElementById("alertaExito");
    const btnSubmit = document.getElementById("btnSubmit");

    formRegistro.addEventListener("submit", function(event) {
        event.preventDefault();

        // 1. Ocultar alertas anteriores
        alertaError.style.display = "none";
        alertaExito.style.display = "none";

        // 2. Obtener valores
        const nombre = document.getElementById("inputNombre").value;
        const email = document.getElementById("inputEmail").value;
        const telefono = document.getElementById("inputTelefono").value;
        const password = document.getElementById("inputPassword").value;
        const passwordConfirm = document.getElementById("inputPasswordConfirm").value;

        // 3. Validar que las contraseñas coincidan
        if (password !== passwordConfirm) {
            alertaError.innerText = "Las contraseñas no coinciden.";
            alertaError.style.display = "block";
            return;
        }

        // 4. Preparar datos para enviar a FastAPI
        const datosRegistro = {
            nombre_completo: nombre,
            correo_electronico: email,
            telefono: telefono,
            password: password
        };

        // Cambiar estado del botón
        btnSubmit.disabled = true;
        btnSubmit.innerText = "Registrando...";

        // 5. Enviar petición a tu API
        fetch("http://127.0.0.1:5000/v1/registro", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(datosRegistro)
        })
        .then(response => {
            if (response.status === 400) {
                throw new Error("El correo ya está registrado.");
            }
            if (!response.ok) {
                throw new Error("Ocurrió un error en el servidor.");
            }
            return response.json();
        })
        .then(data => {
            // ÉXITO
            alertaExito.style.display = "block";
            
            // Esperar 2 segundos y redirigir al login
            setTimeout(() => {
                window.location.href = "{{ route('login') }}";
            }, 2000);
        })
        .catch(error => {
            // ERROR
            alertaError.innerText = error.message;
            alertaError.style.display = "block";
            btnSubmit.disabled = false;
            btnSubmit.innerText = "Registrarme";
        });
    });
});
</script>
@endsection