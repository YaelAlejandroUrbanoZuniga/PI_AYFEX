from sqlalchemy import Column, Integer, String, Boolean, Date
from app.data.dbDATA import Base
from datetime import date

class UsuarioDB(Base):
    __tablename__ = "usuarios_web"

    id = Column(Integer, primary_key=True, index=True)
    
    username = Column(String(100), unique=True, index=True, nullable=False) 
    
    
    nombre_completo = Column(String(150), nullable=False)
    correo_electronico = Column(String(150), unique=True, index=True, nullable=False)
    telefono = Column(String(20), nullable=True)
    foto_url = Column(String(255), nullable=True, default="default_avatar.png")
    
    
    password = Column(String(255), nullable=False)

   
    fecha_registro = Column(Date, default=date.today)
    esta_activo = Column(Boolean, default=True)

    
    tiene_acceso_envios = Column(Boolean, default=False)
    tiene_acceso_clientes = Column(Boolean, default=False)
    tiene_acceso_operadores = Column(Boolean, default=False)
    tiene_acceso_reportes = Column(Boolean, default=False)