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

    .action-icons button { background: none; border: none; padding: 0; cursor: pointer; margin: 0 5px; font-size: 1.1rem; transition: 0.2s; }
    .action-icons button:hover { transform: scale(1.1); }
    .text-primary { color: #0d6efd; }
    .text-primary:hover { color: #0a58ca; }
    .text-danger { color: #dc3545; }
    .text-danger:hover { color: #b02a37; }

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
                <h3 id="card-total">0</h3>
                <div class="stat-icon icon-orange-light"><i class="fa-solid fa-triangle-exclamation"></i></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h6>Pendientes</h6>
                <h3 id="card-pendientes">0</h3>
                <div class="stat-icon icon-red-light"><i class="fa-solid fa-circle-exclamation"></i></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h6>Resueltas</h6>
                <h3 id="card-resueltas">0</h3>
                <div class="stat-icon icon-green-light"><i class="fa-solid fa-circle-check"></i></div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h5 id="titulo-lista" style="font-weight: 800; margin: 0;">Lista de Incidencias (0)</h5>
            <select id="filtro-estado" class="filter-select">
                <option value="TODOS">Todos los estados</option>
                <option value="PENDIENTE">Pendiente</option>
                <option value="RESUELTO">Resuelto</option>
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
                <tbody id="tabla-incidencias">
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
                <form id="formIncidencia">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="custom-form-label">ID Envío</label>
                            <input type="text" id="envio_id" class="custom-form-control" placeholder="Ej. ENV-005" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="custom-form-label">Tipo de Incidencia</label>
                            <select id="tipo" class="custom-form-select" required>
                                <option value="">Selecciona un tipo...</option>
                                <option value="Retraso en tránsito">Retraso en tránsito</option>
                                <option value="Dirección incorrecta">Dirección incorrecta</option>
                                <option value="Paquete dañado">Paquete dañado</option>
                                <option value="Posible extravío">Posible extravío</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label class="custom-form-label">Descripción</label>
                            <textarea id="descripcion" class="custom-form-control" rows="4" placeholder="Detalla la situación..." required></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer custom-modal-footer">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formIncidencia" class="btn-orange-modal">
                    <i class="fa-solid fa-save me-2"></i> Guardar Incidencia
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarIncidencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content custom-modal-content">
            <div class="modal-header custom-modal-header">
                <h5 class="modal-title custom-modal-title">Editar Incidencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-modal-body">
                <form id="formEditIncidencia">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="custom-form-label">ID Envío</label>
                            <input type="text" id="edit_envio_id" class="custom-form-control" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="custom-form-label">Tipo de Incidencia</label>
                            <select id="edit_tipo" class="custom-form-select" required>
                                <option value="">Selecciona un tipo...</option>
                                <option value="Retraso en tránsito">Retraso en tránsito</option>
                                <option value="Dirección incorrecta">Dirección incorrecta</option>
                                <option value="Paquete dañado">Paquete dañado</option>
                                <option value="Posible extravío">Posible extravío</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label class="custom-form-label">Descripción</label>
                            <textarea id="edit_descripcion" class="custom-form-control" rows="4" required></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer custom-modal-footer">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formEditIncidencia" class="btn-orange-modal">
                    <i class="fa-solid fa-save me-2"></i> Actualizar Incidencia
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // URL de tu API FastAPI
    const API_URL = 'http://127.0.0.1:5000/v1/incidencias';

    // Variable global para almacenar las incidencias y facilitar el filtro
    let listaIncidencias = [];
    let idIncidenciaEditando = null;

    // Cargar datos apenas inicia la vista
    document.addEventListener('DOMContentLoaded', cargarIncidencias);

    // Escuchar el evento de enviar del formulario de REGISTRO
    document.getElementById('formIncidencia').addEventListener('submit', function(e) {
        e.preventDefault();
        guardarIncidencia();
    });

    // Escuchar el evento de enviar del formulario de EDICIÓN
    document.getElementById('formEditIncidencia').addEventListener('submit', function(e) {
        e.preventDefault();
        actualizarIncidencia();
    });

    // Escuchar cambios en el FILTRO
    document.getElementById('filtro-estado').addEventListener('change', aplicarFiltro);

    // -------------------------------------------------------------------
    // FUNCIONES PRINCIPALES
    // -------------------------------------------------------------------

    // 1. Obtener datos de FastAPI
    async function cargarIncidencias() {
        try {
            const response = await fetch(API_URL);
            listaIncidencias = await response.json();
            aplicarFiltro(); // Aplica el filtro actual y renderiza la tabla
        } catch (error) {
            console.error("Error al cargar incidencias:", error);
        }
    }

    // 2. Lógica del Filtro
    function aplicarFiltro() {
        const estadoSeleccionado = document.getElementById('filtro-estado').value;
        let datosFiltrados = listaIncidencias;

        if (estadoSeleccionado !== 'TODOS') {
            datosFiltrados = listaIncidencias.filter(inc => inc.estado === estadoSeleccionado);
        }

        renderTable(datosFiltrados);
        actualizarTarjetas(listaIncidencias); // Mantenemos las tarjetas con los totales de TODAS las incidencias
    }

    // 3. Dibujar la tabla HTML
    function renderTable(incidencias) {
        const tbody = document.getElementById('tabla-incidencias');
        document.getElementById('titulo-lista').innerText = `Lista de Incidencias (${incidencias.length})`;
        tbody.innerHTML = '';

        incidencias.forEach(inc => {
            const esPendiente = inc.estado === 'PENDIENTE';
            const badgeClass = esPendiente ? 'status-pending' : 'status-resolved';
            const textoBadge = esPendiente ? 'Pendiente' : 'Resuelto';
            
            // Botones de Acción Modificados (Editar y Eliminar)
            const btnEditar = `<button onclick="abrirModalEditar('${inc.id}')" title="Editar" class="text-primary border-0 bg-transparent mx-1"><i class="fa-solid fa-pen"></i></button>`;
            const btnEliminar = `<button onclick="eliminarIncidencia('${inc.id}')" title="Eliminar" class="text-danger border-0 bg-transparent mx-1"><i class="fa-solid fa-trash"></i></button>`;

            tbody.innerHTML += `
                <tr>
                    <td class="fw-bold">${inc.id}</td>
                    <td>${inc.envio_id}</td>
                    <td>${inc.tipo}</td>
                    <td class="text-muted small text-truncate" style="max-width: 200px;" title="${inc.descripcion}">
                        ${inc.descripcion}
                    </td>
                    <td><span class="badge-status ${badgeClass}">${textoBadge}</span></td>
                    <td>${inc.responsable || 'N/A'}</td>
                    <td>${inc.fecha}</td>
                    <td class="action-icons">
                        ${btnEditar}
                        ${btnEliminar}
                    </td>
                </tr>
            `;
        });
    }

    // 4. Actualizar contadores superiores
    function actualizarTarjetas(incidencias) {
        const pendientes = incidencias.filter(i => i.estado === 'PENDIENTE').length;
        const resueltas = incidencias.filter(i => i.estado === 'RESUELTO').length;
        
        document.getElementById('card-total').innerText = incidencias.length;
        document.getElementById('card-pendientes').innerText = pendientes;
        document.getElementById('card-resueltas').innerText = resueltas;
    }

    // 5. POST: Guardar nueva incidencia
    async function guardarIncidencia() {
        const nueva = {
            envio_id: document.getElementById('envio_id').value,
            tipo: document.getElementById('tipo').value,
            descripcion: document.getElementById('descripcion').value
        };

        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(nueva)
            });

            if(response.ok) {
                // Limpiar formulario y cerrar modal
                document.getElementById('formIncidencia').reset();
                var myModalEl = document.getElementById('modalRegistrarIncidencia');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                modal.hide();
                
                // Recargar tabla para ver el nuevo registro
                cargarIncidencias();
            } else {
                alert('Error al guardar la incidencia.');
            }
        } catch (error) {
            console.error("Error al guardar:", error);
        }
    }

    // 6. Preparar y abrir Modal de Edición
    function abrirModalEditar(id) {
        // Buscamos los datos actuales en nuestra lista global
        const incidencia = listaIncidencias.find(i => i.id === id);
        if (!incidencia) return;

        idIncidenciaEditando = id;
        
        // Rellenamos el formulario con los datos actuales
        document.getElementById('edit_envio_id').value = incidencia.envio_id;
        document.getElementById('edit_tipo').value = incidencia.tipo;
        document.getElementById('edit_descripcion').value = incidencia.descripcion;

        // Abrimos el modal
        var myModal = new bootstrap.Modal(document.getElementById('modalEditarIncidencia'));
        myModal.show();
    }

    // 7. PUT: Actualizar incidencia existente
    async function actualizarIncidencia() {
        const editada = {
            envio_id: document.getElementById('edit_envio_id').value,
            tipo: document.getElementById('edit_tipo').value,
            descripcion: document.getElementById('edit_descripcion').value
        };

        try {
            // Asegúrate de que FastAPI soporte el método PUT para esta ruta
            const response = await fetch(`${API_URL}/${idIncidenciaEditando}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(editada)
            });

            if(response.ok) {
                // Limpiar y cerrar modal
                document.getElementById('formEditIncidencia').reset();
                var myModalEl = document.getElementById('modalEditarIncidencia');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                modal.hide();
                
                // Recargar para ver los cambios
                cargarIncidencias();
            } else {
                alert('Error al actualizar la incidencia en el servidor.');
            }
        } catch (error) {
            console.error("Error al actualizar:", error);
        }
    }

    // 8. DELETE: Eliminar incidencia
    async function eliminarIncidencia(id) {
        if(!confirm(`¿Estás seguro de que deseas eliminar la incidencia ${id}?`)) return;
        
        try {
            // Asegúrate de que FastAPI soporte el método DELETE para esta ruta
            const response = await fetch(`${API_URL}/${id}`, {
                method: 'DELETE'
            });
            
            if(response.ok) {
                cargarIncidencias(); // Recargar la tabla si se eliminó con éxito
            } else {
                alert('Error al eliminar la incidencia.');
            }
        } catch (error) {
            console.error("Error al eliminar:", error);
        }
    }
</script>
@endsection