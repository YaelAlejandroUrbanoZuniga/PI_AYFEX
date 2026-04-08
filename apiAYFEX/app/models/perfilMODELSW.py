from pydantic import BaseModel, EmailStr, Field
from datetime import date
from typing import Optional

# 1. Esquema para VER el perfil (lo que la API devuelve)
# Coincide con la estructura visual de image_6.png
class PerfilResponse(BaseModel):
    id: int
    # Información Personal
    nombre_completo: str
    correo_electronico: EmailStr
    telefono: Optional[str] = None
    foto_url: str

    # Estado de la Cuenta
    fecha_registro: date
    esta_activo: bool

    # Nivel de Acceso
    tiene_acceso_envios: bool
    tiene_acceso_clientes: bool
    tiene_acceso_operadores: bool
    tiene_acceso_reportes: bool

    class Config:
        from_attributes = True # Clave para SQLAlchemy

# 2. Esquema para EDITAR la información personal (lo que entra por POST/PUT)
# Basado en el formulario de la imagen
class PerfilUpdate(BaseModel):
    nombre_completo: str = Field(..., min_length=5, max_length=150)
    correo_electronico: EmailStr = Field(..., max_length=150)
    telefono: Optional[str] = Field(None, max_length=20)
    # Nota: No permitimos actualizar permisos aquí, eso es tema administrativo.
    # Nota: No permitimos actualizar la fecha de registro.