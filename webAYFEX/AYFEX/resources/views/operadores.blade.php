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
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-orange:hover { background-color: #e64a19; color: #fff; }

    .filter-bar { background: #fff; border-radius: 12px; padding: 15px 22px; margin-bottom: 30px; border: 2px solid #222; }
    .filter-search { position: relative; width: 100%; }
    .filter-search i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; }
    .filter-search input { 
        width: 100%; padding: 10px 15px 10px 40px; border-radius: 8px; 
        border: none; font-size: 0.9rem; outline: none; background: transparent;
    }

    .operator-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
    }
    .operator-card {
        background: #fff;
        border: 2px solid #222;
        border-radius: 16px;
        padding: 20px;
        position: relative;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .operator-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.05); }

    .op-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; }
    .op-profile { display: flex; align-items: center; gap: 12px; }
    .op-avatar { 
        width: 48px; height: 48px; border-radius: 50%; background: #ff5722; 
        color: white; display: flex; align-items: center; justify-content: center; 
        font-weight: 900; font-size: 1.2rem; 
    }
    .op-info h5 { margin: 0; font-size: 1.05rem; font-weight: 800; color: #222; }
    .op-info span { font-size: 0.8rem; color: #888; font-weight: 600; }
    
    .op-status { padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;}
    .status-en-ruta { border: 1px solid #ff5722; color: #ff5722; background: #fff0eb; }
    .status-disponible { border: 1px solid #16a34a; color: #16a34a; background: #dcfce7; }

    .op-details { margin-bottom: 20px; }
    .op-details p { margin: 8px 0; font-size: 0.85rem; color: #555; display: flex; align-items: center; }
    .op-details i { width: 25px; color: #999; font-size: 0.9rem; }

    .op-stats { display: flex; justify-content: space-between; font-size: 0.9rem; font-weight: 600; color: #555; margin-bottom: 20px; }
    .op-stats span.count { color: #ff5722; font-weight: 900; font-size: 1.1rem; }

    .op-actions { display: flex; gap: 10px; border-top: 1px solid #eee; padding-top: 15px; }
    .btn-edit { 
        flex: 1; background: #fff; border: 1px solid #ddd; padding: 8px; border-radius: 8px; 
        font-weight: 600; color: #555; display: flex; justify-content: center; align-items: center; gap: 8px; 
        transition: 0.2s; text-decoration: none; font-size: 0.9rem;
    }
    .btn-edit:hover { background: #f9f9f9; color: #222; border-color: #222;}
    .btn-delete { 
        width: 42px; background: #fff; border: 1px solid #fee2e2; color: #ef4444; 
        border-radius: 8px; display: flex; justify-content: center; align-items: center; 
        transition: 0.2s; text-decoration: none;
    }
    .btn-delete:hover { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }

   
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
    .custom-form-control {
        width: 100%;
        border-radius: 10px;
        border: 1px solid #ddd;
        padding: 12px 16px;
        font-size: 0.95rem;
        color: #333;
        transition: all 0.3s ease;
        background-color: #fff;
    }
    .custom-form-control:focus {
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
                <li><a class="dropdown-item" href="{{ route('clientes') }}"><i class="fa-solid fa-users me-2"></i> Clientes</a></li>
                <li><a class="dropdown-item" href="{{ route('operadores') }}" style="{{ request()->routeIs('operadores') ? 'color: #ff5722; font-weight: bold; background-color: #fffaf5;' : '' }}"><i class="fa-solid fa-truck me-2"></i> Operadores</a></li>
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
            <h2>Gestión de Operadores</h2>
            <p>Administra tu equipo de operadores y conductores</p>
        </div>
        <button class="btn-orange" data-bs-toggle="modal" data-bs-target="#modalAgregarOperador" onclick="limpiarFormulario()">
            <i class="fa-solid fa-plus me-2"></i> Agregar Operador
        </button>
    </div>

    <div class="filter-bar">
        <div class="filter-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="buscadorOperadores" placeholder="Buscar por nombre, teléfono o vehículos...">
        </div>
    </div>

    <div class="operator-grid" id="operatorGrid">
        <div style="width: 100%; text-align: center; padding: 40px; color: #888;">
            <i class="fa-solid fa-spinner fa-spin fa-2x mb-3"></i>
            <p>Cargando operadores desde la base de datos...</p>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAgregarOperador" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content custom-modal-content">
            <div class="modal-header custom-modal-header">
                <h5 class="modal-title custom-modal-title" id="modalTitle">Agregar Nuevo Operador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="limpiarFormulario()"></button>
            </div>
            <form id="formOperador">
                <div class="modal-body custom-modal-body">
                    <input type="hidden" id="operador_id_db"> <div class="row">
                        <div class="col-md-8 mb-4">
                            <label class="custom-form-label">Nombre Completo</label>
                            <input type="text" class="custom-form-control" id="nombre_completo" required placeholder="Ej. Carlos Ramírez">
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="custom-form-label">Identificador (ID)</label>
                            <input type="text" class="custom-form-control" id="identificador" required placeholder="Ej. OP-006">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="custom-form-label">Teléfono</label>
                            <input type="text" class="custom-form-control" id="telefono" required placeholder="Ej. 555-0000">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="custom-form-label">Vehículo Asignado</label>
                            <input type="text" class="custom-form-control" id="vehiculo_asignado" required placeholder="Ej. Camioneta - ABC-123">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="custom-form-label">Estado</label>
                            <select class="custom-form-control" id="estado">
                                <option value="DISPONIBLE">Disponible</option>
                                <option value="EN RUTA">En Ruta</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer custom-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal" onclick="limpiarFormulario()">Cancelar</button>
                    <button type="submit" class="btn-orange" id="btnGuardar"><i class="fa-solid fa-save me-2"></i> Guardar Operador</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
   
    const API_URL = 'http://127.0.0.1:5000/v1/operadores';

    document.addEventListener('DOMContentLoaded', () => {
        cargarOperadores();
        inicializarBuscador(); 
    });

    
    function inicializarBuscador() {
        const buscador = document.getElementById('buscadorOperadores');
        
        buscador.addEventListener('input', function(e) {
            const textoBuscado = e.target.value.toLowerCase();
            const tarjetas = document.querySelectorAll('.operator-card'); 

            tarjetas.forEach(tarjeta => {
                
                const contenidoTarjeta = tarjeta.textContent.toLowerCase();
                
                
                if (contenidoTarjeta.includes(textoBuscado)) {
                    tarjeta.style.display = '';
                } else {
                    tarjeta.style.display = 'none';
                }
            });
        });
    }

    async function cargarOperadores() {
        try {
            const response = await fetch(API_URL);
            if (!response.ok) throw new Error('Error al obtener datos');
            
            const data = await response.json();
            const grid = document.getElementById('operatorGrid');
            grid.innerHTML = ''; 

            if(data.operadores && data.operadores.length > 0) {
                data.operadores.forEach(op => {
                    const isEnRuta = op.estado === 'EN RUTA';
                    const statusClass = isEnRuta ? 'status-en-ruta' : 'status-disponible';
                    const avatarBg = isEnRuta ? '#ff5722' : '#16a34a'; 
                    
                    const partesNombre = op.nombre_completo.split(' ');
                    let iniciales = partesNombre[0].charAt(0).toUpperCase();
                    if (partesNombre.length > 1) {
                        iniciales += partesNombre[1].charAt(0).toUpperCase();
                    }

                    const card = `
                        <div class="operator-card">
                            <div class="op-header">
                                <div class="op-profile">
                                    <div class="op-avatar" style="background: ${avatarBg};">${iniciales}</div>
                                    <div class="op-info">
                                        <h5>${op.nombre_completo}</h5>
                                        <span>${op.identificador}</span>
                                    </div>
                                </div>
                                <span class="op-status ${statusClass}">${op.estado}</span>
                            </div>
                            <div class="op-details">
                                <p><i class="fa-solid fa-phone"></i> ${op.telefono}</p>
                                <p><i class="fa-solid fa-truck"></i> ${op.vehiculo_asignado}</p>
                            </div>
                            <div class="op-stats">
                                <span>Envíos asignados</span>
                                <span class="count" style="color: ${avatarBg}">0</span>
                            </div>
                            <div class="op-actions">
                                <button onclick='abrirModalEditar(${JSON.stringify(op).replace(/'/g, "\\'")})' class="btn-edit" style="cursor:pointer; width:100%; border:none; background:#f4f6f9;"><i class="fa-regular fa-pen-to-square"></i> Editar</button>
                                <button onclick="eliminarOperador(${op.id})" class="btn-delete" style="cursor:pointer; border:none; background:#fee2e2;"><i class="fa-regular fa-trash-can"></i></button>
                            </div>
                        </div>
                    `;
                    grid.innerHTML += card;
                });
                
              
                const buscador = document.getElementById('buscadorOperadores');
                if (buscador.value !== "") {
                    buscador.dispatchEvent(new Event('input'));
                }
                
            } else {
                grid.innerHTML = '<div style="width: 100%; text-align: center; padding: 40px;"><p>No hay operadores registrados aún.</p></div>';
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('operatorGrid').innerHTML = '<div style="width: 100%; text-align: center; color: red;"><p>Error de conexión con la base de datos (FastAPI).</p></div>';
        }
    }

    
    document.getElementById('formOperador').addEventListener('submit', async function(e) {
        e.preventDefault();

        const btnGuardar = document.getElementById('btnGuardar');
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Guardando...';

        const id_db = document.getElementById('operador_id_db').value;
        const operadorData = {
            nombre_completo: document.getElementById('nombre_completo').value,
            identificador: document.getElementById('identificador').value,
            telefono: document.getElementById('telefono').value,
            vehiculo_asignado: document.getElementById('vehiculo_asignado').value,
            estado: document.getElementById('estado').value
        };

        const method = id_db ? 'PUT' : 'POST';
        const url = id_db ? `${API_URL}/${id_db}` : API_URL;

        try {
            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(operadorData)
            });

            if (response.ok) {
                
                const modalEl = document.getElementById('modalAgregarOperador');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if(modal) { modal.hide(); }
                
                limpiarFormulario();
                cargarOperadores(); // Recargar la lista
                
                
                alert(id_db ? 'Operador actualizado correctamente' : 'Operador agregado correctamente');
            } else {
                const err = await response.json();
                console.error("Detalle del error:", err);
                alert("Error al guardar: Revisa la consola para más detalles.");
            }
        } catch (error) {
            console.error('Error:', error);
            alert("No se pudo conectar con el servidor.");
        } finally {
            btnGuardar.disabled = false;
            btnGuardar.innerHTML = '<i class="fa-solid fa-save me-2"></i> Guardar Operador';
        }
    });

    
    async function eliminarOperador(id) {
        if(confirm('¿Estás seguro de que deseas eliminar este operador? Esta acción no se puede deshacer.')) {
            try {
                const response = await fetch(`${API_URL}/${id}`, {
                    method: 'DELETE'
                });
                if (response.ok) {
                    cargarOperadores();
                } else {
                    alert('Error al eliminar el operador');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    }

    
    function abrirModalEditar(operador) {
        document.getElementById('modalTitle').innerText = 'Editar Operador';
        document.getElementById('operador_id_db').value = operador.id;
        document.getElementById('nombre_completo').value = operador.nombre_completo;
        document.getElementById('identificador').value = operador.identificador;
        document.getElementById('telefono').value = operador.telefono;
        document.getElementById('vehiculo_asignado').value = operador.vehiculo_asignado;
        document.getElementById('estado').value = operador.estado;
        
        const modal = new bootstrap.Modal(document.getElementById('modalAgregarOperador'));
        modal.show();
    }

    function limpiarFormulario() {
        document.getElementById('modalTitle').innerText = 'Agregar Nuevo Operador';
        document.getElementById('formOperador').reset();
        document.getElementById('operador_id_db').value = '';
        document.getElementById('estado').value = 'DISPONIBLE';
    }
</script>
@endsection