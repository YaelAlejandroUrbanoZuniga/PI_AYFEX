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
    .page-title { margin-bottom: 0; }
    .page-title h2 { font-weight: 900; margin: 0; color: #222; }
    .page-title p { color: #666; font-size: 0.95rem; margin-bottom: 0;}

    .content-box, .table-container { 
        background: #fff; border-radius: 16px; padding: 22px; 
        border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); 
    }
    .btn-orange {
        background-color: #ff5722; color: #fff; border-radius: 8px; 
        font-weight: 600; padding: 10px 24px; transition: 0.3s; border: none;
    }
    .btn-orange:hover { background-color: #e64a19; color: #fff; }

    .filter-bar { display: flex; justify-content: space-between; align-items: center; padding: 15px 22px; margin-bottom: 25px;}
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
    .status-transit { background: #ffedd5; color: #ea580c; }
    .status-delivered { background: #dcfce7; color: #16a34a; }
    .status-prep { background: #fef08a; color: #ca8a04; }
    .status-issue { background: #fee2e2; color: #dc2626; }

    .action-icons { display: flex; gap: 12px; }
    .action-icons a { color: #888; transition: 0.2s; font-size: 1.1rem; }
    .action-icons a:hover { color: #ff5722; }
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
                <input type="text" placeholder="Buscar envíos, clientes, operadores...">
            </div>
        </header>

        <div class="dashboard-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="page-title">
                    <h2>Gestión de Envíos</h2>
                    <p>Administra y monitorea todos los envíos del sistema</p>
                </div>
                <button class="btn-orange" data-bs-toggle="modal" data-bs-target="#modalCrearEnvio"><i class="fa-solid fa-plus me-2"></i> Crear Envío</button>
            </div>

            <div class="content-box filter-bar">
                <div class="filter-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Buscar por id, cliente o destino...">
                </div>
                <div>
                    <select class="filter-select">
                        <option value="">&#xf0b0; Todos los estados</option>
                        <option value="transito">En Tránsito</option>
                        <option value="entregado">Entregado</option>
                        <option value="preparacion">En Preparación</option>
                        <option value="incidencia">Con Incidencia</option>
                    </select>
                </div>
            </div>

            <div class="table-container">
                <h6 class="box-title">Lista de envíos (5)</h6>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle">
                        <thead>
                            <tr style="border-bottom: 2px solid #e9ecef;">
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Origen</th>
                                <th>Destino</th>
                                <th>Peso (kg)</th>
                                <th>Estado</th>
                                <th>Operador</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>ENV-001</strong></td>
                                <td>Comercial López</td>
                                <td>Ciudad de México</td>
                                <td>Monterrey</td>
                                <td>15.5</td>
                                <td><span class="badge-status status-transit">En tránsito</span></td>
                                <td>Carlos Ramírez</td>
                                <td>2026-02-20</td>
                                <td>
                                    <div class="action-icons">
                                        <a href="#"><i class="fa-regular fa-eye"></i></a>
                                        <a href="#"><i class="fa-regular fa-pen-to-square"></i></a>
                                        <a href="#"><i class="fa-solid fa-arrow-right-arrow-left"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>ENV-002</strong></td>
                                <td>Tech Solutions</td>
                                <td>Guadalajara</td>
                                <td>Puebla</td>
                                <td>8.2</td>
                                <td><span class="badge-status status-delivered">Entregado</span></td>
                                <td>María González</td>
                                <td>2026-02-19</td>
                                <td>
                                    <div class="action-icons">
                                        <a href="#"><i class="fa-regular fa-eye"></i></a>
                                        <a href="#"><i class="fa-regular fa-pen-to-square"></i></a>
                                        <a href="#"><i class="fa-solid fa-arrow-right-arrow-left"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>ENV-003</strong></td>
                                <td>Farmacia del Valle</td>
                                <td>Querétaro</td>
                                <td>León</td>
                                <td>3.8</td>
                                <td><span class="badge-status status-prep">Preparación</span></td>
                                <td>Pedro Sánchez</td>
                                <td>2026-02-22</td>
                                <td>
                                    <div class="action-icons">
                                        <a href="#"><i class="fa-regular fa-eye"></i></a>
                                        <a href="#"><i class="fa-regular fa-pen-to-square"></i></a>
                                        <a href="#"><i class="fa-solid fa-arrow-right-arrow-left"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>ENV-004</strong></td>
                                <td>AutoPartes Express</td>
                                <td>Tijuana</td>
                                <td>Mexicali</td>
                                <td>22</td>
                                <td><span class="badge-status status-issue">Incidencia</span></td>
                                <td>Luis Hernández</td>
                                <td>2026-02-21</td>
                                <td>
                                    <div class="action-icons">
                                        <a href="#"><i class="fa-regular fa-eye"></i></a>
                                        <a href="#"><i class="fa-regular fa-pen-to-square"></i></a>
                                        <a href="#"><i class="fa-solid fa-arrow-right-arrow-left"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>ENV-005</strong></td>
                                <td>Librería Académica</td>
                                <td>Mérida</td>
                                <td>Cancún</td>
                                <td>12.5</td>
                                <td><span class="badge-status status-transit">En tránsito</span></td>
                                <td>Ana Martínez</td>
                                <td>2026-02-22</td>
                                <td>
                                    <div class="action-icons">
                                        <a href="#"><i class="fa-regular fa-eye"></i></a>
                                        <a href="#"><i class="fa-regular fa-pen-to-square"></i></a>
                                        <a href="#"><i class="fa-solid fa-arrow-right-arrow-left"></i></a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="modalCrearEnvio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 800;">Registrar Nuevo Envío</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Cliente</label>
                        <input type="text" class="form-control" name="cliente">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Origen</label>
                        <input type="text" class="form-control" name="origen">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Destino</label>
                        <input type="text" class="form-control" name="destino">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Peso (kg)</label>
                        <input type="number" class="form-control" name="peso">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn-orange">Guardar Envío</button>
            </div>
        </div>
    </div>
</div>
@endsection