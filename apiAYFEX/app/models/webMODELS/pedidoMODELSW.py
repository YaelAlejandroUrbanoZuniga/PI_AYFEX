from pydantic import BaseModel
from datetime import date
from typing import Optional

# Lo que devuelve la API a la Web (Laravel)
# Todos los campos coinciden EXACTAMENTE con la tabla Crear_Pedidos
class Pedido(BaseModel):
    id: str                              # ✅ String, no int
    origen: str
    destino: str
    peso: float
    tipo: str
    descripcion: Optional[str] = None
    fecha: date                          # ✅ nombre real del campo en la DB
    estado: str
    ruta_id: Optional[int] = None
    ruta_nombre: Optional[str] = None
    ruta_codigo: Optional[str] = None
    ruta_zonas: Optional[str] = None
    operador_nombre: Optional[str] = None
    operador_telefono: Optional[str] = None
    dias_estimados: Optional[int] = None
    fecha_asignacion: Optional[date] = None
    motivo_rechazo: Optional[str] = None

    class Config:
        from_attributes = True


# Esquema para confirmar un pedido desde la web
class PedidoConfirmar(BaseModel):
    ruta_id: int
    dias_estimados: int


# Esquema para rechazar un pedido desde la web
class PedidoRechazar(BaseModel):
    motivo_rechazo: str