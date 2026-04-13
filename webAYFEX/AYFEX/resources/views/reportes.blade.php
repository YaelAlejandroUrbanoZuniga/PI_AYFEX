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

    /* ── Cajas ── */
    .box-bordered {
        background: #fff; border-radius: 16px; padding: 22px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04); margin-bottom: 24px;
    }
    .box-title { font-weight: 800; font-size: 1rem; color: #222; margin: 0 0 18px 0; }

    /* ── Filtros ── */
    .filter-row { display: flex; gap: 16px; align-items: center; flex-wrap: wrap; }
    .filter-select, .filter-date {
        padding: 10px 14px; border-radius: 8px; border: 1px solid #e0e0e0;
        font-size: 0.9rem; outline: none; background: #f9fafb; color: #444; cursor: pointer;
    }
    .filter-select { flex: 2; min-width: 180px; }
    .filter-date { flex: 1; min-width: 140px; }
    .btn-orange {
        background-color: #ff5722; color: #fff; border-radius: 8px;
        font-weight: 700; padding: 10px 26px; border: none; cursor: pointer; transition: 0.3s;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-orange:hover { background-color: #e64a19; }

    /* ── Tarjetas resumen ── */
    .resumen-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); gap: 16px; }
    .resumen-card {
        padding: 18px 20px; border-radius: 12px; display: flex; align-items: center;
        gap: 14px; background: #fff; border: 1px solid #eee; transition: 0.2s;
    }
    .resumen-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); }
    .resumen-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;
    }
    .resumen-card strong { font-size: 1.4rem; font-weight: 900; color: #222; display: block; line-height: 1; }
    .resumen-card small { color: #888; font-weight: 600; font-size: 0.75rem; margin-top: 4px; display: block; }
    .c-green  { background: #dcfce7; color: #16a34a; }
    .c-orange { background: #ffedd5; color: #ea580c; }
    .c-blue   { background: #e0f2fe; color: #0369a1; }
    .c-red    { background: #fee2e2; color: #dc2626; }
    .c-yellow { background: #fef9c3; color: #ca8a04; }
    .c-purple { background: #f3e8ff; color: #7c3aed; }

    /* ── Gráficas ── */
    .charts-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
    @media (max-width: 900px) { .charts-row { grid-template-columns: 1fr; } }
    .chart-wrap { position: relative; height: 240px; }

    /* ── Tabla operadores ── */
    .op-table { width: 100%; border-collapse: collapse; }
    .op-table th { color: #888; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; padding: 0 10px 12px; border-bottom: 2px solid #f0f0f0; text-align: left; }
    .op-table td { padding: 13px 10px; font-size: 0.88rem; color: #444; border-bottom: 1px solid #f6f6f6; vertical-align: middle; }
    .op-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }
    .op-disponible { background: #dcfce7; color: #16a34a; }
    .op-en-ruta    { background: #ffedd5; color: #ea580c; }

    /* ── Exportar ── */
    .export-row { display: flex; gap: 16px; flex-wrap: wrap; }
    .btn-export {
        flex: 1; min-width: 180px; padding: 12px 20px; border-radius: 10px;
        border: 2px solid #222; background: #fff; color: #222; font-weight: 700;
        font-size: 0.9rem; display: inline-flex; align-items: center; justify-content: center;
        gap: 10px; cursor: pointer; transition: 0.3s; text-decoration: none;
    }
    .btn-export:hover { background: #222; color: #fff; }
    .btn-export.pdf:hover { background: #dc2626; border-color: #dc2626; color: #fff; }
    .btn-export.excel:hover { background: #16a34a; border-color: #16a34a; color: #fff; }

    .text-orange { color: #ff5722; }
    .loading-overlay { text-align: center; padding: 40px; color: #999; }

    /* ══════════════════════════════════════════
       ESTILOS DEL PDF — mismo estilo que comprobante
    ══════════════════════════════════════════ */
    #pdf-template {
        position: fixed; left: -9999px; top: 0;
        width: 750px; background: #fff;
        font-family: 'Segoe UI', Roboto, sans-serif;
        padding: 0; color: #222;
    }
    .pdf-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; padding-bottom: 30px; border-bottom: 1px solid #eee; }
    .pdf-logo-row { display: flex; align-items: center; gap: 14px; }
    .pdf-logo-circle { width: 50px; height: 50px; border-radius: 50%; overflow: hidden; }
    .pdf-logo-circle img { width: 100%; height: 100%; object-fit: contain; }
    .pdf-brand-name { font-weight: 900; font-size: 1.4rem; color: #222; letter-spacing: 1px; }
    .pdf-brand-sub { font-size: 0.8rem; color: #999; }
    .pdf-meta { text-align: right; }
    .pdf-meta h3 { font-weight: 900; font-size: 1rem; color: #333; margin: 0 0 4px; }
    .pdf-meta p { font-size: 0.82rem; color: #888; margin: 0; }
    .pdf-section { border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 20px; }
    .pdf-section-title { color: #ff5722; font-weight: 800; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 20px; padding-bottom: 12px; border-bottom: 1px solid #f0f0f0; }
    .pdf-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .pdf-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
    .pdf-grid-4 { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 16px; }
    .pdf-field { background: #f9fafb; border-radius: 8px; padding: 14px 16px; }
    .pdf-field label { display: block; font-size: 0.72rem; color: #9ca3af; font-weight: 700; text-transform: uppercase; margin-bottom: 6px; }
    .pdf-field span { font-weight: 700; font-size: 0.95rem; color: #111; }
    .pdf-field span.highlight { color: #ff5722; font-size: 1.1rem; }
    .pdf-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    .pdf-table th { background: #f9fafb; padding: 10px 14px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #6b7280; text-transform: uppercase; }
    .pdf-table td { padding: 12px 14px; font-size: 0.85rem; color: #374151; border-bottom: 1px solid #f3f4f6; }
    .pdf-table tr:last-child td { border-bottom: none; }
    .pdf-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; }
    .pdf-badge-green  { background: #dcfce7; color: #16a34a; }
    .pdf-badge-orange { background: #ffedd5; color: #ea580c; }
    .pdf-badge-blue   { background: #e0f2fe; color: #0369a1; }
    .pdf-badge-red    { background: #fee2e2; color: #dc2626; }
    .pdf-badge-yellow { background: #fef9c3; color: #ca8a04; }
    .pdf-footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; }
    .pdf-footer p { font-size: 0.78rem; color: #9ca3af; margin: 3px 0; }
    .pdf-footer strong { color: #ff5722; }
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
                <li><a class="dropdown-item" href="{{ route('reportes') }}" style="{{ request()->routeIs('reportes') ? 'color:#ff5722;font-weight:bold;background-color:#fffaf5;' : '' }}"><i class="fa-solid fa-file-lines me-2"></i> Reportes</a></li>
                <li><a class="dropdown-item" href="{{ route('incidencias') }}"><i class="fa-solid fa-circle-exclamation me-2"></i> Incidencias</a></li>
            </ul>
        </div>
    </div>
</header>

<!-- ══════════ CONTENIDO ══════════ -->
<div class="main-wrapper">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-title">
            <h2>Reportes</h2>
            <p>Datos en tiempo real del sistema AYFEX</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="box-bordered">
        <h6 class="box-title"><i class="fa-solid fa-sliders me-2 text-orange"></i>Configuración de Reporte</h6>
        <div class="filter-row">
            <select class="filter-select" id="tipoReporte">
                <option value="envios">Reporte de Envíos</option>
                <option value="operadores">Rendimiento de Operadores</option>
                <option value="incidencias">Reporte de Incidencias</option>
            </select>
            <input type="date" class="filter-date" id="fechaDesde">
            <input type="date" class="filter-date" id="fechaHasta">
            <button class="btn-orange" onclick="generarReporte()">
                <i class="fa-solid fa-chart-column"></i> Generar
            </button>
        </div>
    </div>

    <!-- Loading -->
    <div id="loading-block" class="box-bordered loading-overlay" style="display:none;">
        <div class="spinner-border" style="color:#ff5722;" role="status"></div>
        <p class="mt-3">Cargando datos...</p>
    </div>

    <!-- ── Resumen KPI ── -->
    <div class="box-bordered" id="bloque-resumen" style="display:none;">
        <h6 class="box-title"><i class="fa-solid fa-chart-simple me-2 text-orange"></i>Resumen General</h6>
        <div class="resumen-grid" id="kpi-grid"></div>
    </div>

    <!-- ── Gráficas ── -->
    <div class="charts-row" id="bloque-graficas" style="display:none;">
        <div class="box-bordered" style="margin-bottom:0;">
            <h6 class="box-title"><i class="fa-solid fa-chart-bar me-2 text-orange"></i><span id="chart-bar-title">Envíos por Estado</span></h6>
            <div class="chart-wrap"><canvas id="chartBar"></canvas></div>
        </div>
        <div class="box-bordered" style="margin-bottom:0;">
            <h6 class="box-title"><i class="fa-solid fa-chart-pie me-2 text-orange"></i>Distribución</h6>
            <div class="chart-wrap"><canvas id="chartDonut"></canvas></div>
        </div>
    </div>

    <!-- ── Tabla detalle ── -->
    <div class="box-bordered" id="bloque-tabla" style="display:none;">
        <h6 class="box-title"><i class="fa-solid fa-table me-2 text-orange"></i><span id="tabla-title">Detalle</span></h6>
        <div class="table-responsive" id="tabla-contenido"></div>
    </div>

    <!-- ── Exportar ── -->
    <div class="box-bordered" id="bloque-exportar" style="display:none;">
        <h6 class="box-title"><i class="fa-solid fa-file-export me-2 text-orange"></i>Exportar Reporte</h6>
        <div class="export-row">
            <button class="btn-export pdf" onclick="exportarPDF()">
                <i class="fa-regular fa-file-pdf" style="color:#dc2626;"></i> Exportar como PDF
            </button>
            <button class="btn-export excel" onclick="exportarExcel()">
                <i class="fa-regular fa-file-excel" style="color:#16a34a;"></i> Exportar como Excel
            </button>
        </div>
    </div>

</div>

<!-- ══════════ TEMPLATE PDF (oculto) ══════════ -->
<div id="pdf-template"></div>

<!-- Chart.js + html2canvas + jsPDF + SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
// ══════════════════════════════════════
// CONFIG
// ══════════════════════════════════════
const API_PEDIDOS     = 'http://127.0.0.1:5000/v1/pedidos-web/';
const API_OPERADORES  = 'http://127.0.0.1:5000/v1/operadores/';
const API_INCIDENCIAS = 'http://127.0.0.1:5000/v1/reportes-movil/';

const token = localStorage.getItem('authToken');
const getHeaders = () => ({
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`
});

let chartBarInst   = null;
let chartDonutInst = null;
let reporteActual  = null; // cache para exportar

// Fechas por defecto: mes actual
const hoy = new Date();
const primerDia = new Date(hoy.getFullYear(), hoy.getMonth(), 1).toISOString().split('T')[0];
const hoyStr    = hoy.toISOString().split('T')[0];
document.getElementById('fechaDesde').value = primerDia;
document.getElementById('fechaHasta').value = hoyStr;

// ══════════════════════════════════════
// GENERAR REPORTE
// ══════════════════════════════════════
async function generarReporte() {
    const tipo   = document.getElementById('tipoReporte').value;
    const desde  = document.getElementById('fechaDesde').value;
    const hasta  = document.getElementById('fechaHasta').value;

    mostrarLoading(true);
    ocultarBloques();

    try {
        if (tipo === 'envios')      await reporteEnvios(desde, hasta);
        if (tipo === 'operadores')  await reporteOperadores(desde, hasta);
        if (tipo === 'incidencias') await reporteIncidencias(desde, hasta);

        mostrarBloques();
    } catch (err) {
        console.error(err);
        alert('Error al conectar con la API: ' + err.message);
    } finally {
        mostrarLoading(false);
    }
}

// ── Filtro por rango de fechas ──
function filtrarPorFecha(items, campo, desde, hasta) {
    if (!desde && !hasta) return items;
    return items.filter(item => {
        const f = item[campo] ? item[campo].substring(0, 10) : null;
        if (!f) return true;
        if (desde && f < desde) return false;
        if (hasta && f > hasta) return false;
        return true;
    });
}

// ══════════════════════════════════════
// REPORTE ENVÍOS
// ══════════════════════════════════════
async function reporteEnvios(desde, hasta) {
    const res = await fetch(API_PEDIDOS, { headers: getHeaders() });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const todos = await res.json();
    const data  = filtrarPorFecha(todos, 'fecha', desde, hasta);

    // KPIs
    const total      = data.length;
    const espera     = data.filter(p => p.estado === 'EN ESPERA').length;
    const camino     = data.filter(p => p.estado === 'EN CAMINO').length;
    const entregado  = data.filter(p => p.estado === 'ENTREGADO').length;
    const rechazado  = data.filter(p => p.estado === 'RECHAZADO').length;
    const confirmar  = data.filter(p => p.estado === 'POR_CONFIRMAR_ENTREGA').length;

    document.getElementById('kpi-grid').innerHTML = `
        ${kpiCard('fa-box-open',            total,     'Total Envíos',       'c-blue')}
        ${kpiCard('fa-hourglass-half',      espera,    'En Espera',          'c-yellow')}
        ${kpiCard('fa-truck',               camino,    'En Camino',          'c-orange')}
        ${kpiCard('fa-circle-check',        entregado, 'Entregados',         'c-green')}
        ${kpiCard('fa-magnifying-glass',    confirmar, 'Por Confirmar',      'c-blue')}
        ${kpiCard('fa-xmark-circle',        rechazado, 'Rechazados',         'c-red')}
    `;

    // Gráfica bar — por estado
    document.getElementById('chart-bar-title').innerText = 'Cantidad por Estado';
    renderBar(
        ['En Espera', 'En Camino', 'Entregado', 'Por Confirmar', 'Rechazado'],
        [espera, camino, entregado, confirmar, rechazado],
        ['#fbbf24','#f97316','#22c55e','#38bdf8','#ef4444']
    );
    renderDonut(
        ['En Espera', 'En Camino', 'Entregado', 'Por Confirmar', 'Rechazado'],
        [espera, camino, entregado, confirmar, rechazado],
        ['#fbbf24','#f97316','#22c55e','#38bdf8','#ef4444']
    );

    // Tabla
    document.getElementById('tabla-title').innerText = `Lista de Envíos (${data.length})`;
    document.getElementById('tabla-contenido').innerHTML = tablaEnvios(data);

    reporteActual = { tipo: 'envios', data, desde, hasta, kpis: { total, espera, camino, entregado, rechazado, confirmar } };
}

function tablaEnvios(data) {
    if (!data.length) return '<p class="text-muted p-3">Sin datos en el rango seleccionado.</p>';
    return `
    <table class="op-table">
        <thead><tr>
            <th>ID</th><th>Origen</th><th>Destino</th><th>Operador</th><th>Días Est.</th><th>Fecha</th><th>Estado</th>
        </tr></thead>
        <tbody>
        ${data.map(p => `<tr>
            <td><strong>${p.id}</strong></td>
            <td>${p.origen}</td>
            <td>${p.destino}</td>
            <td>${p.operador_nombre ?? '<span class="text-muted">Sin asignar</span>'}</td>
            <td class="text-center">${p.dias_estimados ?? '—'}</td>
            <td>${p.fecha}</td>
            <td>${badgeEstado(p.estado)}</td>
        </tr>`).join('')}
        </tbody>
    </table>`;
}

// ══════════════════════════════════════
// REPORTE OPERADORES
// ══════════════════════════════════════
async function reporteOperadores(desde, hasta) {
    const [resOp, resPed] = await Promise.all([
        fetch(API_OPERADORES, { headers: getHeaders() }),
        fetch(API_PEDIDOS,    { headers: getHeaders() })
    ]);
    if (!resOp.ok || !resPed.ok) throw new Error('Error al cargar datos');

    const opData    = await resOp.json();
    const operadores = opData.operadores ?? opData;
    const pedidos   = filtrarPorFecha(await resPed.json(), 'fecha', desde, hasta);

    const disponibles = operadores.filter(o => o.estado === 'DISPONIBLE').length;
    const enRuta      = operadores.filter(o => o.estado === 'EN RUTA').length;

    document.getElementById('kpi-grid').innerHTML = `
        ${kpiCard('fa-users',          operadores.length, 'Total Operadores', 'c-blue')}
        ${kpiCard('fa-circle-check',   disponibles,       'Disponibles',      'c-green')}
        ${kpiCard('fa-truck-fast',     enRuta,            'En Ruta',          'c-orange')}
    `;

    // Envíos por operador
    const conteo = {};
    pedidos.forEach(p => {
        const nombre = p.operador_nombre ?? 'Sin asignar';
        conteo[nombre] = (conteo[nombre] ?? 0) + 1;
    });

    const labels = Object.keys(conteo);
    const valores = Object.values(conteo);
    const colores = ['#ff5722','#22c55e','#3b82f6','#f59e0b','#8b5cf6','#ec4899','#14b8a6'];

    document.getElementById('chart-bar-title').innerText = 'Envíos por Operador';
    renderBar(labels, valores, colores);
    renderDonut(labels, valores, colores);

    document.getElementById('tabla-title').innerText = `Operadores (${operadores.length})`;
    document.getElementById('tabla-contenido').innerHTML = tablaOperadores(operadores, pedidos);

    reporteActual = { tipo: 'operadores', data: operadores, pedidos, desde, hasta };
}

function tablaOperadores(operadores, pedidos) {
    return `
    <table class="op-table">
        <thead><tr>
            <th>Nombre</th><th>Identificador</th><th>Teléfono</th><th>Vehículo</th><th>Envíos Asignados</th><th>Estado</th>
        </tr></thead>
        <tbody>
        ${operadores.map(op => {
            const asignados = pedidos.filter(p => p.operador_nombre === op.nombre_completo).length;
            return `<tr>
                <td><strong>${op.nombre_completo}</strong></td>
                <td>${op.identificador}</td>
                <td>${op.telefono}</td>
                <td>${op.vehiculo_asignado}</td>
                <td class="text-center"><strong>${asignados}</strong></td>
                <td><span class="op-badge ${op.estado === 'DISPONIBLE' ? 'op-disponible' : 'op-en-ruta'}">${op.estado}</span></td>
            </tr>`;
        }).join('')}
        </tbody>
    </table>`;
}

// ══════════════════════════════════════
// REPORTE INCIDENCIAS
// ══════════════════════════════════════
async function reporteIncidencias(desde, hasta) {
    const res = await fetch(API_INCIDENCIAS, { headers: getHeaders() });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const todos = await res.json();
    const data  = filtrarPorFecha(todos, 'fecha', desde, hasta);

    const pendientes = data.filter(i => i.estado === 'PENDIENTE').length;
    const resueltas  = data.filter(i => i.estado === 'RESUELTO').length;
    const altaPrior  = data.filter(i => i.prioridad === 'ALTA').length;

    const porTipo = {};
    data.forEach(i => { porTipo[i.tipo] = (porTipo[i.tipo] ?? 0) + 1; });
    const tipos   = Object.keys(porTipo);
    const valores = Object.values(porTipo);
    const colores = ['#ef4444','#f97316','#eab308','#22c55e','#3b82f6','#8b5cf6'];

    document.getElementById('kpi-grid').innerHTML = `
        ${kpiCard('fa-circle-exclamation', data.length, 'Total Reportes',  'c-red')}
        ${kpiCard('fa-clock',              pendientes,  'Pendientes',       'c-yellow')}
        ${kpiCard('fa-check-circle',       resueltas,   'Resueltos',        'c-green')}
        ${kpiCard('fa-arrow-up',           altaPrior,   'Alta Prioridad',   'c-orange')}
    `;

    document.getElementById('chart-bar-title').innerText = 'Reportes por Tipo';
    renderBar(tipos.length ? tipos : ['Sin datos'], tipos.length ? valores : [0], colores);
    renderDonut(
        ['Pendientes', 'Resueltos'],
        [pendientes, resueltas],
        ['#f97316', '#22c55e']
    );

    document.getElementById('tabla-title').innerText = `Reportes Móvil (${data.length})`;
    document.getElementById('tabla-contenido').innerHTML = tablaIncidencias(data);

    reporteActual = { tipo: 'incidencias', data, desde, hasta, kpis: { total: data.length, pendientes, resueltas, altaPrior } };
}

function tablaIncidencias(data) {
    if (!data.length) return '<p class="text-muted p-3">Sin reportes en el rango seleccionado.</p>';
    return `
    <table class="op-table">
        <thead><tr>
            <th>#</th><th>Cliente</th><th>Tipo</th><th>Descripción</th><th>Prioridad</th><th>Fecha</th><th>Estado</th>
        </tr></thead>
        <tbody>
        ${data.map(i => `<tr>
            <td><strong>#${i.id}</strong></td>
            <td>${i.nombre_usuario ?? 'Sin nombre'}</td>
            <td>${i.tipo}</td>
            <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${i.descripcion}">${i.descripcion}</td>
            <td>${badgePrioridadReporte(i.prioridad)}</td>
            <td>${i.fecha}</td>
            <td><span class="op-badge ${i.estado === 'RESUELTO' ? 'op-disponible' : 'op-en-ruta'}">${i.estado}</span></td>
        </tr>`).join('')}
        </tbody>
    </table>`;
}

function badgePrioridadReporte(p) {
    const map = {
        'ALTA':   'background:#fee2e2;color:#dc2626',
        'NORMAL': 'background:#e0f2fe;color:#0369a1',
        'BAJA':   'background:#f1f5f9;color:#64748b',
    };
    return `<span style="${map[p] ?? map['NORMAL']};padding:4px 10px;border-radius:6px;font-size:0.7rem;font-weight:800;">${p ?? 'NORMAL'}</span>`;
}

// ══════════════════════════════════════
// GRÁFICAS
// ══════════════════════════════════════
function renderBar(labels, data, colors) {
    if (chartBarInst) chartBarInst.destroy();
    const ctx = document.getElementById('chartBar').getContext('2d');
    chartBarInst = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                data,
                backgroundColor: colors,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f0f0' } },
                x: { grid: { display: false } }
            }
        }
    });
}

function renderDonut(labels, data, colors) {
    if (chartDonutInst) chartDonutInst.destroy();
    const ctx = document.getElementById('chartDonut').getContext('2d');
    chartDonutInst = new Chart(ctx, {
        type: 'doughnut',
        data: { labels, datasets: [{ data, backgroundColor: colors, borderWidth: 2, borderColor: '#fff' }] },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 12 } }
            },
            cutout: '65%'
        }
    });
}

// ══════════════════════════════════════
// EXPORTAR PDF — estilo comprobante AYFEX
// ══════════════════════════════════════
async function exportarPDF() {
    if (!reporteActual) { alert('Genera un reporte primero.'); return; }

    const { jsPDF } = window.jspdf;
    const desde  = document.getElementById('fechaDesde').value;
    const hasta  = document.getElementById('fechaHasta').value;
    const tipo   = document.getElementById('tipoReporte').options[document.getElementById('tipoReporte').selectedIndex].text;
    const fechaGen = new Date().toLocaleDateString('es-MX', { day: 'numeric', month: 'long', year: 'numeric' });

    // Construimos el HTML del PDF
    const tpl = document.getElementById('pdf-template');
    tpl.innerHTML = buildPDFHTML(reporteActual, tipo, desde, hasta, fechaGen);
    tpl.style.left = '-9999px';
    tpl.style.position = 'fixed';
    document.body.appendChild(tpl);

    await new Promise(r => setTimeout(r, 300));

    const canvas = await html2canvas(tpl, {
        scale: 2,
        useCORS: true,
        backgroundColor: '#fff',
        width: tpl.scrollWidth,
        windowWidth: tpl.scrollWidth
    });
    const imgData = canvas.toDataURL('image/png');

    const pdf = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
    const pdfWidth  = pdf.internal.pageSize.getWidth();
    const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
    const pageH     = pdf.internal.pageSize.getHeight();

    let posY = 0;
    while (posY < pdfHeight) {
        if (posY > 0) pdf.addPage();
        pdf.addImage(imgData, 'PNG', 0, -posY, pdfWidth, pdfHeight);
        posY += pageH;
    }

    pdf.save(`AYFEX_Reporte_${reporteActual.tipo}_${hoyStr}.pdf`);
    tpl.innerHTML = '';
}

function buildPDFHTML(reporte, tipoLabel, desde, hasta, fechaGen) {
    const logo = "{{ asset('AYFEXLOGO-Photoroom.png') }}";
    let seccionDatos = '';

    if (reporte.tipo === 'envios') {
        const { kpis, data } = reporte;
        seccionDatos = `
        <div class="pdf-section">
            <p class="pdf-section-title">Resumen de Envíos</p>
            <div class="pdf-grid-3">
                ${pdfField('Total', kpis.total)}
                ${pdfField('En Espera', kpis.espera)}
                ${pdfField('En Camino', kpis.camino)}
                ${pdfField('Entregados', kpis.entregado)}
                ${pdfField('Por Confirmar', kpis.confirmar)}
                ${pdfField('Rechazados', kpis.rechazado)}
            </div>
        </div>
        <div class="pdf-section" style="overflow:hidden;">
            <p class="pdf-section-title">Detalle de Envíos</p>
            <table class="pdf-table" style="table-layout:fixed;width:100%;word-break:break-word;">
                <thead><tr>
                    <th style="width:20%">ID</th>
                    <th style="width:11%">Origen</th>
                    <th style="width:11%">Destino</th>
                    <th style="width:18%">Operador</th>
                    <th style="width:7%">Días</th>
                    <th style="width:13%">Fecha</th>
                    <th style="width:20%">Estado</th>
                </tr></thead>
                <tbody>
                ${data.slice(0, 30).map(p => `<tr>
                    <td><strong style="font-size:0.78rem;">${p.id}</strong></td>
                    <td>${p.origen}</td><td>${p.destino}</td>
                    <td>${p.operador_nombre ?? 'Sin asignar'}</td>
                    <td style="text-align:center;">${p.dias_estimados ?? '—'}</td>
                    <td>${p.fecha}</td>
                    <td>${pdfBadgeEstado(p.estado)}</td>
                </tr>`).join('')}
                </tbody>
            </table>
            ${data.length > 30 ? `<p style="color:#888;font-size:0.78rem;margin-top:10px;">Se muestran los primeros 30 registros de ${data.length} totales.</p>` : ''}
        </div>`;
    }

    if (reporte.tipo === 'operadores') {
        const { data, pedidos } = reporte;
        seccionDatos = `
        <div class="pdf-section" style="overflow:hidden;">
            <p class="pdf-section-title">Detalle de Operadores</p>
            <table class="pdf-table" style="table-layout:fixed;width:100%;word-break:break-word;">
                <thead><tr>
                    <th style="width:28%">Nombre</th>
                    <th style="width:18%">Identificador</th>
                    <th style="width:26%">Vehículo</th>
                    <th style="width:10%">Envíos</th>
                    <th style="width:18%">Estado</th>
                </tr></thead>
                <tbody>
                ${data.map(op => {
                    const asig = pedidos.filter(p => p.operador_nombre === op.nombre_completo).length;
                    return `<tr>
                        <td><strong>${op.nombre_completo}</strong></td>
                        <td>${op.identificador}</td>
                        <td>${op.vehiculo_asignado}</td>
                        <td style="text-align:center;">${asig}</td>
                        <td>${op.estado === 'DISPONIBLE'
                            ? '<span class="pdf-badge pdf-badge-green">DISPONIBLE</span>'
                            : '<span class="pdf-badge pdf-badge-orange">EN RUTA</span>'}</td>
                    </tr>`;
                }).join('')}
                </tbody>
            </table>
        </div>`;
    }

    if (reporte.tipo === 'incidencias') {
        const { data, kpis } = reporte;
        seccionDatos = `
        <div class="pdf-section">
            <p class="pdf-section-title">Resumen de Reportes Móvil</p>
            <div class="pdf-grid-3">
                ${pdfField('Total', kpis.total)}
                ${pdfField('Pendientes', kpis.pendientes)}
                ${pdfField('Resueltos', kpis.resueltas)}
                ${pdfField('Alta Prioridad', kpis.altaPrior)}
            </div>
        </div>
        <div class="pdf-section" style="overflow:hidden;">
            <p class="pdf-section-title">Detalle de Reportes</p>
            <table class="pdf-table" style="table-layout:fixed;width:100%;word-break:break-word;">
                <thead><tr>
                    <th style="width:8%">#</th>
                    <th style="width:22%">Cliente</th>
                    <th style="width:18%">Tipo</th>
                    <th style="width:27%">Descripción</th>
                    <th style="width:12%">Fecha</th>
                    <th style="width:13%">Estado</th>
                </tr></thead>
                <tbody>
                ${data.slice(0, 30).map(i => `<tr>
                    <td><strong>#${i.id}</strong></td>
                    <td>${i.nombre_usuario ?? '—'}</td>
                    <td>${i.tipo}</td>
                    <td>${i.descripcion}</td>
                    <td>${i.fecha}</td>
                    <td>${i.estado === 'RESUELTO'
                        ? '<span class="pdf-badge pdf-badge-green">RESUELTO</span>'
                        : '<span class="pdf-badge pdf-badge-yellow">PENDIENTE</span>'}</td>
                </tr>`).join('')}
                </tbody>
            </table>
            ${data.length > 30 ? `<p style="color:#888;font-size:0.78rem;margin-top:10px;">Se muestran los primeros 30 registros de ${data.length} totales.</p>` : ''}
        </div>`;
    }

    return `
    <div style="font-family:'Segoe UI',Roboto,sans-serif;padding:40px;background:#fff;color:#222;width:750px;box-sizing:border-box;">
        <!-- Header -->
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:40px;padding-bottom:30px;border-bottom:1px solid #eee;">
            <div style="display:flex;align-items:center;gap:14px;">
                <div style="width:50px;height:50px;border-radius:50%;overflow:hidden;border:2px solid #f0f0f0;">
                    <img src="${logo}" style="width:100%;height:100%;object-fit:contain;padding:4px;" crossorigin="anonymous">
                </div>
                <div>
                    <div style="font-weight:900;font-size:1.4rem;color:#222;letter-spacing:1px;">AYFEX</div>
                    <div style="font-size:0.8rem;color:#999;white-space:nowrap;">Gestión&nbsp;de&nbsp;Transporte&nbsp;Logístico&nbsp;de&nbsp;Paquetería</div>
                </div>
            </div>
            <div style="text-align:right;">
                <h3 style="font-weight:900;font-size:1rem;color:#333;margin:0 0 4px;">${tipoLabel}</h3>
                <p style="font-size:0.82rem;color:#888;margin:0;">Generado: ${fechaGen}</p>
                <p style="font-size:0.82rem;color:#888;margin:2px 0 0;">Período: ${desde || '—'} al ${hasta || '—'}</p>
            </div>
        </div>

        ${seccionDatos}

        <!-- Footer -->
        <div style="text-align:center;margin-top:40px;padding-top:20px;border-top:1px solid #eee;">
            <p style="font-size:0.78rem;color:#9ca3af;margin:3px 0;">Reporte generado por <strong style="color:#ff5722;">AYFEX</strong></p>
            <p style="font-size:0.78rem;color:#9ca3af;margin:3px 0;">${new Date().getFullYear()} AYFEX — Todos los derechos reservados.</p>
        </div>
    </div>`;
}

function pdfField(label, value) {
    return `<div style="background:#f9fafb;border-radius:8px;padding:14px 16px;">
        <div style="font-size:0.7rem;color:#9ca3af;font-weight:700;text-transform:uppercase;margin-bottom:6px;">${label}</div>
        <div style="font-weight:800;font-size:1.1rem;color:#111;">${value}</div>
    </div>`;
}

function pdfBadgeEstado(estado) {
    const map = {
        'EN ESPERA':            'pdf-badge-yellow',
        'EN CAMINO':            'pdf-badge-orange',
        'ENTREGADO':            'pdf-badge-green',
        'RECHAZADO':            'pdf-badge-red',
        'POR_CONFIRMAR_ENTREGA':'pdf-badge-blue',
    };
    return `<span class="pdf-badge ${map[estado] ?? ''}">${estado}</span>`;
}

// ══════════════════════════════════════
// EXPORTAR EXCEL
// ══════════════════════════════════════
function exportarExcel() {
    if (!reporteActual) { alert('Genera un reporte primero.'); return; }

    const { tipo, data, pedidos } = reporteActual;
    let filas = [];
    let nombreHoja = tipo;

    if (tipo === 'envios') {
        filas = data.map(p => ({
            ID: p.id, Origen: p.origen, Destino: p.destino,
            Operador: p.operador_nombre ?? 'Sin asignar',
            'Días Est.': p.dias_estimados ?? '—',
            Fecha: p.fecha, Estado: p.estado
        }));
        nombreHoja = 'Envíos';
    }
    if (tipo === 'operadores') {
        filas = data.map(op => ({
            Nombre: op.nombre_completo, Identificador: op.identificador,
            Teléfono: op.telefono, Vehículo: op.vehiculo_asignado,
            'Envíos Asignados': (pedidos ?? []).filter(p => p.operador_nombre === op.nombre_completo).length,
            Estado: op.estado
        }));
        nombreHoja = 'Operadores';
    }
    if (tipo === 'incidencias') {
        filas = data.map(i => ({
            '#': i.id,
            Cliente: i.nombre_usuario ?? '—',
            Tipo: i.tipo,
            Descripción: i.descripcion,
            Prioridad: i.prioridad ?? 'NORMAL',
            Fecha: i.fecha,
            Estado: i.estado
        }));
        nombreHoja = 'Reportes Móvil';
    }

    const ws = XLSX.utils.json_to_sheet(filas);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, nombreHoja);
    XLSX.writeFile(wb, `AYFEX_${nombreHoja}_${hoyStr}.xlsx`);
}

// ══════════════════════════════════════
// HELPERS UI
// ══════════════════════════════════════
function kpiCard(icon, valor, label, color) {
    return `
    <div class="resumen-card">
        <div class="resumen-icon ${color}"><i class="fa-solid ${icon}"></i></div>
        <div><strong>${valor}</strong><small>${label}</small></div>
    </div>`;
}

function badgeEstado(estado) {
    const map = {
        'EN ESPERA':            'status-espera',
        'EN CAMINO':            'status-camino',
        'ENTREGADO':            'status-entregado',
        'RECHAZADO':            'status-rechazado',
        'POR_CONFIRMAR_ENTREGA':'status-confirmar',
    };
    const cls = map[estado] || 'status-default';
    // Reusar las clases de envios
    const colorMap = {
        'status-espera':    'background:#fef9c3;color:#ca8a04',
        'status-camino':    'background:#ffedd5;color:#ea580c',
        'status-entregado': 'background:#dcfce7;color:#16a34a',
        'status-rechazado': 'background:#fee2e2;color:#dc2626',
        'status-confirmar': 'background:#e0f2fe;color:#0369a1',
        'status-default':   'background:#f1f5f9;color:#64748b',
    };
    return `<span style="${colorMap[cls]};padding:4px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;">${estado}</span>`;
}

function mostrarLoading(show) {
    document.getElementById('loading-block').style.display = show ? 'block' : 'none';
}

function ocultarBloques() {
    ['bloque-resumen','bloque-graficas','bloque-tabla','bloque-exportar'].forEach(id => {
        document.getElementById(id).style.display = 'none';
    });
}

function mostrarBloques() {
    document.getElementById('bloque-resumen').style.display  = 'block';
    document.getElementById('bloque-graficas').style.display = 'grid';
    document.getElementById('bloque-tabla').style.display    = 'block';
    document.getElementById('bloque-exportar').style.display = 'block';
}

// Cargar reporte de envíos al abrir
document.addEventListener('DOMContentLoaded', () => generarReporte());

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