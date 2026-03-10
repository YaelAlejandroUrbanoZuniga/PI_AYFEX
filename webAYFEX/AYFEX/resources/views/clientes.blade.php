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
       ESTILOS DE LA PÁGINA (CLIENTES)
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

    .content-box, .table-container { 
        background: #fff; border-radius: 16px; padding: 22px; 
        border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); 
    }
    .btn-orange {
        background-color: #ff5722; color: #fff; border-radius: 8px; 
        font-weight: 600; padding: 10px 24px; transition: 0.3s; border: none;
    }
    .btn-orange:hover { background-color: #e64a19; color: #fff; }

    .filter-bar { padding: 15px 22px; margin-bottom: 25px; border: 2px solid #222; }
    .filter-search { position: relative; width: 100%; }
    .filter-search i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; }
    .filter-search input { 
        width: 100%; padding: 10px 15px 10px 40px; border-radius: 8px; 
        border: none; font-size: 0.9rem; outline: none; background: transparent;
    }

    .box-title { font-weight: 800; font-size: 1.1rem; margin-bottom: 20px; color: #222; }
    
    .table-container { border: 2px solid #222; }
    .table th { border-top: none; color: #888; font-size: 0.85rem; font-weight: 700; text-transform: capitalize; padding-bottom: 15px; }
    .table td { vertical-align: middle; font-size: 0.9rem; color: #444; border-bottom: 1px solid #f0f0f0; padding: 15px 8px; }
    
    .contact-icon { color: #aaa; margin-right: 5px; font-size: 0.85rem; }
    .total-envios { color: #ff5722; font-weight: 800; }

    .action-icons { display: flex; gap: 12px; }
    .action-icons a { color: #888; transition: 0.2s; font-size: 1.1rem; }
    .action-icons a:hover { color: #ff5722; }
    .action-icons a.delete-icon:hover { color: #dc3545; }
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
                <li><a class="dropdown-item" href="{{ route('rutas') }}"><i class="fa-solid fa-route me-2"></i> Rutas</a></li>
            </ul>
        </div>

        <div class="dropdown">
            <div class="nav-item {{ request()->routeIs('clientes') || request()->routeIs('operadores') ? 'active' : '' }}" data-bs-toggle="dropdown" aria-expanded="false">
                Gestión <i class="fa-solid fa-chevron-down chevron"></i>
            </div>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('clientes') }}" style="{{ request()->routeIs('clientes') ? 'color: #ff5722; font-weight: bold; background-color: #fffaf5;' : '' }}"><i class="fa-solid fa-users me-2"></i> Clientes</a></li>
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
            <h2>Gestión de Clientes</h2>
            <p>Administra la información de tus clientes</p>
        </div>
        <button class="btn-orange" data-bs-toggle="modal" data-bs-target="#modalAgregarCliente">
            <i class="fa-solid fa-plus me-2"></i> Agregar Cliente
        </button>
    </div>

    <div class="content-box filter-bar">
        <div class="filter-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Buscar por nombre, correo o teléfono...">
        </div>
    </div>

    <div class="table-container">
        <h6 class="box-title">Lista de Clientes (5)</h6>
        <div class="table-responsive">
            <table class="table table-borderless align-middle">
                <thead>
                    <tr style="border-bottom: 2px solid #e9ecef;">
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Dirección</th>
                        <th>Total Envíos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>CLI-001</strong></td>
                        <td>Comercial López</td>
                        <td><i class="fa-solid fa-phone contact-icon"></i> 555-1234</td>
                        <td><i class="fa-regular fa-envelope contact-icon"></i> contacto@lopez.com</td>
                        <td>Av. Reforma 123, CDMX</td>
                        <td class="total-envios">45</td>
                        <td>
                            <div class="action-icons">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditarCliente"><i class="fa-regular fa-pen-to-square"></i></a>
                                <a href="#" class="delete-icon" data-bs-toggle="modal" data-bs-target="#modalEliminarCliente"><i class="fa-regular fa-trash-can"></i></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>CLI-002</strong></td>
                        <td>Tech Solutions</td>
                        <td><i class="fa-solid fa-phone contact-icon"></i> 333-9876</td>
                        <td><i class="fa-regular fa-envelope contact-icon"></i> info@techsol.com</td>
                        <td>Av. Chapultepec 789, Guadalajara</td>
                        <td class="total-envios">32</td>
                        <td>
                            <div class="action-icons">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditarCliente"><i class="fa-regular fa-pen-to-square"></i></a>
                                <a href="#" class="delete-icon" data-bs-toggle="modal" data-bs-target="#modalEliminarCliente"><i class="fa-regular fa-trash-can"></i></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>CLI-003</strong></td>
                        <td>Farmacia del Valle</td>
                        <td><i class="fa-solid fa-phone contact-icon"></i> 442-7890</td>
                        <td><i class="fa-regular fa-envelope contact-icon"></i> ventas@farmavalle.com</td>
                        <td>Av. Universidad 234, Querétaro</td>
                        <td class="total-envios">20</td>
                        <td>
                            <div class="action-icons">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditarCliente"><i class="fa-regular fa-pen-to-square"></i></a>
                                <a href="#" class="delete-icon" data-bs-toggle="modal" data-bs-target="#modalEliminarCliente"><i class="fa-regular fa-trash-can"></i></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>CLI-004</strong></td>
                        <td>AutoPartes Express</td>
                        <td><i class="fa-solid fa-phone contact-icon"></i> 664-5555</td>
                        <td><i class="fa-regular fa-envelope contact-icon"></i> pedidos@autoexpress.com</td>
                        <td>Zona Río 890, Tijuana</td>
                        <td class="total-envios">67</td>
                        <td>
                            <div class="action-icons">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditarCliente"><i class="fa-regular fa-pen-to-square"></i></a>
                                <a href="#" class="delete-icon" data-bs-toggle="modal" data-bs-target="#modalEliminarCliente"><i class="fa-regular fa-trash-can"></i></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>CLI-005</strong></td>
                        <td>Librería Académica</td>
                        <td><i class="fa-solid fa-phone contact-icon"></i> 999-1111</td>
                        <td><i class="fa-regular fa-envelope contact-icon"></i> contacto@libacad.com</td>
                        <td>Paseo Montejo 456, Mérida</td>
                        <td class="total-envios">19</td>
                        <td>
                            <div class="action-icons">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditarCliente"><i class="fa-regular fa-pen-to-square"></i></a>
                                <a href="#" class="delete-icon" data-bs-toggle="modal" data-bs-target="#modalEliminarCliente"><i class="fa-regular fa-trash-can"></i></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="border-bottom: 1px solid #f0f0f0; padding: 20px;">
                <h5 class="modal-title" style="font-weight: 800; color: #333;">Agregar Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST">
                <div class="modal-body" style="padding: 25px;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Nombre o Empresa</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Ej. Comercial López" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" placeholder="Ej. 555-1234" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Correo Electrónico</label>
                            <input type="email" class="form-control" name="correo" placeholder="Ej. contacto@empresa.com" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Dirección Completa</label>
                            <input type="text" class="form-control" name="direccion" placeholder="Ej. Av. Reforma 123, CDMX" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #f0f0f0; padding: 15px 25px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Cancelar</button>
                    <button type="submit" class="btn-orange">Guardar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="border-bottom: 1px solid #f0f0f0; padding: 20px;">
                <h5 class="modal-title" style="font-weight: 800; color: #333;">Editar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST">
                <div class="modal-body" style="padding: 25px;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Nombre o Empresa</label>
                            <input type="text" class="form-control" name="nombre" value="Comercial López" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" value="555-1234" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Correo Electrónico</label>
                            <input type="email" class="form-control" name="correo" value="contacto@lopez.com" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Dirección Completa</label>
                            <input type="text" class="form-control" name="direccion" value="Av. Reforma 123, CDMX" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #f0f0f0; padding: 15px 25px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Cancelar</button>
                    <button type="submit" class="btn-orange">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEliminarCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="border-bottom: 1px solid #f0f0f0; padding: 20px;">
                <h5 class="modal-title" style="font-weight: 800; color: #dc3545;">Eliminar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <p style="color: #555;">¿Estás seguro de que deseas eliminar este cliente? Esta acción no se puede deshacer y borrará todo su historial de envíos.</p>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #f0f0f0; padding: 15px 20px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Cancelar</button>
                <form action="#" method="POST" style="display:inline;">
                    <button type="submit" class="btn btn-danger" style="border-radius: 8px; font-weight: 600;">Sí, Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection