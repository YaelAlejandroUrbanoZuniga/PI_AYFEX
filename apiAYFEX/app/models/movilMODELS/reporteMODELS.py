from pydantic import BaseModel, StringConstraints
from typing import Annotated
from datetime import date

class ReporteCreate(BaseModel):
    tipo: Annotated[str, StringConstraints(min_length=3, max_length=150)]
    descripcion: Annotated[str, StringConstraints(min_length=10)]
    prioridad: str = "NORMAL"

class Reporte(BaseModel):
    id: int
    usuario_id: int
    tipo: str
    descripcion: str
    prioridad: str
    estado: str
    fecha: date

    class Config:
        from_attributes = True