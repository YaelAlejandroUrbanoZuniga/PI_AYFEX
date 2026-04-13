@extends('layouts.app')

@section('content')
<style>
    .main-header {
        position: sticky; top: 0; z-index: 1000;
        background: linear-gradient(90deg, #ff5722 0%, #e64a19 100%);
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .header-top {
        display: flex; justify-content: space-between; align-items: center;
        padding: 12px 24px; border-bottom: 1px solid rgba(255,255,255,0.2);
    }
    .header-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
    .brand-text { display: flex; flex-direction: column; }
    .brand-name { font-weight: 900; font-size: 1.2rem; color: #ffffff; line-height: 1.1; letter-spacing: 1px; }
    .brand-slogan { font-size: 0.75rem; color: rgba(255,255,255,0.85); }
    .header-search { flex: 1; max-width: 600px; margin: 0 2rem; position: relative; }
    .header-search i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #ff5722; z-index: 2; }
    .header-search input {
        width: 100%; padding: 10px 15px 10px 45px; border: none; border-radius: 25px;
        background-color: #ffffff; font-size: 0.95rem; color: #333; outline: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .header-search input::placeholder { color: #aaa; }
    .header-actions { display: flex; align-items: center; gap: 20px; }
    .user-profile { display: flex; align-items: center; gap: 12px; text-decoration: none; }
    .user-info { text-align: right; }
    .user-name { font-weight: 600; font-size: 0.9rem; color: #ffffff; line-height: 1.2; }
    .user-role { font-size: 0.75rem; color: rgba(255,255,255,0.85); }
    .user-avatar {
        width: 38px; height: 38px; background-color: #ffffff; color: #ff5722;
        border-radius: 50%; display: flex; justify-content: center; align-items: center;
        font-size: 1.1rem; font-weight: bold;
    }
    .header-nav { display: flex; padding: 0 24px; gap: 8px; }
    .nav-item {
        padding: 12px 16px; font-size: 0.95rem; color: #ffffff; font-weight: 600; text-decoration: none;
        display: flex; align-items: center; gap: 8px; border-radius: 12px 12px 0 0;
        margin-top: 6px; cursor: pointer; transition: all 0.3s;
    }
    .nav-item:hover { background-color: rgba(255,255,255,0.2); color: #ffffff; }
    .nav-item.active { background-color: #f4f6f9; color: #ff5722; }
    .nav-item.active i { color: #ff5722; }
    .nav-item i.chevron { font-size: 0.75rem; margin-left: 4px; }
    .dropdown-menu { border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 0 8px 8px 8px; padding: 8px 0; margin-top: 0 !important; }
    .dropdown-item { padding: 10px 20px; font-size: 0.9rem; color: #444; font-weight: 500; }
    .dropdown-item:hover { background-color: #fffaf5; color: #ff5722; }

    body { background-color: #f4f6f9; color: #333; overflow-x: hidden; margin: 0; }
    .navbar { display: none !important; }
    .container.mt-4 { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
    .main-wrapper { padding: 30px; max-width: 1400px; margin: 0 auto; }

    .page-title { margin-bottom: 0; }
    .page-title h2 { font-weight: 900; margin: 0; color: #222; }
    .page-title p { color: #666; font-size: 0.95rem; margin-bottom: 0; }

    /* ── Filtro ── */
    .filter-bar {
        background: #fff; border-radius: 12px; padding: 15px 22px;
        margin-bottom: 25px; border: 2px solid #222;
    }
    .filter-search { position: relative; width: 100%; }
    .filter-search i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; }
    .filter-search input {
        width: 100%; padding: 10px 15px 10px 40px; border-radius: 8px;
        border: none; font-size: 0.9rem; outline: none; background: transparent;
    }

    /* ── Tabla ── */
    .table-container { background: #fff; border-radius: 16px; padding: 22px; border: 2px solid #222; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    .box-title { font-weight: 800; font-size: 1.1rem; margin-bottom: 20px; color: #222; }
    .table th { border-top: none; color: #888; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; padding-bottom: 15px; }
    .table td { vertical-align: middle; font-size: 0.9rem; color: #444; border-bottom: 1px solid #f0f0f0; padding: 15px 8px; }
    .contact-icon { color: #aaa; margin-right: 5px; font-size: 0.85rem; }
    .total-envios { color: #ff5722; font-weight: 800; }

    /* ── Avatar inicial ── */
    .cliente-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        background: #ffedd5; color: #ea580c;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 0.9rem; margin-right: 10px; flex-shrink: 0;
    }

    /* ── Skeleton ── */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%; animation: shimmer 1.4s infinite;
        border-radius: 6px; display: inline-block;
    }
    @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

    /* ── Empty / Error ── */
    .empty-state { text-align: center; padding: 40px 20px; color: #bbb; }
    .empty-state i { font-size: 2.5rem; margin-bottom: 12px; display: block; }
    .empty-state p { margin: 0; font-size: 0.9rem; }

    /* ── Badge total ── */
    .badge-total {
        background: #f4f6f9; color: #666; border-radius: 20px;
        font-size: 0.8rem; font-weight: 700; padding: 4px 12px;
    }
</style>

<!-- ══════════════ HEADER ══════════════ -->
<header class="main-header">
    <div class="header-top">
        <a href="{{ route('dashboard') }}" class="header-brand">
            <div style="width:45px;height:45px;background:#fff;border-radius:50%;display:flex;justify-content:center;align-items:center;overflow:hidden;box-shadow:0 2px 5px rgba(0,0,0,0.1);">
                <img src="{{ asset('AYFEXLOGO-Photoroom.png') }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;padding:6px;">
            </div>
            <div class="brand-text">
                <span class="brand-name">AYFEX</span>
                <span class="brand-slogan">Gestión de Transporte Logistico de Paquetería</span>
            </div>
        </a>

        <div class="header-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="buscadorClientes" placeholder="Buscar por nombre, correo o teléfono...">
        </div>

        <div class="header-actions">
            <a href="{{ route('perfil') }}" class="user-profile">
                <div class="user-info d-none d-md-block">
                    <div class="user-name" id="headerNombre">Admin AYFEX</div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar" id="headerAvatar">A</div>
            </a>
            <a href="{{ route('login') }}" class="user-profile" style="margin-left:10px;" title="Cerrar Sesión">
                <i class="fa-solid fa-right-from-bracket" style="color:white;font-size:1.2rem;"></i>
            </a>
        </div>
    </div>

    <div class="header-nav">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-border-all"></i> Dashboard
        </a>
        <div class="dropdown">
            <div class="nav-item {{ request()->routeIs('envios') || request()->routeIs('rutas') ? 'active' : '' }}" data-bs-toggle="dropdown" aria-expanded="false">
                Operaciones <i class="fa-solid fa-chevron-down chevron"></i>
            </div>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('envios') }}"><i class="fa-solid fa-box me-2"></i> Envíos</a></li>
                <li><a class="dropdown-item" href="{{ route('rutas') }}"><i class="fa-solid fa-route me-2"></i> Rutas</a></li>
            </ul>
        </div>
        <div class="dropdown">
            <div class="nav-item {{ request()->routeIs('clientes') || request()->routeIs('operadores') ? 'active' : '' }}" data-bs-toggle="dropdown" aria-expanded="false">
                Gestión <i class="fa-solid fa-chevron-down chevron"></i>
            </div>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('clientes') }}"><i class="fa-solid fa-users me-2"></i> Clientes</a></li>
                <li><a class="dropdown-item" href="{{ route('operadores') }}"><i class="fa-solid fa-truck me-2"></i> Operadores</a></li>
            </ul>
        </div>
        <div class="dropdown">
            <div class="nav-item {{ request()->routeIs('reportes') || request()->routeIs('incidencias') ? 'active' : '' }}" data-bs-toggle="dropdown" aria-expanded="false">
                Administración <i class="fa-solid fa-chevron-down chevron"></i>
            </div>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('reportes') }}"><i class="fa-solid fa-file-lines me-2"></i> Reportes</a></li>
                <li><a class="dropdown-item" href="{{ route('incidencias') }}"><i class="fa-solid fa-circle-exclamation me-2"></i> Incidencias</a></li>
            </ul>
        </div>
    </div>
</header>

<!-- ══════════════ CONTENIDO ══════════════ -->
<div class="main-wrapper">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-title">
            <h2>Clientes</h2>
            <p>Usuarios registrados desde la app móvil</p>
        </div>
        <span class="badge-total" id="badge-total">Cargando...</span>
    </div>

    <!-- Buscador -->
    <div class="filter-bar">
        <div class="filter-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="buscadorTabla" placeholder="Buscar por nombre, correo o teléfono...">
        </div>
    </div>

    <!-- Tabla -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-borderless align-middle">
                <thead>
                    <tr style="border-bottom:2px solid #e9ecef;">
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Correo Electrónico</th>
                        <th>Total de Envíos</th>
                    </tr>
                </thead>
                <tbody id="tabla-clientes">
                    <!-- Skeleton inicial -->
                    <tr>
                        <td><span class="skeleton" style="width:180px;height:14px;display:block;"></span></td>
                        <td><span class="skeleton" style="width:100px;height:14px;display:block;"></span></td>
                        <td><span class="skeleton" style="width:200px;height:14px;display:block;"></span></td>
                        <td><span class="skeleton" style="width:40px;height:14px;display:block;"></span></td>
                    </tr>
                    <tr>
                        <td><span class="skeleton" style="width:160px;height:14px;display:block;"></span></td>
                        <td><span class="skeleton" style="width:100px;height:14px;display:block;"></span></td>
                        <td><span class="skeleton" style="width:210px;height:14px;display:block;"></span></td>
                        <td><span class="skeleton" style="width:40px;height:14px;display:block;"></span></td>
                    </tr>
                    <tr>
                        <td><span class="skeleton" style="width:170px;height:14px;display:block;"></span></td>
                        <td><span class="skeleton" style="width:100px;height:14px;display:block;"></span></td>
                        <td><span class="skeleton" style="width:190px;height:14px;display:block;"></span></td>
                        <td><span class="skeleton" style="width:40px;height:14px;display:block;"></span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// ══════════════════════════════════════
// CONFIG
// ══════════════════════════════════════
const API_BASE = 'http://127.0.0.1:5000';
const token    = localStorage.getItem('authToken');
const getHeaders = () => ({
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`
});

let todosLosClientes = [];

// ══════════════════════════════════════
// RENDER TABLA
// ══════════════════════════════════════
function renderTabla(clientes) {
    const tbody = document.getElementById('tabla-clientes');

    if (clientes.length === 0) {
        tbody.innerHTML = `
            <tr><td colspan="4">
                <div class="empty-state">
                    <i class="fa-solid fa-users-slash"></i>
                    <p>No se encontraron clientes.</p>
                </div>
            </td></tr>`;
        return;
    }

    tbody.innerHTML = clientes.map(c => {
        const inicial = c.nombre_completo.charAt(0).toUpperCase();
        return `
        <tr>
            <td>
                <div style="display:flex;align-items:center;">
                    <div class="cliente-avatar">${inicial}</div>
                    <div>
                        <strong style="font-size:0.95rem;">${c.nombre_completo}</strong>
                        <div style="font-size:0.78rem;color:#aaa;">ID #${c.id}</div>
                    </div>
                </div>
            </td>
            <td><i class="fa-solid fa-phone contact-icon"></i>${c.telefono}</td>
            <td><i class="fa-regular fa-envelope contact-icon"></i>${c.correo_electronico}</td>
            <td class="total-envios">${c.total_envios} envío${c.total_envios !== 1 ? 's' : ''}</td>
        </tr>`;
    }).join('');
}

// ══════════════════════════════════════
// CARGA DE DATOS
// ══════════════════════════════════════
async function cargarClientes() {
    try {
        const res = await fetch(`${API_BASE}/v1/clientes/`, { headers: getHeaders() });

        if (!res.ok) throw new Error(`Error ${res.status}`);

        todosLosClientes = await res.json();

        renderTabla(todosLosClientes);
        document.getElementById('badge-total').textContent =
            `${todosLosClientes.length} cliente${todosLosClientes.length !== 1 ? 's' : ''}`;

    } catch (err) {
        console.error(err);
        document.getElementById('tabla-clientes').innerHTML = `
            <tr><td colspan="4">
                <div class="empty-state" style="color:#ef4444;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <p>Error de conexión. Verifica que la API esté activa.</p>
                </div>
            </td></tr>`;
        document.getElementById('badge-total').textContent = '— clientes';
    }
}

// ══════════════════════════════════════
// BUSCADOR (filtra en tiempo real)
// ══════════════════════════════════════
function conectarBuscador(inputId) {
    document.getElementById(inputId).addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        // Sincronizar ambos inputs
        ['buscadorClientes','buscadorTabla'].forEach(id => {
            const el = document.getElementById(id);
            if (el && el !== this) el.value = this.value;
        });

        if (!q) { renderTabla(todosLosClientes); return; }

        const filtrados = todosLosClientes.filter(c =>
            c.nombre_completo.toLowerCase().includes(q) ||
            c.correo_electronico.toLowerCase().includes(q) ||
            c.telefono.toLowerCase().includes(q)
        );
        renderTabla(filtrados);
    });
}

// ══════════════════════════════════════
// PERFIL HEADER
// ══════════════════════════════════════
function actualizarPerfil() {
    const nombre = localStorage.getItem('nombreUsuario') || 'Admin AYFEX';
    const el = document.getElementById('headerNombre');
    const av = document.getElementById('headerAvatar');
    if (el) el.textContent = nombre;
    if (av) av.textContent = nombre.charAt(0).toUpperCase();
}

// ══════════════════════════════════════
// INIT
// ══════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    cargarClientes();
    conectarBuscador('buscadorClientes');
    conectarBuscador('buscadorTabla');
    actualizarPerfil();
});
</script>
@endsection