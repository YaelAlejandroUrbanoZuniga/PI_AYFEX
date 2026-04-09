from fastapi import APIRouter, Depends, HTTPException, status, Query
from sqlalchemy.orm import Session
from typing import List, Optional

# Importaciones de Base de Datos y Funciones CRUD
from app.data.webDATA.rutasDATAW import (
    RutaDB, 
    obtener_todas_las_rutas, 
    crear_nueva_ruta_db, 
    obtener_ruta_por_id, 
    actualizar_ruta_db, 
    eliminar_ruta_db
)
from app.data.webDATA.crear_perfilDATAW import UsuarioDB 
from app.data.webDATA.crear_operadoresDATAW import Crear_Operadores  # El modelo real de operadores
from app.models.webMODELS.rutasMODELSW import RutaResponse, RutaCreate, OperadorSimplificado
from app.data.dbDATA import get_db

# Seguridad
from app.security.authSECURITY import verificar_Peticion

router = APIRouter(
    prefix="/v1/rutas",
    tags=["Rutas de Distribución"]
)

# 1. OBTENER TODAS LAS RUTAS (Para las tarjetas de la vista principal)
@router.get("/", response_model=List[RutaResponse])
def leer_rutas(
    query: Optional[str] = Query(None, description="Texto de búsqueda"),
    usuario_actual: UsuarioDB = Depends(verificar_Peticion),
    db: Session = Depends(get_db)
):
    rutas_db = obtener_todas_las_rutas(db, query)
    
    respuesta_rutas = []
    for db_ruta in rutas_db:
        # Obtenemos el nombre del operador desde la relación con Crear_Operadores
        nombre_operador = db_ruta.operador.nombre_completo if db_ruta.operador else "Sin asignar"
        
        respuesta_rutas.append(RutaResponse(
            id=db_ruta.id,
            nombre=db_ruta.nombre,
            codigo=db_ruta.codigo,
            estado=db_ruta.estado.upper(),
            zonas_cubiertas=db_ruta.zonas_cubiertas,
            nombre_operador=nombre_operador
        ))
        
    return respuesta_rutas

# 2. OBTENER OPERADORES DISPONIBLES (Para el dropdown del modal)
@router.get("/operadores/activos", response_model=List[OperadorSimplificado])
def leer_operadores_activos(
    usuario_actual: UsuarioDB = Depends(verificar_Peticion),
    db: Session = Depends(get_db)
):
    # CORRECCIÓN: Buscamos en la tabla 'operadores' filtrando por estado DISPONIBLE
    operadores_db = db.query(Crear_Operadores).filter(
        Crear_Operadores.estado == "DISPONIBLE"
    ).all()
    
    return operadores_db

# 3. CREAR NUEVA RUTA
@router.post("/", response_model=RutaResponse, status_code=status.HTTP_201_CREATED)
def crear_ruta(
    ruta_create: RutaCreate,
    usuario_actual: UsuarioDB = Depends(verificar_Peticion),
    db: Session = Depends(get_db)
):
    # Validar código único
    if db.query(RutaDB).filter(RutaDB.codigo == ruta_create.codigo).first():
        raise HTTPException(status_code=400, detail="Este código de ruta ya existe.")

    # Preparar datos (quitamos la lista para que el setter de la DB la maneje como string)
    datos_ruta_db = ruta_create.model_dump()
    zonas = datos_ruta_db.pop('zonas_cubiertas', [])
    
    db_ruta = crear_nueva_ruta_db(db, datos_ruta_db)
    db_ruta.zonas_cubiertas = zonas
    db.commit()
    db.refresh(db_ruta)
    
    nombre_operador = db_ruta.operador.nombre_completo if db_ruta.operador else None
    return RutaResponse(
        id=db_ruta.id,
        nombre=db_ruta.nombre,
        codigo=db_ruta.codigo,
        estado=db_ruta.estado.upper(),
        zonas_cubiertas=db_ruta.zonas_cubiertas,
        nombre_operador=nombre_operador
    )

# 4. ACTUALIZAR RUTA
@router.put("/{ruta_id}", response_model=RutaResponse)
def actualizar_ruta(
    ruta_id: int,
    datos_actualizados: RutaCreate,
    usuario_actual: UsuarioDB = Depends(verificar_Peticion),
    db: Session = Depends(get_db)
):
    db_ruta = obtener_ruta_por_id(db, ruta_id)
    if not db_ruta:
        raise HTTPException(status_code=404, detail="Ruta no encontrada")

    # Mapeo de datos
    datos_db = datos_actualizados.model_dump()
    zonas = datos_db.pop('zonas_cubiertas', [])
    
    actualizar_ruta_db(db, db_ruta, datos_db)
    db_ruta.zonas_cubiertas = zonas
    db.commit()
    
    nombre_operador = db_ruta.operador.nombre_completo if db_ruta.operador else None
    return RutaResponse(
        id=db_ruta.id,
        nombre=db_ruta.nombre,
        codigo=db_ruta.codigo,
        estado=db_ruta.estado.upper(),
        zonas_cubiertas=db_ruta.zonas_cubiertas,
        nombre_operador=nombre_operador
    )

# 5. ELIMINAR RUTA
@router.delete("/{ruta_id}", status_code=status.HTTP_204_NO_CONTENT)
def eliminar_ruta(
    ruta_id: int,
    usuario_actual: UsuarioDB = Depends(verificar_Peticion),
    db: Session = Depends(get_db)
):
    db_ruta = obtener_ruta_por_id(db, ruta_id)
    if not db_ruta:
        raise HTTPException(status_code=404, detail="Ruta no encontrada")
    
    eliminar_ruta_db(db, db_ruta)
    return None