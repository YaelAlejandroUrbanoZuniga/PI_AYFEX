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

    
    .btn-orange {
        background-color: #ff5722; color: #fff; border-radius: 8px; 
        font-weight: 600; padding: 10px 24px; transition: 0.3s; border: none;
    }
    .btn-orange:hover { background-color: #e64a19; color: #fff; }

    .filter-bar { background: #fff; border-radius: 12px; padding: 15px 22px; margin-bottom: 30px; border: 2px solid #222; }
    .filter-search { position: relative; width: 100%; }
    .filter-search i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; }
    .filter-search input { 
        width: 100%; padding: 10px 15px 10px 40px; border-radius: 8px; 
        border: none; font-size: 0.9rem; outline: none; background: transparent;
    }

    
    .operator-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
    }
    .operator-card {
        background: #fff;
        border: 2px solid #222;
        border-radius: 16px;
        padding: 20px;
        position: relative;
    }
    .op-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; }
    .op-profile { display: flex; align-items: center; gap: 12px; }
    .op-avatar { 
        width: 48px; height: 48px; border-radius: 50%; background: #ff5722; 
        color: white; display: flex; align-items: center; justify-content: center; 
        font-weight: 900; font-size: 1.2rem; 
    }
    .op-info h5 { margin: 0; font-size: 1.05rem; font-weight: 800; color: #222; }
    .op-info span { font-size: 0.8rem; color: #888; font-weight: 600; }
    
    .op-status { padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;}
    .status-en-ruta { border: 1px solid #ff5722; color: #ff5722; background: #fff0eb; }
    .status-disponible { border: 1px solid #16a34a; color: #16a34a; background: #dcfce7; }

    .op-details { margin-bottom: 20px; }
    .op-details p { margin: 8px 0; font-size: 0.85rem; color: #555; display: flex; align-items: center; }
    .op-details i { width: 25px; color: #999; font-size: 0.9rem; }

    .op-stats { display: flex; justify-content: space-between; font-size: 0.9rem; font-weight: 600; color: #555; margin-bottom: 20px; }
    .op-stats span.count { color: #ff5722; font-weight: 900; font-size: 1.1rem; }

    .op-actions { display: flex; gap: 10px; border-top: 1px solid #eee; padding-top: 15px; }
    .btn-edit { 
        flex: 1; background: #fff; border: 1px solid #ddd; padding: 8px; border-radius: 8px; 
        font-weight: 600; color: #555; display: flex; justify-content: center; align-items: center; gap: 8px; 
        transition: 0.2s; text-decoration: none; font-size: 0.9rem;
    }
    .btn-edit:hover { background: #f9f9f9; color: #222; border-color: #222;}
    .btn-delete { 
        width: 42px; background: #fff; border: 1px solid #fee2e2; color: #ef4444; 
        border-radius: 8px; display: flex; justify-content: center; align-items: center; 
        transition: 0.2s; text-decoration: none;
    }
    .btn-delete:hover { background: #fee2e2; }

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
                    <h2>Gestión de Operadores</h2>
                    <p>Administra tu equipo de operadores y conductores</p>
                </div>
                <button class="btn-orange"><i class="fa-solid fa-plus me-2"></i> Agregar Operador</button>
            </div>

            <div class="filter-bar">
                <div class="filter-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Buscar por nombre, teléfono o vehículos...">
                </div>
            </div>

            <div class="operator-grid">
                
                <div class="operator-card">
                    <div class="op-header">
                        <div class="op-profile">
                            <div class="op-avatar">CR</div>
                            <div class="op-info">
                                <h5>Carlos Ramírez</h5>
                                <span>OP-001</span>
                            </div>
                        </div>
                        <span class="op-status status-en-ruta">En ruta</span>
                    </div>
                    <div class="op-details">
                        <p><i class="fa-solid fa-phone"></i> 555-0001</p>
                        <p><i class="fa-solid fa-truck"></i> Camioneta - ABC-123</p>
                    </div>
                    <div class="op-stats">
                        <span>Envíos asignados</span>
                        <span class="count">3</span>
                    </div>
                    <div class="op-actions">
                        <a href="#" class="btn-edit"><i class="fa-regular fa-pen-to-square"></i> Editar</a>
                        <a href="#" class="btn-delete"><i class="fa-regular fa-trash-can"></i></a>
                    </div>
                </div>

                <div class="operator-card">
                    <div class="op-header">
                        <div class="op-profile">
                            <div class="op-avatar">MG</div>
                            <div class="op-info">
                                <h5>María González</h5>
                                <span>OP-002</span>
                            </div>
                        </div>
                        <span class="op-status status-disponible">Disponible</span>
                    </div>
                    <div class="op-details">
                        <p><i class="fa-solid fa-phone"></i> 555-0002</p>
                        <p><i class="fa-solid fa-truck"></i> Van - XYZ-456</p>
                    </div>
                    <div class="op-stats">
                        <span>Envíos asignados</span>
                        <span class="count" style="color:#16a34a;">0</span>
                    </div>
                    <div class="op-actions">
                        <a href="#" class="btn-edit"><i class="fa-regular fa-pen-to-square"></i> Editar</a>
                        <a href="#" class="btn-delete"><i class="fa-regular fa-trash-can"></i></a>
                    </div>
                </div>

                <div class="operator-card">
                    <div class="op-header">
                        <div class="op-profile">
                            <div class="op-avatar">PS</div>
                            <div class="op-info">
                                <h5>Pedro Sánchez</h5>
                                <span>OP-003</span>
                            </div>
                        </div>
                        <span class="op-status status-en-ruta">En ruta</span>
                    </div>
                    <div class="op-details">
                        <p><i class="fa-solid fa-phone"></i> 555-0003</p>
                        <p><i class="fa-solid fa-truck"></i> Camión - DEF-789</p>
                    </div>
                    <div class="op-stats">
                        <span>Envíos asignados</span>
                        <span class="count">5</span>
                    </div>
                    <div class="op-actions">
                        <a href="#" class="btn-edit"><i class="fa-regular fa-pen-to-square"></i> Editar</a>
                        <a href="#" class="btn-delete"><i class="fa-regular fa-trash-can"></i></a>
                    </div>
                </div>

                <div class="operator-card">
                    <div class="op-header">
                        <div class="op-profile">
                            <div class="op-avatar">LH</div>
                            <div class="op-info">
                                <h5>Luis Hernández</h5>
                                <span>OP-004</span>
                            </div>
                        </div>
                        <span class="op-status status-en-ruta">En ruta</span>
                    </div>
                    <div class="op-details">
                        <p><i class="fa-solid fa-phone"></i> 555-0004</p>
                        <p><i class="fa-solid fa-truck"></i> Camioneta - GHI-012</p>
                    </div>
                    <div class="op-stats">
                        <span>Envíos asignados</span>
                        <span class="count">2</span>
                    </div>
                    <div class="op-actions">
                        <a href="#" class="btn-edit"><i class="fa-regular fa-pen-to-square"></i> Editar</a>
                        <a href="#" class="btn-delete"><i class="fa-regular fa-trash-can"></i></a>
                    </div>
                </div>

                <div class="operator-card">
                    <div class="op-header">
                        <div class="op-profile">
                            <div class="op-avatar">AM</div>
                            <div class="op-info">
                                <h5>Ana Martínez</h5>
                                <span>OP-005</span>
                            </div>
                        </div>
                        <span class="op-status status-en-ruta">En ruta</span>
                    </div>
                    <div class="op-details">
                        <p><i class="fa-solid fa-phone"></i> 555-0005</p>
                        <p><i class="fa-solid fa-truck"></i> Van - JKL-345</p>
                    </div>
                    <div class="op-stats">
                        <span>Envíos asignados</span>
                        <span class="count">4</span>
                    </div>
                    <div class="op-actions">
                        <a href="#" class="btn-edit"><i class="fa-regular fa-pen-to-square"></i> Editar</a>
                        <a href="#" class="btn-delete"><i class="fa-regular fa-trash-can"></i></a>
                    </div>
                </div>

            </div> </div>
    </main>
</div>
@endsection