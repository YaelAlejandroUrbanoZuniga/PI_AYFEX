from pydantic import BaseModel, EmailStr, Field
from datetime import date
from typing import Optional


class PerfilResponse(BaseModel):
    id: int
    
    nombre_completo: str
    correo_electronico: EmailStr
    telefono: Optional[str] = None
    foto_url: str = "" 

    
    fecha_registro: date
    esta_activo: bool = True 

    
    tiene_acceso_envios: bool = True
    tiene_acceso_clientes: bool = True
    tiene_acceso_operadores: bool = True
    tiene_acceso_reportes: bool = True

    class Config:
        from_attributes = True 


class PerfilUpdate(BaseModel):
    nombre_completo: str = Field(..., min_length=5, max_length=150)
    correo_electronico: EmailStr = Field(..., max_length=150)
    telefono: Optional[str] = Field(None, max_length=20)