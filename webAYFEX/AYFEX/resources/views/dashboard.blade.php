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
    .brand-name { font-weight: 900; font-size: 1.2rem; color: #fff; line-height: 1.1; letter-spacing: 1px; }
    .brand-slogan { font-size: 0.75rem; color: rgba(255,255,255,0.85); }
    .header-actions { display: flex; align-items: center; gap: 20px; }
    .user-profile { display: flex; align-items: center; gap: 12px; text-decoration: none; }
    .user-info { text-align: right; }
    .user-name { font-weight: 600; font-size: 0.9rem; color: #fff; line-height: 1.2; }
    .user-role { font-size: 0.75rem; color: rgba(255,255,255,0.85); }
    .user-avatar { width: 38px; height: 38px; background: #fff; color: #ff5722; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 1.1rem; font-weight: bold; }
    .header-nav { display: flex; padding: 0 24px; gap: 8px; }
    .nav-item { padding: 12px 16px; font-size: 0.95rem; color: #fff; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px; border-radius: 12px 12px 0 0; margin-top: 6px; cursor: pointer; transition: all 0.3s; }
    .nav-item:hover { background-color: rgba(255,255,255,0.2); color: #fff; }
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
    .page-title { margin-bottom: 25px; }
    .page-title h2 { font-weight: 900; margin: 0; color: #222; }
    .page-title p { color: #666; font-size: 0.95rem; }

    .stat-card, .content-box, .table-container { background: #fff; border-radius: 16px; padding: 22px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); height: 100%; }
    .stat-card { position: relative; }
    .stat-card h6 { color: #777; font-size: 0.85rem; font-weight: 600; margin-bottom: 10px; }
    .stat-card h3 { font-weight: 900; margin: 0; color: #222; font-size: 2rem; }
    .stat-icon { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; }
    .icon-blue   { background: #e0f2fe; color: #0284c7; }
    .icon-yellow { background: #fef08a; color: #ca8a04; }
    .icon-orange { background: #ffedd5; color: #ea580c; }
    .icon-green  { background: #dcfce7; color: #16a34a; }

    .skeleton { background: linear-gradient(90deg,#f0f0f0 25%,#e0e0e0 50%,#f0f0f0 75%); background-size: 200% 100%; animation: shimmer 1.4s infinite; border-radius: 8px; display: inline-block; }
    @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

    .box-title { font-weight: 800; font-size: 1.1rem; margin-bottom: 5px; color: #222; }

    /* Donut */
    .donut-wrap { position: relative; display: flex; justify-content: center; align-items: center; margin: 16px auto 10px; }
    .donut-center { position: absolute; text-align: center; pointer-events: none; }
    .donut-center .big { font-size: 1.8rem; font-weight: 900; color: #222; line-height: 1; }
    .donut-center .lbl { font-size: 0.75rem; color: #888; font-weight: 600; }
    .legend-list { list-style: none; padding: 0; margin: 0; }
    .legend-list li { display: flex; align-items: center; justify-content: space-between; font-size: 0.82rem; color: #555; padding: 6px 0; border-bottom: 1px solid #f5f5f5; }
    .legend-list li:last-child { border-bottom: none; }
    .legend-dot { width: 10px; height: 10px; border-radius: 50%; margin-right: 8px; flex-shrink: 0; }
    .legend-label { display: flex; align-items: center; font-weight: 600; }
    .legend-val { font-weight: 800; color: #222; font-size: 0.85rem; }
    .legend-pct { font-weight: 400; color: #aaa; font-size: 0.75rem; margin-left: 4px; }

    /* Timeline */
    .timeline { list-style: none; padding: 0; margin: 0; position: relative; }
    .timeline::before { content:''; position: absolute; left: 7px; top: 5px; bottom: 0; width: 2px; background: #eee; }
    .timeline-item { position: relative; padding-left: 25px; margin-bottom: 15px; }
    .timeline-dot { position: absolute; left: 0; top: 4px; width: 16px; height: 16px; border-radius: 50%; border: 3px solid #fff; }
    .dot-orange { background: #fd5d14; } .dot-green { background: #16a34a; }
    .dot-yellow { background: #ca8a04; } .dot-red { background: #dc3545; }
    .dot-blue   { background: #0284c7; } .dot-gray { background: #94a3b8; }
    .timeline-item h6 { margin: 0; font-size: 0.85rem; font-weight: bold; }
    .timeline-item p  { margin: 0; font-size: 0.75rem; color: #666; }

    .table-container { margin-top: 20px; }
    .table th { border-top: none; color: #888; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; padding-bottom: 15px; }
    .table td { vertical-align: middle; font-size: 0.95rem; color: #444; border-bottom: 1px solid #f0f0f0; padding: 12px 8px; }
    .empty-state { text-align: center; padding: 30px; color: #aaa; font-size: 0.9rem; }
    .empty-state i { font-size: 2rem; margin-bottom: 10px; display: block; }
</style>

<!-- HEADER -->
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
        <div class="header-actions">
            <a href="{{ route('perfil') }}" class="user-profile">
                <div class="user-info d-none d-md-block">
                    <div class="user-name" id="headerNombre">—</div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar" id="headerAvatar">—</div>
            </a>
            <a href="{{ route('login') }}" class="user-profile" style="margin-left:10px;" title="Cerrar Sesión"
               onclick="localStorage.removeItem('authToken');localStorage.removeItem('nombreUsuario');">
                <i class="fa-solid fa-right-from-bracket" style="color:white;font-size:1.2rem;"></i>
            </a>
        </div>
    </div>
    <div class="header-nav">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-border-all"></i> Dashboard
        </a>
        <div class="dropdown">
            <div class="nav-item {{ request()->routeIs('envios')||request()->routeIs('rutas') ? 'active' : '' }}" data-bs-toggle="dropdown">
                Operaciones <i class="fa-solid fa-chevron-down chevron"></i>
            </div>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('envios') }}"><i class="fa-solid fa-box me-2"></i>Envíos</a></li>
                <li><a class="dropdown-item" href="{{ route('rutas') }}"><i class="fa-solid fa-route me-2"></i>Rutas</a></li>
            </ul>
        </div>
        <div class="dropdown">
            <div class="nav-item {{ request()->routeIs('clientes')||request()->routeIs('operadores') ? 'active' : '' }}" data-bs-toggle="dropdown">
                Gestión <i class="fa-solid fa-chevron-down chevron"></i>
            </div>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('clientes') }}"><i class="fa-solid fa-users me-2"></i>Clientes</a></li>
                <li><a class="dropdown-item" href="{{ route('operadores') }}"><i class="fa-solid fa-truck me-2"></i>Operadores</a></li>
            </ul>
        </div>
        <div class="dropdown">
            <div class="nav-item {{ request()->routeIs('reportes')||request()->routeIs('incidencias') ? 'active' : '' }}" data-bs-toggle="dropdown">
                Administración <i class="fa-solid fa-chevron-down chevron"></i>
            </div>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('reportes') }}"><i class="fa-solid fa-file-lines me-2"></i>Reportes</a></li>
                <li><a class="dropdown-item" href="{{ route('incidencias') }}"><i class="fa-solid fa-circle-exclamation me-2"></i>Incidencias</a></li>
            </ul>
        </div>
    </div>
</header>

<!-- CONTENIDO -->
<div class="main-wrapper">
    <div class="page-title">
        <h2>Panel Principal</h2>
        <p>Resumen operativo de hoy</p>
    </div>

    <!-- KPIs -->
    <div class="row g-4 mb-4">
        <div class="col-md-3"><div class="stat-card">
            <h6>Total de Envíos</h6>
            <h3 id="kpi-total"><span class="skeleton" style="width:70px;height:32px;display:block;"></span></h3>
            <div class="stat-icon icon-blue"><i class="fa-solid fa-box-open"></i></div>
        </div></div>
        <div class="col-md-3"><div class="stat-card">
            <h6>En Espera / Preparación</h6>
            <h3 id="kpi-espera"><span class="skeleton" style="width:70px;height:32px;display:block;"></span></h3>
            <div class="stat-icon icon-yellow"><i class="fa-regular fa-clock"></i></div>
        </div></div>
        <div class="col-md-3"><div class="stat-card">
            <h6>En Tránsito</h6>
            <h3 id="kpi-transito"><span class="skeleton" style="width:70px;height:32px;display:block;"></span></h3>
            <div class="stat-icon icon-orange"><i class="fa-solid fa-truck-fast"></i></div>
        </div></div>
        <div class="col-md-3"><div class="stat-card">
            <h6>Entregados</h6>
            <h3 id="kpi-entregados"><span class="skeleton" style="width:70px;height:32px;display:block;"></span></h3>
            <div class="stat-icon icon-green"><i class="fa-solid fa-circle-check"></i></div>
        </div></div>
    </div>

    <!-- Donut + Timeline -->
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="content-box">
                <h6 class="box-title">Distribución de Envíos por Estado</h6>
                <p style="font-size:0.8rem;color:#888;margin-bottom:0;">Total histórico de todos los envíos registrados</p>
                <div class="row align-items-center mt-3">
                    <div class="col-md-5 d-flex justify-content-center">
                        <div class="donut-wrap">
                            <canvas id="donutChart" width="190" height="190"></canvas>
                            <div class="donut-center">
                                <div class="big" id="donut-total">—</div>
                                <div class="lbl">envíos</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <ul class="legend-list" id="donut-legend">
                            <li><span class="legend-label"><span class="skeleton legend-dot"></span><span class="skeleton" style="width:100px;height:12px;display:inline-block;"></span></span></li>
                            <li><span class="legend-label"><span class="skeleton legend-dot"></span><span class="skeleton" style="width:80px;height:12px;display:inline-block;"></span></span></li>
                            <li><span class="legend-label"><span class="skeleton legend-dot"></span><span class="skeleton" style="width:90px;height:12px;display:inline-block;"></span></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="content-box">
                <h6 class="box-title">Actividad Reciente</h6>
                <p style="font-size:0.8rem;color:#666;margin-bottom:20px;">Últimas actualizaciones</p>
                <ul class="timeline" id="timeline-actividad">
                    <li class="timeline-item"><div class="timeline-dot dot-gray"></div>
                        <h6><span class="skeleton" style="width:90px;height:13px;display:block;"></span></h6>
                        <p><span class="skeleton" style="width:120px;height:11px;display:block;margin-top:4px;"></span></p>
                    </li>
                    <li class="timeline-item"><div class="timeline-dot dot-gray"></div>
                        <h6><span class="skeleton" style="width:90px;height:13px;display:block;"></span></h6>
                        <p><span class="skeleton" style="width:120px;height:11px;display:block;margin-top:4px;"></span></p>
                    </li>
                    <li class="timeline-item"><div class="timeline-dot dot-gray"></div>
                        <h6><span class="skeleton" style="width:90px;height:13px;display:block;"></span></h6>
                        <p><span class="skeleton" style="width:120px;height:11px;display:block;margin-top:4px;"></span></p>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="box-title mb-0">Historial Reciente de Envíos</h6>
            <span style="font-size:0.82rem;color:#aaa;" id="tabla-fecha"></span>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless align-middle">
                <thead>
                    <tr style="border-bottom:2px solid #e9ecef;">
                        <th>Guía</th><th>Origen</th><th>Destino</th>
                        <th>Estado</th><th>Operador</th><th>Fecha</th>
                    </tr>
                </thead>
                <tbody id="tabla-envios">
                    <tr><td colspan="6" style="text-align:center;padding:30px;color:#bbb;">
                        <i class="fa-solid fa-circle-notch fa-spin" style="font-size:1.5rem;"></i>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const API_BASE = 'http://127.0.0.1:5000';
const token    = localStorage.getItem('authToken');
const getH     = () => ({ 'Content-Type':'application/json', 'Authorization':`Bearer ${token}` });

// ── Perfil ──────────────────────────────────────────
function actualizarPerfil() {
    const nombre = localStorage.getItem('nombreUsuario') || 'Admin';
    document.getElementById('headerNombre').textContent = nombre;
    document.getElementById('headerAvatar').textContent = nombre.charAt(0).toUpperCase();
}

// ── Badge estado ────────────────────────────────────
function badgeEstado(estado) {
    const m = {
        'EN ESPERA':'background:#fef9c3;color:#ca8a04',
        'EN PREPARACIÓN':'background:#fef9c3;color:#ca8a04',
        'EN CAMINO':'background:#ffedd5;color:#ea580c',
        'EN CAMINO AL DESTINO':'background:#ffedd5;color:#ea580c',
        'POR_CONFIRMAR_ENTREGA':'background:#e0f2fe;color:#0369a1',
        'ENTREGADO':'background:#dcfce7;color:#16a34a',
        'RECHAZADO':'background:#fee2e2;color:#dc2626',
    };
    const s = m[estado] || 'background:#f1f5f9;color:#64748b';
    return `<span style="${s};padding:5px 12px;border-radius:20px;font-size:0.75rem;font-weight:700;">${estado}</span>`;
}

function colorDot(estado) {
    const m = { 'EN ESPERA':'dot-yellow','EN PREPARACIÓN':'dot-yellow','EN CAMINO':'dot-orange','EN CAMINO AL DESTINO':'dot-orange','POR_CONFIRMAR_ENTREGA':'dot-blue','ENTREGADO':'dot-green','RECHAZADO':'dot-red' };
    return m[estado] || 'dot-gray';
}

// ── Donut (Canvas puro) ─────────────────────────────
function dibujarDonut(canvas, datos, total) {
    const ctx = canvas.getContext('2d');
    const cx = canvas.width/2, cy = canvas.height/2, r = 72, g = 28;
    let ang = -Math.PI / 2;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    if (total === 0) {
        ctx.beginPath(); ctx.arc(cx,cy,r,0,Math.PI*2);
        ctx.strokeStyle='#e9ecef'; ctx.lineWidth=g; ctx.stroke(); return;
    }
    datos.forEach(d => {
        const slice = (d.valor / total) * Math.PI * 2;
        ctx.beginPath(); ctx.arc(cx, cy, r, ang, ang + slice);
        ctx.strokeStyle = d.color; ctx.lineWidth = g; ctx.lineCap = 'butt'; ctx.stroke();
        ang += slice;
    });
    // Hueco central blanco
    ctx.beginPath(); ctx.arc(cx, cy, r - g/2 - 2, 0, Math.PI*2);
    ctx.fillStyle = '#fff'; ctx.fill();
}

// ── KPIs ────────────────────────────────────────────
function renderKPIs(pedidos) {
    document.getElementById('kpi-total').textContent      = pedidos.length;
    document.getElementById('kpi-espera').textContent     = pedidos.filter(p=>['EN ESPERA','EN PREPARACIÓN'].includes(p.estado)).length;
    document.getElementById('kpi-transito').textContent   = pedidos.filter(p=>['EN CAMINO','EN CAMINO AL DESTINO','POR_CONFIRMAR_ENTREGA'].includes(p.estado)).length;
    document.getElementById('kpi-entregados').textContent = pedidos.filter(p=>p.estado==='ENTREGADO').length;
}

// ── Donut render ────────────────────────────────────
function renderDonut(pedidos) {
    const total = pedidos.length;
    document.getElementById('donut-total').textContent = total;

    const grupos = [
        { label:'Entregados',      estados:['ENTREGADO'],                                color:'#16a34a' },
        { label:'En Tránsito',     estados:['EN CAMINO','EN CAMINO AL DESTINO'],         color:'#ea580c' },
        { label:'Por Confirmar',   estados:['POR_CONFIRMAR_ENTREGA'],                    color:'#0369a1' },
        { label:'En Espera/Prep.', estados:['EN ESPERA','EN PREPARACIÓN'],               color:'#ca8a04' },
        { label:'Rechazados',      estados:['RECHAZADO'],                                color:'#dc2626' },
    ];

    const datos = grupos.map(g => ({
        label: g.label, color: g.color,
        valor: pedidos.filter(p => g.estados.includes(p.estado)).length
    })).filter(d => d.valor > 0);

    dibujarDonut(document.getElementById('donutChart'), datos, total);

    document.getElementById('donut-legend').innerHTML = (datos.length ? datos : grupos.map(g=>({...g,valor:0}))).map(d => `
        <li>
            <span class="legend-label">
                <span class="legend-dot" style="background:${d.color};"></span>${d.label}
            </span>
            <span class="legend-val">${d.valor}<span class="legend-pct">(${total>0?Math.round(d.valor/total*100):0}%)</span></span>
        </li>`).join('');
}

// ── Timeline ────────────────────────────────────────
function renderTimeline(pedidos) {
    const ul = document.getElementById('timeline-actividad');
    const recientes = [...pedidos].slice(-5).reverse();
    ul.innerHTML = recientes.length
        ? recientes.map(p=>`
            <li class="timeline-item">
                <div class="timeline-dot ${colorDot(p.estado)}"></div>
                <h6>${p.id}</h6>
                <p>${p.destino||'—'} &nbsp;${badgeEstado(p.estado)}</p>
            </li>`).join('')
        : `<div class="empty-state"><i class="fa-solid fa-inbox"></i>Sin actividad reciente</div>`;
}

// ── Tabla ───────────────────────────────────────────
function renderTabla(pedidos) {
    const tbody = document.getElementById('tabla-envios');
    const recientes = [...pedidos].slice(-10).reverse();
    tbody.innerHTML = recientes.length
        ? recientes.map(p=>`
            <tr>
                <td><strong>${p.id}</strong></td>
                <td>${p.origen||'—'}</td>
                <td>${p.destino||'—'}</td>
                <td>${badgeEstado(p.estado)}</td>
                <td>${p.operador_nombre||'<span style="color:#bbb;">Sin asignar</span>'}</td>
                <td style="color:#888;font-size:0.85rem;">${(p.fecha||'').split('T')[0]||'—'}</td>
            </tr>`).join('')
        : `<tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-box-open"></i>No hay envíos.</div></td></tr>`;

    document.getElementById('tabla-fecha').textContent =
        `Actualizado: ${new Date().toLocaleTimeString('es-MX',{hour:'2-digit',minute:'2-digit'})}`;
}

// ── Init ────────────────────────────────────────────
async function cargarDashboard() {
    try {
        const res = await fetch(`${API_BASE}/v1/pedidos-web/`, { headers: getH() });
        if (!res.ok) throw new Error();
        const pedidos = await res.json();
        renderKPIs(pedidos);
        renderDonut(pedidos);
        renderTimeline(pedidos);
        renderTabla(pedidos);
    } catch {
        document.getElementById('tabla-envios').innerHTML =
            `<tr><td colspan="6"><div class="empty-state" style="color:#ef4444;"><i class="fa-solid fa-triangle-exclamation"></i><p>Error de conexión con la API.</p></div></td></tr>`;
        document.getElementById('donut-legend').innerHTML = '';
    }
}

document.addEventListener('DOMContentLoaded', () => { actualizarPerfil(); cargarDashboard(); });
</script>
@endsection