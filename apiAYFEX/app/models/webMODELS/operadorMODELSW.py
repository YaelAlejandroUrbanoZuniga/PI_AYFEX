from pydantic import BaseModel, StringConstraints
from typing import Annotated, Optional

class OperadorBase(BaseModel):
    nombre_completo: Annotated[str, StringConstraints(min_length=3, max_length=150)]
    identificador: Annotated[str, StringConstraints(min_length=3, max_length=50)]
    telefono: Annotated[str, StringConstraints(min_length=7, max_length=20)]
    vehiculo_asignado: Annotated[str, StringConstraints(min_length=3, max_length=100)]
    estado: Optional[str] = "DISPONIBLE"