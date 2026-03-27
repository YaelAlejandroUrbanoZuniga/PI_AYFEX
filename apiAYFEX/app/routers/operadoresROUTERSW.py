from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session
from app.data.dbDATA import get_db
from app.models.operadorMODELSW import OperadorBase
from app.data.crear_operadoresDATAW import Crear_Operadores
# Si quieres proteger la ruta de eliminar, descomenta la siguiente línea:
from app.security.authSECURITY import verificar_Peticion 

router = APIRouter(
    prefix="/v1/operadores",
    tags=["Operadores"]
)

# GET: Obtener todos los operadores
@router.get("/")
async def leer_operadores(db: Session = Depends(get_db)):
    queryOperadores = db.query(Crear_Operadores).all()
    return {
        "status": "200",
        "total": len(queryOperadores),
        "operadores": queryOperadores
    }

# POST: Crear un nuevo operador
@router.post("/", status_code=status.HTTP_201_CREATED)
async def crear_operador(operadorP: OperadorBase, db: Session = Depends(get_db)):
    nuevoOperador = Crear_Operadores(
        nombre_completo=operadorP.nombre_completo,
        identificador=operadorP.identificador,
        telefono=operadorP.telefono,
        vehiculo_asignado=operadorP.vehiculo_asignado,
        estado=operadorP.estado
    )
    
    db.add(nuevoOperador)
    db.commit()
    db.refresh(nuevoOperador)

    return {
        "mensaje": "Operador Agregado Exitosamente",
        "Operador": nuevoOperador
    }

# PUT: Editar un operador
@router.put("/{id}", status_code=status.HTTP_200_OK)
async def actualizar_operador(id: int, operadorP: OperadorBase, db: Session = Depends(get_db)):
    operador_db = db.query(Crear_Operadores).filter(Crear_Operadores.id == id).first()
    
    if operador_db:
        operador_db.nombre_completo = operadorP.nombre_completo
        operador_db.identificador = operadorP.identificador
        operador_db.telefono = operadorP.telefono
        operador_db.vehiculo_asignado = operadorP.vehiculo_asignado
        if operadorP.estado:
            operador_db.estado = operadorP.estado
            
        db.commit()
        db.refresh(operador_db)
        
        return {
            "mensaje": "Operador Actualizado",
            "Operador": operador_db
        }
        
    raise HTTPException(status_code=404, detail="El ID del operador no existe")

# DELETE: Eliminar un operador
@router.delete("/{id}", status_code=status.HTTP_200_OK)
async def eliminar_operador(id: int, db: Session = Depends(get_db),userAuth: str = Depends(verificar_Peticion)): 
    # Si quieres usar autenticación, agrega: userAuth: str = Depends(verificar_Peticion) en los parámetros
    operador_db = db.query(Crear_Operadores).filter(Crear_Operadores.id == id).first()
    
    if operador_db:
        db.delete(operador_db)
        db.commit()
        return {
            "mensaje": "Operador Eliminado correctamente"
        }
        
    raise HTTPException(status_code=404, detail="El ID del operador no existe")