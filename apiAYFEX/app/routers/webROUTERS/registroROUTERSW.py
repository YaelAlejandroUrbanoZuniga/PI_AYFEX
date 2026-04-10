from fastapi import APIRouter, Depends, HTTPException, status
from pydantic import BaseModel, EmailStr
from sqlalchemy.orm import Session


from app.data.webDATA.crear_perfilDATAW import UsuarioDB
from app.data.dbDATA import get_db

router = APIRouter(
    prefix="/v1/registro",
    tags=["Web | Autenticación"]
)


class UsuarioRegistro(BaseModel):
    nombre_completo: str
    correo_electronico: EmailStr
    telefono: str
    password: str

@router.post("")
def registrar_usuario(datos: UsuarioRegistro, db: Session = Depends(get_db)):
    
    usuario_existente = db.query(UsuarioDB).filter(
        (UsuarioDB.correo_electronico == datos.correo_electronico) | 
        (UsuarioDB.username == datos.correo_electronico) 
    ).first()

    if usuario_existente:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Este correo electrónico ya está registrado."
        )

    
    nuevo_usuario = UsuarioDB(
        username=datos.correo_electronico, 
        nombre_completo=datos.nombre_completo,
        correo_electronico=datos.correo_electronico,
        telefono=datos.telefono,
        password=datos.password 
    )

    
    db.add(nuevo_usuario)
    db.commit()
    db.refresh(nuevo_usuario)

    return {
        "status": "success",
        "mensaje": "Usuario registrado correctamente",
        "usuario": nuevo_usuario.correo_electronico
    }