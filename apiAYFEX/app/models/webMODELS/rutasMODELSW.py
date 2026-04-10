from pydantic import BaseModel, Field
from typing import Optional, List


class RutaResponse(BaseModel):
    id: int
    nombre: str = Field(..., description="Nombre de la Ruta, ej: Ruta Centro")
    codigo: str = Field(..., description="Código de Ruta, ej: RJTT-001")
    estado: str = Field(..., description="Estado de la Ruta, ej: ACTIVA")
    
    
    zonas_cubiertas: List[str] = Field(..., description="Lista de Zonas Cubiertas")
    
    
    nombre_operador: Optional[str] = Field(None, description="Nombre del Operador asignado")

    class Config:
        from_attributes = True 


class RutaCreate(BaseModel):
    nombre: str = Field(..., min_length=3, max_length=100, description="Nombre de la Ruta")
    codigo: str = Field(..., min_length=3, max_length=50, description="Código de Ruta")
    operador_id: Optional[int] = Field(None, description="ID del Operador Asignado")
    estado: str = Field("Activa", description="Estado de la Ruta")
    
    
    zonas_cubiertas: List[str] = Field(..., description="Lista de Zonas Cubiertas")


class OperadorSimplificado(BaseModel):
    id: int
    nombre_completo: str
    
    class Config:
        from_attributes = True