from sqlalchemy import Column, Integer, String, Float, Date, ForeignKey
from app.data.dbDATA import Base
from datetime import date

class Crear_Pedidos(Base):
    __tablename__ = "CrearPedidos"
    
    id = Column(String(20), primary_key=True, index=True, autoincrement=False)
    usuario_id = Column(Integer, ForeignKey("usuarios_movil.id"), nullable=False, index=True)
    origen = Column(String(150), nullable=False)
    destino = Column(String(150), nullable=False)
    peso = Column(Float, nullable=False)
    tipo = Column(String(50), nullable=False)
    altura = Column(Float, nullable=False)
    anchura = Column(Float, nullable=False)
    descripcion = Column(String(300), default="")
    fecha = Column(Date, default=date.today, nullable=False)

    # Nuevos campos de estado y logística
    estado = Column(String(30), default="EN PREPARACIÓN", nullable=False)
    ruta_nombre = Column(String(100), nullable=True)
    ruta_codigo = Column(String(50), nullable=True)
    ruta_zonas = Column(String(500), nullable=True)
    operador_nombre = Column(String(150), nullable=True)
    operador_telefono = Column(String(20), nullable=True)
    dias_estimados = Column(Integer, nullable=True)
    fecha_asignacion = Column(Date, nullable=True)
    motivo_rechazo = Column(String(300), nullable=True)