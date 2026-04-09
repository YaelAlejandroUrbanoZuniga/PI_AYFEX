from datetime import datetime, timedelta
from fastapi import Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer
from jose import JWTError, jwt
from passlib.context import CryptContext
from sqlalchemy.orm import Session

# Importamos tu base de datos
from app.data.dbDATA import get_db
from app.data.webDATA.crear_perfilDATAW import UsuarioDB
from app.data.movilDATA.crear_usuarioDATA import UsuarioMDB

SECRET_KEY = "parangaricutirimicuarizador1234567890987654321ggpapaefeefe"
ALGORITHM = "HS256"
ACCESS_TOKEN_EXPIRE_MINUTES = 60

pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")

# URL donde el frontend pedirá el token
oauth2_scheme = OAuth2PasswordBearer(tokenUrl="/v1/login/") 

def verificar_password(password_plano, password_hash):
    # NOTA: Por ahora, como tienes "Hola1234" en tu DB, lo validamos en texto plano.
    # Cuando arreglen el registro para usar hash, cambia esto a: return pwd_context.verify(password_plano, password_hash)
    return password_plano == password_hash

def crear_token(data: dict):
    to_encode = data.copy()
    expiracion = datetime.utcnow() + timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES)
    to_encode.update({"exp": expiracion})
    return jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)

def verificar_Peticion(token: str = Depends(oauth2_scheme), db: Session = Depends(get_db)):
    credenciales_excepcion = HTTPException(
        status_code=status.HTTP_401_UNAUTHORIZED,
        detail="No se pudieron validar las credenciales",
        headers={"WWW-Authenticate": "Bearer"},
    )
    try:
        payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        username: str = payload.get("sub")
        if username is None:
            raise credenciales_excepcion
    except JWTError:
        raise credenciales_excepcion
        
    # Buscamos al usuario real en la base de datos
    usuario_db = db.query(UsuarioDB).filter(UsuarioDB.username == username).first()
    if usuario_db is None:
        raise credenciales_excepcion
        
    return usuario_db

def verificar_Peticion_Movil(token: str = Depends(oauth2_scheme), db: Session = Depends(get_db)):
    credenciales_excepcion = HTTPException(
        status_code=status.HTTP_401_UNAUTHORIZED,
        detail="No se pudieron validar las credenciales",
        headers={"WWW-Authenticate": "Bearer"},
    )
    try:
        payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        usuario_id: str = payload.get("sub")
        if usuario_id is None:
            raise credenciales_excepcion
    except JWTError:
        raise credenciales_excepcion

    usuario_db = db.query(UsuarioMDB).filter(UsuarioMDB.id == int(usuario_id)).first()
    if usuario_db is None:
        raise credenciales_excepcion

    return str(usuario_db.id)