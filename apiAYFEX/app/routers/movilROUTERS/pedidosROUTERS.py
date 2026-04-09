from fastapi import status, HTTPException, Depends, APIRouter
from app.models.movilMODELS.pedidoMODELS import PedidoBase, Pedido
from app.security.authSECURITY import verificar_Peticion_Movil
from datetime import date
from sqlalchemy.orm import Session
from app.data.dbDATA import get_db
from app.data.movilDATA.crear_pedidosDATA import Crear_Pedidos

router = APIRouter(
    prefix="/v1/pedidos",
    tags=["Movil | Pedidos"]
)

def generar_id_pedido(db: Session) -> str:
    hoy = date.today()
    prefijo = f"PED{hoy.strftime('%d%m%y')}"
    
    # Contamos cuántos pedidos hay hoy
    pedidos_hoy = db.query(Crear_Pedidos).filter(
        Crear_Pedidos.id.like(f"{prefijo}%")
    ).count()
    
    consecutivo = pedidos_hoy + 1
    return f"{prefijo}{consecutivo:04d}"

@router.get("/", response_model=list[Pedido])
async def leer_pedidos(
    db: Session = Depends(get_db),
    usuario_id: str = Depends(verificar_Peticion_Movil)
):
    return db.query(Crear_Pedidos).filter(
        Crear_Pedidos.usuario_id == int(usuario_id)
    ).all()

@router.post("/", response_model=Pedido, status_code=status.HTTP_201_CREATED)
async def crear_pedido(
    pedidoP: PedidoBase,
    db: Session = Depends(get_db),
    usuario_id: str = Depends(verificar_Peticion_Movil)
):
    nuevo_id = generar_id_pedido(db)
    
    nuevoPedido = Crear_Pedidos(
        id=nuevo_id,
        usuario_id=int(usuario_id),
        origen=pedidoP.origen,
        destino=pedidoP.destino,
        peso=pedidoP.peso,
        tipo=pedidoP.tipo,
        altura=pedidoP.altura,
        anchura=pedidoP.anchura,
        descripcion=pedidoP.descripcion,
        fecha=date.today()
    )
    
    db.add(nuevoPedido)
    db.commit()
    db.refresh(nuevoPedido)
    return nuevoPedido

@router.put("/{id}", status_code=status.HTTP_200_OK)
async def actualizar_pedido(
    id: str,
    pedidoP: PedidoBase,
    db: Session = Depends(get_db),
    usuario_id: str = Depends(verificar_Peticion_Movil)
):
    pedido_db = db.query(Crear_Pedidos).filter(
        Crear_Pedidos.id == id,
        Crear_Pedidos.usuario_id == int(usuario_id)
    ).first()
    
    if not pedido_db:
        raise HTTPException(status_code=404, detail="Pedido no encontrado")

    pedido_db.origen = pedidoP.origen
    pedido_db.destino = pedidoP.destino
    pedido_db.peso = pedidoP.peso
    pedido_db.tipo = pedidoP.tipo
    pedido_db.altura = pedidoP.altura
    pedido_db.anchura = pedidoP.anchura
    pedido_db.descripcion = pedidoP.descripcion
    
    db.commit()
    db.refresh(pedido_db)
    return {"mensaje": "Pedido Actualizado", "Pedido": pedido_db}

@router.delete("/{id}", status_code=status.HTTP_200_OK)
async def eliminar_pedido(
    id: str,
    db: Session = Depends(get_db),
    usuario_id: str = Depends(verificar_Peticion_Movil)
):
    pedido_db = db.query(Crear_Pedidos).filter(
        Crear_Pedidos.id == id,
        Crear_Pedidos.usuario_id == int(usuario_id)
    ).first()
    
    if not pedido_db:
        raise HTTPException(status_code=404, detail="Pedido no encontrado")

    db.delete(pedido_db)
    db.commit()
    return {"mensaje": "Pedido eliminado correctamente"}