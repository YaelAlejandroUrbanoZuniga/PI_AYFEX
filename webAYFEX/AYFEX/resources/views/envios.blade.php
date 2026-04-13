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
    body { background-color: #f4f6f9; color: #333; overflow-x: hidden; margin: 0; }
    .navbar { display: none !important; }
    .container.mt-4 { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
    .main-wrapper { padding: 30px; max-width: 1400px; margin: 0 auto; }
    .page-title h2 { font-weight: 900; margin: 0; color: #222; }
    .page-title p { color: #666; font-size: 0.95rem; margin-bottom: 0; }
    .content-box, .table-container {
        background: #fff; border-radius: 16px; padding: 22px;
        border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    .btn-orange {
        background-color: #ff5722; color: #fff; border-radius: 8px;
        font-weight: 600; padding: 10px 24px; transition: 0.3s; border: none; cursor: pointer;
    }
    .btn-orange:hover { background-color: #e64a19; color: #fff; }
    .filter-bar { display: flex; justify-content: space-between; align-items: center; padding: 15px 22px; margin-bottom: 25px; }
    .filter-search { position: relative; flex: 1; max-width: 400px; }
    .filter-search i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; }
    .filter-search input {
        width: 100%; padding: 10px 15px 10px 40px; border-radius: 8px;
        border: 1px solid #eee; font-size: 0.9rem; outline: none; background: #f9fafb;
    }
    .filter-select {
        padding: 10px 15px; border-radius: 8px; border: 1px solid #eee;
        font-size: 0.9rem; outline: none; background: #f9fafb; color: #555; cursor: pointer;
    }
    .box-title { font-weight: 800; font-size: 1.1rem; margin-bottom: 20px; color: #222; }
    .table th { border-top: none; color: #888; font-size: 0.85rem; font-weight: 700; text-transform: capitalize; padding-bottom: 15px; }
    .table td { vertical-align: middle; font-size: 0.9rem; color: #444; border-bottom: 1px solid #f0f0f0; padding: 15px 8px; }
    .badge-status { padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; }
    .status-camino    { background: #ffedd5; color: #ea580c; }
    .status-entregado { background: #dcfce7; color: #16a34a; }
    .status-espera    { background: #fef08a; color: #ca8a04; }
    .status-rechazado { background: #fee2e2; color: #dc2626; }
    .status-confirmar { background: #e0f2fe; color: #0369a1; }
    .status-default   { background: #f1f5f9; color: #64748b; }
    .action-icons { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn-action {
        padding: 5px 12px; border-radius: 6px; font-size: 0.78rem;
        font-weight: 700; border: none; transition: 0.2s; cursor: pointer;
    }
    .btn-confirm  { background: #dcfce7; color: #16a34a; }
    .btn-confirm:hover  { background: #16a34a; color: white; }
    .btn-reject   { background: #fee2e2; color: #dc2626; }
    .btn-reject:hover   { background: #dc2626; color: white; }
    .btn-deliver  { background: #e0f2fe; color: #0369a1; }
    .btn-deliver:hover  { background: #0369a1; color: white; }
    .notification-wrapper { position: relative; display: flex; align-items: center; margin-right: 10px; }
    .notification-bell { color: #ffffff; font-size: 1.3rem; cursor: pointer; position: relative; transition: transform 0.2s; }
    .notification-bell:hover { transform: scale(1.1); }
    .notification-badge {
        position: absolute; top: -6px; right: -8px; background-color: #ffffff; color: #ff5722;
        font-size: 0.65rem; font-weight: 800; padding: 2px 5px; border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .notif-dropdown {
        width: 320px; padding: 0; border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: none;
        margin-top: 15px !important; right: 0 !important; left: auto !important; overflow: hidden;
    }
    .notif-header { padding: 15px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; background-color: #fafafa; }
    .notif-header span { font-weight: 800; color: #333; font-size: 0.95rem; }
    .notif-header a { font-size: 0.75rem; color: #ff5722; text-decoration: none; font-weight: 600; }
    .notif-item { padding: 12px 15px; border-bottom: 1px solid #f9f9f9; display: flex; gap: 12px; text-decoration: none; transition: background 0.2s; }
    .notif-item:hover { background-color: #fffaf5; }
    .notif-icon { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; flex-shrink: 0; }
    .notif-icon.warning { background-color: #fef08a; color: #ca8a04; }
    .notif-icon.success { background-color: #dcfce7; color: #16a34a; }
    .notif-content { display: flex; flex-direction: column; }
    .notif-text { font-size: 0.85rem; color: #444; line-height: 1.3; margin-bottom: 4px; }
    .notif-time { font-size: 0.7rem; color: #888; }
    .notif-footer { text-align: center; padding: 12px; background: #fafafa; }
    .notif-footer a { color: #ff5722; text-decoration: none; font-size: 0.85rem; font-weight: 700; }
    .text-orange { color: #ff5722 !important; }
    .spinner-border.text-orange { border-color: #ff5722; border-right-color: transparent; }
    .alert-api { background: #fff3cd; border: 1px solid #ffc107; color: #856404; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.88rem; display: none; }
    .alert-api.show { display: block; }
</style>

<header class="main-header">
    <div class="header-top">
        <a href="{{ route('dashboard') }}" class="header-brand">
            <div style="width: 45px; height: 45px; background-color: #ffffff; border-radius: 50%; display: flex; justify-content: center; align-items: center; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <img src="{{ asset('AYFEXLOGO-Photoroom.png') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain; padding: 6px;">
            </div>
            <div class="brand-text">
                <span class="brand-name">AYFEX</span>
                <span class="brand-slogan">Gestión de Transporte Logistico de Paquetería</span>
            </div>
        </a>
        <div class="header-actions">
            <div class="dropdown notification-wrapper">
                <div class="notification-bell" data-bs-toggle="dropdown" aria-expanded="false" title="Notificaciones">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notification-badge" id="notif-badge">0</span>
                </div>
                <div class="dropdown-menu notif-dropdown dropdown-menu-end">
                    <div class="notif-header">
                        <span>Notificaciones</span>
                        <a href="#" onclick="marcarLeidas()">Marcar leídas</a>
                    </div>
                    <div class="notif-body" id="notif-body">
                        <div class="notif-item"><div class="notif-content"><span class="notif-text text-muted">Sin notificaciones nuevas</span></div></div>
                    </div>
                    <div class="notif-footer">
                        <a href="{{ route('notificaciones') }}">Ver todas las notificaciones</a>
                    </div>
                </div>
            </div>

            <a href="{{ route('perfil') }}" class="user-profile">
                <div class="user-info d-none d-md-block">
                    <div class="user-name"><span id="headerNombre">—</span></div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar"><span id="headerAvatar">—</span></div>
            </a>
            <a href="{{ route('login') }}" class="user-profile" style="margin-left: 10px;" title="Cerrar Sesión">
                <i class="fa-solid fa-right-from-bracket" style="color: white; font-size: 1.2rem;"></i>
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
                <li><a class="dropdown-item" href="{{ route('envios') }}" style="{{ request()->routeIs('envios') ? 'color:#ff5722;font-weight:bold;background-color:#fffaf5;' : '' }}"><i class="fa-solid fa-box me-2"></i> Envíos</a></li>
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

<div class="main-wrapper">

    {{-- Alerta si la API no responde --}}
    <div class="alert-api" id="alert-api">
        <i class="fa-solid fa-triangle-exclamation me-2"></i>
        <strong>Sin conexión con el servidor FastAPI.</strong> Verifica que esté corriendo en
        <code>http://127.0.0.1:5000</code> y que el CORS esté configurado correctamente.
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-title">
            <h2>Gestión de Envíos</h2>
            <p>Pedidos recibidos desde la App Móvil — Estado: <strong>EN ESPERA</strong></p>
        </div>
        <button class="btn-orange" onclick="cargarPedidos()">
            <i class="fa-solid fa-sync me-2"></i> Actualizar
        </button>
    </div>

    <div class="content-box filter-bar">
        <div class="filter-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="filtroTabla" placeholder="Filtrar por ID, origen o destino..." oninput="filtrarTabla()">
        </div>
        <div>
            <select class="filter-select" id="filtroEstado" onchange="filtrarTabla()">
                <option value="">Todos los estados</option>
                <option value="EN ESPERA">En Espera</option>
                <option value="EN CAMINO">En Camino</option>
                <option value="ENTREGADO">Entregado</option>
                <option value="RECHAZADO">Rechazado</option>
                <option value="POR_CONFIRMAR_ENTREGA">Por Confirmar</option>
            </select>
        </div>
    </div>

    <div class="table-container">
        <h6 class="box-title" id="contador-pedidos">Cargando envíos...</h6>
        <div class="table-responsive">
            <table class="table table-borderless align-middle" id="tabla-envios">
                <thead>
                    <tr style="border-bottom: 2px solid #e9ecef;">
                        <th>ID</th>
                        <th>Origen / Destino</th>
                        <th>Paquete</th>
                        <th>Operador</th>
                        <th>Días Est.</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-pedidos-body">
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="spinner-border text-orange" role="status"></div>
                            <p class="mt-2 text-muted">Conectando con FastAPI...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===== MODAL: CONFIRMAR PEDIDO ===== --}}
<div class="modal fade" id="modalConfirmar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="border-bottom: 1px solid #f0f0f0; padding: 20px;">
                <h5 class="modal-title" style="font-weight: 800; color: #333;">
                    <i class="fa-solid fa-check-circle text-success me-2"></i>
                    Confirmar Pedido <span id="conf-id-text" style="color:#ff5722;"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 25px;">
                <input type="hidden" id="conf-pedido-id">
                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Ruta de Entrega</label>
                    <select class="form-select" id="conf-ruta-id" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        <option value="">Cargando rutas...</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Días Estimados de Entrega</label>
                    <input type="number" class="form-control" id="conf-dias" placeholder="Ej: 3" min="1"
                           style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #f0f0f0; padding: 15px 25px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Cancelar</button>
                <button type="button" class="btn-orange" onclick="procesarConfirmacion()">
                    <i class="fa-solid fa-check me-2"></i> Confirmar y Notificar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL: RECHAZAR PEDIDO ===== --}}
<div class="modal fade" id="modalRechazar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="border-bottom: 1px solid #f0f0f0; padding: 20px;">
                <h5 class="modal-title" style="font-weight: 800; color: #333;">
                    <i class="fa-solid fa-xmark-circle text-danger me-2"></i>
                    Rechazar Pedido <span id="rech-id-text" style="color:#ff5722;"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 25px;">
                <input type="hidden" id="rech-pedido-id">
                <p style="color:#555; font-size:0.9rem;">El cliente recibirá una notificación con el motivo.</p>
                <div class="mb-3">
                    <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Motivo del Rechazo</label>
                    <textarea class="form-control" id="rech-motivo" rows="3"
                              placeholder="Ej: El peso excede el límite permitido..."
                              style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #f0f0f0; padding: 15px 25px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="procesarRechazo()" style="border-radius: 8px; font-weight: 600;">
                    <i class="fa-solid fa-xmark me-2"></i> Confirmar Rechazo
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL: VER DETALLES ===== --}}
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="border-bottom: 1px solid #f0f0f0; padding: 20px;">
                <h5 class="modal-title" style="font-weight: 800; color: #333;">
                    <i class="fa-solid fa-box me-2" style="color:#ff5722;"></i>
                    Detalle del Pedido <span id="det-id"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 25px;" id="detalle-body">
                {{-- Se llena dinámicamente --}}
            </div>
            <div class="modal-footer" style="border-top: 1px solid #f0f0f0; padding: 15px 25px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // =====================================================
    // CONFIGURACIÓN
    // =====================================================
    const API_BASE  = 'http://127.0.0.1:5000/v1/pedidos-web';
    const RUTAS_API = 'http://127.0.0.1:5000/v1/rutas';

    // ✅ TOKEN — igual que en rutas.blade.php
    const token = localStorage.getItem('authToken');
    const getHeaders = () => ({
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
    });

    let todosPedidos = []; // cache local para filtros

    // =====================================================
    // INIT
    // =====================================================
    document.addEventListener('DOMContentLoaded', () => {
        cargarPedidos();
        cargarRutas();
    });

    // =====================================================
    // HELPERS
    // =====================================================
    function estadoBadge(estado) {
        const mapa = {
            'EN ESPERA':            'status-espera',
            'EN CAMINO':            'status-camino',
            'ENTREGADO':            'status-entregado',
            'RECHAZADO':            'status-rechazado',
            'POR_CONFIRMAR_ENTREGA':'status-confirmar',
        };
        const cls = mapa[estado] || 'status-default';
        return `<span class="badge-status ${cls}">${estado}</span>`;
    }

    function botonesAccion(p) {
        let btns = `<button class="btn-action" style="background:#f1f5f9;color:#64748b;"
                        onclick="abrirDetalle('${p.id}')">
                        <i class="fa-regular fa-eye me-1"></i> Ver
                    </button>`;

        if (p.estado === 'EN ESPERA') {
            btns += `
                <button class="btn-action btn-confirm" onclick="abrirModalConfirmar('${p.id}')">
                    <i class="fa-solid fa-check me-1"></i> Confirmar
                </button>
                <button class="btn-action btn-reject" onclick="abrirModalRechazar('${p.id}')">
                    <i class="fa-solid fa-xmark me-1"></i> Rechazar
                </button>`;
        }

        if (p.estado === 'EN CAMINO') {
            btns += `
                <button class="btn-action btn-deliver" onclick="marcarEntregado('${p.id}')">
                    <i class="fa-solid fa-truck-fast me-1"></i> Marcar Entregado
                </button>`;
        }

        return btns;
    }

    function mostrarError(msg) {
        const alerta = document.getElementById('alert-api');
        alerta.innerHTML = `<i class="fa-solid fa-triangle-exclamation me-2"></i>${msg}`;
        alerta.classList.add('show');
    }

    function ocultarError() {
        document.getElementById('alert-api').classList.remove('show');
    }

    // =====================================================
    // CARGAR PEDIDOS  ✅ con token
    // =====================================================
    async function cargarPedidos() {
        const tbody    = document.getElementById('tabla-pedidos-body');
        const contador = document.getElementById('contador-pedidos');

        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-5">
                    <div class="spinner-border text-orange" role="status"></div>
                    <p class="mt-2 text-muted">Cargando pedidos...</p>
                </td>
            </tr>`;

        try {
            const res = await fetch(`${API_BASE}/`, {
                headers: getHeaders() // ✅ token incluido
            });

            if (!res.ok) throw new Error(`HTTP ${res.status}`);

            const pedidos = await res.json();
            todosPedidos = pedidos;
            ocultarError();
            renderTabla(pedidos);

            // Notificaciones: pedidos EN ESPERA
            const enEspera = pedidos.filter(p => p.estado === 'EN ESPERA');
            actualizarNotificaciones(enEspera);

        } catch (err) {
            console.error(err);
            mostrarError(`No se pudo conectar con <code>${API_BASE}</code>. ¿Está corriendo FastAPI?`);
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-danger py-5">
                        <i class="fa-solid fa-plug-circle-xmark fa-2x mb-2 d-block"></i>
                        Sin conexión con el servidor
                    </td>
                </tr>`;
            contador.innerText = 'Error al cargar envíos';
        }
    }

    function renderTabla(pedidos) {
        const tbody    = document.getElementById('tabla-pedidos-body');
        const contador = document.getElementById('contador-pedidos');
        contador.innerText = `Lista de envíos (${pedidos.length})`;

        if (pedidos.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-5">No hay pedidos disponibles</td></tr>`;
            return;
        }

        tbody.innerHTML = pedidos.map(p => `
            <tr data-id="${p.id}" data-estado="${p.estado}" data-origen="${p.origen}" data-destino="${p.destino}">
                <td><strong>${p.id}</strong></td>
                <td>
                    <div class="small"><b>De:</b> ${p.origen}</div>
                    <div class="small"><b>A:</b> ${p.destino}</div>
                </td>
                <td>
                    <div class="small">${p.tipo ?? '—'} · ${p.peso} kg</div>
                    <div class="text-muted" style="font-size:0.72rem;">${p.descripcion || 'Sin descripción'}</div>
                </td>
                <td>${p.operador_nombre ?? '<span class="text-muted">Sin asignar</span>'}</td>
                <td class="text-center">${p.dias_estimados ?? '—'}</td>
                <td>${p.fecha}</td>
                <td>${estadoBadge(p.estado)}</td>
                <td><div class="action-icons">${botonesAccion(p)}</div></td>
            </tr>
        `).join('');
    }

    // =====================================================
    // FILTROS
    // =====================================================
    function filtrarTabla() {
        const texto  = document.getElementById('filtroTabla').value.toLowerCase();
        const estado = document.getElementById('filtroEstado').value;

        const filtrados = todosPedidos.filter(p => {
            const matchTexto = !texto ||
                p.id.toLowerCase().includes(texto) ||
                p.origen.toLowerCase().includes(texto) ||
                p.destino.toLowerCase().includes(texto);
            const matchEstado = !estado || p.estado === estado;
            return matchTexto && matchEstado;
        });

        renderTabla(filtrados);
    }

    // =====================================================
    // CARGAR RUTAS para el select del modal  ✅ con token
    // =====================================================
    async function cargarRutas() {
        try {
            const res = await fetch(`${RUTAS_API}/`, {
                headers: getHeaders() // ✅ token incluido
            });
            if (!res.ok) return;
            const rutas = await res.json();
            const select = document.getElementById('conf-ruta-id');
            select.innerHTML = '<option value="">Seleccione una ruta...</option>';
            rutas.forEach(r => {
                select.innerHTML += `<option value="${r.id}">${r.codigo ?? r.id} — ${r.nombre}</option>`;
            });
        } catch {
            document.getElementById('conf-ruta-id').innerHTML =
                '<option value="">No se pudieron cargar las rutas</option>';
        }
    }

    // =====================================================
    // MODAL — CONFIRMAR
    // =====================================================
    function abrirModalConfirmar(id) {
        document.getElementById('conf-pedido-id').value = id;
        document.getElementById('conf-id-text').innerText = id;
        document.getElementById('conf-dias').value = '';
        new bootstrap.Modal(document.getElementById('modalConfirmar')).show();
    }

    async function procesarConfirmacion() {
        const id     = document.getElementById('conf-pedido-id').value;
        const rutaId = document.getElementById('conf-ruta-id').value;
        const dias   = document.getElementById('conf-dias').value;

        if (!rutaId || !dias) {
            alert('Por favor completa la ruta y los días estimados.');
            return;
        }

        try {
            const res = await fetch(`${API_BASE}/${id}/confirmar`, {
                method: 'PATCH',
                headers: getHeaders(), // ✅ token incluido
                body: JSON.stringify({ ruta_id: parseInt(rutaId), dias_estimados: parseInt(dias) })
            });

            if (!res.ok) {
                const err = await res.json();
                throw new Error(err.detail ?? 'Error al confirmar');
            }

            bootstrap.Modal.getInstance(document.getElementById('modalConfirmar')).hide();
            await cargarPedidos();

        } catch (err) {
            alert(`Error: ${err.message}`);
        }
    }

    // =====================================================
    // MODAL — RECHAZAR
    // =====================================================
    function abrirModalRechazar(id) {
        document.getElementById('rech-pedido-id').value = id;
        document.getElementById('rech-id-text').innerText = id;
        document.getElementById('rech-motivo').value = '';
        new bootstrap.Modal(document.getElementById('modalRechazar')).show();
    }

    async function procesarRechazo() {
        const id     = document.getElementById('rech-pedido-id').value;
        const motivo = document.getElementById('rech-motivo').value.trim();

        if (!motivo) {
            alert('Escribe un motivo de rechazo.');
            return;
        }

        try {
            const res = await fetch(`${API_BASE}/${id}/rechazar`, {
                method: 'PATCH',
                headers: getHeaders(), // ✅ token incluido
                body: JSON.stringify({ motivo_rechazo: motivo })
            });

            if (!res.ok) {
                const err = await res.json();
                throw new Error(err.detail ?? 'Error al rechazar');
            }

            bootstrap.Modal.getInstance(document.getElementById('modalRechazar')).hide();
            await cargarPedidos();

        } catch (err) {
            alert(`Error: ${err.message}`);
        }
    }

    // =====================================================
    // MARCAR ENTREGADO  ✅ con token
    // =====================================================
    async function marcarEntregado(id) {
        if (!confirm(`¿Marcar el pedido ${id} como entregado? El cliente recibirá una notificación.`)) return;

        try {
            const res = await fetch(`${API_BASE}/${id}/marcar-entregado`, {
                method: 'PATCH',
                headers: getHeaders() // ✅ token incluido
            });
            if (!res.ok) {
                const err = await res.json();
                throw new Error(err.detail ?? 'Error');
            }
            await cargarPedidos();
        } catch (err) {
            alert(`Error: ${err.message}`);
        }
    }

    // =====================================================
    // VER DETALLES
    // =====================================================
    function abrirDetalle(id) {
        const p = todosPedidos.find(x => x.id == id);
        if (!p) return;

        document.getElementById('det-id').innerText = p.id;

        document.getElementById('detalle-body').innerHTML = `
            <div class="row g-3">
                <div class="col-md-6">
                    <div style="background:#f9fafb;border-radius:10px;padding:15px;">
                        <p class="text-muted mb-1" style="font-size:0.78rem;font-weight:700;">ORIGEN</p>
                        <p style="font-weight:600;margin:0;">${p.origen}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="background:#f9fafb;border-radius:10px;padding:15px;">
                        <p class="text-muted mb-1" style="font-size:0.78rem;font-weight:700;">DESTINO</p>
                        <p style="font-weight:600;margin:0;">${p.destino}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="background:#f9fafb;border-radius:10px;padding:15px;">
                        <p class="text-muted mb-1" style="font-size:0.78rem;font-weight:700;">PESO</p>
                        <p style="font-weight:600;margin:0;">${p.peso} kg</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="background:#f9fafb;border-radius:10px;padding:15px;">
                        <p class="text-muted mb-1" style="font-size:0.78rem;font-weight:700;">TIPO</p>
                        <p style="font-weight:600;margin:0;">${p.tipo ?? '—'}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="background:#f9fafb;border-radius:10px;padding:15px;">
                        <p class="text-muted mb-1" style="font-size:0.78rem;font-weight:700;">ESTADO</p>
                        <p style="margin:0;">${estadoBadge(p.estado)}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="background:#f9fafb;border-radius:10px;padding:15px;">
                        <p class="text-muted mb-1" style="font-size:0.78rem;font-weight:700;">OPERADOR</p>
                        <p style="font-weight:600;margin:0;">${p.operador_nombre ?? 'Sin asignar'}</p>
                        <p style="font-size:0.8rem;color:#888;margin:0;">${p.operador_telefono ?? ''}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="background:#f9fafb;border-radius:10px;padding:15px;">
                        <p class="text-muted mb-1" style="font-size:0.78rem;font-weight:700;">RUTA</p>
                        <p style="font-weight:600;margin:0;">${p.ruta_nombre ?? '—'}</p>
                        <p style="font-size:0.8rem;color:#888;margin:0;">${p.ruta_codigo ?? ''}</p>
                    </div>
                </div>
                ${p.motivo_rechazo ? `
                <div class="col-12">
                    <div style="background:#fee2e2;border-radius:10px;padding:15px;">
                        <p class="mb-1" style="font-size:0.78rem;font-weight:700;color:#dc2626;">MOTIVO DE RECHAZO</p>
                        <p style="font-weight:600;margin:0;color:#dc2626;">${p.motivo_rechazo}</p>
                    </div>
                </div>` : ''}
                <div class="col-12">
                    <div style="background:#f9fafb;border-radius:10px;padding:15px;">
                        <p class="text-muted mb-1" style="font-size:0.78rem;font-weight:700;">DESCRIPCIÓN</p>
                        <p style="margin:0;">${p.descripcion || 'Sin descripción'}</p>
                    </div>
                </div>
            </div>`;

        new bootstrap.Modal(document.getElementById('modalDetalle')).show();
    }

    // =====================================================
    // NOTIFICACIONES dinámicas
    // =====================================================
    function actualizarNotificaciones(enEspera) {
        const badge = document.getElementById('notif-badge');
        const body  = document.getElementById('notif-body');

        badge.innerText = enEspera.length;
        badge.style.display = enEspera.length > 0 ? 'block' : 'none';

        if (enEspera.length === 0) {
            body.innerHTML = `<div class="notif-item"><div class="notif-content"><span class="notif-text text-muted">Sin pedidos en espera</span></div></div>`;
            return;
        }

        body.innerHTML = enEspera.slice(0, 5).map(p => `
            <a href="#" class="notif-item" onclick="abrirDetalle('${p.id}'); return false;">
                <div class="notif-icon warning"><i class="fa-solid fa-box-open"></i></div>
                <div class="notif-content">
                    <span class="notif-text">Pedido <strong>${p.id}</strong> esperando asignación.<br>
                        <small>${p.origen} → ${p.destino}</small></span>
                    <span class="notif-time">${p.fecha}</span>
                </div>
            </a>`).join('');
    }

    function marcarLeidas() {
        document.getElementById('notif-badge').innerText = '0';
        document.getElementById('notif-badge').style.display = 'none';
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