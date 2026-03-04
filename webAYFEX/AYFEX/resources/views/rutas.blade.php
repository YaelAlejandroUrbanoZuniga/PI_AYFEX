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

   
    .rutas-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px;}
    .ruta-card { background: #fff; border: 2px solid #222; border-radius: 16px; padding: 20px; }
    
    .badge-activa { color: #16a34a; background: #dcfce7; padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;}
    .zone-tag { display: inline-block; background: #f3f4f6; border: 1px solid #e5e7eb; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; color: #4b5563; margin: 4px 4px 4px 0;}
    
    .btn-edit-del { display: flex; gap: 10px; border-top: 1px solid #eee; margin-top: 15px; padding-top: 15px; }
    .btn-edit-del a { flex: 1; text-align: center; padding: 8px; border-radius: 8px; font-weight: 600; text-decoration: none; border: 1px solid #ddd; color: #555; transition: 0.2s;}
    .btn-edit-del a.delete { flex: 0 0 45px; border-color: #fee2e2; color: #ef4444; background: #fff;}
    .btn-edit-del a:hover { background: #f9f9f9; color: #222; border-color: #222;}
    .btn-edit-del a.delete:hover { background: #fee2e2; border-color: #fee2e2;}
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
            <a href="{{ route('rutas') }}" class="nav-item {{ request()->routeIs('rutas') ? 'active' : 'active' }}"><i class="fa-solid fa-route"></i> Rutas</a>
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
                    <h2>Gestión de Rutas</h2>
                    <p>Administra las rutas de distribución</p>
                </div>
                <button class="btn-orange"><i class="fa-solid fa-plus me-2"></i> Crear Ruta</button>
            </div>

            <div class="filter-bar">
                <div class="filter-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Buscar por nombre, zona o operador...">
                </div>
            </div>

            <div class="rutas-grid">
                <div class="ruta-card">
                    <div class="d-flex justify-content-between mb-3">
                        <div><h5 style="font-weight: 800; margin:0; font-size:1.1rem; color:#222;">Ruta Centro</h5><small class="text-muted" style="font-weight:600;">RUT-001</small></div>
                        <span class="badge-activa">Activa</span>
                    </div>
                    <p style="font-size: 0.8rem; color:#666; font-weight:600; margin-bottom: 5px;"><i class="fa-solid fa-location-dot"></i> Zonas Cubiertas</p>
                    <div style="margin-bottom: 15px;">
                        <span class="zone-tag">CDMX Centro</span> <span class="zone-tag">Polanco</span> <span class="zone-tag">Reforma</span>
                    </div>
                    <p style="font-size: 0.85rem; color:#444;"><i class="fa-solid fa-truck text-muted me-2"></i> Operador: <strong>Carlos Ramírez</strong></p>
                    <div class="btn-edit-del">
                        <a href="#"><i class="fa-regular fa-pen-to-square"></i> Editar</a>
                        <a href="#" class="delete"><i class="fa-regular fa-trash-can"></i></a>
                    </div>
                </div>
                
                <div class="ruta-card">
                    <div class="d-flex justify-content-between mb-3">
                        <div><h5 style="font-weight: 800; margin:0; font-size:1.1rem; color:#222;">Ruta Norte</h5><small class="text-muted" style="font-weight:600;">RUT-002</small></div>
                        <span class="badge-activa">Activa</span>
                    </div>
                    <p style="font-size: 0.8rem; color:#666; font-weight:600; margin-bottom: 5px;"><i class="fa-solid fa-location-dot"></i> Zonas Cubiertas</p>
                    <div style="margin-bottom: 15px;">
                        <span class="zone-tag">Monterrey</span> <span class="zone-tag">San Pedro</span> <span class="zone-tag">Santa Catarina</span>
                    </div>
                    <p style="font-size: 0.85rem; color:#444;"><i class="fa-solid fa-truck text-muted me-2"></i> Operador: <strong>María González</strong></p>
                    <div class="btn-edit-del">
                        <a href="#"><i class="fa-regular fa-pen-to-square"></i> Editar</a>
                        <a href="#" class="delete"><i class="fa-regular fa-trash-can"></i></a>
                    </div>
                </div>

                <div class="ruta-card">
                    <div class="d-flex justify-content-between mb-3">
                        <div><h5 style="font-weight: 800; margin:0; font-size:1.1rem; color:#222;">Ruta Bajío</h5><small class="text-muted" style="font-weight:600;">RUT-003</small></div>
                        <span class="badge-activa">Activa</span>
                    </div>
                    <p style="font-size: 0.8rem; color:#666; font-weight:600; margin-bottom: 5px;"><i class="fa-solid fa-location-dot"></i> Zonas Cubiertas</p>
                    <div style="margin-bottom: 15px;">
                        <span class="zone-tag">Querétaro</span> <span class="zone-tag">León</span> <span class="zone-tag">Celaya</span>
                    </div>
                    <p style="font-size: 0.85rem; color:#444;"><i class="fa-solid fa-truck text-muted me-2"></i> Operador: <strong>Pedro Sánchez</strong></p>
                    <div class="btn-edit-del">
                        <a href="#"><i class="fa-regular fa-pen-to-square"></i> Editar</a>
                        <a href="#" class="delete"><i class="fa-regular fa-trash-can"></i></a>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
@endsection