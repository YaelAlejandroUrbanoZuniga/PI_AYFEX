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
    
    .topbar-right { flex: 1; text-align: right; }

    .dashboard-content { padding: 30px; }
    
    .box-bordered { 
        background: #fff; 
        border: 2px solid #222; 
        border-radius: 12px; 
        padding: 20px; 
        margin-bottom: 20px; 
    }

    .form-group-reporte {
        display: flex;
        gap: 20px;
        align-items: center;
        margin-top: 15px;
    }

    .input-reporte-select {
        flex: 1.5;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #fdfdfd;
        padding: 10px;
        outline: none;
        cursor: pointer;
    }

    .input-reporte-date {
        flex: 1;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #fdfdfd;
        padding: 10px;
        cursor: pointer;
        outline: none;
    }

    .btn-orange-full {
        background-color: #ff5722; 
        color: #fff; 
        border-radius: 8px; 
        font-weight: 600; 
        padding: 10px 30px; 
        border: none;
        display: inline-flex;
        align-items: center;
    }

    .chart-skeletal-bar {
        height: 150px;
        display: flex;
        justify-content: space-around;
        align-items: flex-end;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
    }

    .chart-bar-rect {
        width: 30px;
        background: linear-gradient(to top, #ff5722, #ff9e7f);
        border-radius: 4px;
    }

    .chart-legend-row {
        display: flex;
        justify-content: center;
        gap: 20px;
        font-size: 0.8rem;
        color: #888;
        font-weight: 600;
        margin-top: 10px;
    }

    .pie-skeletal-wrapper {
        position: relative;
        width: 140px;
        height: 140px;
        margin: 0 auto;
    }
    
    .pie-skeletal-donut {
        width: 100%;
        height: 100%;
        background: conic-gradient(
            #10b981 0% 60%,   
            #ff5722 60% 80%,  
            #f59e0b 80% 95%,  
            #ef4444 95% 100%  
        );
        border-radius: 50%;
        position: relative;
    }
    
    .pie-skeletal-hole {
        position: absolute;
        top: 25%;
        left: 25%;
        width: 50%;
        height: 50%;
        background: #fff;
        border-radius: 50%;
    }

    .donut-details {
        font-size: 0.8rem;
        margin-top: 15px;
    }
    
    .status-item {
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        font-weight: 600;
    }

    .resumen-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }
    
    .resumen-card {
        padding: 15px 20px;
        border: 1px solid #eee;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .resumen-card i {
        font-size: 1.2rem;
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .resumen-card strong {
        font-size: 1.1rem;
        font-weight: 900;
        color: #222;
        display: block;
    }

    .resumen-card small {
        color: #888;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .card-envios { border-bottom: 3px solid #16a34a; }
    .card-envios i { background: #dcfce7; color: #16a34a; }
    
    .card-transito { border-bottom: 3px solid #ff5722; }
    .card-transito i { background: #fff0eb; color: #ff5722; }
    
    .card-completados { border-bottom: 3px solid #007bff; }
    .card-completados i { background: #e0f2fe; color: #007bff; }
    
    .card-incidencias { border-bottom: 3px solid #ef4444; }
    .card-incidencias i { background: #fee2e2; color: #ef4444; }

    .exportar-group {
        display: flex;
        gap: 15px;
    }

    .btn-outline-dark-report {
        flex: 1;
        border: 2px solid #222;
        background: #fff;
        color: #222;
        font-weight: 700;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 0.9rem;
    }
    .btn-outline-dark-report:hover {
        background: #222;
        color: #fff;
    }
</style>

<div class="wrapper">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <h4>AYFEX</h4>
            <p>Gestión de Transporte Logístico de Paquetería</p>
        </div>

        <nav>
            <a href="{{ route('dashboard') }}" class="nav-item"><i class="fa-solid fa-border-all"></i> Dashboard</a>
            <a href="{{ route('envios') }}" class="nav-item"><i class="fa-solid fa-box"></i> Envíos</a>
            <a href="{{ route('clientes') }}" class="nav-item"><i class="fa-solid fa-users"></i> Clientes</a>
            <a href="{{ route('operadores') }}" class="nav-item"><i class="fa-solid fa-truck"></i> Operadores</a>
            <a href="{{ route('rutas') }}" class="nav-item"><i class="fa-solid fa-route"></i> Rutas</a>
            <a href="{{ route('reportes') }}" class="nav-item active"><i class="fa-solid fa-file-lines"></i> Reportes</a>
            <a href="{{ route('incidencias') }}" class="nav-item"><i class="fa-solid fa-circle-exclamation"></i> Incidencias</a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('perfil') }}" class="user-profile" style="text-decoration:none; display:flex; align-items:center; margin-bottom:15px; padding: 10px; border-radius: 12px; transition: 0.3s;">
                <div class="user-avatar">A</div>
                <div>
                    <h6>Admin AYFEX</h6>
                </div>
            </a>
            <a href="{{ route('login') }}" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="topbar-right"></div>
        </header>

        <div class="dashboard-content">
            <h2 style="font-weight: 900; margin: 0; color: #222;">Reportes</h2>
            <p style="color: #666; font-size: 0.95rem; margin-top: 5px; margin-bottom: 25px;">Genera y exporta reportes del sistema</p>

            <div class="box-bordered">
                <h6 style="font-weight: 800; color: #222; margin: 0; font-size: 0.95rem;">Configuración de Reporte</h6>
                
                <div class="form-group-reporte">
                    <select class="input-reporte-select">
                        <option value="envios">Reporte de Envíos</option>
                        <option value="operadores">Rendimiento Operadores</option>
                        <option value="clientes">Actividad Clientes</option>
                    </select>
                    
                    <input type="date" class="input-reporte-date" value="2026-02-01"> <input type="date" class="input-reporte-date" value="2026-02-28"> <button class="btn-orange-full">
                        <i class="fa-solid fa-file-chart-column me-2"></i> Generar
                    </button>
                </div>
            </div>

            <div class="box-bordered">
                <h6 style="font-weight: 800; color: #222; margin: 0 0 15px 0; font-size: 0.95rem;">Exportar Reporte</h6>
                <div class="exportar-group">
                    <a href="#" class="btn-outline-dark-report">
                        <i class="fa-regular fa-file-pdf"></i> Exportar como PDF
                    </a>
                    <a href="#" class="btn-outline-dark-report">
                        <i class="fa-regular fa-file-excel"></i> Exportar como Excel
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="box-bordered h-100">
                        <h6 style="font-weight: 800; color: #222; margin: 0 0 15px 0; font-size: 0.95rem;"><i class="fa-solid fa-chart-bar me-1 text-orange"></i> Envíos por Semana</h6>
                        <div class="chart-skeletal-bar">
                            <div class="chart-bar-rect" style="height: 50%;"></div>
                            <div class="chart-bar-rect" style="height: 75%;"></div>
                            <div class="chart-bar-rect" style="height: 100%;"></div>
                            <div class="chart-bar-rect" style="height: 40%;"></div>
                        </div>
                        <div class="chart-legend-row">
                            <span>Sem 1</span>
                            <span>Sem 2</span>
                            <span>Sem 3</span>
                            <span>Sem 4</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="box-bordered h-100 text-center">
                        <h6 style="font-weight: 800; color: #222; margin: 0 0 15px 0; font-size: 0.95rem;"><i class="fa-solid fa-chart-pie me-1 card-envios"></i> Distribución por Estado</h6>
                        
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="pie-skeletal-wrapper">
                                <div class="pie-skeletal-donut">
                                    <div class="pie-skeletal-hole"></div>
                                </div>
                            </div>
                        </div>

                        <div class="donut-details text-start px-3">
                            <div class="status-item text-success"><i class="fa-solid fa-circle me-1"></i> Entregados: 95</div>
                            <div class="status-item text-orange"><i class="fa-solid fa-circle me-1"></i> En tránsito: 45</div>
                            <div class="status-item text-warning"><i class="fa-solid fa-circle me-1"></i> Preparación: 12</div>
                            <div class="status-item text-danger"><i class="fa-solid fa-circle me-1"></i> Incidencias: 4</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-bordered mt-4">
                <h6 style="font-weight: 800; color: #222; margin: 0 0 15px 0; font-size: 0.95rem;">Resumen de Datos</h6>
                <div class="resumen-cards">
                    <div class="resumen-card card-envios">
                        <i class="fa-solid fa-box-open"></i>
                        <div>
                            <strong>156</strong>
                            <small>Total de Envíos</small>
                        </div>
                    </div>
                    <div class="resumen-card card-transito">
                        <i class="fa-solid fa-truck-fast"></i>
                        <div>
                            <strong>45</strong>
                            <small>En Tránsito</small>
                        </div>
                    </div>
                    <div class="resumen-card card-completados">
                        <i class="fa-solid fa-circle-check"></i>
                        <div>
                            <strong>95</strong>
                            <small>Completados</small>
                        </div>
                    </div>
                    <div class="resumen-card card-incidencias">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <div>
                            <strong>4</strong>
                            <small>Incidencias</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>
@endsection