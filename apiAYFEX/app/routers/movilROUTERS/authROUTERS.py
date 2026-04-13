from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session
from app.data.dbDATA import get_db
from app.data.movilDATA.crear_usuarioDATA import UsuarioMDB
from app.models.movilMODELS.usuarioMODELS import UsuarioRegistro, UsuarioLogin, UsuarioResponse
from app.security.authSECURITY import crear_token, pwd_context
from fastapi.security import OAuth2PasswordRequestForm


router = APIRouter(
    prefix="/v1/auth",
    tags=["Móvil | Autenticación"]
)

@router.post("/registro", response_model=UsuarioResponse, status_code=status.HTTP_201_CREATED)
def registrar_usuario(datos: UsuarioRegistro, db: Session = Depends(get_db)):
    
    existente = db.query(UsuarioMDB).filter(
        UsuarioMDB.correo_electronico == datos.correo_electronico
    ).first()
    
    if existente:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Este correo ya está registrado"
        )

    nuevo_usuario = UsuarioMDB(
        nombre_completo=datos.nombre_completo,
        correo_electronico=datos.correo_electronico,
        telefono=datos.telefono,
        password_hash=pwd_context.hash(datos.password)
    )

    db.add(nuevo_usuario)
    db.commit()
    db.refresh(nuevo_usuario)

    return nuevo_usuario


@router.post("/login")
def iniciar_sesion(datos: UsuarioLogin, db: Session = Depends(get_db)):

    usuario = db.query(UsuarioMDB).filter(
        UsuarioMDB.correo_electronico == datos.correo_electronico
    ).first()

    if not usuario or not pwd_context.verify(datos.password, usuario.password_hash):
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Correo o contraseña incorrectos"
        )

    token = crear_token({"sub": str(usuario.id)})

    return {
        "access_token": token,
        "token_type": "bearer",
        "usuario": {
            "id": usuario.id,
            "nombre_completo": usuario.nombre_completo,
            "correo_electronico": usuario.correo_electronico,
            "telefono": usuario.telefono
        }
    }

@router.post("/login-swagger", include_in_schema=True)
def login_swagger_movil(
    form_data: OAuth2PasswordRequestForm = Depends(),
    db: Session = Depends(get_db)
):
    usuario = db.query(UsuarioMDB).filter(
        UsuarioMDB.correo_electronico == form_data.username
    ).first()

    if not usuario or not pwd_context.verify(form_data.password, usuario.password_hash):
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Correo o contraseña incorrectos"
        )

    token = crear_token({"sub": str(usuario.id)})
    return {"access_token": token, "token_type": "bearer"}