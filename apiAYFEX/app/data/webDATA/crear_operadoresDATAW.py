from sqlalchemy import Column, Integer, String
from app.data.dbDATA import Base

class Crear_Operadores(Base):
    __tablename__ = "operadores"

    id = Column(Integer, primary_key=True, index=True)
    nombre_completo = Column(String(150), nullable=False)
    identificador = Column(String(50), unique=True, index=True, nullable=False)
    telefono = Column(String(20), nullable=False)
    vehiculo_asignado = Column(String(100), nullable=False)
    estado = Column(String(20), default="DISPONIBLE") # Puede ser DISPONIBLE o EN RUTA