@extends('layouts.app')

@section('content')
<style>
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

    body { background-color: #f4f6f9; color: #333; overflow-x: hidden; margin: 0; }
    .navbar { display: none !important; }
    .container.mt-4 { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }

    .main-wrapper { 
        padding: 30px; 
        max-width: 1200px; 
        margin: 0 auto; 
    }

    .page-title { margin-bottom: 0; }
    .page-title h2 { font-weight: 900; margin: 0; color: #222; }
    .page-title p { color: #666; font-size: 0.95rem; margin-bottom: 0; }

    .content-box { 
        background: #fff; border-radius: 16px; padding: 22px; 
        border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); 
    }
    
    .btn-outline-gray {
        background-color: #fff; color: #444; border-radius: 8px; 
        font-weight: 600; padding: 10px 24px; transition: 0.3s; border: 1px solid #ddd;
        text-decoration: none; display: flex; align-items: center; gap: 8px;
    }
    .btn-outline-gray:hover { background-color: #f8f9fa; color: #222; }

    .filter-bar { display: flex; justify-content: space-between; align-items: center; padding: 15px 22px; margin-bottom: 25px;}
    .filter-tabs { display: flex; gap: 15px; }
    .filter-tab { 
        padding: 8px 16px; border-radius: 20px; font-size: 0.9rem; font-weight: 600; cursor: pointer; transition: 0.3s; border: 1px solid transparent; background: transparent; color: #666;
    }
    .filter-tab.active { background: #fffaf5; color: #ff5722; border-color: #ffd0b8; }
    .filter-tab:hover:not(.active) { background: #f8f9fa; }

    .notif-list { display: flex; flex-direction: column; }
    .notif-card {
        display: flex; align-items: flex-start; padding: 24px; border-bottom: 1px solid #f0f0f0; gap: 20px; transition: 0.2s;
    }
    .notif-card:last-child { border-bottom: none; }
    .notif-card:hover { background-color: #fcfcfc; }
    .notif-card.unread { background-color: #fffaf5; position: relative; }
    .notif-card.unread::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background-color: #ff5722; border-radius: 4px 0 0 4px;
    }

    .notif-icon-large {
        width: 54px; height: 54px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 1.4rem; flex-shrink: 0;
    }
    .bg-light-warning { background-color: #fef08a; color: #ca8a04; }
    .bg-light-success { background-color: #dcfce7; color: #16a34a; }
    .bg-light-danger { background-color: #fee2e2; color: #dc2626; }
    .bg-light-info { background-color: #e0f2fe; color: #0284c7; }

    .notif-content-wrap { flex: 1; }
    .notif-card-title { font-size: 1.1rem; font-weight: 800; color: #222; margin-bottom: 6px; }
    .notif-card-text { font-size: 0.95rem; color: #555; margin-bottom: 12px; line-height: 1.5; }
    .notif-card-meta { display: flex; align-items: center; gap: 15px; font-size: 0.8rem; color: #888; font-weight: 500; }
    
    .notif-actions { display: flex; gap: 10px; }
    .btn-action-primary {
        background-color: #ff5722; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: 0.2s;
    }
    .btn-action-primary:hover { background-color: #e64a19; color: #fff; }
    .btn-action-secondary {
        background-color: #f1f5f9; color: #475569; border: none; padding: 8px 16px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: 0.2s;
    }
    .btn-action-secondary:hover { background-color: #e2e8f0; color: #334155; }

    .notification-wrapper { position: relative; display: flex; align-items: center; margin-right: 10px; }
    .notification-bell { color: #ffffff; font-size: 1.3rem; cursor: pointer; position: relative; transition: transform 0.2s; }
    .notification-bell:hover { transform: scale(1.1); }
    .notification-badge { position: absolute; top: -6px; right: -8px; background-color: #ffffff; color: #ff5722; font-size: 0.65rem; font-weight: 800; padding: 2px 5px; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
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
            <div class="notification-wrapper">
                <div class="notification-bell" title="Notificaciones">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notification-badge">2</span> 
                </div>
            </div>

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
        <a href="{{ route('dashboard') }}" class="nav-item">
            <i class="fa-solid fa-border-all"></i> Dashboard
        </a>

        <div class="dropdown">
            <div class="nav-item" data-bs-toggle="dropdown" aria-expanded="false">
                Operaciones <i class="fa-solid fa-chevron-down chevron"></i>
            </div>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('envios') }}"><i class="fa-solid fa-box me-2"></i> Envíos</a></li>
                <li><a class="dropdown-item" href="{{ route('rutas') }}"><i class="fa-solid fa-route me-2"></i> Rutas</a></li>
            </ul>
        </div>

        <div class="dropdown">
            <div class="nav-item" data-bs-toggle="dropdown" aria-expanded="false">
                Gestión <i class="fa-solid fa-chevron-down chevron"></i>
            </div>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('clientes') }}"><i class="fa-solid fa-users me-2"></i> Clientes</a></li>
                <li><a class="dropdown-item" href="{{ route('operadores') }}"><i class="fa-solid fa-truck me-2"></i> Operadores</a></li>
            </ul>
        </div>

        <div class="dropdown">
            <div class="nav-item" data-bs-toggle="dropdown" aria-expanded="false">
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-title">
            <h2>Centro de Notificaciones</h2>
            <p>Historial y gestión de alertas del sistema</p>
        </div>
        <a href="{{ route('envios') }}" class="btn-outline-gray">
            <i class="fa-solid fa-arrow-left"></i> Volver a Envíos
        </a>
    </div>

    <div class="content-box filter-bar">
        <div class="filter-tabs">
            <button class="filter-tab active">Todas</button>
            <button class="filter-tab">No leídas (2)</button>
            <button class="filter-tab">Entregas</button>
            <button class="filter-tab">Incidencias</button>
        </div>
        <button class="btn-outline-gray" style="padding: 8px 16px; font-size: 0.85rem; border-color: transparent;">
            <i class="fa-solid fa-check-double text-muted"></i> Marcar todas como leídas
        </button>
    </div>

    <div class="content-box" style="padding: 0; overflow: hidden;">
        <div class="notif-list">
            
            <div class="notif-card unread">
                <div class="notif-icon-large bg-light-warning">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div class="notif-content-wrap">
                    <div class="notif-card-title">Pedido Listo para Revisión</div>
                    <div class="notif-card-text">
                        El usuario <strong>Comercial López</strong> ha marcado el pedido <strong>ENV-008</strong> como preparado. El estado actual es <span class="badge bg-warning text-dark">EN ESPERA</span>. Requiere asignación de ruta y confirmación.
                    </div>
                    <div class="notif-card-meta">
                        <span><i class="fa-regular fa-clock"></i> Hace 10 minutos</span>
                        <span><i class="fa-solid fa-location-dot"></i> Ciudad de México -> Monterrey</span>
                    </div>
                </div>
                <div class="notif-actions flex-column">
                    <a href="#" class="btn-action-primary text-center">Confirmar Envío</a>
                    <a href="#" class="btn-action-secondary text-center">Ver Detalles</a>
                </div>
            </div>

            <div class="notif-card unread">
                <div class="notif-icon-large bg-light-danger">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <div class="notif-content-wrap">
                    <div class="notif-card-title">Incidencia Reportada en Ruta</div>
                    <div class="notif-card-text">
                        El operador <strong>Luis Hernández</strong> reportó un retraso por tráfico extremo en el pedido <strong>ENV-004</strong> con destino a Mexicali. 
                    </div>
                    <div class="notif-card-meta">
                        <span><i class="fa-regular fa-clock"></i> Hace 45 minutos</span>
                        <span><i class="fa-solid fa-truck"></i> Unidad 102</span>
                    </div>
                </div>
                <div class="notif-actions flex-column">
                    <a href="#" class="btn-action-secondary text-center">Gestionar Incidencia</a>
                </div>
            </div>

            <div class="notif-card">
                <div class="notif-icon-large bg-light-success">
                    <i class="fa-solid fa-check-double"></i>
                </div>
                <div class="notif-content-wrap">
                    <div class="notif-card-title">Entrega Completada Exitosamente</div>
                    <div class="notif-card-text">
                        El pedido <strong>ENV-002</strong> de <strong>Tech Solutions</strong> ha sido entregado en Puebla y la firma ha sido registrada en el sistema móvil.
                    </div>
                    <div class="notif-card-meta">
                        <span><i class="fa-regular fa-clock"></i> Hoy, 11:30 AM</span>
                        <span><i class="fa-regular fa-user"></i> Recibió: Juan Pérez</span>
                    </div>
                </div>
                <div class="notif-actions flex-column">
                    <a href="#" class="btn-action-secondary text-center">Ver Comprobante</a>
                </div>
            </div>

            <div class="notif-card">
                <div class="notif-icon-large bg-light-info">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div class="notif-content-wrap">
                    <div class="notif-card-title">Nuevo Cliente Registrado</div>
                    <div class="notif-card-text">
                        La empresa <strong>Distribuidora Norte</strong> ha completado su registro en la plataforma web y está pendiente de verificación comercial.
                    </div>
                    <div class="notif-card-meta">
                        <span><i class="fa-regular fa-clock"></i> Ayer, 04:15 PM</span>
                    </div>
                </div>
                <div class="notif-actions flex-column">
                    <a href="#" class="btn-action-secondary text-center">Revisar Perfil</a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection