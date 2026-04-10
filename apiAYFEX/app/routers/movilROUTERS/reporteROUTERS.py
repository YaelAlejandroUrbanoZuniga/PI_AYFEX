from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session
from datetime import date
from app.data.dbDATA import get_db
from app.data.movilDATA.crear_reporteDATA import Crear_Reporte
from app.models.movilMODELS.reporteMODELS import ReporteCreate, Reporte
from app.security.authSECURITY import verificar_Peticion_Movil

router = APIRouter(
    prefix="/v1/reportes",
    tags=["Movil | Reportes"]
)

PRIORIDADES_VALIDAS = ["BAJA", "NORMAL", "ALTA", "URGENTE"]

@router.post("/", response_model=Reporte, status_code=status.HTTP_201_CREATED)
def crear_reporte(
    datos: ReporteCreate,
    db: Session = Depends(get_db),
    usuario_id: str = Depends(verificar_Peticion_Movil)
):
    if datos.prioridad not in PRIORIDADES_VALIDAS:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail=f"Prioridad inválida. Opciones: {', '.join(PRIORIDADES_VALIDAS)}"
        )

    nuevo_reporte = Crear_Reporte(
        usuario_id=int(usuario_id),
        tipo=datos.tipo,
        descripcion=datos.descripcion,
        prioridad=datos.prioridad,
        estado="PENDIENTE",
        fecha=date.today()
    )

    db.add(nuevo_reporte)
    db.commit()
    db.refresh(nuevo_reporte)
    return nuevo_reporte


@router.get("/", response_model=list[Reporte])
def obtener_mis_reportes(
    db: Session = Depends(get_db),
    usuario_id: str = Depends(verificar_Peticion_Movil)
):
    return db.query(Crear_Reporte).filter(
        Crear_Reporte.usuario_id == int(usuario_id)
    ).order_by(Crear_Reporte.fecha.desc()).all()