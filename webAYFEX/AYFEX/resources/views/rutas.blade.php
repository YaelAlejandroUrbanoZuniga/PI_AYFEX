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
    .ruta-card { background: #fff; border: 2px solid #222; border-radius: 16px; padding: 20px; transition: transform 0.2s;}
    .ruta-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05);}
    
    .badge-activa { color: #16a34a; background: #dcfce7; padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;}
    .badge-inactiva { color: #dc2626; background: #fee2e2; padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;}
    .zone-tag { display: inline-block; background: #f3f4f6; border: 1px solid #e5e7eb; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; color: #4b5563; margin: 4px 4px 4px 0;}
    
    .btn-edit-del { display: flex; gap: 10px; border-top: 1px solid #eee; margin-top: 15px; padding-top: 15px; }
    .btn-edit-del a, .btn-edit-del button { flex: 1; text-align: center; padding: 8px; border-radius: 8px; font-weight: 600; text-decoration: none; border: 1px solid #ddd; color: #555; transition: 0.2s; background: white; cursor: pointer;}
    .btn-edit-del button.delete { flex: 0 0 45px; border-color: #fee2e2; color: #ef4444; background: #fff;}
    .btn-edit-del button.edit:hover { background: #f9f9f9; color: #222; border-color: #222;}
    .btn-edit-del button.delete:hover { background: #fee2e2; border-color: #fee2e2;}
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
        <div class="header-actions">
            <a href="{{ route('perfil') }}" class="user-profile">
                <div class="user-info d-none d-md-block">
                    <div class="user-name"><span id="headerNombre">—</span></div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar"><span id="headerAvatar">—</span></div>
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
        <button class="btn-orange" id="btnAbrirModalCrear">
            <i class="fa-solid fa-plus me-2"></i> Crear Ruta
        </button>
    </div>

    <div class="filter-bar">
        <div class="filter-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="buscadorRutas" placeholder="Buscar por nombre, zona o operador...">
        </div>
    </div>

    <div class="rutas-grid" id="rutasContainer">
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #666;">
            <i class="fa-solid fa-spinner fa-spin fa-2x mb-3" style="color: #ff5722;"></i>
            <p>Cargando rutas...</p>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRuta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="border-bottom: 1px solid #f0f0f0; padding: 20px;">
                <h5 class="modal-title" id="tituloModal" style="font-weight: 800; color: #333;">Crear Nueva Ruta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formRuta">
                <div class="modal-body" style="padding: 25px;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Nombre de la Ruta</label>
                            <input type="text" class="form-control" name="nombre" id="nombreRuta" placeholder="Ej. Ruta Pacífico" required style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Código de Ruta</label>
                            <input type="text" class="form-control" name="codigo" id="codigoRuta" placeholder="Ej. RUT-004" required style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Operador Asignado</label>
                            <select class="form-select" name="operador_id" id="selectOperadores" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                                <option value="" selected disabled>Cargando operadores...</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Estado de la Ruta</label>
                            <select class="form-select" name="estado" id="estadoRuta" style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                                <option value="Activa" selected>Activa</option>
                                <option value="Inactiva">Inactiva</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.9rem; color: #555;">Zonas Cubiertas (separadas por comas)</label>
                            <input type="text" class="form-control" name="zonas_cubiertas" id="zonasRuta" placeholder="Ej: Zona A, Zona B, Zona C" required style="border-radius: 8px; border: 1px solid #ddd; padding: 10px;">
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer" style="border-top: 1px solid #f0f0f0; padding: 15px 25px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Cancelar</button>
                    <button type="submit" class="btn-orange" id="btnGuardarRuta">Guardar Ruta</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_URL = 'http://127.0.0.1:5000/v1/rutas';
    const token = localStorage.getItem('authToken'); 
    
    const getHeaders = () => ({
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
    });

    
    const rutasContainer = document.getElementById('rutasContainer');
    const selectOperadores = document.getElementById('selectOperadores');
    const formRuta = document.getElementById('formRuta');
    const buscadorRutas = document.getElementById('buscadorRutas');
    const btnAbrirModalCrear = document.getElementById('btnAbrirModalCrear');
    
    
    let rutasActuales = []; 
    let rutaEditandoId = null; 

    
    const modalRuta = new bootstrap.Modal(document.getElementById('modalRuta'));

    
    async function cargarRutas(query = '') {
        try {
            const url = query ? `${API_URL}/?query=${encodeURIComponent(query)}` : `${API_URL}/`;
            const response = await fetch(url, { headers: getHeaders() });
            
            if (!response.ok) throw new Error('Error al cargar rutas');
            
            rutasActuales = await response.json(); 
            renderizarRutas(rutasActuales);
        } catch (error) {
            console.error(error);
            rutasContainer.innerHTML = `<div style="grid-column: 1/-1; color: red;">Error de conexión con la API.</div>`;
        }
    }

    
    function renderizarRutas(rutas) {
        rutasContainer.innerHTML = ''; 
        
        if (rutas.length === 0) {
            rutasContainer.innerHTML = `<div style="grid-column: 1/-1; text-align: center; color: #666;">No se encontraron rutas.</div>`;
            return;
        }

        rutas.forEach(ruta => {
            const badgeClass = ruta.estado.toUpperCase() === 'ACTIVA' ? 'badge-activa' : 'badge-inactiva';
            const zonasHtml = ruta.zonas_cubiertas.map(zona => `<span class="zone-tag">${zona}</span>`).join('');
            const nombreOp = ruta.nombre_operador ? ruta.nombre_operador : 'Sin asignar';

            const card = document.createElement('div');
            card.className = 'ruta-card';
            card.innerHTML = `
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <h5 style="font-weight: 800; margin:0; font-size:1.1rem; color:#222;">${ruta.nombre}</h5>
                        <small class="text-muted" style="font-weight:600;">${ruta.codigo}</small>
                    </div>
                    <span class="${badgeClass}">${ruta.estado}</span>
                </div>
                <p style="font-size: 0.8rem; color:#666; font-weight:600; margin-bottom: 5px;"><i class="fa-solid fa-location-dot"></i> Zonas Cubiertas</p>
                <div style="margin-bottom: 15px;">
                    ${zonasHtml}
                </div>
                <p style="font-size: 0.85rem; color:#444;"><i class="fa-solid fa-truck text-muted me-2"></i> Operador: <strong>${nombreOp}</strong></p>
                <div class="btn-edit-del">
                    <button class="edit btn-edit" data-id="${ruta.id}" title="Editar Ruta"><i class="fa-regular fa-pen-to-square"></i> Editar</button>
                    <button class="delete btn-delete" data-id="${ruta.id}" title="Eliminar Ruta"><i class="fa-regular fa-trash-can"></i></button>
                </div>
            `;
            rutasContainer.appendChild(card);
        });

        
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                eliminarRuta(this.getAttribute('data-id'));
            });
        });

        
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                abrirModalEditar(this.getAttribute('data-id'));
            });
        });
    }

    
    async function cargarOperadores() {
        try {
            const response = await fetch(`${API_URL}/operadores/activos`, { headers: getHeaders() });
            if (!response.ok) throw new Error('Error al cargar operadores');
            
            const operadores = await response.json();
            
            selectOperadores.innerHTML = '<option value="" selected disabled>Seleccione un operador...</option>';
            operadores.forEach(op => {
                selectOperadores.innerHTML += `<option value="${op.id}">${op.nombre_completo}</option>`;
            });
        } catch (error) {
            console.error(error);
            selectOperadores.innerHTML = '<option value="" disabled>Error al cargar</option>';
        }
    }

    
    btnAbrirModalCrear.addEventListener('click', () => {
        rutaEditandoId = null; 
        document.getElementById('tituloModal').innerText = "Crear Nueva Ruta";
        formRuta.reset();
        modalRuta.show();
    });

    
    function abrirModalEditar(id) {
        
        const ruta = rutasActuales.find(r => r.id == id);
        if(!ruta) return;

        rutaEditandoId = ruta.id; 
        document.getElementById('tituloModal').innerText = "Editar Ruta";
        
        
        document.getElementById('nombreRuta').value = ruta.nombre;
        document.getElementById('codigoRuta').value = ruta.codigo;
        document.getElementById('selectOperadores').value = ruta.operador_id || "";
        document.getElementById('estadoRuta').value = ruta.estado;
        document.getElementById('zonasRuta').value = ruta.zonas_cubiertas.join(', ');

        modalRuta.show();
    }

    
    formRuta.addEventListener('submit', async function(e) {
        e.preventDefault(); 
        const btnGuardar = document.getElementById('btnGuardarRuta');
        btnGuardar.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Guardando...';
        btnGuardar.disabled = true;

        const zonasArray = formRuta.zonas_cubiertas.value.split(',').map(z => z.trim()).filter(z => z !== '');

        const payload = {
            nombre: formRuta.nombre.value,
            codigo: formRuta.codigo.value,
            estado: formRuta.estado.value,
            zonas_cubiertas: zonasArray,
            operador_id: formRuta.operador_id.value ? parseInt(formRuta.operador_id.value) : null
        };

        
        const metodo = rutaEditandoId ? 'PUT' : 'POST';
        const urlPeticion = rutaEditandoId ? `${API_URL}/${rutaEditandoId}` : `${API_URL}/`;

        try {
            const response = await fetch(urlPeticion, {
                method: metodo,
                headers: getHeaders(),
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                const err = await response.json();
                throw new Error(err.detail || 'Error al guardar la ruta');
            }

            formRuta.reset(); 
            modalRuta.hide();
            cargarRutas(); 
            
            
            alert(rutaEditandoId ? "¡Ruta actualizada exitosamente!" : "¡Ruta creada exitosamente!");

        } catch (error) {
            alert("Error: " + error.message);
        } finally {
            btnGuardar.innerHTML = 'Guardar Ruta';
            btnGuardar.disabled = false;
        }
    });

    
    async function eliminarRuta(id) {
        if (!confirm('¿Estás seguro de que deseas eliminar esta ruta? Esta acción no se puede deshacer.')) return;

        try {
            const response = await fetch(`${API_URL}/${id}`, {
                method: 'DELETE',
                headers: getHeaders()
            });

            if (!response.ok) throw new Error('Error al eliminar');
            cargarRutas(); 
            
        } catch (error) {
            alert("Error al eliminar la ruta.");
        }
    }

    
    let debounceTimer;
    buscadorRutas.addEventListener('input', function(e) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            cargarRutas(e.target.value);
        }, 500);
    });

    
    cargarRutas();
    cargarOperadores();
});

// ── Nombre de usuario en header ──────────────────────
function actualizarPerfil() {
    const nombre = localStorage.getItem('nombreUsuario') || 'Admin';
    const elNombre = document.getElementById('headerNombre');
    const elAvatar = document.getElementById('headerAvatar');
    if (elNombre) elNombre.textContent = nombre;
    if (elAvatar) elAvatar.textContent = nombre.charAt(0).toUpperCase();
}
document.addEventListener('DOMContentLoaded', function() {
    actualizarPerfil();
});
</script>
@endsection