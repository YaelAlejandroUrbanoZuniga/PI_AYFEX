@extends('layouts.app')

@section('content')
<style>
    
    .navbar { display: none !important; } 
    .container.mt-4 { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
    body { background-color: #f4f6f9; color: #333; overflow-x: hidden; font-family: 'Segoe UI', sans-serif; }

    
    .wrapper { display: flex; width: 100%; height: 100vh; overflow: hidden; }

    
    .sidebar {
        width: 260px;
        background: linear-gradient(180deg, #ff5722 0%, #e64a19 100%); 
        display: flex; flex-direction: column; padding: 20px 15px; color: white;
        box-shadow: 4px 0 15px rgba(0,0,0,0.05); z-index: 10;
    }
    .sidebar-logo { text-align: center; margin-bottom: 30px; }
    .sidebar-logo h4 { font-weight: 900; color: #ffffff; margin: 0; letter-spacing: 1px; font-size: 2rem; }
    .sidebar-logo p { font-size: 0.8rem; color: rgba(255,255,255,0.8); margin: 0; }

    
    .nav-item {
        display: flex; align-items: center; padding: 12px 15px; color: #ffffff;
        text-decoration: none; font-weight: 700; border-radius: 12px; margin-bottom: 8px;
        opacity: 0.9; transition: all 0.3s ease;
    }
    .nav-item i { width: 30px; font-size: 1.1rem; }
    .nav-item:hover { background-color: rgba(255, 255, 255, 0.2); opacity: 1; color: #fff; text-decoration: none; }
    .nav-item.active { background-color: #ffffff; color: #ff5722; opacity: 1; box-shadow: 0 4px 10px rgba(0,0,0,0.1); } 

    .sidebar-footer { margin-top: auto; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 15px; }
    .logout-btn { color: #ffffff; text-decoration: none; font-weight: 700; opacity: 0.9; display: flex; align-items: center; transition: 0.3s; }
    .logout-btn:hover { opacity: 1; color: #fff; text-decoration: none; }
    .logout-btn i { margin-right: 10px; }

    
    .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }

    
    .topbar { 
        background: #ff5722; padding: 15px 30px; display: flex; align-items: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05); z-index: 5;
    }
    .search-bar { position: relative; max-width: 400px; width: 100%; }
    .search-bar i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #ff5722; z-index: 2; }
    .search-bar input { 
        width: 100%; padding: 10px 15px 10px 45px; border-radius: 25px; border: none; 
        font-size: 0.95rem; outline: none; background-color: #ffffff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    
    .dashboard-content { padding: 30px; }
    .page-title { margin-bottom: 25px; text-align: center; }
    .page-title h2 { font-weight: 900; margin: 0; color: #222; }
    .page-title p { color: #666; font-size: 0.95rem; }

    
    .content-box { 
        background: #fff; border-radius: 16px; padding: 20px; 
        border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); 
        margin-bottom: 20px;
    }
    .box-title { font-weight: 800; font-size: 1rem; margin-bottom: 15px; color: #222; text-transform: uppercase; letter-spacing: 0.5px; }

    
    .profile-avatar-container { display: flex; justify-content: center; align-items: center; padding: 10px 0; }
    .avatar-circle {
        width: 120px; height: 120px; 
        background: linear-gradient(135deg, #ff8a65 0%, #ff5722 100%);
        border-radius: 50%; display: flex; justify-content: center; align-items: center;
        font-size: 3.5rem; color: white; position: relative; border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .camera-btn {
        position: absolute; bottom: 0; right: 0; background: white; 
        width: 35px; height: 35px; border-radius: 50%; display: flex; 
        justify-content: center; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        color: #666; cursor: pointer; border: 2px solid #fff;
    }

    
    .permission-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f2f2f2; }
    .permission-item:last-child { border-bottom: none; }
    .permission-label { font-weight: 600; color: #555; font-size: 0.9rem; }
    .permission-status { color: #888; font-size: 0.85rem; }

    
    .form-group-profile { margin-bottom: 15px; }
    .form-label-profile { font-weight: 700; font-size: 0.8rem; color: #666; margin-bottom: 5px; text-transform: uppercase; }
    .input-with-icon { position: relative; }
    .input-with-icon i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 1rem; }
    .form-control-profile {
        width: 100%; padding: 10px 15px 10px 45px; height: 45px;
        border-radius: 10px; background-color: #fbfbfb; border: 1px solid #eee;
        font-weight: 600; color: #333; transition: 0.3s; outline: none;
    }
    .form-control-profile:focus { border-color: #ff5722; background-color: #fff; box-shadow: 0 0 0 3px rgba(255,87,34,0.1); }

    
    .info-item { margin-bottom: 10px; }
    .info-label { font-size: 0.8rem; color: #888; margin-bottom: 2px; }
    .info-value { font-weight: 700; color: #333; font-size: 0.95rem; }
    .status-active { color: #16a34a; background: #dcfce7; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }

    
    .btn-save-profile {
        background: #ff5722; border: none; color: white; padding: 12px 30px;
        border-radius: 10px; font-weight: 800; transition: 0.3s; cursor: pointer;
        width: 100%; font-size: 1rem;
    }
    .btn-save-profile:hover { background: #e64a19; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(230,74,25,0.2); }
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
            <a href="{{ route('perfil') }}" class="nav-item {{ request()->routeIs('perfil') ? 'active' : '' }}" style="margin-bottom:15px;">
                <div class="user-avatar" style="width: 30px; height: 30px; background: {{ request()->routeIs('perfil') ? '#ff5722' : '#fff' }}; color: {{ request()->routeIs('perfil') ? '#fff' : '#ff5722' }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 12px;">A</div>
                <span>Perfil</span>
            </a>
            <a href="{{ route('login') }}" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a>
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
            <div class="page-title">
                <h2>Mi Perfil</h2>
                <p>Administra tu información personal y configuración de cuenta</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    
                    <div class="row g-3">
                        
                        <div class="col-md-5">
                            <div class="content-box">
                                <h6 class="box-title text-center">Foto</h6>
                                <div class="profile-avatar-container">
                                    <div class="avatar-circle">
                                        <i class="fa-solid fa-user"></i>
                                        <div class="camera-btn"><i class="fa-solid fa-camera"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="content-box">
                                <h6 class="box-title">Permisos</h6>
                                <div class="permission-item"><span class="permission-label">Envíos</span><span class="permission-status">Completo</span></div>
                                <div class="permission-item"><span class="permission-label">Clientes</span><span class="permission-status">Completo</span></div>
                                <div class="permission-item"><span class="permission-label">Operadores</span><span class="permission-status">Completo</span></div>
                                <div class="permission-item"><span class="permission-label">Reportes</span><span class="permission-status">Completo</span></div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="content-box">
                                <h6 class="box-title">Cuenta</h6>
                                <div class="row info-item align-items-center">
                                    <div class="col-7"><div class="info-label">Registro</div><div class="info-value">15 Ene, 2026</div></div>
                                    <div class="col-5 text-end"><span class="status-active"><i class="fa-solid fa-circle"></i> Activa</span></div>
                                </div>
                            </div>

                            <div class="content-box">
                                <h6 class="box-title">Información Personal</h6>
                                <form>
                                    <div class="form-group-profile">
                                        <label class="form-label-profile">Nombre</label>
                                        <div class="input-with-icon">
                                            <i class="fa-solid fa-user-tag"></i>
                                            <input type="text" class="form-control-profile" value="Admin AYFEX">
                                        </div>
                                    </div>

                                    <div class="form-group-profile">
                                        <label class="form-label-profile">Correo</label>
                                        <div class="input-with-icon">
                                            <i class="fa-solid fa-envelope"></i>
                                            <input type="email" class="form-control-profile" value="admin@ayfex.com">
                                        </div>
                                    </div>

                                    <div class="form-group-profile mb-3">
                                        <label class="form-label-profile">Teléfono</label>
                                        <div class="input-with-icon">
                                            <i class="fa-solid fa-phone"></i>
                                            <input type="text" class="form-control-profile" value="+52 555 123 4567">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn-save-profile shadow">Guardar Cambios</button>
                                </form>
                            </div>
                        </div>
                    </div> </div>
            </div> </div>
    </main>
</div>
@endsection