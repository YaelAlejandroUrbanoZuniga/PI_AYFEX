from fastapi import APIRouter, HTTPException
from app.models.incidenciaMODELSW import Incidencia, IncidenciaCreate
from datetime import date
from typing import List

router = APIRouter(
    prefix="/v1/incidencias",
    tags=["Incidencias"]
)

# Simulamos la base de datos con los datos de tu imagen
incidencias_db = [
    {"id": "INC-001", "envio_id": "ENV-004", "tipo": "Dirección incorrecta", "descripcion": "El destinatario proporcionó una dirección inc...", "estado": "PENDIENTE", "responsable": "Luis Hernández", "fecha": "2026-02-21"},
    {"id": "INC-002", "envio_id": "ENV-001", "tipo": "Retraso en tránsito", "descripcion": "Tráfico intenso en autopista", "estado": "RESUELTO", "responsable": "Carlos Ramírez", "fecha": "2026-02-20"},
    {"id": "INC-003", "envio_id": "ENV-003", "tipo": "Paquete dañado", "descripcion": "Daños menores en el empaque", "estado": "PENDIENTE", "responsable": "Pedro Sánchez", "fecha": "2026-02-22"},
]

@router.get("/", response_model=List[Incidencia])
def obtener_incidencias():
    return incidencias_db

@router.post("/", response_model=Incidencia)
def crear_incidencia(incidencia: IncidenciaCreate):
    # Generamos un ID automático
    nuevo_id = f"INC-00{len(incidencias_db) + 1}"
    
    nueva_incidencia = {
        "id": nuevo_id,
        "envio_id": incidencia.envio_id,
        "tipo": incidencia.tipo,
        "descripcion": incidencia.descripcion,
        "estado": "PENDIENTE", # Por defecto al crear
        "responsable": "Asignado automático", # Simulado
        "fecha": date.today().strftime("%Y-%m-%d")
    }
    incidencias_db.append(nueva_incidencia)
    return nueva_incidencia

@router.patch("/{incidencia_id}/resolver")
def resolver_incidencia(incidencia_id: str):
    for inc in incidencias_db:
        if inc["id"] == incidencia_id:
            inc["estado"] = "RESUELTO"
            return inc
    raise HTTPException(status_code=404, detail="Incidencia no encontrada")

# ==========================================
# NUEVOS ENDPOINTS: EDITAR Y ELIMINAR
# ==========================================

@router.put("/{incidencia_id}")
def actualizar_incidencia(incidencia_id: str, incidencia_actualizada: IncidenciaCreate):
    """
    Actualiza los datos de una incidencia existente.
    """
    for inc in incidencias_db:
        if inc["id"] == incidencia_id:
            # Actualizamos solo los campos permitidos del formulario
            inc["envio_id"] = incidencia_actualizada.envio_id
            inc["tipo"] = incidencia_actualizada.tipo
            inc["descripcion"] = incidencia_actualizada.descripcion
            return inc
            
    # Si termina el bucle y no la encuentra, lanzamos error 404
    raise HTTPException(status_code=404, detail="Incidencia no encontrada")


@router.delete("/{incidencia_id}")
def eliminar_incidencia(incidencia_id: str):
    """
    Elimina una incidencia de la base de datos simulada.
    """
    for index, inc in enumerate(incidencias_db):
        if inc["id"] == incidencia_id:
            # Eliminamos el elemento de la lista
            del incidencias_db[index]
            return {"mensaje": f"Incidencia {incidencia_id} eliminada correctamente"}
            
    # Si no la encuentra, lanzamos error 404
    raise HTTPException(status_code=404, detail="Incidencia no encontrada")