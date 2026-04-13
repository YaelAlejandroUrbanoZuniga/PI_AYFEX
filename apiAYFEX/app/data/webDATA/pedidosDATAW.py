from sqlalchemy import Column, Integer, String, Float, Text, Date
from app.data.dbDATA import Base 

class Pedidos(Base):
    __tablename__ = "pedidos_web" # Esta tabla es la que compartes con la app móvil

    id = Column(Integer, primary_key=True, index=True) 
    cliente = Column(String(150), nullable=False)
    origen = Column(String(100), nullable=False)
    destino = Column(String(100), nullable=False)
    peso = Column(Float, nullable=False)
    estado = Column(String(50), default="EN ESPERA", nullable=False)
    
    # Datos que se llenan cuando la web confirma
    operador_nombre = Column(String(100), nullable=True)
    ruta_id = Column(Integer, nullable=True)
    dias_estimados = Column(Integer, nullable=True)
    
    # Dato que se llena si la web rechaza
    motivo_rechazo = Column(Text, nullable=True)
    
    fecha_creacion = Column(Date, nullable=False)