@extends('layouts.app')

@section('content')
<style>
    
    .navbar { display: none !important; } 
    .container.mt-4 { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
    body { background-color: #f4f6f9; color: #333; overflow-x: hidden; }

   
    .wrapper { 
        display: flex; 
        width: 100%; 
        height: 100vh; 
        overflow: hidden; 
    }

   
    .sidebar {
        width: 260px;
        background: linear-gradient(180deg, #ff5722 0%, #e64a19 100%); 
        display: flex;
        flex-direction: column;
        padding: 20px 15px;
        color: white;
        box-shadow: 4px 0 15px rgba(0,0,0,0.05);
        z-index: 10;
    }
    
    .sidebar-logo { text-align: center; margin-bottom: 30px; }
    .sidebar-logo h4 { font-weight: 900; color: #ffffff; margin: 0; letter-spacing: 1px; font-size: 2rem; }
    .sidebar-logo p { font-size: 0.8rem; color: rgba(255,255,255,0.8); margin: 0; }

   
    .nav-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        color: #ffffff;
        text-decoration: none;
        font-weight: 700;
        border-radius: 12px;
        margin-bottom: 8px;
        opacity: 0.9;
        transition: all 0.3s ease;
    }
    .nav-item i { width: 30px; font-size: 1.1rem; }
    
    .nav-item:hover { background-color: rgba(255, 255, 255, 0.2); opacity: 1; }
    
    .nav-item.active { 
        background-color: #ffffff; 
        color: #ff5722; 
        opacity: 1;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
    } 

    .sidebar-footer { margin-top: auto; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 15px; }
    .user-profile { display: flex; align-items: center; margin-bottom: 15px; }
    .user-avatar { 
        width: 38px; height: 38px; background: #ffffff; color: #ff5722; 
        border-radius: 50%; display: flex; align-items: center; justify-content: center; 
        font-weight: bold; margin-right: 12px; font-size: 1.1rem;
    }
    .user-profile h6 { margin: 0; font-weight: bold; font-size: 0.95rem; color: white; }
    .logout-btn { color: #ffffff; text-decoration: none; font-weight: 700; opacity: 0.9; display: flex; align-items: center; transition: 0.3s; }
    .logout-btn:hover { opacity: 1; }
    .logout-btn i { margin-right: 10px; }

    
    .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }

    
    .topbar { 
        background: #ff5722; 
        padding: 15px 30px; 
        display: flex;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        z-index: 5;
    }
    .search-bar { position: relative; max-width: 400px; width: 100%; }
    .search-bar i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #ff5722; z-index: 2; }
    .search-bar input { 
        width: 100%; padding: 10px 15px 10px 45px; border-radius: 25px; 
        border: none; font-size: 0.95rem; outline: none; 
        background-color: #ffffff; color: #333;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .search-bar input::placeholder { color: #aaa; }

    
    .dashboard-content { padding: 30px; }
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

<div class="wrapper">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <h4>AYFEX</h4>
            <p>Gestión de Transporte Logístico de Paquetería</p>
        </div>

<nav>
    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fa-solid fa-border-all"></i> Dashboard</a>
    <a href="{{ route('envios') }}" class="nav-item {{ request()->routeIs('envios') ? 'active' : '' }}"><i class="fa-solid fa-box"></i> Envíos</a>
    <a href="{{ route('clientes') }}" class="nav-item {{ request()->routeIs('clientes') ? 'active' : '' }}"><i class="fa-solid fa-users"></i> Clientes</a>
    <a href="{{ route('operadores') }}" class="nav-item {{ request()->routeIs('operadores') ? 'active' : '' }}"><i class="fa-solid fa-truck"></i> Operadores</a>
    <a href="{{ route('rutas') }}" class="nav-item {{ request()->routeIs('rutas') ? 'active' : '' }}"><i class="fa-solid fa-route"></i> Rutas</a>
    <a href="{{ route('reportes') }}" class="nav-item {{ request()->routeIs('reportes') ? 'active' : '' }}"><i class="fa-solid fa-file-lines"></i> Reportes</a>
    <a href="{{ route('incidencias') }}" class="nav-item {{ request()->routeIs('incidencias') ? 'active' : '' }}"><i class="fa-solid fa-circle-exclamation"></i> Incidencias</a>
</nav>

<div class="sidebar-footer">
    <a href="{{ route('perfil') }}" style="text-decoration:none; display:flex; align-items:center; margin-bottom:15px; background: {{ request()->routeIs('perfil') ? 'rgba(255,255,255,0.2)' : 'transparent' }}; padding: 10px; border-radius: 12px; transition: 0.3s;">
        <div class="user-avatar" style="width: 38px; height: 38px; background: #fff; color: #ff5722; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 12px;">A</div>
        <div>
            <h6 style="margin: 0; font-weight: bold; font-size: 0.95rem; color: white;">Admin AYFEX</h6>
        </div>
    </a>
    <a href="{{ route('login') }}" class="logout-btn" style="color:#fff; text-decoration:none; font-weight:700;"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a>
</div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="search-bar">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Buscar número de guía, cliente o ciudad...">
            </div>
        </header>

        <div class="dashboard-content">
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
    </main>
</div>
@endsection