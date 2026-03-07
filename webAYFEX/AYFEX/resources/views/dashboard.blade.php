@extends('layouts.app')

@section('content')
<style>
    /* =========================================
       ESTILOS DEL HEADER ESTÁTICO (NARANJA)
       ========================================= */
    .main-header {
        position: sticky;
        top: 0;
        z-index: 1000;
        background: linear-gradient(90deg, #ff5722 0%, #e64a19 100%);
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    .header-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
    }
    .brand-icon {
        width: 40px;
        height: 40px;
        background: #ffffff;
        border-radius: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #ff5722;
        font-size: 1.2rem;
    }
    .brand-text { display: flex; flex-direction: column; }
    .brand-name { font-weight: 900; font-size: 1.2rem; color: #ffffff; line-height: 1.1; letter-spacing: 1px;}
    .brand-slogan { font-size: 0.75rem; color: rgba(255, 255, 255, 0.85); }

    .header-search {
        flex: 1;
        max-width: 600px;
        margin: 0 2rem;
        position: relative;
    }
    .header-search i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #ff5722;
        z-index: 2;
    }
    .header-search input {
        width: 100%;
        padding: 10px 15px 10px 45px;
        border: none;
        border-radius: 25px;
        background-color: #ffffff;
        font-size: 0.95rem;
        color: #333;
        outline: none;
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
        display: flex; align-items: center; gap: 8px; border-radius: 12px 12px 0 0; margin-top: 6px; cursor: pointer; transition: all 0.3s;
    }
    .nav-item:hover { background-color: rgba(255, 255, 255, 0.2); color: #ffffff; }
    
    .nav-item.active { background-color: #f4f6f9; color: #ff5722; }
    .nav-item.active i { color: #ff5722; }
    .nav-item i.chevron { font-size: 0.75rem; margin-left: 4px; transition: color 0.3s;}
    
    /* Menú desplegable (Blanco) */
    .dropdown-menu { border: none; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); border-radius: 0 8px 8px 8px; padding: 8px 0; margin-top: 0 !important;}
    .dropdown-item { padding: 10px 20px; font-size: 0.9rem; color: #444; font-weight: 500;}
    .dropdown-item:hover { background-color: #fffaf5; color: #ff5722; }

    /* =========================================
       ESTILOS DEL DASHBOARD (CONTENIDO)
       ========================================= */
    body { background-color: #f4f6f9; color: #333; overflow-x: hidden; margin: 0; }
    .navbar { display: none !important; }
    .container.mt-4 { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }

    .main-wrapper { 
        padding: 30px; 
        max-width: 1400px; 
        margin: 0 auto; 
    }

    .page-title { margin-bottom: 25px; }
    .page-title h2 { font-weight: 900; margin: 0; color: #222; }
    .page-title p { color: #666; font-size: 0.95rem; }

    .stat-card, .content-box, .table-container { 
        background: #fff; border-radius: 16px; padding: 22px; 
        border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); 
        height: 100%;
    }
    .stat-card { position: relative; }
    .stat-card h6 { color: #777; font-size: 0.85rem; font-weight: 600; margin-bottom: 10px; }
    .stat-card h3 { font-weight: 900; margin: 0; color: #222; font-size: 2rem; }
    .stat-icon { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; }
    
    .icon-blue { background: #e0f2fe; color: #0284c7; }
    .icon-yellow { background: #fef08a; color: #ca8a04; }
    .icon-orange { background: #ffedd5; color: #ea580c; }
    .icon-green { background: #dcfce7; color: #16a34a; }

    .box-title { font-weight: 800; font-size: 1.1rem; margin-bottom: 5px; color: #222; }
    
    .chart-mockup { display: flex; align-items: flex-end; height: 160px; gap: 15px; padding-top: 20px; }
    .bar { flex: 1; background: linear-gradient(to top, #ff5722, #ff9b70); border-radius: 6px 6px 0 0; }

    .timeline { list-style: none; padding: 0; margin: 0; position: relative; }
    .timeline::before { content: ''; position: absolute; left: 7px; top: 5px; bottom: 0; width: 2px; background: #eee; }
    .timeline-item { position: relative; padding-left: 25px; margin-bottom: 15px; }
    .timeline-dot { position: absolute; left: 0; top: 4px; width: 16px; height: 16px; border-radius: 50%; border: 3px solid #fff; }
    
    .dot-orange { background: #fd5d14; }
    .dot-green { background: #16a34a; }
    .dot-yellow { background: #ca8a04; }
    .dot-red { background: #dc3545; }
    
    .timeline-item h6 { margin: 0; font-size: 0.85rem; font-weight: bold; }
    .timeline-item p { margin: 0; font-size: 0.75rem; color: #666; }

    .table-container { margin-top: 20px; }
    .table th { border-top: none; color: #888; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; padding-bottom: 15px; }
    .table td { vertical-align: middle; font-size: 0.95rem; color: #444; border-bottom: 1px solid #f0f0f0; padding: 12px 8px; }
    
    .badge-status { padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; }
    .status-transit { background: #ffedd5; color: #ea580c; }
    .status-delivered { background: #dcfce7; color: #16a34a; }
    .status-prep { background: #fef08a; color: #ca8a04; }
    .status-issue { background: #fee2e2; color: #dc2626; }
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

        <div class="header-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Buscar número de guía, cliente o ciudad...">
        </div>

        <div class="header-actions">
            <a href="{{ route('perfil') }}" class="user-profile">
                <div class="user-info d-none d-md-block">
                    <div class="user-name">Admin AYFEX</div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar">A</div>
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

<div class="main-wrapper">
    <div class="page-title">
        <h2>Panel Principal</h2>
        <p>Resumen operativo de hoy</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <h6>Total de Envíos</h6>
                <h3>156</h3>
                <div class="stat-icon icon-blue"><i class="fa-solid fa-box-open"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6>En Preparación</h6>
                <h3>12</h3>
                <div class="stat-icon icon-yellow"><i class="fa-regular fa-clock"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6>En Tránsito</h6>
                <h3>45</h3>
                <div class="stat-icon icon-orange"><i class="fa-solid fa-truck-fast"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6>Entregados</h6>
                <h3>95</h3>
                <div class="stat-icon icon-green"><i class="fa-solid fa-circle-check"></i></div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="content-box">
                <h6 class="box-title">Volumen de Envíos de la Semana</h6>
                <div class="chart-mockup">
                    <div class="bar" style="height: 60%;"></div>
                    <div class="bar" style="height: 85%;"></div>
                    <div class="bar" style="height: 75%;"></div>
                    <div class="bar" style="height: 100%;"></div>
                    <div class="bar" style="height: 90%;"></div>
                    <div class="bar" style="height: 40%;"></div>
                    <div class="bar" style="height: 25%;"></div>
                </div>
                <div class="d-flex justify-content-between mt-2 px-2" style="font-size: 0.85rem; color:#888; font-weight:bold;">
                    <span>LUN</span><span>MAR</span><span>MIÉ</span><span>JUE</span><span>VIE</span><span>SÁB</span><span>DOM</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="content-box">
                <h6 class="box-title">Actividad Reciente</h6>
                <p style="font-size: 0.8rem; color: #666; margin-bottom: 20px;">Últimas actualizaciones</p>
                
                <ul class="timeline">
                    <li class="timeline-item">
                        <div class="timeline-dot dot-orange"></div>
                        <h6>#PED-001</h6>
                        <p>Monterrey <span class="badge badge-status status-transit ms-1">En tránsito</span></p>
                    </li>
                    <li class="timeline-item">
                        <div class="timeline-dot dot-green"></div>
                        <h6>#PED-002</h6>
                        <p>Puebla <span class="badge badge-status status-delivered ms-1">Entregado</span></p>
                    </li>
                    <li class="timeline-item">
                        <div class="timeline-dot dot-yellow"></div>
                        <h6>#PED-003</h6>
                        <p>León <span class="badge badge-status status-prep ms-1">Preparación</span></p>
                    </li>
                    <li class="timeline-item">
                        <div class="timeline-dot dot-red"></div>
                        <h6>#PED-004</h6>
                        <p>Mexicali <span class="badge badge-status status-issue ms-1">Incidencia</span></p>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="table-container">
        <h6 class="box-title mb-4">Historial Reciente de Envíos</h6>
        <div class="table-responsive">
            <table class="table table-borderless align-middle">
                <thead>
                    <tr style="border-bottom: 2px solid #e9ecef;">
                        <th>Guía</th>
                        <th>Cliente / Empresa</th>
                        <th>Destino</th>
                        <th>Estado</th>
                        <th>Operador Asignado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>#PED-001</td><td>Comercial López</td><td>Monterrey, NL</td><td><span class="badge-status status-transit">En tránsito</span></td><td>Carlos Ramírez</td></tr>
                    <tr><td>#PED-002</td><td>Tech Solutions S.A.</td><td>Puebla, PUE</td><td><span class="badge-status status-delivered">Entregado</span></td><td>María González</td></tr>
                    <tr><td>#PED-003</td><td>Farmacia del Valle</td><td>León, GTO</td><td><span class="badge-status status-prep">Preparación</span></td><td>Pedro Sánchez</td></tr>
                    <tr><td>#PED-004</td><td>AutoPartes Express</td><td>Mexicali, BC</td><td><span class="badge-status status-issue">Incidencia</span></td><td>Luis Hernández</td></tr>
                    <tr><td>#PED-005</td><td>Librería Académica</td><td>Cancún, QR</td><td><span class="badge-status status-transit">En tránsito</span></td><td>Ana Martínez</td></tr>
                    <tr><td>#PED-006</td><td>Grupo Textil MX</td><td>Guadalajara, JAL</td><td><span class="badge-status status-prep">Preparación</span></td><td>José Luis Torres</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection