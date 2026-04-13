@extends('layouts.app')

@section('content')
<style>
    /* ===== BASE ===== */
    body { background-color: #f4f6f9; color: #333; overflow-x: hidden; margin: 0; }
    .navbar { display: none !important; }
    .container.mt-4 { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }

    /* ===== HEADER ===== */
    .main-header {
        position: sticky; top: 0; z-index: 1000;
        background: linear-gradient(90deg, #ff5722 0%, #e64a19 100%);
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .header-top { display: flex; justify-content: space-between; align-items: center; padding: 12px 24px; border-bottom: 1px solid rgba(255,255,255,0.2); }
    .header-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
    .brand-text { display: flex; flex-direction: column; }
    .brand-name { font-weight: 900; font-size: 1.2rem; color: #ffffff; line-height: 1.1; letter-spacing: 1px; }
    .brand-slogan { font-size: 0.75rem; color: rgba(255,255,255,0.85); }
    .header-search { flex: 1; max-width: 600px; margin: 0 2rem; position: relative; }
    .header-search i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #ff5722; z-index: 2; }
    .header-search input { width: 100%; padding: 10px 15px 10px 45px; border: none; border-radius: 25px; background-color: #ffffff; font-size: 0.95rem; color: #333; outline: none; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .header-search input::placeholder { color: #aaa; }
    .header-actions { display: flex; align-items: center; gap: 20px; }
    .user-profile { display: flex; align-items: center; gap: 12px; text-decoration: none; }
    .user-info { text-align: right; }
    .user-name { font-weight: 600; font-size: 0.9rem; color: #ffffff; line-height: 1.2; }
    .user-role { font-size: 0.75rem; color: rgba(255,255,255,0.85); }
    .user-avatar { width: 38px; height: 38px; background-color: #ffffff; color: #ff5722; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 1.1rem; font-weight: bold; }
    .header-nav { display: flex; padding: 0 24px; gap: 8px; }
    .nav-item { padding: 12px 16px; font-size: 0.95rem; color: #ffffff; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px; border-radius: 12px 12px 0 0; margin-top: 6px; cursor: pointer; transition: all 0.3s; }
    .nav-item:hover { background-color: rgba(255,255,255,0.2); color: #ffffff; }
    .nav-item.active { background-color: #f4f6f9; color: #ff5722; }
    .nav-item i.chevron { font-size: 0.75rem; margin-left: 4px; }
    .dropdown-menu { border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 0 8px 8px 8px; padding: 8px 0; margin-top: 0 !important; }
    .dropdown-item { padding: 10px 20px; font-size: 0.9rem; color: #444; font-weight: 500; }
    .dropdown-item:hover { background-color: #fffaf5; color: #ff5722; }
    .notification-wrapper { position: relative; display: flex; align-items: center; margin-right: 10px; }
    .notification-bell { color: #ffffff; font-size: 1.3rem; cursor: pointer; position: relative; transition: transform 0.2s; }
    .notification-bell:hover { transform: scale(1.1); }
    .notification-badge { position: absolute; top: -6px; right: -8px; background-color: #ffffff; color: #ff5722; font-size: 0.65rem; font-weight: 800; padding: 2px 5px; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.2); display: none; }
    .notif-dropdown { width: 320px; padding: 0; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: none; margin-top: 15px !important; right: 0 !important; left: auto !important; overflow: hidden; }
    .notif-header { padding: 15px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; background-color: #fafafa; }
    .notif-header span { font-weight: 800; color: #333; font-size: 0.95rem; }
    .notif-header a { font-size: 0.75rem; color: #ff5722; text-decoration: none; font-weight: 600; }
    .notif-item-dd { padding: 12px 15px; border-bottom: 1px solid #f9f9f9; display: flex; gap: 12px; text-decoration: none; transition: background 0.2s; }
    .notif-item-dd:hover { background-color: #fffaf5; }
    .notif-icon-sm { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; flex-shrink: 0; }
    .notif-icon-sm.warning { background-color: #fef08a; color: #ca8a04; }
    .notif-content-sm { display: flex; flex-direction: column; }
    .notif-text-sm { font-size: 0.85rem; color: #444; line-height: 1.3; margin-bottom: 4px; }
    .notif-time-sm { font-size: 0.7rem; color: #888; }
    .notif-footer-dd { text-align: center; padding: 12px; background: #fafafa; }
    .notif-footer-dd a { color: #ff5722; text-decoration: none; font-size: 0.85rem; font-weight: 700; }

    /* ===== LAYOUT ===== */
    .main-wrapper { padding: 30px; max-width: 1200px; margin: 0 auto; }
    .page-title h2 { font-weight: 900; margin: 0; color: #222; }
    .page-title p { color: #666; font-size: 0.95rem; margin-bottom: 0; }
    .content-box { background: #fff; border-radius: 16px; padding: 22px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    .btn-outline-gray { background-color: #fff; color: #444; border-radius: 8px; font-weight: 600; padding: 10px 24px; transition: 0.3s; border: 1px solid #ddd; text-decoration: none; display: flex; align-items: center; gap: 8px; cursor: pointer; }
    .btn-outline-gray:hover { background-color: #f8f9fa; color: #222; }

    /* ===== FILTER BAR ===== */
    .filter-bar { display: flex; justify-content: space-between; align-items: center; padding: 15px 22px; margin-bottom: 25px; }
    .filter-tabs { display: flex; gap: 10px; flex-wrap: wrap; }
    .filter-tab { padding: 8px 16px; border-radius: 20px; font-size: 0.9rem; font-weight: 600; cursor: pointer; transition: 0.3s; border: 1px solid transparent; background: transparent; color: #666; }
    .filter-tab.active { background: #fffaf5; color: #ff5722; border-color: #ffd0b8; }
    .filter-tab:hover:not(.active) { background: #f8f9fa; }

    /* ===== NOTIFICATION CARDS ===== */
    .notif-list { display: flex; flex-direction: column; }
    .notif-card { display: flex; align-items: flex-start; padding: 24px; border-bottom: 1px solid #f0f0f0; gap: 20px; transition: 0.2s; position: relative; }
    .notif-card:last-child { border-bottom: none; }
    .notif-card:hover { background-color: #fcfcfc; }
    .notif-card.unread { background-color: #fffaf5; }
    .notif-card.unread::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background-color: #ff5722; border-radius: 4px 0 0 4px; }
    .notif-card.hidden { display: none; }

    .notif-icon-large { width: 54px; height: 54px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 1.4rem; flex-shrink: 0; }
    .bg-light-warning { background-color: #fef08a; color: #ca8a04; }
    .bg-light-success { background-color: #dcfce7; color: #16a34a; }
    .bg-light-danger  { background-color: #fee2e2; color: #dc2626; }
    .bg-light-info    { background-color: #e0f2fe; color: #0284c7; }

    .notif-content-wrap { flex: 1; }
    .notif-card-title { font-size: 1.05rem; font-weight: 800; color: #222; margin-bottom: 6px; }
    .notif-card-text  { font-size: 0.93rem; color: #555; margin-bottom: 12px; line-height: 1.5; }
    .notif-card-meta  { display: flex; align-items: center; gap: 15px; font-size: 0.8rem; color: #888; font-weight: 500; flex-wrap: wrap; }

    .notif-actions { display: flex; flex-direction: column; gap: 8px; min-width: 130px; }
    .btn-action-primary { background-color: #ff5722; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: 0.2s; text-align: center; cursor: pointer; }
    .btn-action-primary:hover { background-color: #e64a19; color: #fff; }
    .btn-action-secondary { background-color: #f1f5f9; color: #475569; border: none; padding: 8px 16px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: 0.2s; text-align: center; cursor: pointer; }
    .btn-action-secondary:hover { background-color: #e2e8f0; color: #334155; }
    .btn-action-danger { background-color: #fee2e2; color: #dc2626; border: none; padding: 8px 16px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: 0.2s; text-align: center; cursor: pointer; }
    .btn-action-danger:hover { background-color: #dc2626; color: #fff; }

    /* ===== EMPTY / LOADING ===== */
    .empty-state { text-align: center; padding: 60px 20px; color: #aaa; }
    .empty-state i { font-size: 3rem; margin-bottom: 15px; display: block; }
    .spinner-border.text-orange { border-color: #ff5722; border-right-color: transparent; }

    /* ===== ALERT API ===== */
    .alert-api { background: #fff3cd; border: 1px solid #ffc107; color: #856404; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.88rem; display: none; }
    .alert-api.show { display: block; }
</style>

{{-- ===== HEADER ===== --}}
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

        <div class="header-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Buscar número de guía, cliente o ciudad...">
        </div>

        <div class="header-actions">
            {{-- Campana con dropdown --}}
            <div class="dropdown notification-wrapper">
                <div class="notification-bell" data-bs-toggle="dropdown" aria-expanded="false" title="Notificaciones">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notification-badge" id="notif-badge">0</span>
                </div>
                <div class="dropdown-menu notif-dropdown dropdown-menu-end">
                    <div class="notif-header">
                        <span>Notificaciones</span>
                        <a href="#" onclick="marcarTodasLeidas(); return false;">Marcar leídas</a>
                    </div>
                    <div id="notif-body-dd">
                        <div class="notif-item-dd">
                            <div class="notif-content-sm"><span class="notif-text-sm text-muted">Cargando...</span></div>
                        </div>
                    </div>
                    <div class="notif-footer-dd">
                        <a href="{{ route('notificaciones') }}">Ver todas las notificaciones</a>
                    </div>
                </div>
            </div>

            <a href="{{ route('perfil') }}" class="user-profile">
                <div class="user-info d-none d-md-block">
                    <div class="user-name">Admin AYFEX</div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar">A</div>
            </a>
            <a href="{{ route('login') }}" class="user-profile" style="margin-left:10px;" title="Cerrar Sesión">
                <i class="fa-solid fa-right-from-bracket" style="color:white;font-size:1.2rem;"></i>
            </a>
        </div>
    </div>

    <div class="header-nav">
        <a href="{{ route('dashboard') }}" class="nav-item">
            <i class="fa-solid fa-border-all"></i> Dashboard
        </a>
        <div class="dropdown">
            <div class="nav-item" data-bs-toggle="dropdown"><i class="fa-solid fa-box"></i> Operaciones <i class="fa-solid fa-chevron-down chevron"></i></div>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('envios') }}"><i class="fa-solid fa-box me-2"></i> Envíos</a></li>
                <li><a class="dropdown-item" href="{{ route('rutas') }}"><i class="fa-solid fa-route me-2"></i> Rutas</a></li>
            </ul>
        </div>
        <div class="dropdown">
            <div class="nav-item" data-bs-toggle="dropdown">Gestión <i class="fa-solid fa-chevron-down chevron"></i></div>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('clientes') }}"><i class="fa-solid fa-users me-2"></i> Clientes</a></li>
                <li><a class="dropdown-item" href="{{ route('operadores') }}"><i class="fa-solid fa-truck me-2"></i> Operadores</a></li>
            </ul>
        </div>
        <div class="dropdown">
            <div class="nav-item" data-bs-toggle="dropdown">Administración <i class="fa-solid fa-chevron-down chevron"></i></div>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('reportes') }}"><i class="fa-solid fa-file-lines me-2"></i> Reportes</a></li>
                <li><a class="dropdown-item" href="{{ route('incidencias') }}"><i class="fa-solid fa-circle-exclamation me-2"></i> Incidencias</a></li>
            </ul>
        </div>
        {{-- Nav item activo para Notificaciones --}}
        <a href="{{ route('notificaciones') }}" class="nav-item active">
            <i class="fa-solid fa-bell"></i> Notificaciones
        </a>
    </div>
</header>

{{-- ===== CONTENIDO ===== --}}
<div class="main-wrapper">

    <div class="alert-api" id="alert-api">
        <i class="fa-solid fa-triangle-exclamation me-2"></i>
        <strong>Sin conexión con FastAPI.</strong> Verifica que esté corriendo en <code>http://127.0.0.1:8001</code>.
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-title">
            <h2>Centro de Notificaciones</h2>
            <p>Historial y gestión de alertas del sistema</p>
        </div>
        <a href="{{ route('envios') }}" class="btn-outline-gray">
            <i class="fa-solid fa-arrow-left"></i> Volver a Envíos
        </a>
    </div>

    {{-- Barra de filtros --}}
    <div class="content-box filter-bar">
        <div class="filter-tabs">
            <button class="filter-tab active" onclick="filtrar('todas', this)">Todas</button>
            <button class="filter-tab" id="tab-no-leidas" onclick="filtrar('no-leidas', this)">No leídas (0)</button>
            <button class="filter-tab" onclick="filtrar('entregas', this)">Entregas</button>
            <button class="filter-tab" onclick="filtrar('incidencias', this)">Incidencias</button>
            <button class="filter-tab" onclick="filtrar('espera', this)">En Espera</button>
        </div>
        <button class="btn-outline-gray" style="padding:8px 16px;font-size:0.85rem;border-color:transparent;" onclick="marcarTodasLeidas()">
            <i class="fa-solid fa-check-double text-muted"></i> Marcar todas como leídas
        </button>
    </div>

    {{-- Lista de notificaciones --}}
    <div class="content-box" style="padding:0;overflow:hidden;">
        <div class="notif-list" id="notif-list">
            <div class="empty-state">
                <div class="spinner-border text-orange" role="status"></div>
                <p class="mt-3">Conectando con FastAPI...</p>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL: CONFIRMAR PEDIDO ===== --}}
<div class="modal fade" id="modalConfirmar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 10px 30px rgba(0,0,0,.1);">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:20px;">
                <h5 class="modal-title" style="font-weight:800;color:#333;">
                    <i class="fa-solid fa-check-circle text-success me-2"></i>
                    Confirmar Pedido <span id="conf-id-text" style="color:#ff5722;"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:25px;">
                <input type="hidden" id="conf-pedido-id">
                <div class="mb-3">
                    <label class="form-label" style="font-weight:600;font-size:.9rem;color:#555;">Ruta de Entrega</label>
                    <select class="form-select" id="conf-ruta-id" style="border-radius:8px;border:1px solid #ddd;padding:10px;">
                        <option value="">Cargando rutas...</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" style="font-weight:600;font-size:.9rem;color:#555;">Días Estimados de Entrega</label>
                    <input type="number" class="form-control" id="conf-dias" placeholder="Ej: 3" min="1"
                           style="border-radius:8px;border:1px solid #ddd;padding:10px;">
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:15px 25px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;font-weight:600;">Cancelar</button>
                <button type="button" class="btn-action-primary" onclick="procesarConfirmacion()" style="border-radius:8px;">
                    <i class="fa-solid fa-check me-1"></i> Confirmar y Notificar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL: RECHAZAR PEDIDO ===== --}}
<div class="modal fade" id="modalRechazar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:12px;border:none;box-shadow:0 10px 30px rgba(0,0,0,.1);">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:20px;">
                <h5 class="modal-title" style="font-weight:800;color:#333;">
                    <i class="fa-solid fa-xmark-circle text-danger me-2"></i>
                    Rechazar Pedido <span id="rech-id-text" style="color:#ff5722;"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:25px;">
                <input type="hidden" id="rech-pedido-id">
                <p style="color:#555;font-size:.9rem;">El cliente recibirá una notificación con el motivo.</p>
                <div class="mb-3">
                    <label class="form-label" style="font-weight:600;font-size:.9rem;color:#555;">Motivo del Rechazo</label>
                    <textarea class="form-control" id="rech-motivo" rows="3"
                              placeholder="Ej: El peso excede el límite permitido..."
                              style="border-radius:8px;border:1px solid #ddd;padding:10px;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:15px 25px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;font-weight:600;">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="procesarRechazo()" style="border-radius:8px;font-weight:600;">
                    <i class="fa-solid fa-xmark me-1"></i> Confirmar Rechazo
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // =====================================================
    // CONFIG — misma API que envios.blade.php
    // =====================================================
    const API_BASE  = 'http://127.0.0.1:5000/v1/pedidos-web';
    const RUTAS_API = 'http://127.0.0.1:5000/v1/rutas';

    // Pedidos en memoria + set de IDs ya "leídos" (se guarda en sessionStorage)
    let todosPedidos = [];
    let leidos = new Set(JSON.parse(sessionStorage.getItem('notif_leidas') || '[]'));

    // =====================================================
    // INIT
    // =====================================================
    document.addEventListener('DOMContentLoaded', () => {
        cargarTodo();
        cargarRutas();
    });

    async function cargarTodo() {
        try {
            const res = await fetch(`${API_BASE}/`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            todosPedidos = await res.json();
            document.getElementById('alert-api').classList.remove('show');
            renderNotificaciones(todosPedidos);
            actualizarBadge();
            actualizarDropdown();
        } catch (err) {
            document.getElementById('alert-api').classList.add('show');
            document.getElementById('notif-list').innerHTML = `
                <div class="empty-state">
                    <i class="fa-solid fa-plug-circle-xmark" style="color:#dc2626;"></i>
                    <p>Sin conexión con el servidor FastAPI</p>
                </div>`;
        }
    }

    // =====================================================
    // CONSTRUIR NOTIFICACIONES desde pedidos reales
    // =====================================================
    function pedidosANotificaciones(pedidos) {
        const notifs = [];

        pedidos.forEach(p => {
            // EN ESPERA → acción requerida (pedido listo para revisión)
            if (p.estado === 'EN ESPERA') {
                notifs.push({
                    id:       `espera-${p.id}`,
                    pedidoId: p.id,
                    tipo:     'espera',
                    icono:    'fa-box-open',
                    colorCls: 'bg-light-warning',
                    titulo:   'Pedido Listo para Revisión',
                    texto:    `El pedido <strong>${p.id}</strong> de <strong>${p.origen}</strong> está en espera de asignación de ruta y confirmación.`,
                    meta:     `<span><i class="fa-solid fa-location-dot"></i> ${p.origen} → ${p.destino}</span><span><i class="fa-regular fa-calendar"></i> ${p.fecha}</span>`,
                    acciones: `
                        <button class="btn-action-primary" onclick="abrirModalConfirmar('${p.id}')">Confirmar Envío</button>
                        <button class="btn-action-secondary" onclick="abrirModalRechazar('${p.id}')">Rechazar</button>`,
                    unread: !leidos.has(`espera-${p.id}`)
                });
            }

            // RECHAZADO → informativo
            if (p.estado === 'RECHAZADO') {
                notifs.push({
                    id:       `rechazado-${p.id}`,
                    pedidoId: p.id,
                    tipo:     'incidencias',
                    icono:    'fa-circle-exclamation',
                    colorCls: 'bg-light-danger',
                    titulo:   'Pedido Rechazado',
                    texto:    `El pedido <strong>${p.id}</strong> fue rechazado. Motivo: <em>${p.motivo_rechazo || 'No especificado'}</em>`,
                    meta:     `<span><i class="fa-solid fa-location-dot"></i> ${p.origen} → ${p.destino}</span>`,
                    acciones: '',
                    unread: !leidos.has(`rechazado-${p.id}`)
                });
            }

            // EN CAMINO → informativo
            if (p.estado === 'EN CAMINO') {
                notifs.push({
                    id:       `camino-${p.id}`,
                    pedidoId: p.id,
                    tipo:     'entregas',
                    icono:    'fa-truck',
                    colorCls: 'bg-light-info',
                    titulo:   'Pedido En Camino',
                    texto:    `El pedido <strong>${p.id}</strong> fue confirmado y está en ruta hacia <strong>${p.destino}</strong>. Operador: ${p.operador_nombre ?? 'Asignado'}.`,
                    meta:     `<span><i class="fa-solid fa-route"></i> ${p.ruta_nombre ?? p.ruta_codigo ?? 'Ruta asignada'}</span><span><i class="fa-regular fa-clock"></i> ${p.dias_estimados ?? '?'} día(s) est.</span>`,
                    acciones: `<button class="btn-action-secondary" onclick="marcarEntregado('${p.id}')"><i class="fa-solid fa-truck-fast me-1"></i> Marcar Entregado</button>`,
                    unread: !leidos.has(`camino-${p.id}`)
                });
            }

            // POR_CONFIRMAR_ENTREGA
            if (p.estado === 'POR_CONFIRMAR_ENTREGA') {
                notifs.push({
                    id:       `confirmar-${p.id}`,
                    pedidoId: p.id,
                    tipo:     'entregas',
                    icono:    'fa-box-open',
                    colorCls: 'bg-light-warning',
                    titulo:   'Pendiente de Confirmar Entrega',
                    texto:    `El pedido <strong>${p.id}</strong> está esperando confirmación de entrega final al cliente en <strong>${p.destino}</strong>.`,
                    meta:     `<span><i class="fa-solid fa-location-dot"></i> ${p.destino}</span>`,
                    acciones: '',
                    unread: !leidos.has(`confirmar-${p.id}`)
                });
            }

            // ENTREGADO
            if (p.estado === 'ENTREGADO') {
                notifs.push({
                    id:       `entregado-${p.id}`,
                    pedidoId: p.id,
                    tipo:     'entregas',
                    icono:    'fa-check-double',
                    colorCls: 'bg-light-success',
                    titulo:   'Entrega Completada',
                    texto:    `El pedido <strong>${p.id}</strong> fue entregado exitosamente en <strong>${p.destino}</strong>.`,
                    meta:     `<span><i class="fa-regular fa-calendar"></i> ${p.fecha}</span><span><i class="fa-regular fa-user"></i> Op: ${p.operador_nombre ?? '—'}</span>`,
                    acciones: '',
                    unread: false // entregados siempre leídos
                });
            }
        });

        return notifs;
    }

    // =====================================================
    // RENDER LISTA
    // =====================================================
    function renderNotificaciones(pedidos) {
        const notifs = pedidosANotificaciones(pedidos);
        const lista  = document.getElementById('notif-list');

        // Actualizar texto del tab "No leídas"
        const noLeidas = notifs.filter(n => n.unread).length;
        document.getElementById('tab-no-leidas').textContent = `No leídas (${noLeidas})`;

        if (notifs.length === 0) {
            lista.innerHTML = `
                <div class="empty-state">
                    <i class="fa-regular fa-bell-slash"></i>
                    <p>No hay notificaciones disponibles</p>
                </div>`;
            return;
        }

        lista.innerHTML = notifs.map(n => `
            <div class="notif-card ${n.unread ? 'unread' : ''}" 
                 data-tipo="${n.tipo}" 
                 data-unread="${n.unread}"
                 data-id="${n.id}"
                 onclick="marcarLeida('${n.id}', this)">
                <div class="notif-icon-large ${n.colorCls}">
                    <i class="fa-solid ${n.icono}"></i>
                </div>
                <div class="notif-content-wrap">
                    <div class="notif-card-title">${n.titulo}</div>
                    <div class="notif-card-text">${n.texto}</div>
                    <div class="notif-card-meta">${n.meta}</div>
                </div>
                <div class="notif-actions">${n.acciones}</div>
            </div>
        `).join('');
    }

    // =====================================================
    // FILTROS
    // =====================================================
    function filtrar(tipo, btn) {
        // Actualizar tab activo
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');

        const notifs   = pedidosANotificaciones(todosPedidos);
        const lista    = document.getElementById('notif-list');

        let filtradas;
        if (tipo === 'todas')       filtradas = notifs;
        else if (tipo === 'no-leidas') filtradas = notifs.filter(n => n.unread);
        else                           filtradas = notifs.filter(n => n.tipo === tipo);

        if (filtradas.length === 0) {
            lista.innerHTML = `<div class="empty-state"><i class="fa-regular fa-bell-slash"></i><p>Sin notificaciones en esta categoría</p></div>`;
            return;
        }

        lista.innerHTML = filtradas.map(n => `
            <div class="notif-card ${n.unread ? 'unread' : ''}" 
                 data-tipo="${n.tipo}" data-unread="${n.unread}" data-id="${n.id}"
                 onclick="marcarLeida('${n.id}', this)">
                <div class="notif-icon-large ${n.colorCls}">
                    <i class="fa-solid ${n.icono}"></i>
                </div>
                <div class="notif-content-wrap">
                    <div class="notif-card-title">${n.titulo}</div>
                    <div class="notif-card-text">${n.texto}</div>
                    <div class="notif-card-meta">${n.meta}</div>
                </div>
                <div class="notif-actions">${n.acciones}</div>
            </div>
        `).join('');
    }

    // =====================================================
    // MARCAR LEÍDA / TODAS LEÍDAS
    // =====================================================
    function marcarLeida(id, card) {
        leidos.add(id);
        sessionStorage.setItem('notif_leidas', JSON.stringify([...leidos]));
        card.classList.remove('unread');
        card.dataset.unread = 'false';
        actualizarBadge();
        // Actualizar tab contador
        const notifs   = pedidosANotificaciones(todosPedidos);
        const noLeidas = notifs.filter(n => !leidos.has(n.id)).length;
        document.getElementById('tab-no-leidas').textContent = `No leídas (${noLeidas})`;
    }

    function marcarTodasLeidas() {
        const notifs = pedidosANotificaciones(todosPedidos);
        notifs.forEach(n => leidos.add(n.id));
        sessionStorage.setItem('notif_leidas', JSON.stringify([...leidos]));
        document.querySelectorAll('.notif-card.unread').forEach(c => {
            c.classList.remove('unread');
            c.dataset.unread = 'false';
        });
        document.getElementById('tab-no-leidas').textContent = 'No leídas (0)';
        actualizarBadge();
        actualizarDropdown();
    }

    // =====================================================
    // BADGE CAMPANA
    // =====================================================
    function actualizarBadge() {
        const notifs   = pedidosANotificaciones(todosPedidos);
        const noLeidas = notifs.filter(n => n.unread).length;
        const badge    = document.getElementById('notif-badge');
        badge.textContent = noLeidas;
        badge.style.display = noLeidas > 0 ? 'block' : 'none';
    }

    // =====================================================
    // DROPDOWN MINI (campana del header)
    // =====================================================
    function actualizarDropdown() {
        const enEspera = todosPedidos.filter(p => p.estado === 'EN ESPERA');
        const body     = document.getElementById('notif-body-dd');

        if (enEspera.length === 0) {
            body.innerHTML = `<div class="notif-item-dd"><div class="notif-content-sm"><span class="notif-text-sm text-muted">Sin pedidos en espera</span></div></div>`;
            return;
        }

        body.innerHTML = enEspera.slice(0, 5).map(p => `
            <a href="#" class="notif-item-dd" onclick="abrirModalConfirmar('${p.id}'); return false;">
                <div class="notif-icon-sm warning"><i class="fa-solid fa-box-open"></i></div>
                <div class="notif-content-sm">
                    <span class="notif-text-sm">Pedido <strong>${p.id}</strong> en espera<br>
                        <small>${p.origen} → ${p.destino}</small></span>
                    <span class="notif-time-sm">${p.fecha}</span>
                </div>
            </a>`).join('');
    }

    // =====================================================
    // CARGAR RUTAS (para modal confirmar)
    // =====================================================
    async function cargarRutas() {
        try {
            const res = await fetch(`${RUTAS_API}/`);
            if (!res.ok) return;
            const rutas = await res.json();
            const select = document.getElementById('conf-ruta-id');
            select.innerHTML = '<option value="">Seleccione una ruta...</option>';
            rutas.forEach(r => {
                select.innerHTML += `<option value="${r.id}">${r.codigo ?? r.id} — ${r.nombre}</option>`;
            });
        } catch {
            document.getElementById('conf-ruta-id').innerHTML = '<option value="">No se pudieron cargar las rutas</option>';
        }
    }

    // =====================================================
    // MODAL CONFIRMAR
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

        if (!rutaId || !dias) { alert('Por favor completa la ruta y los días estimados.'); return; }

        try {
            const res = await fetch(`${API_BASE}/${id}/confirmar`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ruta_id: parseInt(rutaId), dias_estimados: parseInt(dias) })
            });
            if (!res.ok) { const e = await res.json(); throw new Error(e.detail ?? 'Error'); }
            bootstrap.Modal.getInstance(document.getElementById('modalConfirmar')).hide();
            await cargarTodo(); // recarga todo y re-renderiza
        } catch (err) { alert(`Error: ${err.message}`); }
    }

    // =====================================================
    // MODAL RECHAZAR
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

        if (!motivo) { alert('Escribe un motivo de rechazo.'); return; }

        try {
            const res = await fetch(`${API_BASE}/${id}/rechazar`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ motivo_rechazo: motivo })
            });
            if (!res.ok) { const e = await res.json(); throw new Error(e.detail ?? 'Error'); }
            bootstrap.Modal.getInstance(document.getElementById('modalRechazar')).hide();
            await cargarTodo();
        } catch (err) { alert(`Error: ${err.message}`); }
    }

    // =====================================================
    // MARCAR ENTREGADO (desde notificación EN CAMINO)
    // =====================================================
    async function marcarEntregado(id) {
        if (!confirm(`¿Marcar el pedido ${id} como entregado? El cliente deberá confirmar en la app.`)) return;
        try {
            const res = await fetch(`${API_BASE}/${id}/marcar-entregado`, { method: 'PATCH' });
            if (!res.ok) { const e = await res.json(); throw new Error(e.detail ?? 'Error'); }
            await cargarTodo();
        } catch (err) { alert(`Error: ${err.message}`); }
    }
</script>
@endsection