from pydantic import BaseModel, Field, StringConstraints
from typing import Annotated

class PedidoBase(BaseModel):
    origen: Annotated[str, StringConstraints(min_length=5, max_length=150)]
    destino: Annotated[str, StringConstraints(min_length=5, max_length=150)]

    peso: float = Field(..., gt=0, le=999.99)

    tipo: Annotated[str, StringConstraints(min_length=3, max_length=50)]

    altura: float = Field(..., gt=0, le=999.99)
    anchura: float = Field(..., gt=0, le=999.99)

    descripcion: Annotated[str, StringConstraints(max_length=300)] = ""