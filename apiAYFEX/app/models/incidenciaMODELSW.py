from pydantic import BaseModel
from typing import Optional
from datetime import date

# Lo que recibimos del formulario de Laravel
class IncidenciaCreate(BaseModel):
    envio_id: str
    tipo: str
    descripcion: str

# Lo que enviamos hacia Laravel (la tabla)
class Incidencia(BaseModel):
    id: str
    envio_id: str
    tipo: str
    descripcion: str
    estado: str
    responsable: str
    fecha: str