from fastapi import APIRouter, Depends
from sqlalchemy.orm import Session
from typing import List
from pydantic import BaseModel

from app.data.movilDATA.crear_usuarioDATA import UsuarioMDB
from app.data.movilDATA.crear_pedidosDATA import Crear_Pedidos
from app.data.dbDATA import get_db
from app.security.authSECURITY import verificar_Peticion

router = APIRouter(
    prefix="/v1/clientes",
    tags=["Web | Clientes"]
)

class ClienteResponse(BaseModel):
    id: int
    nombre_completo: str
    correo_electronico: str
    telefono: str
    total_envios: int

    class Config:
        from_attributes = True


@router.get("/", response_model=List[ClienteResponse])
def obtener_clientes(
    db: Session = Depends(get_db),
    usuario_actual=Depends(verificar_Peticion)
):
    usuarios = db.query(UsuarioMDB).all()

    resultado = []
    for u in usuarios:
        total = db.query(Crear_Pedidos).filter(Crear_Pedidos.usuario_id == u.id).count()
        resultado.append(ClienteResponse(
            id=u.id,
            nombre_completo=u.nombre_completo,
            correo_electronico=u.correo_electronico,
            telefono=u.telefono,
            total_envios=total
        ))

    return resultado