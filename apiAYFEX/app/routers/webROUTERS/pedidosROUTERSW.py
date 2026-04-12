from fastapi import APIRouter, HTTPException, Depends
from sqlalchemy.orm import Session
from typing import List
from datetime import date

from app.data.movilDATA.crear_pedidosDATA import Crear_Pedidos
from app.data.webDATA.rutasDATAW import RutaDB
from app.models.webMODELS.pedidoMODELSW import Pedido, PedidoConfirmar, PedidoRechazar
from app.data.dbDATA import get_db

router = APIRouter(
    prefix="/v1/pedidos-web",
    tags=["Web | Pedidos"]
)


# ─── GET todos los pedidos (la web ve todos, no solo EN ESPERA) ───────────────
@router.get("/", response_model=List[Pedido])
def obtener_pedidos(db: Session = Depends(get_db)):
    """
    Devuelve todos los pedidos. El frontend filtra por estado.
    Si solo quieres los EN ESPERA cambia el filtro abajo.
    """
    pedidos = db.query(Crear_Pedidos).all()
    return pedidos


# ─── PATCH confirmar ──────────────────────────────────────────────────────────
@router.patch("/{pedido_id}/confirmar", response_model=Pedido)
def confirmar_pedido(pedido_id: str, datos: PedidoConfirmar, db: Session = Depends(get_db)):
    # ✅ pedido_id es str, coincide con el tipo de la PK
    pedido = db.query(Crear_Pedidos).filter(Crear_Pedidos.id == pedido_id).first()
    if not pedido:
        raise HTTPException(status_code=404, detail="Pedido no encontrado")

    ruta = db.query(RutaDB).filter(RutaDB.id == datos.ruta_id).first()
    if not ruta:
        raise HTTPException(status_code=404, detail="Ruta no encontrada")

    # Obtenemos el operador vinculado a la ruta
    nombre_operador   = getattr(ruta.operador, 'nombre',    'Sin operador') if hasattr(ruta, 'operador') else 'Sin operador'
    telefono_operador = getattr(ruta.operador, 'telefono',  None)           if hasattr(ruta, 'operador') else None

    pedido.estado             = "EN CAMINO"
    pedido.ruta_id            = ruta.id
    pedido.ruta_nombre        = getattr(ruta, 'nombre', None)
    pedido.ruta_codigo        = getattr(ruta, 'codigo', None)
    pedido.operador_nombre    = nombre_operador
    pedido.operador_telefono  = telefono_operador
    pedido.dias_estimados     = datos.dias_estimados
    pedido.fecha_asignacion   = date.today()

    db.commit()
    db.refresh(pedido)
    return pedido


# ─── PATCH rechazar ───────────────────────────────────────────────────────────
@router.patch("/{pedido_id}/rechazar", response_model=Pedido)
def rechazar_pedido(pedido_id: str, datos: PedidoRechazar, db: Session = Depends(get_db)):
    pedido = db.query(Crear_Pedidos).filter(Crear_Pedidos.id == pedido_id).first()
    if not pedido:
        raise HTTPException(status_code=404, detail="Pedido no encontrado")

    pedido.estado          = "RECHAZADO"
    pedido.motivo_rechazo  = datos.motivo_rechazo

    db.commit()
    db.refresh(pedido)
    return pedido


# ─── PATCH marcar entregado ───────────────────────────────────────────────────
@router.patch("/{pedido_id}/marcar-entregado", response_model=Pedido)
def marcar_como_entregado(pedido_id: str, db: Session = Depends(get_db)):
    """La web marca el pedido como listo para confirmar; la app móvil muestra el botón final al cliente."""
    pedido = db.query(Crear_Pedidos).filter(Crear_Pedidos.id == pedido_id).first()
    if not pedido:
        raise HTTPException(status_code=404, detail="Pedido no encontrado")

    pedido.estado = "POR_CONFIRMAR_ENTREGA"

    db.commit()
    db.refresh(pedido)
    return pedido