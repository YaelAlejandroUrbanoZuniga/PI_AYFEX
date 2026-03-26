from fastapi import status , HTTPException, Depends, APIRouter
from app.models.pedido import Pedido
from app.data.database import pedidos
from app.security.auth import Verificar_Peticion
from datetime import datetime

from sqlalchemy.orm import Session
from app.data.db import get_db
from app.data.pedido import Pedido as pedidoDB

router = APIRouter(
    prefix="/v1/pedidos",
    tags=["Pedidos"]
)

# ---------- Endpoints Pedidos --------------- 

#Endpoint GET Obtener Pedidos
@router.get("/")
async def leer_pedidos(db: Session = Depends(get_db)):
    queryPedidos = db.query(PedidoDB).all()
    return {
        "status": "200",
        "total": len(queryPedidos),
        "pedidos": queryPedidos
    }

#Endpoint Post Crear Pedidos
@router.post("/", status_code=status.HTTP_201_CREATED)
async def crear_pedido(pedidoP: PedidoCreate, db: Session = Depends(get_db)):
    
    nuevoPedido = PedidoDB(
        origen=pedidoP.origen,
        destino=pedidoP.destino,
        peso=pedidoP.peso,
        tipo=pedidoP.tipo,
        altura=pedidoP.altura,
        anchura=pedidoP.anchura,
        descripcion=pedidoP.descripcion,
        fecha=datetime.now()
    )
    
    db.add(nuevoPedido)
    db.commit()
    db.refresh(nuevoPedido) 

    return {
        "mensaje": "Pedido Agregado Exitosamente",
        "Pedido": nuevoPedido
    }

#Endpoint Put Editar Pedido
@router.put("/{id}", status_code=status.HTTP_200_OK)
async def actualizar_pedido(id: int, pedidoP: PedidoCreate, db: Session = Depends(get_db)):
    pedido_db = db.query(PedidoDB).filter(PedidoDB.id == id).first()
    
    if pedido_db:
        
        pedido_db.origen = pedidoP.origen
        pedido_db.destino = pedidoP.destino
        pedido_db.peso = pedidoP.peso
        pedido_db.tipo = pedidoP.tipo
        pedido_db.altura = pedidoP.altura
        pedido_db.anchura = pedidoP.anchura
        pedido_db.descripcion = pedidoP.descripcion
        
        db.commit()
        db.refresh(pedido_db)
        
        return {
            "mensaje": "Pedido Actualizado",
            "Pedido": pedido_db
        }
        
    raise HTTPException(status_code=404, detail="El ID del pedido no existe")

#Endpoint Delete Eliminar Pedido
@router.delete("/{id}", status_code=status.HTTP_200_OK)
async def eliminar_pedido(id: int, db: Session = Depends(get_db), userAuth: str = Depends(Verificar_Peticion)):
    pedido_db = db.query(PedidoDB).filter(PedidoDB.id == id).first()
    
    if pedido_db:
        db.delete(pedido_db)
        db.commit()
        return {
            "mensaje": f"Pedido Eliminado por el usuario: {userAuth}"
        }
        
    raise HTTPException(status_code=404, detail="El ID del pedido no existe")