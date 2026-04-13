from fastapi import APIRouter, HTTPException, Depends
from sqlalchemy.orm import Session
from app.data.movilDATA.crear_reporteDATA import Crear_Reporte
from app.data.movilDATA.crear_usuarioDATA import UsuarioMDB
from app.data.dbDATA import get_db

router = APIRouter(
    prefix="/v1/reportes-movil",
    tags=["Web | Reportes Móvil"]
)

@router.get("/")
def obtener_reportes(db: Session = Depends(get_db)):
    resultados = (
        db.query(Crear_Reporte, UsuarioMDB.nombre_completo)
        .join(UsuarioMDB, Crear_Reporte.usuario_id == UsuarioMDB.id)
        .order_by(Crear_Reporte.fecha.desc())
        .all()
    )

    reportes = []
    for reporte, nombre in resultados:
        reportes.append({
            "id":             reporte.id,
            "usuario_id":     reporte.usuario_id,
            "nombre_usuario": nombre,
            "tipo":           reporte.tipo,
            "descripcion":    reporte.descripcion,
            "prioridad":      reporte.prioridad,
            "estado":         reporte.estado,
            "fecha":          str(reporte.fecha),
        })

    return reportes


@router.patch("/{reporte_id}/resolver")
def resolver_reporte(reporte_id: int, db: Session = Depends(get_db)):
    reporte = db.query(Crear_Reporte).filter(Crear_Reporte.id == reporte_id).first()
    if not reporte:
        raise HTTPException(status_code=404, detail="Reporte no encontrado")
    reporte.estado = "RESUELTO"
    db.commit()
    db.refresh(reporte)
    return reporte


@router.patch("/{reporte_id}/pendiente")
def reabrir_reporte(reporte_id: int, db: Session = Depends(get_db)):
    reporte = db.query(Crear_Reporte).filter(Crear_Reporte.id == reporte_id).first()
    if not reporte:
        raise HTTPException(status_code=404, detail="Reporte no encontrado")
    reporte.estado = "PENDIENTE"
    db.commit()
    db.refresh(reporte)
    return reporte