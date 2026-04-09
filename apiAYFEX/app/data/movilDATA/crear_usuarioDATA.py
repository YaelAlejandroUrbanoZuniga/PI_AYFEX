from sqlalchemy import Column, Integer, String
from app.data.dbDATA import Base

class UsuarioMDB(Base):
    __tablename__ = "usuarios_movil"

    id = Column(Integer, primary_key=True, index=True)
    nombre_completo = Column(String(150), nullable=False)
    correo_electronico = Column(String(150), unique=True, index=True, nullable=False)
    telefono = Column(String(20), nullable=False)
    password_hash = Column(String(255), nullable=False)