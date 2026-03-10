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
       ESTILOS DE LA PÁGINA (INCIDENCIAS)
       ========================================= */
    body { background-color: #f4f6f9; color: #333; overflow-x: hidden; margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;}
    .navbar { display: none !important; } 
    .container.mt-4 { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }

    .main-wrapper { 
        padding: 30px; 
        max-width: 1400px; 
        margin: 0 auto; 
    }

    .header-actions-page { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;}
    .page-title h2 { font-weight: 900; margin: 0; color: #222; }
    .page-title p { color: #666; font-size: 0.95rem; margin-top: 5px; margin-bottom: 0;}

    .btn-register { 
        background-color: #ff5722; color: white; border: none; padding: 10px 20px; 
        border-radius: 10px; font-weight: bold; transition: 0.3s; display: inline-flex; align-items: center;
    }
    .btn-register:hover { background-color: #e64a19; color: white; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(230, 74, 25, 0.2); }

    .stat-card { 
        background: #fff; border-radius: 16px; padding: 22px; 
        border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); 
        position: relative; height: 100%; transition: transform 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.06); }
    .stat-card h6 { color: #777; font-size: 0.85rem; font-weight: 600; margin-bottom: 10px; }
    .stat-card h3 { font-weight: 900; margin: 0; color: #222; font-size: 2rem; }
    .stat-icon { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
    
    .icon-orange-light { background: #fff7ed; color: #f97316; }
    .icon-red-light { background: #fef2f2; color: #ef4444; }
    .icon-green-light { background: #f0fdf4; color: #22c55e; }

    .table-container { background: #fff; border-radius: 16px; padding: 25px; margin-top: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    .filter-select { border-radius: 10px; border: 1px solid #ddd; padding: 8px 15px; font-size: 0.9rem; outline: none; transition: border-color 0.2s;}
    .filter-select:focus { border-color: #ff5722; }

    .badge-status { padding: 5px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
    .status-pending { background: #fee2e2; color: #dc2626; }
    .status-resolved { background: #dcfce7; color: #16a34a; }

    .action-icons i { cursor: pointer; margin: 0 5px; font-size: 1.1rem; transition: 0.2s; }
    .action-icons i:hover { transform: scale(1.1); }
    .text-view { color: #666; }
    .text-view:hover { color: #222; }
    .text-check { color: #16a34a; }
    .text-check:hover { color: #14532d; }

    /* =========================================
       ESTILOS DEL MODAL Y FORMULARIOS
       ========================================= */
    .custom-modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        overflow: hidden;
    }
    .custom-modal-header {
        border-bottom: 1px solid #f0f0f0;
        padding: 24px;
        background-color: #fff;
    }
    .custom-modal-title {
        font-weight: 800;
        color: #222;
        font-size: 1.25rem;
        margin: 0;
    }
    .custom-modal-body {
        padding: 24px;
        background-color: #fafbfc;
    }
    .custom-form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #555;
        margin-bottom: 8px;
        display: block;
    }
    .custom-form-control, .custom-form-select {
        width: 100%;
        border-radius: 10px;
        border: 1px solid #ddd;
        padding: 12px 16px;
        font-size: 0.95rem;
        color: #333;
        transition: all 0.3s ease;
        background-color: #fff;
    }
    .custom-form-control:focus, .custom-form-select:focus {
        border-color: #ff5722;
        box-shadow: 0 0 0 4px rgba(255, 87, 34, 0.1);
        outline: none;
    }
    .custom-form-control::placeholder {
        color: #aaa;
    }
    .custom-modal-footer {
        border-top: 1px solid #f0f0f0;
        padding: 16px 24px;
        background-color: #fff;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    .btn-cancel {
        background-color: #f1f5f9;
        color: #475569;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        padding: 10px 20px;
        transition: 0.3s;
    }
    .btn-cancel:hover {
        background-color: #e2e8f0;
        color: #1e293b;
    }
    .btn-orange-modal {
        background-color: #ff5722;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-orange-modal:hover {
        background-color: #e64a19;
        color: white;
    }
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
            <input type="text" placeholder="Buscar por ID, envío o tipo...">
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
                <li><a class="dropdown-item" href="{{ route('reportes') }}"><i class="fa-solid fa-file-lines me-2"></i> Reportes</a></li>
                <li><a class="dropdown-item" href="{{ route('incidencias') }}" style="{{ request()->routeIs('incidencias') ? 'color: #ff5722; font-weight: bold; background-color: #fffaf5;' : '' }}"><i class="fa-solid fa-circle-exclamation me-2"></i> Incidencias</a></li>
            </ul>
        </div>
    </div>
</header>

<div class="main-wrapper">
    <div class="header-actions-page">
        <div class="page-title">
            <h2>Gestión de Incidencias</h2>
            <p>Monitorea y resuelve problemas en los envíos</p>
        </div>
        <button class="btn-register" data-bs-toggle="modal" data-bs-target="#modalRegistrarIncidencia">
            <i class="fa-solid fa-plus me-2"></i> Registrar Incidencia
        </button>
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
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
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
                            <i class="fa-regular fa-eye text-view" title="Ver detalles"></i>
                            <i class="fa-regular fa-circle-check text-check" title="Marcar como resuelto"></i>
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
                            <i class="fa-regular fa-eye text-view" title="Ver detalles"></i>
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
                            <i class="fa-regular fa-eye text-view" title="Ver detalles"></i>
                            <i class="fa-regular fa-circle-check text-check" title="Marcar como resuelto"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRegistrarIncidencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content custom-modal-content">
            <div class="modal-header custom-modal-header">
                <h5 class="modal-title custom-modal-title">Registrar Nueva Incidencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-modal-body">
                <form action="#" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="custom-form-label">ID Envío</label>
                            <input type="text" class="custom-form-control" name="envio_id" placeholder="Ej. ENV-005">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="custom-form-label">Tipo de Incidencia</label>
                            <select class="custom-form-select" name="tipo">
                                <option value="">Selecciona un tipo...</option>
                                <option value="retraso">Retraso en tránsito</option>
                                <option value="direccion">Dirección incorrecta</option>
                                <option value="danio">Paquete dañado</option>
                                <option value="extravio">Posible extravío</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label class="custom-form-label">Descripción</label>
                            <textarea class="custom-form-control" name="descripcion" rows="4" placeholder="Detalla la situación..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer custom-modal-footer">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn-orange-modal"><i class="fa-solid fa-save me-2"></i> Guardar Incidencia</button>
            </div>
        </div>
    </div>
</div>
@endsection