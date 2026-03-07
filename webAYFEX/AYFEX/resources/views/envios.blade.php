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
       ESTILOS DE LA PÁGINA (ENVÍOS)
       ========================================= */
    body { background-color: #f4f6f9; color: #333; overflow-x: hidden; margin: 0; }
    .navbar { display: none !important; }
    .container.mt-4 { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }

    .main-wrapper { 
        padding: 30px; 
        max-width: 1400px; 
        margin: 0 auto; 
    }

    .page-title { margin-bottom: 0; }
    .page-title h2 { font-weight: 900; margin: 0; color: #222; }
    .page-title p { color: #666; font-size: 0.95rem; margin-bottom: 0; }

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
                <li><a class="dropdown-item" href="{{ route('envios') }}" style="{{ request()->routeIs('envios') ? 'color: #ff5722; font-weight: bold; background-color: #fffaf5;' : '' }}"><i class="fa-solid fa-box me-2"></i> Envíos</a></li>
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-title">
            <h2>Gestión de Envíos</h2>
            <p>Administra y monitorea todos los envíos del sistema</p>
        </div>
        <button class="btn-orange" data-bs-toggle="modal" data-bs-target="#modalCrearEnvio">
            <i class="fa-solid fa-plus me-2"></i> Crear Envío
        </button>
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
                                <a href="#" title="Ver Detalles"><i class="fa-regular fa-eye"></i></a>
                                <a href="#" title="Editar"><i class="fa-regular fa-pen-to-square"></i></a>
                                <a href="#" title="Cambiar Estado"><i class="fa-solid fa-arrow-right-arrow-left"></i></a>
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

<div class="modal fade" id="modalCrearEnvio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="border-bottom: 1px solid #f0f0f0; padding: 20px;">
                <h5 class="modal-title" style="font-weight: 800; color: #333;">Registrar Nuevo Envío</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="#" method="POST">
                <div class="modal-body" style="padding: 25px;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Cliente</label>
                            <input type="text" class="form-control" name="cliente" placeholder="Ej. Comercial López" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Operador</label>
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
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Origen</label>
                            <input type="text" class="form-control" name="origen" placeholder="Ciudad de origen" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Destino</label>
                            <input type="text" class="form-control" name="destino" placeholder="Ciudad de destino" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Peso (kg)</label>
                            <input type="number" step="0.1" class="form-control" name="peso" placeholder="Ej. 15.5" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Fecha de Envío</label>
                            <input type="date" class="form-control" name="fecha" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer" style="border-top: 1px solid #f0f0f0; padding: 15px 25px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Cancelar</button>
                    <button type="submit" class="btn-orange">Guardar Envío</button>
                </div>
            </form>
            
        </div>
    </div>
</div>
@endsection