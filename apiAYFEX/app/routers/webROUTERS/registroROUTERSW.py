from fastapi import APIRouter, Depends, HTTPException, status
from pydantic import BaseModel, EmailStr
from sqlalchemy.orm import Session

# Importamos tu modelo de base de datos y la conexión
from app.data.webDATA.crear_perfilDATAW import UsuarioDB
from app.data.dbDATA import get_db

router = APIRouter(
    prefix="/v1/registro",
    tags=["Autenticación"]
)

# Creamos un esquema Pydantic para validar los datos que llegan del frontend
class UsuarioRegistro(BaseModel):
    nombre_completo: str
    correo_electronico: EmailStr
    telefono: str
    password: str

@router.post("")
def registrar_usuario(datos: UsuarioRegistro, db: Session = Depends(get_db)):
    # 1. Verificamos si el correo ya está registrado
    usuario_existente = db.query(UsuarioDB).filter(
        (UsuarioDB.correo_electronico == datos.correo_electronico) | 
        (UsuarioDB.username == datos.correo_electronico) # Por si usas username como correo
    ).first()

    if usuario_existente:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Este correo electrónico ya está registrado."
        )

    # 2. Creamos el nuevo usuario
    # NOTA: Por ahora se guarda la contraseña en texto plano para mantener compatibilidad 
    # con tu login actual. En el futuro, lo ideal es usar una librería como 'passlib' para encriptarla.
    nuevo_usuario = UsuarioDB(
        username=datos.correo_electronico, # Usamos el correo como username para el login
        nombre_completo=datos.nombre_completo,
        correo_electronico=datos.correo_electronico,
        telefono=datos.telefono,
        password=datos.password 
    )

    # 3. Guardamos en la base de datos
    db.add(nuevo_usuario)
    db.commit()
    db.refresh(nuevo_usuario)

    return {
        "status": "success",
        "mensaje": "Usuario registrado correctamente",
        "usuario": nuevo_usuario.correo_electronico
    }