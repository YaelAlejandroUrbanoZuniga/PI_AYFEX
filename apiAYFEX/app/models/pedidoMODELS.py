from pydantic import BaseModel, Field, StringConstraints
from typing import Annotated
from datetime import date # <-- Agregamos esta importación

class PedidoBase(BaseModel):
    origen: Annotated[str, StringConstraints(min_length=5, max_length=150)]
    destino: Annotated[str, StringConstraints(min_length=5, max_length=150)]

    peso: float = Field(..., gt=0, le=999.99)

    tipo: Annotated[str, StringConstraints(min_length=3, max_length=50)]

    altura: float = Field(..., gt=0, le=999.99)
    anchura: float = Field(..., gt=0, le=999.99)

    descripcion: Annotated[str, StringConstraints(max_length=300)] = ""

# Esquema para crear (lo que recibe el POST desde el frontend)
class PedidoCreate(PedidoBase):
    pass

# Esquema de respuesta (lo que la API le devuelve al frontend)
class Pedido(PedidoBase):
    id: int
    fecha: date # <-- Agregamos la fecha para que se devuelva correctamente

    class Config:
        from_attributes = True # <-- Esto es clave para que lea desde SQLAlchemy