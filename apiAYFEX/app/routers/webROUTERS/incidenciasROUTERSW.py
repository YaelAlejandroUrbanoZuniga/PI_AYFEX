from fastapi import APIRouter, HTTPException, Depends
from sqlalchemy.orm import Session
from typing import List
from datetime import date

# Importamos el modelo de DB y los esquemas
from app.data.webDATA.crear_incidenciasDATAW import Crear_Incidencias
from app.models.webMODELS.incidenciaMODELSW import Incidencia, IncidenciaCreate

# Importa tu función get_db (ajusta la ruta según cómo lo tengas en tu proyecto)
from app.data.dbDATA import get_db 

router = APIRouter(
    prefix="/v1/incidencias",
    tags=["Incidencias"]
)

@router.get("/", response_model=List[Incidencia])
def obtener_incidencias(db: Session = Depends(get_db)):
    incidencias = db.query(Crear_Incidencias).all()
    return incidencias

@router.post("/", response_model=Incidencia)
def crear_incidencia(incidencia: IncidenciaCreate, db: Session = Depends(get_db)):
    # Lógica simple para generar "INC-00X"
    total_incidencias = db.query(Crear_Incidencias).count()
    nuevo_id = f"INC-{total_incidencias + 1:03d}" 
    
    nueva_incidencia = Crear_Incidencias(
        id=nuevo_id,
        envio_id=incidencia.envio_id,
        tipo=incidencia.tipo,
        descripcion=incidencia.descripcion,
        estado="PENDIENTE",
        responsable="Asignado automático", # Esto después lo puedes sacar del login
        fecha=date.today()
    )
    
    db.add(nueva_incidencia)
    db.commit()
    db.refresh(nueva_incidencia)
    return nueva_incidencia

@router.patch("/{incidencia_id}/resolver")
def resolver_incidencia(incidencia_id: str, db: Session = Depends(get_db)):
    incidencia = db.query(Crear_Incidencias).filter(Crear_Incidencias.id == incidencia_id).first()
    if not incidencia:
        raise HTTPException(status_code=404, detail="Incidencia no encontrada")
    
    incidencia.estado = "RESUELTO"
    db.commit()
    db.refresh(incidencia)
    return incidencia

@router.delete("/{incidencia_id}")
def eliminar_incidencia(incidencia_id: str, db: Session = Depends(get_db)):
    incidencia = db.query(Crear_Incidencias).filter(Crear_Incidencias.id == incidencia_id).first()
    if not incidencia:
        raise HTTPException(status_code=404, detail="Incidencia no encontrada")
    
    db.delete(incidencia)
    db.commit()
    return {"mensaje": "Incidencia eliminada correctamente"}