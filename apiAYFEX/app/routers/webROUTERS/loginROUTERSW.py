from fastapi import APIRouter, Depends, HTTPException, status
from fastapi.security import OAuth2PasswordRequestForm
from sqlalchemy.orm import Session

from app.data.webDATA.crear_perfilDATAW import UsuarioDB
from app.data.dbDATA import get_db
from app.security.authSECURITY import verificar_password, crear_token

router = APIRouter(
    prefix="/v1/login",
    tags=["Autenticación"]
)

@router.post("/")
def iniciar_sesion(
    # OAuth2PasswordRequestForm extrae automáticamente el usuario y contraseña del formulario
    form_data: OAuth2PasswordRequestForm = Depends(),
    db: Session = Depends(get_db)
):
    # 1. Buscamos al usuario en la BD (FastAPI por defecto llama "username" al campo de texto)
    usuario_db = db.query(UsuarioDB).filter(UsuarioDB.username == form_data.username).first()

    # 2. Verificamos si existe y si la contraseña coincide
    if not usuario_db or not verificar_password(form_data.password, usuario_db.password):
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Correo o contraseña incorrectos",
            headers={"WWW-Authenticate": "Bearer"},
        )

    # 3. Si todo es correcto, generamos el JWT
    token_acceso = crear_token(data={"sub": usuario_db.username})

    # 4. Devolvemos la estructura exacta que JWT exige
    return {
        "access_token": token_acceso,
        "token_type": "bearer",
        "usuario": usuario_db.username,
        "nombre": usuario_db.nombre_completo
    }