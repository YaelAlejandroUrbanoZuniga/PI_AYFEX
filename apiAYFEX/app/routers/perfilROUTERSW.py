from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session

# Importamos nuestros modelos de DB y Pydantic
from app.data.crear_perfilDATAW import UsuarioDB
from app.models.perfilMODELSW import PerfilResponse, PerfilUpdate
from app.data.dbDATA import get_db

# IMPORTAMOS TU SEGURIDAD REAL
from app.security.authSECURITY import verificar_Peticion

router = APIRouter(
    prefix="/v1/mi-perfil",
    tags=["Perfil Administrativo"]
)

@router.get("/", response_model=PerfilResponse)
def leer_mi_perfil(
    # 1. Tu seguridad verifica la contraseña y nos da el "username"
    username_actual: str = Depends(verificar_Peticion),
    # 2. Nos conectamos a la Base de Datos
    db: Session = Depends(get_db)
):
    # 3. Buscamos a ese usuario en la tabla de PostgreSQL/SQLite
    usuario_db = db.query(UsuarioDB).filter(UsuarioDB.username == username_actual).first()

    if not usuario_db:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND, 
            detail="El usuario está autorizado, pero aún no tiene un perfil en la base de datos."
        )

    return usuario_db

@router.put("/", response_model=PerfilResponse)
def actualizar_mi_perfil(
    datos_actualizados: PerfilUpdate,
    username_actual: str = Depends(verificar_Peticion),
    db: Session = Depends(get_db)
):
    # Buscamos al usuario en la BD
    usuario_db = db.query(UsuarioDB).filter(UsuarioDB.username == username_actual).first()

    if not usuario_db:
        raise HTTPException(status_code=404, detail="Perfil no encontrado")

    # Validar que el nuevo correo no choque con otro
    if datos_actualizados.correo_electronico != usuario_db.correo_electronico:
        correo_existe = db.query(UsuarioDB).filter(UsuarioDB.correo_electronico == datos_actualizados.correo_electronico).first()
        if correo_existe:
            raise HTTPException(status_code=400, detail="Este correo ya está en uso.")

    # Actualizamos los datos
    usuario_db.nombre_completo = datos_actualizados.nombre_completo
    usuario_db.correo_electronico = datos_actualizados.correo_electronico
    usuario_db.telefono = datos_actualizados.telefono
    
    db.commit()
    db.refresh(usuario_db)
    
    return usuario_db