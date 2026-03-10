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
        display: flex; justify-content: space-between; align-items: center;
        padding: 12px 24px; border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    .header-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
    .brand-icon {
        width: 40px; height: 40px; background: #ffffff; border-radius: 10px;
        display: flex; justify-content: center; align-items: center; color: #ff5722; font-size: 1.2rem;
    }
    .brand-text { display: flex; flex-direction: column; }
    .brand-name { font-weight: 900; font-size: 1.2rem; color: #ffffff; line-height: 1.1; letter-spacing: 1px;}
    .brand-slogan { font-size: 0.75rem; color: rgba(255, 255, 255, 0.85); }

    .header-search { flex: 1; max-width: 600px; margin: 0 2rem; position: relative; }
    .header-search i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #ff5722; z-index: 2; }
    .header-search input {
        width: 100%; padding: 10px 15px 10px 45px; border: none; border-radius: 25px;
        background-color: #ffffff; font-size: 0.95rem; color: #333; outline: none; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
    
    .dropdown-menu { border: none; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); border-radius: 0 8px 8px 8px; padding: 8px 0; margin-top: 0 !important;}
    .dropdown-item { padding: 10px 20px; font-size: 0.9rem; color: #444; font-weight: 500;}
    .dropdown-item:hover { background-color: #fffaf5; color: #ff5722; }

    /* =========================================
       ESTILOS DE LA PÁGINA (REPORTES)
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
    .page-title p { color: #666; font-size: 0.95rem; margin-top: 5px; margin-bottom: 0;}

    .box-bordered { 
        background: #fff; 
        border: 2px solid #222; 
        border-radius: 12px; 
        padding: 20px; 
        margin-bottom: 20px; 
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .box-bordered:hover { box-shadow: 0 8px 20px rgba(0,0,0,0.05); }

    .form-group-reporte {
        display: flex; gap: 20px; align-items: center; margin-top: 15px; flex-wrap: wrap;
    }

    .input-reporte-select {
        flex: 1.5; min-width: 200px; border: 1px solid #ddd; border-radius: 8px;
        background: #fdfdfd; padding: 10px; outline: none; cursor: pointer; transition: border-color 0.2s;
    }
    .input-reporte-select:focus { border-color: #ff5722; }

    .input-reporte-date {
        flex: 1; min-width: 150px; border: 1px solid #ddd; border-radius: 8px;
        background: #fdfdfd; padding: 10px; cursor: pointer; outline: none; transition: border-color 0.2s;
    }
    .input-reporte-date:focus { border-color: #ff5722; }

    .btn-orange-full {
        background-color: #ff5722; color: #fff; border-radius: 8px; font-weight: 600; 
        padding: 10px 30px; border: none; display: inline-flex; align-items: center; transition: 0.3s;
    }
    .btn-orange-full:hover { background-color: #e64a19; }

    .chart-skeletal-bar {
        height: 150px; display: flex; justify-content: space-around; align-items: flex-end;
        border-bottom: 2px solid #eee; padding-bottom: 10px; margin-top: 20px;
    }

    .chart-bar-rect {
        width: 30px; background: linear-gradient(to top, #ff5722, #ff9e7f);
        border-radius: 4px; transition: height 1s ease-out, transform 0.2s;
    }
    .chart-bar-rect:hover { transform: scaleY(1.05); filter: brightness(1.1); }

    .chart-legend-row {
        display: flex; justify-content: center; gap: 20px; font-size: 0.8rem;
        color: #888; font-weight: 600; margin-top: 10px; width: 100%;
    }

    .pie-skeletal-wrapper { position: relative; width: 140px; height: 140px; margin: 15px auto; }
    
    .pie-skeletal-donut {
        width: 100%; height: 100%; border-radius: 50%; position: relative;
        background: conic-gradient(
            #10b981 0% 60%,   
            #ff5722 60% 80%,  
            #f59e0b 80% 95%,  
            #ef4444 95% 100%  
        );
        box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
    }
    
    .pie-skeletal-hole {
        position: absolute; top: 25%; left: 25%; width: 50%; height: 50%;
        background: #fff; border-radius: 50%; box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .donut-details { font-size: 0.8rem; margin-top: 15px; }
    .status-item { margin-bottom: 5px; display: flex; align-items: center; font-weight: 600; }
    .text-orange { color: #ff5722 !important; }

    .resumen-cards {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px; margin-top: 15px;
    }
    
    .resumen-card {
        padding: 15px 20px; border: 1px solid #eee; border-radius: 8px; display: flex;
        align-items: center; gap: 12px; transition: 0.2s;
    }
    .resumen-card:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }

    .resumen-card i {
        font-size: 1.2rem; width: 40px; height: 40px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
    }

    .resumen-card strong { font-size: 1.1rem; font-weight: 900; color: #222; display: block; }
    .resumen-card small { color: #888; font-weight: 600; font-size: 0.75rem; }

    .card-envios { border-bottom: 3px solid #16a34a; }
    .card-envios i { background: #dcfce7; color: #16a34a; }
    
    .card-transito { border-bottom: 3px solid #ff5722; }
    .card-transito i { background: #fff0eb; color: #ff5722; }
    
    .card-completados { border-bottom: 3px solid #007bff; }
    .card-completados i { background: #e0f2fe; color: #007bff; }
    
    .card-incidencias { border-bottom: 3px solid #ef4444; }
    .card-incidencias i { background: #fee2e2; color: #ef4444; }

    .exportar-group { display: flex; gap: 15px; flex-wrap: wrap; }
    .btn-outline-dark-report {
        flex: 1; min-width: 180px; border: 2px solid #222; background: #fff; color: #222;
        font-weight: 700; border-radius: 8px; padding: 10px; text-align: center; text-decoration: none;
        display: inline-flex; align-items: center; justify-content: center; gap: 10px; font-size: 0.9rem; transition: 0.3s;
    }
    .btn-outline-dark-report:hover { background: #222; color: #fff; }
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


        <div class="header-search d-none d-md-block">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Buscar número de guía, cliente o ciudad...">
        </div>

        <div class="header-actions">
            <a href="{{ route('perfil') }}" class="user-profile">
                <div class="user-info d-none d-sm-block">
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

    <div class="header-nav flex-wrap">
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
                <li><a class="dropdown-item" href="{{ route('reportes') }}" style="{{ request()->routeIs('reportes') ? 'color: #ff5722; font-weight: bold; background-color: #fffaf5;' : '' }}"><i class="fa-solid fa-file-lines me-2"></i> Reportes</a></li>
                <li><a class="dropdown-item" href="{{ route('incidencias') }}"><i class="fa-solid fa-circle-exclamation me-2"></i> Incidencias</a></li>
            </ul>
        </div>
    </div>
</header>

<div class="main-wrapper">
    <div class="page-title">
        <h2>Reportes</h2>
        <p>Genera y exporta reportes del sistema</p>
    </div>

    <div class="box-bordered">
        <h6 style="font-weight: 800; color: #222; margin: 0; font-size: 0.95rem;">Configuración de Reporte</h6>
        
        <div class="form-group-reporte">
            <select class="input-reporte-select">
                <option value="envios">Reporte de Envíos</option>
                <option value="operadores">Rendimiento Operadores</option>
                <option value="clientes">Actividad Clientes</option>
            </select>
            
            <input type="date" class="input-reporte-date" value="2026-02-01"> 
            <input type="date" class="input-reporte-date" value="2026-02-28"> 
            
            <button class="btn-orange-full">
                <i class="fa-solid fa-file-chart-column me-2"></i> Generar
            </button>
        </div>
    </div>

    <div class="box-bordered">
        <h6 style="font-weight: 800; color: #222; margin: 0 0 15px 0; font-size: 0.95rem;">Exportar Reporte</h6>
        <div class="exportar-group">
            <a href="#" class="btn-outline-dark-report">
                <i class="fa-regular fa-file-pdf text-danger"></i> Exportar como PDF
            </a>
            <a href="#" class="btn-outline-dark-report">
                <i class="fa-regular fa-file-excel text-success"></i> Exportar como Excel
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="box-bordered h-100 m-0">
                <h6 style="font-weight: 800; color: #222; margin: 0 0 15px 0; font-size: 0.95rem;">
                    <i class="fa-solid fa-chart-bar me-1 text-orange"></i> Envíos por Semana
                </h6>
                <div class="chart-skeletal-bar">
                    <div class="chart-bar-rect" style="height: 50%;"></div>
                    <div class="chart-bar-rect" style="height: 75%;"></div>
                    <div class="chart-bar-rect" style="height: 100%;"></div>
                    <div class="chart-bar-rect" style="height: 40%;"></div>
                </div>
                <div class="chart-legend-row d-flex justify-content-around">
                    <span>Sem 1</span>
                    <span>Sem 2</span>
                    <span>Sem 3</span>
                    <span>Sem 4</span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <div class="box-bordered h-100 m-0 text-center">
                <h6 style="font-weight: 800; color: #222; margin: 0 0 15px 0; font-size: 0.95rem;">
                    <i class="fa-solid fa-chart-pie me-1 text-orange"></i> Distribución por Estado
                </h6>
                
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <div class="pie-skeletal-wrapper">
                        <div class="pie-skeletal-donut">
                            <div class="pie-skeletal-hole"></div>
                        </div>
                    </div>

                    <div class="donut-details text-start px-3 mt-3 w-100" style="max-width: 250px;">
                        <div class="status-item text-success"><i class="fa-solid fa-circle me-2"></i> Entregados: 95</div>
                        <div class="status-item text-orange"><i class="fa-solid fa-circle me-2"></i> En tránsito: 45</div>
                        <div class="status-item text-warning"><i class="fa-solid fa-circle me-2"></i> Preparación: 12</div>
                        <div class="status-item text-danger"><i class="fa-solid fa-circle me-2"></i> Incidencias: 4</div>
                    </div>
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
@endsection