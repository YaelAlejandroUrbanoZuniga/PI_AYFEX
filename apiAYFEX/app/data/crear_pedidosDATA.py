# ==================DEFINIMOS MODELO DE LA BASE DE DATOS==================
from sqlalchemy import Column, Integer, String, Float, Date
from app.data.dbDATA import Base
from datetime import date


class Crear_Pedidos(Base):
    __tablename__ = "CrearPedidos"
    
    id = Column(Integer, primary_key=True, index=True)

    origen = Column(String(150), nullable=False)
    destino = Column(String(150), nullable=False)

    peso = Column(Float, nullable=False)

    tipo = Column(String(50), nullable=False)

    altura = Column(Float, nullable=False)
    anchura = Column(Float, nullable=False)

    descripcion = Column(String(300), default="")
    fecha = Column(Date, default=date.today, nullable=False)