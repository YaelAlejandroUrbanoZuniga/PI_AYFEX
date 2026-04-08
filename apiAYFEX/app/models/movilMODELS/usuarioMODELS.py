from pydantic import BaseModel, EmailStr
from typing import Annotated
from pydantic import StringConstraints

class UsuarioRegistro(BaseModel):
    nombre_completo: Annotated[str, StringConstraints(min_length=3, max_length=150)]
    correo_electronico: EmailStr
    telefono: Annotated[str, StringConstraints(min_length=7, max_length=20)]
    password: Annotated[str, StringConstraints(min_length=6, max_length=100)]

class UsuarioLogin(BaseModel):
    correo_electronico: EmailStr
    password: str

class UsuarioResponse(BaseModel):
    id: int
    nombre_completo: str
    correo_electronico: str
    telefono: str

    class Config:
        from_attributes = True