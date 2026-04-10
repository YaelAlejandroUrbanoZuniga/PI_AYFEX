from sqlalchemy import Column, String, Text, Date
from app.data.dbDATA import Base 

class Crear_Incidencias(Base):
    __tablename__ = "incidencias_web"

    
    id = Column(String(20), primary_key=True, index=True) 
    envio_id = Column(String(20), index=True, nullable=False)
    tipo = Column(String(100), nullable=False)
    descripcion = Column(Text, nullable=False)
    estado = Column(String(50), default="PENDIENTE", nullable=False)
    responsable = Column(String(100), nullable=True)
    fecha = Column(Date, nullable=False)