from sqlalchemy import Column, Integer, String, Boolean, Date
from app.data.dbDATA import Base
from datetime import date

class UsuarioDB(Base):
    __tablename__ = "usuarios"

    id = Column(Integer, primary_key=True, index=True)
    # Este es el que hace match con tu authSECURITY (ej. "JuanFidelJuarezTorres")
    username = Column(String(100), unique=True, index=True, nullable=False) 
    
    # Información Personal
    nombre_completo = Column(String(150), nullable=False)
    correo_electronico = Column(String(150), unique=True, index=True, nullable=False)
    telefono = Column(String(20), nullable=True)
    foto_url = Column(String(255), nullable=True, default="default_avatar.png")
    
    #  Columna para guardar la contraseña
    password = Column(String(255), nullable=False)

    # Estado de la Cuenta
    fecha_registro = Column(Date, default=date.today)
    esta_activo = Column(Boolean, default=True)

    # Nivel de Acceso 
    tiene_acceso_envios = Column(Boolean, default=False)
    tiene_acceso_clientes = Column(Boolean, default=False)
    tiene_acceso_operadores = Column(Boolean, default=False)
    tiene_acceso_reportes = Column(Boolean, default=False)