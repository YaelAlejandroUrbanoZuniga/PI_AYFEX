from pydantic import BaseModel, EmailStr, Field
from datetime import date
from typing import Optional

# 1. Esquema para VER el perfil (lo que la API devuelve)
class PerfilResponse(BaseModel):
    id: int
    # Información Personal
    nombre_completo: str
    correo_electronico: EmailStr
    telefono: Optional[str] = None
    foto_url: str = "" # Añadimos valor por defecto para evitar errores si no hay foto

    # Estado de la Cuenta
    fecha_registro: date
    esta_activo: bool = True # Por defecto activo

    # Nivel de Acceso (Forzado a True por el momento)
    tiene_acceso_envios: bool = True
    tiene_acceso_clientes: bool = True
    tiene_acceso_operadores: bool = True
    tiene_acceso_reportes: bool = True

    class Config:
        from_attributes = True # Clave para SQLAlchemy

# 2. Esquema para EDITAR la información personal
class PerfilUpdate(BaseModel):
    nombre_completo: str = Field(..., min_length=5, max_length=150)
    correo_electronico: EmailStr = Field(..., max_length=150)
    telefono: Optional[str] = Field(None, max_length=20)