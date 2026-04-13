from datetime import datetime, timedelta
from fastapi import Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer
from jose import JWTError, jwt
from passlib.context import CryptContext
from sqlalchemy.orm import Session

from app.data.dbDATA import get_db
from app.data.webDATA.crear_perfilDATAW import UsuarioDB
from app.data.movilDATA.crear_usuarioDATA import UsuarioMDB

SECRET_KEY = "parangaricutirimicuarizador1234567890987654321ggpapaefeefe"
ALGORITHM = "HS256"
ACCESS_TOKEN_EXPIRE_MINUTES = 60

pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")


oauth2_scheme = OAuth2PasswordBearer(tokenUrl="/v1/login/")
oauth2_scheme_movil = OAuth2PasswordBearer(
    tokenUrl="/v1/auth/login-swagger",
    scheme_name="OAuth2PasswordBearer_Movil"
)

def verificar_password(password_plano, password_hash):
    
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
        
    
    usuario_db = db.query(UsuarioDB).filter(UsuarioDB.username == username).first()
    if usuario_db is None:
        raise credenciales_excepcion
        
    return usuario_db

def verificar_Peticion_Movil(token: str = Depends(oauth2_scheme_movil), db: Session = Depends(get_db)):
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

