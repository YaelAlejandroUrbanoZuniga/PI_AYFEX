from fastapi import APIRouter, Depends, HTTPException, status
from fastapi.security import OAuth2PasswordRequestForm
from sqlalchemy.orm import Session

from app.data.webDATA.crear_perfilDATAW import UsuarioDB
from app.data.dbDATA import get_db
from app.security.authSECURITY import verificar_password, crear_token

router = APIRouter(
    prefix="/v1/login",
    tags=["Web | Autenticación"]
)

@router.post("/")
def iniciar_sesion(
    
    form_data: OAuth2PasswordRequestForm = Depends(),
    db: Session = Depends(get_db)
):
    
    usuario_db = db.query(UsuarioDB).filter(UsuarioDB.username == form_data.username).first()

    
    if not usuario_db or not verificar_password(form_data.password, usuario_db.password):
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Correo o contraseña incorrectos",
            headers={"WWW-Authenticate": "Bearer"},
        )

    
    token_acceso = crear_token(data={"sub": usuario_db.username})

    
    return {
        "access_token": token_acceso,
        "token_type": "bearer",
        "usuario": usuario_db.username,
        "nombre": usuario_db.nombre_completo
    }