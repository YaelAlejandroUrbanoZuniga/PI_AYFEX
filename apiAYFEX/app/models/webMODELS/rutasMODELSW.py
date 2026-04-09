from pydantic import BaseModel, Field
from typing import Optional, List

# 1. Esquema para VER una ruta (lo que la API devuelve para llenar las tarjetas)
# Coincide con la estructura visual de image_1.png
class RutaResponse(BaseModel):
    id: int
    nombre: str = Field(..., description="Nombre de la Ruta, ej: Ruta Centro")
    codigo: str = Field(..., description="Código de Ruta, ej: RJTT-001")
    estado: str = Field(..., description="Estado de la Ruta, ej: ACTIVA")
    
    # Campo convertido a lista para que el frontend pinte los tags fácilmente
    zonas_cubiertas: List[str] = Field(..., description="Lista de Zonas Cubiertas")
    
    # Objeto simplificado del operador asignado
    nombre_operador: Optional[str] = Field(None, description="Nombre del Operador asignado")

    class Config:
        from_attributes = True # Clave para SQLAlchemy

# 2. Esquema para CREAR una ruta (lo que entra por POST/PUT del formulario modal)
# Coincide con el formulario de image_2.png
class RutaCreate(BaseModel):
    nombre: str = Field(..., min_length=3, max_length=100, description="Nombre de la Ruta")
    codigo: str = Field(..., min_length=3, max_length=50, description="Código de Ruta")
    operador_id: Optional[int] = Field(None, description="ID del Operador Asignado")
    estado: str = Field("Activa", description="Estado de la Ruta")
    
    # Campo que recibe la lista de zonas cubiertas
    zonas_cubiertas: List[str] = Field(..., description="Lista de Zonas Cubiertas")

# 3. Esquema simplificado para la lista de operadores activos (para el dropdown del modal)
class OperadorSimplificado(BaseModel):
    id: int
    nombre_completo: str
    
    class Config:
        from_attributes = True