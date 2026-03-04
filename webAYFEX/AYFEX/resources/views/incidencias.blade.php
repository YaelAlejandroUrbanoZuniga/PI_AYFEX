@extends('layouts.app')

@section('content')
<style>
    
    .navbar { display: none !important; } 
    .container.mt-4 { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
    body { background-color: #f4f6f9; color: #333; overflow-x: hidden; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

    
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
    .nav-item:hover { background-color: rgba(255, 255, 255, 0.2); opacity: 1; text-decoration: none; color: white; }
    .nav-item.active { 
        background-color: #ffffff; 
        color: #ff5722; 
        opacity: 1;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
    } 

    .sidebar-footer { margin-top: auto; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 15px; }
    .logout-btn { color: #ffffff; text-decoration: none; font-weight: 700; opacity: 0.9; display: flex; align-items: center; transition: 0.3s; }
    .logout-btn:hover { opacity: 1; color: white; }

    
    .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }

    
    .topbar { 
        background: #ff5722; 
        padding: 15px 30px; 
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        z-index: 5;
    }
    .search-bar { position: relative; max-width: 400px; width: 100%; }
    .search-bar i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #ff5722; z-index: 2; }
    .search-bar input { 
        width: 100%; padding: 10px 15px 10px 45px; border-radius: 25px; 
        border: none; font-size: 0.95rem; outline: none; 
        background-color: #ffffff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

   
    .dashboard-content { padding: 30px; }
    .header-actions { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
    .btn-register { background-color: #ff5722; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-weight: bold; transition: 0.3s; }
    .btn-register:hover { background-color: #e64a19; color: white; transform: translateY(-2px); }

    .stat-card { 
        background: #fff; border-radius: 16px; padding: 22px; 
        border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); 
        position: relative; height: 100%;
    }
    .stat-card h6 { color: #777; font-size: 0.85rem; font-weight: 600; margin-bottom: 10px; }
    .stat-card h3 { font-weight: 900; margin: 0; color: #222; font-size: 2rem; }
    .stat-icon { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
    
    .icon-orange-light { background: #fff7ed; color: #f97316; }
    .icon-red-light { background: #fef2f2; color: #ef4444; }
    .icon-green-light { background: #f0fdf4; color: #22c55e; }

    .table-container { background: #fff; border-radius: 16px; padding: 25px; margin-top: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    .table-header-tools { display: flex; gap: 15px; margin-bottom: 20px; }
    .filter-select { border-radius: 10px; border: 1px solid #ddd; padding: 8px 15px; font-size: 0.9rem; outline: none; }

    .badge-status { padding: 5px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
    .status-pending { background: #fee2e2; color: #dc2626; }
    .status-resolved { background: #dcfce7; color: #16a34a; }

    .action-icons i { cursor: pointer; margin: 0 5px; font-size: 1.1rem; transition: 0.2s; }
    .action-icons i:hover { opacity: 0.7; }
    .text-view { color: #666; }
    .text-check { color: #16a34a; }
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
            <div class="user-profile" style="display: flex; align-items: center; margin-bottom: 15px;">
                <div class="user-avatar" style="width: 35px; height: 35px; background: #fff; color: #ff5722; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 10px;">A</div>
                <h6 style="margin:0; color:white; font-size:0.9rem;">Admin AYFEX</h6>
            </div>
            <a href="{{ route('login') }}" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="search-bar">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Buscar por ID, envío o tipo...">
            </div>
        </header>

        <div class="dashboard-content">
            <div class="header-actions">
                <div class="page-title">
                    <h2 style="font-weight: 900;">Gestión de Incidencias</h2>
                    <p>Monitorea y resuelve problemas en los envíos</p>
                </div>
                <button class="btn-register"><i class="fa-solid fa-plus me-2"></i> Registrar Incidencia</button>
            </div>

            <div class="row g-4 mb-2">
                <div class="col-md-4">
                    <div class="stat-card">
                        <h6>Total Incidencias</h6>
                        <h3>3</h3>
                        <div class="stat-icon icon-orange-light"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <h6>Pendientes</h6>
                        <h3>2</h3>
                        <div class="stat-icon icon-red-light"><i class="fa-solid fa-circle-exclamation"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <h6>Resueltas</h6>
                        <h3>1</h3>
                        <div class="stat-icon icon-green-light"><i class="fa-solid fa-circle-check"></i></div>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 style="font-weight: 800; margin: 0;">Lista de Incidencias (3)</h5>
                    <select class="filter-select">
                        <option>Todos los estados</option>
                        <option>Pendiente</option>
                        <option>Resuelto</option>
                    </select>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-secondary small fw-bold">ID</th>
                                <th class="text-secondary small fw-bold">ENVÍO</th>
                                <th class="text-secondary small fw-bold">TIPO</th>
                                <th class="text-secondary small fw-bold">DESCRIPCIÓN</th>
                                <th class="text-secondary small fw-bold">ESTADO</th>
                                <th class="text-secondary small fw-bold">RESPONSABLE</th>
                                <th class="text-secondary small fw-bold">FECHA</th>
                                <th class="text-secondary small fw-bold">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">INC-001</td>
                                <td>ENV-004</td>
                                <td>Dirección incorrecta</td>
                                <td class="text-muted small">El destinatario proporcionó una dirección inc...</td>
                                <td><span class="badge-status status-pending">Pendiente</span></td>
                                <td>Luis Hernández</td>
                                <td>2026-02-21</td>
                                <td class="action-icons">
                                    <i class="fa-regular fa-eye text-view"></i>
                                    <i class="fa-regular fa-circle-check text-check"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">INC-002</td>
                                <td>ENV-001</td>
                                <td>Retraso en tránsito</td>
                                <td class="text-muted small">Tráfico intenso en autopista</td>
                                <td><span class="badge-status status-resolved">Resuelto</span></td>
                                <td>Carlos Ramírez</td>
                                <td>2026-02-20</td>
                                <td class="action-icons">
                                    <i class="fa-regular fa-eye text-view"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">INC-003</td>
                                <td>ENV-003</td>
                                <td>Paquete dañado</td>
                                <td class="text-muted small">Daños menores en el empaque</td>
                                <td><span class="badge-status status-pending">Pendiente</span></td>
                                <td>Pedro Sánchez</td>
                                <td>2026-02-22</td>
                                <td class="action-icons">
                                    <i class="fa-regular fa-eye text-view"></i>
                                    <i class="fa-regular fa-circle-check text-check"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection