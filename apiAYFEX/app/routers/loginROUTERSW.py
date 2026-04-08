from fastapi import APIRouter, Depends
from sqlalchemy.orm import Session
from app.data.crear_perfilDATAW import UsuarioDB
from app.data.dbDATA import get_db
from app.security.authSECURITY import verificar_Peticion

router = APIRouter(
    prefix="/v1/login",
    tags=["Autenticación"]
)

@router.post("/")
def iniciar_sesion(
    username_actual: str = Depends(verificar_Peticion),
    db: Session = Depends(get_db)
):
    # Buscamos al usuario solo para devolver su nombre completo en la respuesta
    usuario_db = db.query(UsuarioDB).filter(UsuarioDB.username == username_actual).first()

    return {
        "status": "success",
        "mensaje": "Login exitoso",
        "usuario": username_actual,
        "nombre": usuario_db.nombre_completo
    }