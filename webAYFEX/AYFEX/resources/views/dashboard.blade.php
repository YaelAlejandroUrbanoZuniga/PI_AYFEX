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
        padding: 12px 24px; border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    .header-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
    .brand-text { display: flex; flex-direction: column; }
    .brand-name { font-weight: 900; font-size: 1.2rem; color: #ffffff; line-height: 1.1; letter-spacing: 1px; }
    .brand-slogan { font-size: 0.75rem; color: rgba(255, 255, 255, 0.85); }
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
    .user-role { font-size: 0.75rem; color: rgba(255, 255, 255, 0.85); }
    .user-avatar {
        width: 38px; height: 38px; background-color: #ffffff; color: #ff5722; border-radius: 50%;
        display: flex; justify-content: center; align-items: center; font-size: 1.1rem; font-weight: bold;
    }
    .header-nav { display: flex; padding: 0 24px; gap: 8px; }
    .nav-item {
        padding: 12px 16px; font-size: 0.95rem; color: #ffffff; font-weight: 600; text-decoration: none;
        display: flex; align-items: center; gap: 8px; border-radius: 12px 12px 0 0;
        margin-top: 6px; cursor: pointer; transition: all 0.3s;
    }
    .nav-item:hover { background-color: rgba(255, 255, 255, 0.2); color: #ffffff; }
    .nav-item.active { background-color: #f4f6f9; color: #ff5722; }
    .nav-item.active i { color: #ff5722; }
    .nav-item i.chevron { font-size: 0.75rem; margin-left: 4px; transition: color 0.3s; }
    .dropdown-menu { border: none; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); border-radius: 0 8px 8px 8px; padding: 8px 0; margin-top: 0 !important; }
    .dropdown-item { padding: 10px 20px; font-size: 0.9rem; color: #444; font-weight: 500; }
    .dropdown-item:hover { background-color: #fffaf5; color: #ff5722; }

    body { background-color: #f4f6f9; color: #333; overflow-x: hidden; margin: 0; }
    .navbar { display: none !important; }
    .container.mt-4 { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
    .main-wrapper { padding: 30px; max-width: 1400px; margin: 0 auto; }
    .page-title { margin-bottom: 25px; }
    .page-title h2 { font-weight: 900; margin: 0; color: #222; }
    .page-title p { color: #666; font-size: 0.95rem; }

    /* ── Stat Cards ── */
    .stat-card, .content-box, .table-container {
        background: #fff; border-radius: 16px; padding: 22px;
        border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); height: 100%;
    }
    .stat-card { position: relative; }
    .stat-card h6 { color: #777; font-size: 0.85rem; font-weight: 600; margin-bottom: 10px; }
    .stat-card h3 { font-weight: 900; margin: 0; color: #222; font-size: 2rem; transition: all 0.4s; }
    .stat-icon {
        position: absolute; right: 20px; top: 50%; transform: translateY(-50%);
        width: 50px; height: 50px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 1.6rem;
    }
    .icon-blue   { background: #e0f2fe; color: #0284c7; }
    .icon-yellow { background: #fef08a; color: #ca8a04; }
    .icon-orange { background: #ffedd5; color: #ea580c; }
    .icon-green  { background: #dcfce7; color: #16a34a; }

    /* ── Skeleton loader ── */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.4s infinite;
        border-radius: 8px;
        display: inline-block;
    }
    @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
    .skeleton-num { width: 80px; height: 36px; }
    .skeleton-text { width: 60%; height: 14px; margin-top: 8px; }

    /* ── Gráfica ── */
    .box-title { font-weight: 800; font-size: 1.1rem; margin-bottom: 5px; color: #222; }
    .chart-wrap { display: flex; align-items: flex-end; height: 160px; gap: 10px; padding-top: 20px; }
    .bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; }
    .bar {
        width: 100%; background: linear-gradient(to top, #ff5722, #ff9b70);
        border-radius: 6px 6px 0 0; transition: height 0.6s ease;
        position: relative; cursor: pointer;
    }
    .bar:hover::after {
        content: attr(data-count);
        position: absolute; top: -28px; left: 50%; transform: translateX(-50%);
        background: #222; color: #fff; border-radius: 6px;
        padding: 3px 8px; font-size: 0.75rem; font-weight: 700; white-space: nowrap;
    }
    .bar-label { font-size: 0.78rem; color: #888; font-weight: 700; }

    /* ── Timeline ── */
    .timeline { list-style: none; padding: 0; margin: 0; position: relative; }
    .timeline::before { content: ''; position: absolute; left: 7px; top: 5px; bottom: 0; width: 2px; background: #eee; }
    .timeline-item { position: relative; padding-left: 25px; margin-bottom: 15px; }
    .timeline-dot { position: absolute; left: 0; top: 4px; width: 16px; height: 16px; border-radius: 50%; border: 3px solid #fff; }
    .dot-orange { background: #fd5d14; }
    .dot-green  { background: #16a34a; }
    .dot-yellow { background: #ca8a04; }
    .dot-red    { background: #dc3545; }
    .dot-blue   { background: #0284c7; }
    .dot-gray   { background: #94a3b8; }
    .timeline-item h6 { margin: 0; font-size: 0.85rem; font-weight: bold; }
    .timeline-item p  { margin: 0; font-size: 0.75rem; color: #666; }

    /* ── Tabla ── */
    .table-container { margin-top: 20px; }
    .table th { border-top: none; color: #888; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; padding-bottom: 15px; }
    .table td { vertical-align: middle; font-size: 0.95rem; color: #444; border-bottom: 1px solid #f0f0f0; padding: 12px 8px; }
    .badge-status { padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; }
    .status-EN\ ESPERA      { background: #fef9c3; color: #ca8a04; }
    .status-EN\ CAMINO      { background: #ffedd5; color: #ea580c; }
    .status-ENTREGADO       { background: #dcfce7; color: #16a34a; }
    .status-RECHAZADO       { background: #fee2e2; color: #dc2626; }
    .status-POR_CONFIRMAR_ENTREGA { background: #e0f2fe; color: #0369a1; }
    .status-EN\ PREPARACIÓN { background: #fef9c3; color: #ca8a04; }
    .status-default         { background: #f1f5f9; color: #64748b; }

    /* ── Empty state ── */
    .empty-state { text-align: center; padding: 30px 20px; color: #aaa; font-size: 0.9rem; }
    .empty-state i { font-size: 2rem; margin-bottom: 10px; display: block; }

    /* ── Toast ── */
    #toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
    .toast-msg {
        background: #222; color: #fff; padding: 12px 20px; border-radius: 10px;
        font-size: 0.88rem; font-weight: 600; box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        animation: fadeInRight 0.3s ease;
    }
    .toast-msg.error { background: #dc2626; }
    @keyframes fadeInRight { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
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
            <input type="text" id="buscadorGlobal" placeholder="Buscar número de guía, cliente o ciudad...">
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
    <div class="page-title">
        <h2>Panel Principal</h2>
        <p>Resumen operativo de hoy</p>
    </div>

    <!-- ── KPI Cards ── -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <h6>Total de Envíos</h6>
                <h3 id="kpi-total"><span class="skeleton skeleton-num"></span></h3>
                <div class="stat-icon icon-blue"><i class="fa-solid fa-box-open"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6>En Espera / Preparación</h6>
                <h3 id="kpi-espera"><span class="skeleton skeleton-num"></span></h3>
                <div class="stat-icon icon-yellow"><i class="fa-regular fa-clock"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6>En Tránsito</h6>
                <h3 id="kpi-transito"><span class="skeleton skeleton-num"></span></h3>
                <div class="stat-icon icon-orange"><i class="fa-solid fa-truck-fast"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6>Entregados</h6>
                <h3 id="kpi-entregados"><span class="skeleton skeleton-num"></span></h3>
                <div class="stat-icon icon-green"><i class="fa-solid fa-circle-check"></i></div>
            </div>
        </div>
    </div>

    <!-- ── Gráfica + Actividad Reciente ── -->
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="content-box">
                <h6 class="box-title">Volumen de Envíos — Últimos 7 días</h6>
                <p style="font-size:0.8rem;color:#888;margin-bottom:0;" id="grafica-subtitulo">Cargando datos...</p>
                <div class="chart-wrap" id="grafica-barras">
                    <!-- Se renderiza dinámicamente -->
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="content-box">
                <h6 class="box-title">Actividad Reciente</h6>
                <p style="font-size:0.8rem;color:#666;margin-bottom:20px;">Últimas actualizaciones</p>
                <ul class="timeline" id="timeline-actividad">
                    <!-- Se renderiza dinámicamente -->
                    <li class="timeline-item">
                        <div class="timeline-dot dot-gray"></div>
                        <h6><span class="skeleton" style="width:90px;height:13px;display:block;"></span></h6>
                        <p><span class="skeleton" style="width:120px;height:11px;display:block;margin-top:4px;"></span></p>
                    </li>
                    <li class="timeline-item">
                        <div class="timeline-dot dot-gray"></div>
                        <h6><span class="skeleton" style="width:90px;height:13px;display:block;"></span></h6>
                        <p><span class="skeleton" style="width:120px;height:11px;display:block;margin-top:4px;"></span></p>
                    </li>
                    <li class="timeline-item">
                        <div class="timeline-dot dot-gray"></div>
                        <h6><span class="skeleton" style="width:90px;height:13px;display:block;"></span></h6>
                        <p><span class="skeleton" style="width:120px;height:11px;display:block;margin-top:4px;"></span></p>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- ── Tabla reciente ── -->
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="box-title mb-0">Historial Reciente de Envíos</h6>
            <span style="font-size:0.82rem;color:#aaa;" id="tabla-fecha-actualizacion"></span>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless align-middle">
                <thead>
                    <tr style="border-bottom:2px solid #e9ecef;">
                        <th>Guía</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Estado</th>
                        <th>Operador Asignado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody id="tabla-envios">
                    <tr>
                        <td colspan="6" style="text-align:center;padding:30px;color:#bbb;">
                            <i class="fa-solid fa-circle-notch fa-spin" style="font-size:1.5rem;"></i>
                            <p style="margin-top:10px;">Cargando envíos...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast-container"></div>

<script>
// ══════════════════════════════════════
// CONFIGURACIÓN
// ══════════════════════════════════════
const API_BASE = 'http://127.0.0.1:5000';
const token    = localStorage.getItem('authToken');

const getHeaders = () => ({
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`
});

// ══════════════════════════════════════
// HELPERS
// ══════════════════════════════════════
function toast(msg, tipo = 'ok') {
    const c = document.getElementById('toast-container');
    const el = document.createElement('div');
    el.className = 'toast-msg' + (tipo === 'error' ? ' error' : '');
    el.textContent = msg;
    c.appendChild(el);
    setTimeout(() => el.remove(), 3500);
}

function colorDot(estado) {
    const mapa = {
        'EN ESPERA':             'dot-yellow',
        'EN PREPARACIÓN':        'dot-yellow',
        'EN CAMINO':             'dot-orange',
        'EN CAMINO AL DESTINO':  'dot-orange',
        'POR_CONFIRMAR_ENTREGA': 'dot-blue',
        'ENTREGADO':             'dot-green',
        'RECHAZADO':             'dot-red',
    };
    return mapa[estado] || 'dot-gray';
}

function badgeEstado(estado) {
    const estilos = {
        'EN ESPERA':             'background:#fef9c3;color:#ca8a04',
        'EN PREPARACIÓN':        'background:#fef9c3;color:#ca8a04',
        'EN CAMINO':             'background:#ffedd5;color:#ea580c',
        'EN CAMINO AL DESTINO':  'background:#ffedd5;color:#ea580c',
        'POR_CONFIRMAR_ENTREGA': 'background:#e0f2fe;color:#0369a1',
        'ENTREGADO':             'background:#dcfce7;color:#16a34a',
        'RECHAZADO':             'background:#fee2e2;color:#dc2626',
    };
    const estilo = estilos[estado] || 'background:#f1f5f9;color:#64748b';
    return `<span style="${estilo};padding:5px 12px;border-radius:20px;font-size:0.75rem;font-weight:700;">${estado}</span>`;
}

// Obtiene los últimos 7 días en formato legible
function ultimos7Dias() {
    const dias = ['DOM','LUN','MAR','MIÉ','JUE','VIE','SÁB'];
    const hoy = new Date();
    const resultado = [];
    for (let i = 6; i >= 0; i--) {
        const d = new Date(hoy);
        d.setDate(hoy.getDate() - i);
        resultado.push({
            label: dias[d.getDay()],
            fecha: d.toISOString().split('T')[0]  // YYYY-MM-DD
        });
    }
    return resultado;
}

// ══════════════════════════════════════
// CARGA DE DATOS
// ══════════════════════════════════════
async function cargarDashboard() {
    try {
        // Llamadas en paralelo para mayor velocidad
        const [resPedidos, resIncidencias] = await Promise.all([
            fetch(`${API_BASE}/v1/pedidos-web/`, { headers: getHeaders() }),
            fetch(`${API_BASE}/v1/incidencias/`, { headers: getHeaders() })
        ]);

        if (!resPedidos.ok) throw new Error('Error al obtener pedidos');

        const pedidos     = await resPedidos.json();
        const incidencias = resIncidencias.ok ? await resIncidencias.json() : [];

        renderKPIs(pedidos);
        renderGrafica(pedidos);
        renderTimeline(pedidos, incidencias);
        renderTabla(pedidos);
        actualizarPerfil();

    } catch (err) {
        console.error(err);
        toast('Error de conexión con el servidor. Verifica que la API esté activa.', 'error');
        renderErrorEstado();
    }
}

// ── KPI Cards ──
function renderKPIs(pedidos) {
    const total      = pedidos.length;
    const enEspera   = pedidos.filter(p => ['EN ESPERA','EN PREPARACIÓN'].includes(p.estado)).length;
    const enTransito = pedidos.filter(p => ['EN CAMINO','EN CAMINO AL DESTINO','POR_CONFIRMAR_ENTREGA'].includes(p.estado)).length;
    const entregados = pedidos.filter(p => p.estado === 'ENTREGADO').length;

    document.getElementById('kpi-total').textContent     = total;
    document.getElementById('kpi-espera').textContent    = enEspera;
    document.getElementById('kpi-transito').textContent  = enTransito;
    document.getElementById('kpi-entregados').textContent = entregados;
}

// ── Gráfica de barras ──
function renderGrafica(pedidos) {
    const contenedor = document.getElementById('grafica-barras');
    const dias = ultimos7Dias();

    // Contar pedidos por día según su fecha
    const conteo = {};
    dias.forEach(d => conteo[d.fecha] = 0);

    pedidos.forEach(p => {
        const fechaPedido = (p.fecha || '').split('T')[0];
        if (conteo.hasOwnProperty(fechaPedido)) {
            conteo[fechaPedido]++;
        }
    });

    const valores = dias.map(d => conteo[d.fecha]);
    const maxVal  = Math.max(...valores, 1); // evitar división por 0

    document.getElementById('grafica-subtitulo').textContent =
        `Total en los últimos 7 días: ${valores.reduce((a,b) => a+b, 0)} envíos`;

    contenedor.innerHTML = dias.map((d, i) => {
        const pct   = Math.max((valores[i] / maxVal) * 100, 4); // mínimo 4% para visibilidad
        const count = valores[i];
        return `
        <div class="bar-col">
            <div class="bar" style="height:${pct}%;" data-count="${count} envío${count !== 1 ? 's' : ''}"></div>
            <span class="bar-label">${d.label}</span>
        </div>`;
    }).join('');
}

// ── Timeline de actividad reciente ──
function renderTimeline(pedidos, incidencias) {
    const lista = document.getElementById('timeline-actividad');

    // Tomamos los últimos 5 pedidos modificados (asumimos que están ordenados por fecha desc o usamos los últimos del array)
    const recientes = [...pedidos].slice(-5).reverse();

    if (recientes.length === 0) {
        lista.innerHTML = `<div class="empty-state"><i class="fa-solid fa-inbox"></i>Sin actividad reciente</div>`;
        return;
    }

    lista.innerHTML = recientes.map(p => `
        <li class="timeline-item">
            <div class="timeline-dot ${colorDot(p.estado)}"></div>
            <h6>${p.id}</h6>
            <p>${p.destino || '—'} &nbsp;${badgeEstado(p.estado)}</p>
        </li>
    `).join('');
}

// ── Tabla de envíos recientes ──
function renderTabla(pedidos) {
    const tbody = document.getElementById('tabla-envios');

    // Mostrar los últimos 10, más recientes primero
    const recientes = [...pedidos].slice(-10).reverse();

    if (recientes.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-box-open"></i>No hay envíos registrados aún.</div></td></tr>`;
        return;
    }

    tbody.innerHTML = recientes.map(p => `
        <tr>
            <td><strong>${p.id}</strong></td>
            <td>${p.origen || '—'}</td>
            <td>${p.destino || '—'}</td>
            <td>${badgeEstado(p.estado)}</td>
            <td>${p.operador_nombre || '<span style="color:#bbb;">Sin asignar</span>'}</td>
            <td style="color:#888;font-size:0.85rem;">${p.fecha ? p.fecha.split('T')[0] : '—'}</td>
        </tr>
    `).join('');

    // Actualizar timestamp
    const ahora = new Date();
    document.getElementById('tabla-fecha-actualizacion').textContent =
        `Actualizado: ${ahora.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' })}`;
}

// ── Perfil del header desde localStorage ──
function actualizarPerfil() {
    const nombre = localStorage.getItem('nombreUsuario') || 'Admin AYFEX';
    const inicial = nombre.charAt(0).toUpperCase();
    const el = document.getElementById('headerNombre');
    const av = document.getElementById('headerAvatar');
    if (el) el.textContent = nombre;
    if (av) av.textContent = inicial;
}

// ── Estado de error en UI ──
function renderErrorEstado() {
    ['kpi-total','kpi-espera','kpi-transito','kpi-entregados'].forEach(id => {
        document.getElementById(id).textContent = '—';
    });
    document.getElementById('tabla-envios').innerHTML =
        `<tr><td colspan="6"><div class="empty-state" style="color:#ef4444;">
            <i class="fa-solid fa-triangle-exclamation"></i>
            No se pudo conectar con la API. Verifica que el servidor esté corriendo.
        </div></td></tr>`;
    document.getElementById('timeline-actividad').innerHTML =
        `<div class="empty-state" style="color:#ef4444;font-size:0.8rem;">Sin conexión</div>`;
    document.getElementById('grafica-barras').innerHTML = '';
    document.getElementById('grafica-subtitulo').textContent = 'Sin datos disponibles';
}

// ══════════════════════════════════════
// BUSCADOR (filtra la tabla en tiempo real)
// ══════════════════════════════════════
let todosPedidos = [];

async function cargarParaBusqueda() {
    try {
        const res = await fetch(`${API_BASE}/v1/pedidos-web/`, { headers: getHeaders() });
        if (res.ok) todosPedidos = await res.json();
    } catch (_) {}
}

document.getElementById('buscadorGlobal').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    if (!q) { renderTabla(todosPedidos); return; }

    const filtrados = todosPedidos.filter(p =>
        (p.id           || '').toLowerCase().includes(q) ||
        (p.origen       || '').toLowerCase().includes(q) ||
        (p.destino      || '').toLowerCase().includes(q) ||
        (p.operador_nombre || '').toLowerCase().includes(q)
    );
    renderTabla(filtrados);
});

// ══════════════════════════════════════
// INIT
// ══════════════════════════════════════
document.addEventListener('DOMContentLoaded', async () => {
    await cargarDashboard();
    await cargarParaBusqueda(); // para el buscador global
});
</script>
@endsection