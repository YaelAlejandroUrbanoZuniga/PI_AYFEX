from sqlalchemy import Column, Integer, String, ForeignKey, or_
from sqlalchemy.orm import relationship, Session
from app.data.dbDATA import Base
from app.data.webDATA.crear_operadoresDATAW import Crear_Operadores

class RutaDB(Base):
    __tablename__ = "web_rutas"

    id = Column(Integer, primary_key=True, index=True)
    nombre = Column(String(100), nullable=False)
    codigo = Column(String(50), nullable=False, unique=True)
    estado = Column(String(20), default="Activa")
    
    
    zonas_cubiertas_raw = Column(String(500), nullable=True)

     
    
    
    operador_id = Column(Integer, ForeignKey("operadores_web.id"), nullable=True)
    operador = relationship("Crear_Operadores", backref="rutas")

    @property
    def zonas_cubiertas(self) -> list[str]:
        if not self.zonas_cubiertas_raw:
            return []
        return [zona.strip() for zona in self.zonas_cubiertas_raw.split(',') if zona.strip()]

    @zonas_cubiertas.setter
    def zonas_cubiertas(self, zonas: list[str]):
        if not zonas:
            self.zonas_cubiertas_raw = ""
        else:
            self.zonas_cubiertas_raw = ", ".join(zonas)



def obtener_todas_las_rutas(db: Session, query: str = None):
    """Obtiene rutas y hace join con operadores para evitar errores de relación."""
    db_query = db.query(RutaDB).join(RutaDB.operador, isouter=True)

    if query:
        search = f"%{query}%"
        db_query = db_query.filter(
            or_(
                RutaDB.nombre.ilike(search),
                RutaDB.codigo.ilike(search),
                RutaDB.zonas_cubiertas_raw.ilike(search)
            )
        )
    return db_query.all()

def crear_nueva_ruta_db(db: Session, nueva_ruta_data: dict):
    
    zonas = nueva_ruta_data.pop("zonas_cubiertas", [])
    
    db_ruta = RutaDB(**nueva_ruta_data)
    db_ruta.zonas_cubiertas = zonas 
    
    db.add(db_ruta)
    db.commit()
    db.refresh(db_ruta)
    return db_ruta

def obtener_ruta_por_id(db: Session, ruta_id: int):
    return db.query(RutaDB).filter(RutaDB.id == ruta_id).first()

def actualizar_ruta_db(db: Session, db_ruta: RutaDB, datos_actualizados: dict):
    if "zonas_cubiertas" in datos_actualizados:
        db_ruta.zonas_cubiertas = datos_actualizados.pop("zonas_cubiertas")
        
    for key, value in datos_actualizados.items():
        setattr(db_ruta, key, value)
        
    db.commit()
    db.refresh(db_ruta)
    return db_ruta

def eliminar_ruta_db(db: Session, db_ruta: RutaDB):
    db.delete(db_ruta)
    db.commit()