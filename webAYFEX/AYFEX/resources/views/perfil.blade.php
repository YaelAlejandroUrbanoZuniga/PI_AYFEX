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
    
    .user-profile { display: flex; align-items: center; gap: 12px; text-decoration: none; padding: 5px; border-radius: 8px; transition: background-color 0.3s;}
    .user-profile:hover { background-color: rgba(255, 255, 255, 0.1); }
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
       ESTILOS DE LA PÁGINA (PERFIL)
       ========================================= */
    .navbar { display: none !important; } 
    .container.mt-4 { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
    body { background-color: #f4f6f9; color: #333; overflow-x: hidden; font-family: 'Segoe UI', sans-serif; margin: 0;}

    .main-wrapper { 
        padding: 40px 30px; 
        max-width: 1200px; 
        margin: 0 auto; 
    }

    .page-title { margin-bottom: 30px; text-align: center; }
    .page-title h2 { font-weight: 900; margin: 0; color: #222; font-size: 2.2rem;}
    .page-title p { color: #666; font-size: 1rem; margin-top: 5px;}

    .content-box { 
        background: #fff; border-radius: 16px; padding: 25px; 
        border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); 
        margin-bottom: 25px; transition: transform 0.2s, box-shadow 0.2s;
    }
    .content-box:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.06); transform: translateY(-2px); }
    .box-title { font-weight: 800; font-size: 1rem; margin-bottom: 20px; color: #222; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #f4f6f9; padding-bottom: 10px;}

    /* Perfil y Avatar */
    .profile-avatar-container { display: flex; justify-content: center; align-items: center; padding: 10px 0 20px; }
    .avatar-circle {
        width: 130px; height: 130px; 
        background: linear-gradient(135deg, #ff8a65 0%, #ff5722 100%);
        border-radius: 50%; display: flex; justify-content: center; align-items: center;
        font-size: 4rem; color: white; position: relative; border: 5px solid #fff; box-shadow: 0 8px 20px rgba(255, 87, 34, 0.25);
    }
    .camera-btn {
        position: absolute; bottom: 0; right: 5px; background: white; 
        width: 40px; height: 40px; border-radius: 50%; display: flex; 
        justify-content: center; align-items: center; box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        color: #ff5722; cursor: pointer; border: 2px solid #fff; transition: 0.3s;
    }
    .camera-btn:hover { background: #f4f6f9; transform: scale(1.1); }

    /* Permisos */
    .permission-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f2f2f2; }
    .permission-item:last-child { border-bottom: none; }
    .permission-label { font-weight: 600; color: #555; font-size: 0.95rem; }
    .permission-status { color: #16a34a; font-size: 0.85rem; font-weight: bold; background: #dcfce7; padding: 4px 12px; border-radius: 20px;}

    /* Formulario */
    .form-group-profile { margin-bottom: 20px; }
    .form-label-profile { font-weight: 700; font-size: 0.85rem; color: #666; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;}
    .input-with-icon { position: relative; }
    .input-with-icon i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #ff5722; font-size: 1.1rem; }
    .form-control-profile {
        width: 100%; padding: 12px 15px 12px 48px; height: 50px;
        border-radius: 12px; background-color: #f8fafc; border: 1px solid #e2e8f0;
        font-weight: 600; color: #333; transition: all 0.3s; outline: none; font-size: 0.95rem;
    }
    .form-control-profile:focus { border-color: #ff5722; background-color: #fff; box-shadow: 0 0 0 4px rgba(255,87,34,0.1); }

    /* Info */
    .info-item { margin-bottom: 10px; }
    .info-label { font-size: 0.85rem; color: #888; margin-bottom: 2px; text-transform: uppercase; font-weight: 600;}
    .info-value { font-weight: 700; color: #333; font-size: 1rem; }
    .status-active { color: #16a34a; background: #dcfce7; padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 5px rgba(22, 163, 74, 0.1);}

    /* Botón Guardar */
    .btn-save-profile {
        background: linear-gradient(135deg, #ff5722 0%, #e64a19 100%); border: none; color: white; padding: 14px 30px;
        border-radius: 12px; font-weight: 800; transition: 0.3s; cursor: pointer;
        width: 100%; font-size: 1.05rem; margin-top: 10px; text-transform: uppercase; letter-spacing: 1px;
    }
    .btn-save-profile:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(230,74,25,0.3); }
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
            <input type="text" placeholder="Buscar envíos, clientes, operadores...">
        </div>

        <div class="header-actions">
            <a href="{{ route('perfil') }}" class="user-profile" style="background-color: rgba(255, 255, 255, 0.15);">
                <div class="user-info d-none d-sm-block">
                    <div class="user-name">Admin AYFEX</div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar">A</div>
            </a>
            <a href="{{ route('login') }}" class="user-profile" style="margin-left: 5px;" title="Cerrar Sesión">
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
                <li><a class="dropdown-item" href="{{ route('reportes') }}"><i class="fa-solid fa-file-lines me-2"></i> Reportes</a></li>
                <li><a class="dropdown-item" href="{{ route('incidencias') }}"><i class="fa-solid fa-circle-exclamation me-2"></i> Incidencias</a></li>
            </ul>
        </div>
    </div>
</header>

<div class="main-wrapper">
    <div class="page-title">
        <h2>Mi Perfil</h2>
        <p>Administra tu información personal y configuración de cuenta</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-11 col-lg-9">
            <div class="row g-4">
                
                <div class="col-md-5">
                    <div class="content-box text-center">
                        <h6 class="box-title">Foto de Perfil</h6>
                        <div class="profile-avatar-container">
                            <div class="avatar-circle">
                                <i class="fa-solid fa-user"></i>
                                <div class="camera-btn" title="Actualizar foto"><i class="fa-solid fa-camera"></i></div>
                            </div>
                        </div>
                        <p class="text-muted small mt-2 mb-0">Permitido JPG, GIF o PNG. Max tamaño de 2MB</p>
                    </div>

                    <div class="content-box">
                        <h6 class="box-title">Nivel de Acceso</h6>
                        <div class="permission-item">
                            <span class="permission-label"><i class="fa-solid fa-box text-secondary me-2"></i> Envíos</span>
                            <span class="permission-status">Completo</span>
                        </div>
                        <div class="permission-item">
                            <span class="permission-label"><i class="fa-solid fa-users text-secondary me-2"></i> Clientes</span>
                            <span class="permission-status">Completo</span>
                        </div>
                        <div class="permission-item">
                            <span class="permission-label"><i class="fa-solid fa-truck text-secondary me-2"></i> Operadores</span>
                            <span class="permission-status">Completo</span>
                        </div>
                        <div class="permission-item">
                            <span class="permission-label"><i class="fa-solid fa-file-lines text-secondary me-2"></i> Reportes</span>
                            <span class="permission-status">Completo</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="content-box">
                        <h6 class="box-title">Estado de la Cuenta</h6>
                        <div class="row info-item align-items-center mt-3">
                            <div class="col-7">
                                <div class="info-label">Fecha de Registro</div>
                                <div class="info-value"><i class="fa-regular fa-calendar text-muted me-2"></i> 15 Ene, 2026</div>
                            </div>
                            <div class="col-5 text-end">
                                <span class="status-active"><i class="fa-solid fa-circle text-success" style="font-size: 0.5rem;"></i> Activa</span>
                            </div>
                        </div>
                    </div>

                    <div class="content-box">
                        <h6 class="box-title">Información Personal</h6>
                        <form action="#" method="POST" class="mt-4">
                            <div class="form-group-profile">
                                <label class="form-label-profile">Nombre Completo</label>
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-user-tag"></i>
                                    <input type="text" class="form-control-profile" value="Admin AYFEX">
                                </div>
                            </div>

                            <div class="form-group-profile">
                                <label class="form-label-profile">Correo Electrónico</label>
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-envelope"></i>
                                    <input type="email" class="form-control-profile" value="admin@ayfex.com">
                                </div>
                            </div>

                            <div class="form-group-profile mb-4">
                                <label class="form-label-profile">Número de Teléfono</label>
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-phone"></i>
                                    <input type="text" class="form-control-profile" value="+52 555 123 4567">
                                </div>
                            </div>

                            <button type="submit" class="btn-save-profile shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Guardar Cambios
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection