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

    body { background-color: #f4f6f9; color: #333; overflow-x: hidden; margin: 0; font-family: 'Segoe UI', Roboto, sans-serif; }
    .navbar { display: none !important; }
    .container.mt-4 { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
    .main-wrapper { padding: 30px; max-width: 1400px; margin: 0 auto; }

    /* ── KPI Cards ── */
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .kpi-card {
        background: #fff; border-radius: 16px; padding: 22px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex;
        align-items: center; gap: 16px; position: relative; transition: transform 0.2s;
    }
    .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.06); }
    .kpi-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; }
    .kpi-card h6 { color: #777; font-size: 0.82rem; font-weight: 600; margin: 0 0 4px; }
    .kpi-card h3 { font-weight: 900; margin: 0; color: #222; font-size: 2rem; line-height: 1; }
    .ic-orange { background: #fff7ed; color: #f97316; }
    .ic-red    { background: #fef2f2; color: #ef4444; }
    .ic-green  { background: #f0fdf4; color: #22c55e; }
    .ic-blue   { background: #eff6ff; color: #3b82f6; }

    /* ── Tabla ── */
    .table-container {
        background: #fff; border-radius: 16px; padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    .table-toolbar {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 20px; flex-wrap: wrap; gap: 12px;
    }
    .tabla-titulo { font-weight: 800; font-size: 1.05rem; color: #222; margin: 0; }
    .toolbar-right { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .filter-search-wrap { position: relative; }
    .filter-search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 0.85rem; }
    .filter-input {
        padding: 8px 12px 8px 34px; border-radius: 8px; border: 1px solid #e0e0e0;
        font-size: 0.88rem; outline: none; background: #f9fafb; min-width: 200px;
    }
    .filter-select {
        padding: 8px 14px; border-radius: 8px; border: 1px solid #e0e0e0;
        font-size: 0.88rem; outline: none; background: #f9fafb; color: #444; cursor: pointer;
    }
    .btn-refresh {
        background: #f9fafb; border: 1px solid #e0e0e0; border-radius: 8px;
        padding: 8px 14px; font-size: 0.88rem; color: #555; cursor: pointer;
        display: flex; align-items: center; gap: 6px; transition: 0.2s; font-weight: 600;
    }
    .btn-refresh:hover { background: #ff5722; color: #fff; border-color: #ff5722; }

    .table th { border-top: none; color: #888; font-size: 0.78rem; font-weight: 700; text-transform: uppercase; padding-bottom: 14px; letter-spacing: 0.5px; }
    .table td { vertical-align: middle; font-size: 0.88rem; color: #444; border-bottom: 1px solid #f4f4f4; padding: 13px 8px; }

    /* badges */
    .badge-estado { padding: 5px 12px; border-radius: 20px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
    .b-pendiente  { background: #fef9c3; color: #ca8a04; }
    .b-resuelto   { background: #dcfce7; color: #16a34a; }

    .badge-prioridad { padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
    .p-alta   { background: #fee2e2; color: #dc2626; }
    .p-normal { background: #e0f2fe; color: #0369a1; }
    .p-baja   { background: #f1f5f9; color: #64748b; }

    /* action buttons */
    .btn-accion {
        border: none; background: none; padding: 5px 8px; border-radius: 6px;
        font-size: 0.85rem; cursor: pointer; transition: 0.2s;
    }
    .btn-resolver { background: #dcfce7; color: #16a34a; }
    .btn-resolver:hover { background: #16a34a; color: #fff; }
    .btn-reabrir  { background: #fef9c3; color: #ca8a04; }
    .btn-reabrir:hover  { background: #ca8a04; color: #fff; }
    .btn-ver      { background: #f1f5f9; color: #475569; }
    .btn-ver:hover      { background: #475569; color: #fff; }

    /* modal detalle */
    .detalle-field { background: #f9fafb; border-radius: 10px; padding: 14px 16px; margin-bottom: 12px; }
    .detalle-label { font-size: 0.72rem; color: #9ca3af; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; }
    .detalle-value { font-weight: 700; color: #111; font-size: 0.95rem; }

    .page-title h2 { font-weight: 900; margin: 0; color: #222; }
    .page-title p  { color: #666; font-size: 0.95rem; margin-bottom: 0; }
    .text-orange   { color: #ff5722; }

    .empty-state { text-align: center; padding: 50px 20px; color: #aaa; }
    .empty-state i { font-size: 2.5rem; margin-bottom: 12px; display: block; }
</style>

<!-- ══════════ HEADER ══════════ -->
<header class="main-header">
    <div class="header-top">
        <a href="{{ route('dashboard') }}" class="header-brand">
            <div style="width:45px;height:45px;background:#fff;border-radius:50%;display:flex;justify-content:center;align-items:center;overflow:hidden;box-shadow:0 2px 5px rgba(0,0,0,.1);">
                <img src="{{ asset('AYFEXLOGO-Photoroom.png') }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;padding:6px;">
            </div>
            <div class="brand-text">
                <span class="brand-name">AYFEX</span>
                <span class="brand-slogan">Gestión de Transporte Logistico de Paquetería</span>
            </div>
        </a>
        <div class="header-actions">
            <a href="{{ route('perfil') }}" class="user-profile">
                <div class="user-info d-none d-md-block">
                    <div class="user-name"><span id="headerNombre">—</span></div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar"><span id="headerAvatar">—</span></div>
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
                <li><a class="dropdown-item" href="{{ route('incidencias') }}" style="{{ request()->routeIs('incidencias') ? 'color:#ff5722;font-weight:bold;background-color:#fffaf5;' : '' }}"><i class="fa-solid fa-circle-exclamation me-2"></i> Incidencias</a></li>
            </ul>
        </div>
    </div>
</header>

<!-- ══════════ CONTENIDO ══════════ -->
<div class="main-wrapper">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-title">
            <h2>Reportes de la App Móvil</h2>
            <p>Incidencias reportadas por los clientes — Monitorea y da solución</p>
        </div>
        <button class="btn-refresh" onclick="cargarReportes()" style="padding:10px 20px;font-size:0.9rem;">
            <i class="fa-solid fa-sync"></i> Actualizar
        </button>
    </div>

    <!-- KPIs -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon ic-orange"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div><h6>Total Reportes</h6><h3 id="kpi-total">—</h3></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon ic-red"><i class="fa-solid fa-circle-exclamation"></i></div>
            <div><h6>Pendientes</h6><h3 id="kpi-pendientes">—</h3></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon ic-green"><i class="fa-solid fa-circle-check"></i></div>
            <div><h6>Resueltos</h6><h3 id="kpi-resueltos">—</h3></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon ic-blue"><i class="fa-solid fa-arrow-up"></i></div>
            <div><h6>Alta Prioridad</h6><h3 id="kpi-alta">—</h3></div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="table-container">
        <div class="table-toolbar">
            <h5 class="tabla-titulo" id="tabla-titulo">Reportes de la App (0)</h5>
            <div class="toolbar-right">
                <div class="filter-search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="filter-input" id="filtro-texto" placeholder="Buscar por tipo o descripción..." oninput="filtrar()">
                </div>
                <select class="filter-select" id="filtro-estado" onchange="filtrar()">
                    <option value="">Todos los estados</option>
                    <option value="PENDIENTE">Pendiente</option>
                    <option value="RESUELTO">Resuelto</option>
                </select>
                <select class="filter-select" id="filtro-prioridad" onchange="filtrar()">
                    <option value="">Todas las prioridades</option>
                    <option value="ALTA">Alta</option>
                    <option value="NORMAL">Normal</option>
                    <option value="BAJA">Baja</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-borderless align-middle">
                <thead>
                    <tr style="border-bottom:2px solid #f0f0f0;">
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Prioridad</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbody-reportes">
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="spinner-border" style="color:#ff5722;" role="status"></div>
                            <p class="mt-2 text-muted">Cargando reportes...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ══════════ MODAL DETALLE ══════════ -->
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 10px 40px rgba(0,0,0,0.12);">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:22px 24px;">
                <h5 class="modal-title" style="font-weight:800;color:#222;">
                    <i class="fa-solid fa-circle-exclamation me-2 text-orange"></i>
                    Reporte <span id="det-id" style="color:#ff5722;"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:24px;" id="modal-detalle-body">
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:16px 24px;gap:10px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;font-weight:600;">Cerrar</button>
                <button type="button" id="btn-modal-accion" class="btn" style="border-radius:8px;font-weight:600;"></button>
            </div>
        </div>
    </div>
</div>

<script>
// ══════════════════════════════════════
// CONFIG
// ══════════════════════════════════════
const API_REPORTES = 'http://127.0.0.1:5000/v1/reportes-movil';

const token = localStorage.getItem('authToken');
const getHeaders = () => ({
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`
});

let todosReportes = [];

// ══════════════════════════════════════
// INIT
// ══════════════════════════════════════
document.addEventListener('DOMContentLoaded', cargarReportes);

// ══════════════════════════════════════
// CARGAR REPORTES
// ══════════════════════════════════════
async function cargarReportes() {
    const tbody = document.getElementById('tbody-reportes');
    tbody.innerHTML = `
        <tr><td colspan="8" class="text-center py-5">
            <div class="spinner-border" style="color:#ff5722;" role="status"></div>
            <p class="mt-2 text-muted">Cargando reportes...</p>
        </td></tr>`;

    try {
        const res = await fetch(`${API_REPORTES}/`, { headers: getHeaders() });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        todosReportes = await res.json();
        actualizarKPIs(todosReportes);
        filtrar();
    } catch (err) {
        console.error(err);
        tbody.innerHTML = `
            <tr><td colspan="8" class="text-center text-danger py-5">
                <i class="fa-solid fa-plug-circle-xmark fa-2x mb-2 d-block"></i>
                Sin conexión con el servidor. ¿Está corriendo FastAPI?
            </td></tr>`;
    }
}

// ══════════════════════════════════════
// KPIs
// ══════════════════════════════════════
function actualizarKPIs(data) {
    document.getElementById('kpi-total').innerText      = data.length;
    document.getElementById('kpi-pendientes').innerText = data.filter(r => r.estado === 'PENDIENTE').length;
    document.getElementById('kpi-resueltos').innerText  = data.filter(r => r.estado === 'RESUELTO').length;
    document.getElementById('kpi-alta').innerText       = data.filter(r => r.prioridad === 'ALTA').length;
}

// ══════════════════════════════════════
// FILTROS
// ══════════════════════════════════════
function filtrar() {
    const texto     = document.getElementById('filtro-texto').value.toLowerCase();
    const estado    = document.getElementById('filtro-estado').value;
    const prioridad = document.getElementById('filtro-prioridad').value;

    const filtrados = todosReportes.filter(r => {
        const matchTexto     = !texto    || r.tipo.toLowerCase().includes(texto) || r.descripcion.toLowerCase().includes(texto);
        const matchEstado    = !estado   || r.estado === estado;
        const matchPrioridad = !prioridad|| r.prioridad === prioridad;
        return matchTexto && matchEstado && matchPrioridad;
    });

    renderTabla(filtrados);
}

// ══════════════════════════════════════
// RENDER TABLA
// ══════════════════════════════════════
function renderTabla(data) {
    const tbody = document.getElementById('tbody-reportes');
    document.getElementById('tabla-titulo').innerText = `Reportes de la App (${data.length})`;

    if (!data.length) {
        tbody.innerHTML = `
            <tr><td colspan="8">
                <div class="empty-state">
                    <i class="fa-solid fa-inbox"></i>
                    <p style="font-weight:600;">Sin reportes para mostrar</p>
                </div>
            </td></tr>`;
        return;
    }

    tbody.innerHTML = data.map(r => `
        <tr>
            <td><strong>#${r.id}</strong></td>
            <td>
                <div style="font-weight:600;font-size:0.85rem;">${r.nombre_usuario}</div>
            </td>
            <td><span style="font-weight:600;">${r.tipo}</span></td>
            <td>
                <span class="text-muted" style="font-size:0.82rem;max-width:200px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${r.descripcion}">
                    ${r.descripcion}
                </span>
            </td>
            <td>${badgePrioridad(r.prioridad)}</td>
            <td style="font-size:0.83rem;">${r.fecha}</td>
            <td>${badgeEstado(r.estado)}</td>
            <td>
                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                    <button class="btn-accion btn-ver" onclick="abrirDetalle(${r.id})" title="Ver detalle">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                    ${r.estado === 'PENDIENTE'
                        ? `<button class="btn-accion btn-resolver" onclick="cambiarEstado(${r.id}, 'resolver')" title="Marcar resuelto">
                               <i class="fa-solid fa-check"></i> Resolver
                           </button>`
                        : `<button class="btn-accion btn-reabrir" onclick="cambiarEstado(${r.id}, 'pendiente')" title="Reabrir">
                               <i class="fa-solid fa-rotate-left"></i> Reabrir
                           </button>`
                    }
                </div>
            </td>
        </tr>
    `).join('');
}

// ══════════════════════════════════════
// CAMBIAR ESTADO (resolver / pendiente)
// ══════════════════════════════════════
async function cambiarEstado(id, accion) {
    const labels = { resolver: 'resuelto', pendiente: 'pendiente' };
    if (!confirm(`¿Marcar el reporte #${id} como ${labels[accion]}?`)) return;

    try {
        const res = await fetch(`${API_REPORTES}/${id}/${accion}`, {
            method: 'PATCH',
            headers: getHeaders()
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        await cargarReportes();
    } catch (err) {
        alert(`Error: ${err.message}`);
    }
}

// ══════════════════════════════════════
// MODAL DETALLE
// ══════════════════════════════════════
function abrirDetalle(id) {
    const r = todosReportes.find(x => x.id === id);
    if (!r) return;

    document.getElementById('det-id').innerText = `#${r.id}`;

    document.getElementById('modal-detalle-body').innerHTML = `
        <div class="detalle-field">
            <div class="detalle-label">Usuario que reportó</div>
            <div class="detalle-value">${r.nombre_usuario}</div>
        </div>
        <div class="row g-3">
            <div class="col-6">
                <div class="detalle-field">
                    <div class="detalle-label">Tipo</div>
                    <div class="detalle-value">${r.tipo}</div>
                </div>
            </div>
            <div class="col-6">
                <div class="detalle-field">
                    <div class="detalle-label">Prioridad</div>
                    <div class="detalle-value">${badgePrioridad(r.prioridad)}</div>
                </div>
            </div>
            <div class="col-6">
                <div class="detalle-field">
                    <div class="detalle-label">Estado</div>
                    <div class="detalle-value">${badgeEstado(r.estado)}</div>
                </div>
            </div>
            <div class="col-6">
                <div class="detalle-field">
                    <div class="detalle-label">Fecha</div>
                    <div class="detalle-value">${r.fecha}</div>
                </div>
            </div>
        </div>
        <div class="detalle-field mt-1">
            <div class="detalle-label">Descripción</div>
            <div class="detalle-value" style="font-weight:500;line-height:1.6;">${r.descripcion}</div>
        </div>
    `;

    // Botón de acción en el modal
    const btnAccion = document.getElementById('btn-modal-accion');
    if (r.estado === 'PENDIENTE') {
        btnAccion.className = 'btn btn-success';
        btnAccion.innerHTML = '<i class="fa-solid fa-check me-2"></i> Resolver';
        btnAccion.onclick = () => {
            bootstrap.Modal.getInstance(document.getElementById('modalDetalle')).hide();
            cambiarEstado(r.id, 'resolver');
        };
    } else {
        btnAccion.className = 'btn btn-warning';
        btnAccion.innerHTML = '<i class="fa-solid fa-rotate-left me-2"></i> Reabrir';
        btnAccion.onclick = () => {
            bootstrap.Modal.getInstance(document.getElementById('modalDetalle')).hide();
            cambiarEstado(r.id, 'pendiente');
        };
    }

    new bootstrap.Modal(document.getElementById('modalDetalle')).show();
}

// ══════════════════════════════════════
// HELPERS BADGES
// ══════════════════════════════════════
function badgeEstado(estado) {
    const cls = estado === 'RESUELTO' ? 'b-resuelto' : 'b-pendiente';
    return `<span class="badge-estado ${cls}">${estado}</span>`;
}

function badgePrioridad(prioridad) {
    const map = { 'ALTA': 'p-alta', 'NORMAL': 'p-normal', 'BAJA': 'p-baja' };
    return `<span class="badge-prioridad ${map[prioridad] ?? 'p-normal'}">${prioridad ?? 'NORMAL'}</span>`;
}

// ── Nombre de usuario en header ──────────────────────
function actualizarPerfil() {
    const nombre = localStorage.getItem('nombreUsuario') || 'Admin';
    const elNombre = document.getElementById('headerNombre');
    const elAvatar = document.getElementById('headerAvatar');
    if (elNombre) elNombre.textContent = nombre;
    if (elAvatar) elAvatar.textContent = nombre.charAt(0).toUpperCase();
}
document.addEventListener('DOMContentLoaded', function() {
    actualizarPerfil();
});
</script>
@endsection