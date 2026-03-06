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

    .filter-bar { padding: 15px 22px; margin-bottom: 25px; }
    .filter-search { position: relative; width: 100%; }
    .filter-search i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; }
    .filter-search input { 
        width: 100%; padding: 10px 15px 10px 40px; border-radius: 8px; 
        border: 1px solid #eee; font-size: 0.9rem; outline: none; background: #f9fafb;
    }

    .box-title { font-weight: 800; font-size: 1.1rem; margin-bottom: 20px; color: #222; }
    .table th { border-top: none; color: #888; font-size: 0.85rem; font-weight: 700; text-transform: capitalize; padding-bottom: 15px; }
    .table td { vertical-align: middle; font-size: 0.9rem; color: #444; border-bottom: 1px solid #f0f0f0; padding: 15px 8px; }
    
    .contact-icon { color: #aaa; margin-right: 5px; font-size: 0.85rem; }
    .total-envios { color: #ff5722; font-weight: 800; }

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
                    <h2>Gestión de Clientes</h2>
                    <p>Administra la información de tus clientes</p>
                </div>
                <button class="btn-orange" data-bs-toggle="modal" data-bs-target="#modalAgregarCliente"><i class="fa-solid fa-plus me-2"></i> Agregar Cliente</button>
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
                                        <a href="#"><i class="fa-regular fa-pen-to-square"></i></a>
                                        <a href="#"><i class="fa-regular fa-trash-can"></i></a>
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
                                        <a href="#"><i class="fa-regular fa-pen-to-square"></i></a>
                                        <a href="#"><i class="fa-regular fa-trash-can"></i></a>
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
                                        <a href="#"><i class="fa-regular fa-pen-to-square"></i></a>
                                        <a href="#"><i class="fa-regular fa-trash-can"></i></a>
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
                                        <a href="#"><i class="fa-regular fa-pen-to-square"></i></a>
                                        <a href="#"><i class="fa-regular fa-trash-can"></i></a>
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
                                        <a href="#"><i class="fa-regular fa-pen-to-square"></i></a>
                                        <a href="#"><i class="fa-regular fa-trash-can"></i></a>
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

<div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 800;">Agregar Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="telefono">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" class="form-control" name="correo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="direccion">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn-orange">Guardar Cliente</button>
            </div>
        </div>
    </div>
</div>
@endsection