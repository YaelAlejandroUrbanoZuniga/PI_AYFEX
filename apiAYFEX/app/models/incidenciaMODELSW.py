from pydantic import BaseModel
from datetime import date
from typing import Optional

class IncidenciaBase(BaseModel):
    envio_id: str
    tipo: str
    descripcion: str

class IncidenciaCreate(IncidenciaBase):
    pass

class Incidencia(IncidenciaBase):
    id: str
    estado: str
    responsable: Optional[str] = None
    fecha: date

    class Config:
        from_attributes = True # Permite convertir el modelo de DB a JSON