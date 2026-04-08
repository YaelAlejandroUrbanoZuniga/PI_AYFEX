from fastapi import status, HTTPException, Depends
from fastapi.security import HTTPBasic, HTTPBasicCredentials
from sqlalchemy.orm import Session
import secrets

# Importamos la conexión y el modelo de usuario
from app.data.dbDATA import get_db
from app.data.crear_perfilDATAW import UsuarioDB

security = HTTPBasic()

def verificar_Peticion(credenciales: HTTPBasicCredentials = Depends(security), db: Session = Depends(get_db)):
    username = credenciales.username
    password = credenciales.password

    # Buscamos al usuario en la Base de Datos (por correo o username)
    usuario = db.query(UsuarioDB).filter(
        (UsuarioDB.correo_electronico == username) | 
        (UsuarioDB.username == username)
    ).first()

    # Si el usuario no existe
    if not usuario:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Usuario no encontrado"
        )

    # Comparamos la contraseña de la BD con la que escribió el usuario
    # Usamos secrets.compare_digest por seguridad
    if not secrets.compare_digest(password, usuario.password):
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Contraseña incorrecta"
        )

    return usuario.username