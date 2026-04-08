@extends('layouts.app')

@section('content')
<style>
    /* =========================================
       ESTILOS DEL HEADER ESTÁTICO (NARANJA)
       ========================================= */
    .main-header { position:sticky;top:0;z-index:1000;background:linear-gradient(90deg,#ff5722 0%,#e64a19 100%);font-family:'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;box-shadow:0 4px 15px rgba(0,0,0,0.1); }
    .header-top { display:flex;justify-content:space-between;align-items:center;padding:12px 24px;border-bottom:1px solid rgba(255,255,255,0.2); }
    .header-brand { display:flex;align-items:center;gap:12px;text-decoration:none; }
    .brand-text { display:flex;flex-direction:column; }
    .brand-name { font-weight:900;font-size:1.2rem;color:#ffffff;line-height:1.1;letter-spacing:1px; }
    .brand-slogan { font-size:0.75rem;color:rgba(255,255,255,0.85); }
    .header-search { flex:1;max-width:600px;margin:0 2rem;position:relative; }
    .header-search i { position:absolute;left:18px;top:50%;transform:translateY(-50%);color:#ff5722;z-index:2; }
    .header-search input { width:100%;padding:10px 15px 10px 45px;border:none;border-radius:25px;background-color:#ffffff;font-size:0.95rem;color:#333;outline:none;box-shadow:0 2px 5px rgba(0,0,0,0.1); }
    .header-search input::placeholder { color:#aaa; }
    .header-actions { display:flex;align-items:center;gap:20px; }
    .user-profile { display:flex;align-items:center;gap:12px;text-decoration:none;padding:5px;border-radius:8px;transition:background-color 0.3s; }
    .user-profile:hover { background-color:rgba(255,255,255,0.1); }
    .user-info { text-align:right; }
    .user-name { font-weight:600;font-size:0.9rem;color:#ffffff;line-height:1.2; }
    .user-role { font-size:0.75rem;color:rgba(255,255,255,0.85); }
    .user-avatar { width:38px;height:38px;background-color:#ffffff;color:#ff5722;border-radius:50%;display:flex;justify-content:center;align-items:center;font-size:1.1rem;font-weight:bold; }
    .header-nav { display:flex;padding:0 24px;gap:8px; }
    .nav-item { padding:12px 16px;font-size:0.95rem;color:#ffffff;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:8px;border-radius:12px 12px 0 0;margin-top:6px;cursor:pointer;transition:all 0.3s; }
    .nav-item:hover { background-color:rgba(255,255,255,0.2);color:#ffffff; }
    .nav-item.active { background-color:#f4f6f9;color:#ff5722; }
    .nav-item.active i { color:#ff5722; }
    .nav-item i.chevron { font-size:0.75rem;margin-left:4px; }
    .dropdown-menu { border:none;box-shadow:0 10px 25px rgba(0,0,0,0.1);border-radius:0 8px 8px 8px;padding:8px 0;margin-top:0 !important; }
    .dropdown-item { padding:10px 20px;font-size:0.9rem;color:#444;font-weight:500; }
    .dropdown-item:hover { background-color:#fffaf5;color:#ff5722; }

    /* =========================================
       ESTILOS DE LA PÁGINA (PERFIL)
       ========================================= */
    .navbar { display:none !important; }
    .container.mt-4 { max-width:100% !important;padding:0 !important;margin:0 !important; }
    body { background-color:#f4f6f9;color:#333;overflow-x:hidden;font-family:'Segoe UI',sans-serif;margin:0; }

    .main-wrapper { padding:40px 30px;max-width:960px;margin:0 auto; }
    .page-title { margin-bottom:30px;text-align:center; }
    .page-title h2 { font-weight:900;margin:0;color:#222;font-size:2rem; }
    .page-title p { color:#666;font-size:1rem;margin-top:5px; }

    .content-box { background:#fff;border-radius:16px;padding:22px;border:none;box-shadow:0 4px 15px rgba(0,0,0,0.03);margin-bottom:20px;transition:transform 0.2s,box-shadow 0.2s; }
    .content-box:hover { box-shadow:0 8px 25px rgba(0,0,0,0.06);transform:translateY(-2px); }
    .box-title { font-weight:800;font-size:0.85rem;margin-bottom:16px;color:#555;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #f4f6f9;padding-bottom:10px; }

    /* Avatar */
    .profile-avatar-container { display:flex;justify-content:center;align-items:center;padding:10px 0 14px; }
    .avatar-circle { width:110px;height:110px;background:linear-gradient(135deg,#ff8a65 0%,#ff5722 100%);border-radius:50%;display:flex;justify-content:center;align-items:center;font-size:3rem;color:white;position:relative;border:4px solid #fff;box-shadow:0 8px 20px rgba(255,87,34,0.25); }
    .camera-btn { position:absolute;bottom:2px;right:4px;background:white;width:34px;height:34px;border-radius:50%;display:flex;justify-content:center;align-items:center;box-shadow:0 4px 10px rgba(0,0,0,0.15);color:#ff5722;cursor:pointer;border:2px solid #fff;transition:0.3s; }
    .camera-btn:hover { background:#f4f6f9;transform:scale(1.1); }
    .user-display-name { font-size:1.1rem;font-weight:800;color:#222;text-align:center; }
    .user-display-email { font-size:0.88rem;color:#888;text-align:center;margin-top:2px; }
    .status-active-pill { display:inline-flex;align-items:center;gap:6px;background:#dcfce7;color:#166534;font-size:0.78rem;font-weight:700;padding:5px 14px;border-radius:999px;margin-top:8px; }

    /* Permisos */
    .permission-item { display:flex;justify-content:space-between;align-items:center;padding:11px 0;border-bottom:1px solid #f2f2f2; }
    .permission-item:last-child { border-bottom:none; }
    .permission-label { font-weight:600;color:#555;font-size:0.9rem; }
    .permission-status { font-size:0.8rem;font-weight:bold;padding:3px 12px;border-radius:20px; }
    .status-completo { color:#16a34a;background:#dcfce7; }
    .status-bloqueado { color:#dc2626;background:#fee2e2; }

    /* Información en modo lectura */
    .info-grid { display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:20px; }
    .info-field { display:flex;flex-direction:column;gap:4px; }
    .info-field.full { grid-column:1/-1; }
    .info-field-label { font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#999; }
    .info-field-value { font-size:1rem;font-weight:700;color:#222; }

    /* Detalles cuenta */
    .meta-row { display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #f2f2f2; }
    .meta-row:last-child { border-bottom:none; }
    .meta-label { font-size:0.9rem;color:#777; }
    .meta-value { font-size:0.9rem;font-weight:700;color:#333; }

    /* Botón editar */
    .btn-edit-profile { background:linear-gradient(135deg,#ff5722 0%,#e64a19 100%);border:none;color:white;padding:13px 20px;border-radius:12px;font-weight:800;transition:0.3s;cursor:pointer;width:100%;font-size:0.95rem;text-transform:uppercase;letter-spacing:1px;display:flex;align-items:center;justify-content:center;gap:8px; }
    .btn-edit-profile:hover { transform:translateY(-2px);box-shadow:0 8px 20px rgba(230,74,25,0.3); }

    /* MODAL */
    .modal-overlay { display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:9999;align-items:center;justify-content:center; }
    .modal-overlay.show { display:flex; }
    .modal-card { background:#fff;border-radius:18px;padding:30px;width:100%;max-width:460px;box-shadow:0 20px 50px rgba(0,0,0,0.2);position:relative; }
    .modal-header-row { display:flex;align-items:center;justify-content:space-between;margin-bottom:22px; }
    .modal-title { font-weight:900;font-size:1.1rem;color:#222; }
    .modal-close-btn { background:none;border:none;font-size:1.4rem;color:#999;cursor:pointer;line-height:1; }
    .modal-close-btn:hover { color:#333; }
    .modal-field { margin-bottom:18px; }
    .modal-field label { display:block;font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#888;margin-bottom:7px; }
    .modal-field input { width:100%;padding:11px 14px;border-radius:10px;border:1px solid #e2e8f0;background:#f8fafc;font-size:0.95rem;font-weight:600;color:#333;outline:none;transition:all 0.2s; }
    .modal-field input:focus { border-color:#ff5722;background:#fff;box-shadow:0 0 0 4px rgba(255,87,34,0.1); }
    .modal-footer-row { display:flex;gap:10px;margin-top:10px; }
    .btn-modal-cancel { flex:1;padding:12px;background:none;border:1px solid #ddd;border-radius:10px;color:#555;font-size:0.9rem;font-weight:600;cursor:pointer; }
    .btn-modal-cancel:hover { background:#f4f6f9; }
    .btn-modal-save { flex:2;padding:12px;background:linear-gradient(135deg,#ff5722,#e64a19);border:none;border-radius:10px;color:#fff;font-size:0.9rem;font-weight:800;cursor:pointer; }
    .btn-modal-save:hover { opacity:0.9; }
</style>

<header class="main-header">
    <div class="header-top">
        <a href="{{ route('dashboard') }}" class="header-brand">
            <div style="width:45px;height:45px;background:#fff;border-radius:50%;display:flex;justify-content:center;align-items:center;overflow:hidden;box-shadow:0 2px 5px rgba(0,0,0,0.1)">
                <img src="{{ asset('AYFEXLOGO-Photoroom.png') }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;padding:6px">
            </div>
            <div class="brand-text">
                <span class="brand-name">AYFEX</span>
                <span class="brand-slogan">Gestión de Transporte Logístico de Paquetería</span>
            </div>
        </a>

        <div class="header-search d-none d-md-block">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Buscar envíos, clientes, operadores...">
        </div>

        <div class="header-actions">
            <a href="{{ route('perfil') }}" class="user-profile" style="background-color:rgba(255,255,255,0.15)">
                <div class="user-info d-none d-sm-block">
                    <div class="user-name" id="header_nombre_usuario">Cargando...</div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar" id="header_avatar_letra">?</div>
            </a>
            <a href="{{ route('login') }}" class="user-profile" style="margin-left:5px" title="Cerrar Sesión">
                <i class="fa-solid fa-right-from-bracket" style="color:white;font-size:1.2rem"></i>
            </a>
        </div>
    </div>

    <div class="header-nav flex-wrap">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-border-all"></i> Dashboard
        </a>
        <div class="dropdown">
            <div class="nav-item {{ request()->routeIs('envios') || request()->routeIs('rutas') ? 'active' : '' }}" data-bs-toggle="dropdown">
                Operaciones <i class="fa-solid fa-chevron-down chevron"></i>
            </div>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('envios') }}"><i class="fa-solid fa-box me-2"></i> Envíos</a></li>
                <li><a class="dropdown-item" href="{{ route('rutas') }}"><i class="fa-solid fa-route me-2"></i> Rutas</a></li>
            </ul>
        </div>
        <div class="dropdown">
            <div class="nav-item {{ request()->routeIs('clientes') || request()->routeIs('operadores') ? 'active' : '' }}" data-bs-toggle="dropdown">
                Gestión <i class="fa-solid fa-chevron-down chevron"></i>
            </div>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('clientes') }}"><i class="fa-solid fa-users me-2"></i> Clientes</a></li>
                <li><a class="dropdown-item" href="{{ route('operadores') }}"><i class="fa-solid fa-truck me-2"></i> Operadores</a></li>
            </ul>
        </div>
        <div class="dropdown">
            <div class="nav-item {{ request()->routeIs('reportes') || request()->routeIs('incidencias') ? 'active' : '' }}" data-bs-toggle="dropdown">
                Administración <i class="fa-solid fa-chevron-down chevron"></i>
            </div>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('reportes') }}"><i class="fa-solid fa-file-lines me-2"></i> Reportes</a></li>
                <li><a class="dropdown-item" href="{{ route('incidencias') }}"><i class="fa-solid fa-circle-exclamation me-2"></i> Incidencias</a></li>
            </ul>
        </div>
    </div>
</header>

<!-- MODAL EDITAR -->
<div class="modal-overlay" id="modalEditar">
    <div class="modal-card">
        <div class="modal-header-row">
            <span class="modal-title"><i class="fa-solid fa-pen-to-square me-2" style="color:#ff5722"></i>Editar información</span>
            <button class="modal-close-btn" onclick="cerrarModal()">✕</button>
        </div>
        <div class="modal-field">
            <label>Nombre completo</label>
            <input type="text" id="modal_nombre" placeholder="Tu nombre completo">
        </div>
        <div class="modal-field">
            <label>Correo electrónico</label>
            <input type="email" id="modal_correo" placeholder="correo@ejemplo.com">
        </div>
        <div class="modal-field">
            <label>Teléfono</label>
            <input type="text" id="modal_telefono" placeholder="+52 ...">
        </div>
        <div class="modal-footer-row">
            <button class="btn-modal-cancel" onclick="cerrarModal()">Cancelar</button>
            <button class="btn-modal-save" id="btnGuardarModal">
                <i class="fa-solid fa-floppy-disk me-1"></i> Guardar cambios
            </button>
        </div>
    </div>
</div>

<div class="main-wrapper">
    <div class="page-title">
        <h2>Mi Perfil</h2>
        <p>Consulta y administra tu información personal</p>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="content-box text-center">
                <h6 class="box-title">Foto de perfil</h6>
                <div class="profile-avatar-container">
                    <div class="avatar-circle">
                        <span id="avatar_letra_grande">?</span>
                        <div class="camera-btn" title="Actualizar foto"><i class="fa-solid fa-camera"></i></div>
                    </div>
                </div>
                <div class="user-display-name" id="disp_nombre">—</div>
                <div class="user-display-email" id="disp_email">—</div>
                <div style="display:flex;justify-content:center">
                    <span class="status-active-pill"><i class="fa-solid fa-circle" style="color:#16a34a;font-size:0.45rem"></i> Cuenta activa</span>
                </div>
            </div>

            <div class="content-box">
                <h6 class="box-title">Nivel de acceso</h6>
                <div class="permission-item">
                    <span class="permission-label"><i class="fa-solid fa-box text-secondary me-2"></i>Envíos</span>
                    <span id="permiso_envios" class="permission-status status-bloqueado">Validando...</span>
                </div>
                <div class="permission-item">
                    <span class="permission-label"><i class="fa-solid fa-users text-secondary me-2"></i>Clientes</span>
                    <span id="permiso_clientes" class="permission-status status-bloqueado">Validando...</span>
                </div>
                <div class="permission-item">
                    <span class="permission-label"><i class="fa-solid fa-truck text-secondary me-2"></i>Operadores</span>
                    <span id="permiso_operadores" class="permission-status status-bloqueado">Validando...</span>
                </div>
                <div class="permission-item">
                    <span class="permission-label"><i class="fa-solid fa-file-lines text-secondary me-2"></i>Reportes</span>
                    <span id="permiso_reportes" class="permission-status status-bloqueado">Validando...</span>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="content-box">
                <h6 class="box-title">Información personal</h6>
                <div class="info-grid">
                    <div class="info-field">
                        <div class="info-field-label">Nombre completo</div>
                        <div class="info-field-value" id="disp_nombre2">—</div>
                    </div>
                    <div class="info-field">
                        <div class="info-field-label">Teléfono</div>
                        <div class="info-field-value" id="disp_telefono">—</div>
                    </div>
                    <div class="info-field full">
                        <div class="info-field-label">Correo electrónico</div>
                        <div class="info-field-value" id="disp_email2">—</div>
                    </div>
                </div>
                <button class="btn-edit-profile" onclick="abrirModal()">
                    <i class="fa-solid fa-pen-to-square"></i> Editar información
                </button>
            </div>

            <div class="content-box">
                <h6 class="box-title">Detalles de la cuenta</h6>
                <div class="meta-row">
                    <span class="meta-label">Rol</span>
                    <span class="meta-value">Administrador</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Fecha de registro</span>
                    <span class="meta-value"><i class="fa-regular fa-calendar text-muted me-1"></i><span id="fecha_registro">—</span></span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Estado</span>
                    <span class="permission-status status-completo">Activo</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const authCredentials = localStorage.getItem("authCredentials");
    if (!authCredentials) {
        alert("Atención: Primero debes iniciar sesión.");
        window.location.href = "/";
        return;
    }

    const API_URL = "http://127.0.0.1:5000/v1/mi-perfil/";
    const headers = { 'Authorization': `Basic ${authCredentials}`, 'Accept': 'application/json' };

    function actualizarVista(u) {
        const inicial = (u.nombre_completo || '?').charAt(0).toUpperCase();
        document.getElementById('disp_nombre').innerText    = u.nombre_completo   || '—';
        document.getElementById('disp_nombre2').innerText   = u.nombre_completo   || '—';
        document.getElementById('disp_email').innerText     = u.correo_electronico || '—';
        document.getElementById('disp_email2').innerText    = u.correo_electronico || '—';
        document.getElementById('disp_telefono').innerText  = u.telefono           || '—';
        document.getElementById('fecha_registro').innerText = u.fecha_registro      || '—';
        document.getElementById('avatar_letra_grande').innerText   = inicial;
        document.getElementById('header_nombre_usuario').innerText = u.nombre_completo || '—';
        document.getElementById('header_avatar_letra').innerText   = inicial;
        actualizarPermisoUI('permiso_envios',     true);
        actualizarPermisoUI('permiso_clientes',   true);
        actualizarPermisoUI('permiso_operadores', false);
        actualizarPermisoUI('permiso_reportes',   true);
    }

    function actualizarPermisoUI(id, ok) {
        const el = document.getElementById(id);
        el.innerText   = ok ? 'Completo' : 'Sin Acceso';
        el.className   = 'permission-status ' + (ok ? 'status-completo' : 'status-bloqueado');
    }

    fetch(API_URL, { method: 'GET', headers })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(u => actualizarVista(u))
        .catch(() => alert("Error al cargar el perfil. Verifica la conexión con la API."));

    window.abrirModal = function () {
        document.getElementById('modal_nombre').value   = document.getElementById('disp_nombre2').innerText.replace('—','');
        document.getElementById('modal_correo').value   = document.getElementById('disp_email2').innerText.replace('—','');
        document.getElementById('modal_telefono').value = document.getElementById('disp_telefono').innerText.replace('—','');
        document.getElementById('modalEditar').classList.add('show');
    };

    window.cerrarModal = function () {
        document.getElementById('modalEditar').classList.remove('show');
    };

    document.getElementById('modalEditar').addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });

    document.getElementById('btnGuardarModal').addEventListener('click', function () {
        const datos = {
            nombre_completo:    document.getElementById('modal_nombre').value,
            correo_electronico: document.getElementById('modal_correo').value,
            telefono:           document.getElementById('modal_telefono').value
        };

        fetch(API_URL, {
            method: 'PUT',
            headers: { ...headers, 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        })
        .then(r => {
            if (r.status === 401) { localStorage.removeItem("authCredentials"); window.location.href = "{{ route('login') }}"; throw new Error(); }
            if (r.status === 400) { alert("Ese correo electrónico ya está en uso."); throw new Error(); }
            if (!r.ok) throw new Error("Error al guardar");
            return r.json();
        })
        .then(data => {
            actualizarVista(data);
            cerrarModal();
            alert("¡Perfil actualizado con éxito!");
        })
        .catch(err => console.error(err));
    });
});
</script>
@endsection