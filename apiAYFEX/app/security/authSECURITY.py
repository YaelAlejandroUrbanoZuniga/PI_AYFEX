from datetime import datetime, timedelta
from fastapi import Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer, OAuth2PasswordRequestForm
from jose import JWTError, jwt
from passlib.context import CryptContext

SECRET_KEY = "parangaricutirimicuarizador1234567890987654321ggpapaefeefe"
ALGORITHM = "HS256"
ACCESS_TOKEN_EXPIRE_MINUTES = 60

pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")

oauth2_scheme = OAuth2PasswordBearer(tokenUrl="/v1/auth/login")

usuarios_db = {
    "JuanFidelJuarezTorres": {
        "username": "JuanFidelJuarezTorres",
        "password_hash": pwd_context.hash("Gege1234")
    },
    "MiguelAngelTovarMorales": {
        "username": "MiguelAngelTovarMorales",
        "password_hash": pwd_context.hash("1234")
    },
    "YaelAlejandroUrbanoZuniga": {
        "username": "YaelAlejandroUrbanoZuniga",
        "password_hash": pwd_context.hash("1234")
    }
}

def verificar_password(password_plano, password_hash):
    return pwd_context.verify(password_plano, password_hash)

def crear_token(data: dict):
    to_encode = data.copy()
    expiracion = datetime.utcnow() + timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES)
    to_encode.update({"exp": expiracion})
    return jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)

def verificar_Peticion(token: str = Depends(oauth2_scheme)):
    try:
        payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        username: str = payload.get("sub")
        if username is None:
            raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Token inválido")
        return username
    except JWTError:
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Token inválido o expirado")