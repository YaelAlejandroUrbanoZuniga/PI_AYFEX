from sqlalchemy import Column, Integer, String, Text, Date, ForeignKey
from app.data.dbDATA import Base
from datetime import date

class Crear_Reporte(Base):
    __tablename__ = "reportes"

    id = Column(Integer, primary_key=True, index=True, autoincrement=True)
    usuario_id = Column(Integer, ForeignKey("usuarios_movil.id"), nullable=False, index=True)
    tipo = Column(String(150), nullable=False)
    descripcion = Column(Text, nullable=False)
    prioridad = Column(String(20), default="NORMAL", nullable=False)
    estado = Column(String(30), default="PENDIENTE", nullable=False)
    fecha = Column(Date, default=date.today, nullable=False)