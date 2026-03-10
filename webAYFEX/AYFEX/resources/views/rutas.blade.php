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
       ESTILOS DE LA PÁGINA (RUTAS)
       ========================================= */
    body { background-color: #f4f6f9; color: #333; overflow-x: hidden; margin: 0;}
    .navbar { display: none !important; }
    .container.mt-4 { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }

    .main-wrapper { 
        padding: 30px; 
        max-width: 1400px; 
        margin: 0 auto; 
    }

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
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-border-all"></i> Dashboard
        </a>

        <div class="dropdown">
            <div class="nav-item {{ request()->routeIs('envios') || request()->routeIs('rutas') ? 'active' : '' }}" data-bs-toggle="dropdown" aria-expanded="false">
                Operaciones <i class="fa-solid fa-chevron-down chevron"></i>
            </div>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('envios') }}"><i class="fa-solid fa-box me-2"></i> Envíos</a></li>
                <li><a class="dropdown-item" href="{{ route('rutas') }}" style="{{ request()->routeIs('rutas') ? 'color: #ff5722; font-weight: bold; background-color: #fffaf5;' : '' }}"><i class="fa-solid fa-route me-2"></i> Rutas</a></li>
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-title">
            <h2>Gestión de Rutas</h2>
            <p>Administra las rutas de distribución</p>
        </div>
        <button class="btn-orange" data-bs-toggle="modal" data-bs-target="#modalCrearRuta">
            <i class="fa-solid fa-plus me-2"></i> Crear Ruta
        </button>
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

<div class="modal fade" id="modalCrearRuta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="border-bottom: 1px solid #f0f0f0; padding: 20px;">
                <h5 class="modal-title" style="font-weight: 800; color: #333;">Crear Nueva Ruta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="#" method="POST">
                <div class="modal-body" style="padding: 25px;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Nombre de la Ruta</label>
                            <input type="text" class="form-control" name="nombre_ruta" placeholder="Ej. Ruta Pacífico" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Código de Ruta</label>
                            <input type="text" class="form-control" name="codigo_ruta" placeholder="Ej. RUT-004" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Operador Asignado</label>
                            <select class="form-select" name="operador" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                                <option value="" selected disabled>Seleccione un operador...</option>
                                <option value="1">Carlos Ramírez</option>
                                <option value="2">María González</option>
                                <option value="3">Pedro Sánchez</option>
                                <option value="4">Luis Hernández</option>
                                <option value="5">Ana Martínez</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Estado de la Ruta</label>
                            <select class="form-select" name="estado" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                                <option value="activa" selected>Activa</option>
                                <option value="inactiva">Inactiva</option>
                                <option value="mantenimiento">En Mantenimiento</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Zonas Cubiertas (separadas por comas)</label>
                            <input type="text" class="form-control" name="zonas" placeholder="Ej: Zona A, Zona B, Zona C" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer" style="border-top: 1px solid #f0f0f0; padding: 15px 25px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Cancelar</button>
                    <button type="submit" class="btn-orange">Guardar Ruta</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection