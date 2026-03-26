# -----------------------------------SEGURIDAD HTTP BASIC-----------------------------------
from fastapi import status, HTTPException, Depends

from fastapi.security import HTTPBasic, HTTPBasicCredentials
import secrets

security = HTTPBasic()

usuariosVerificados = {
    "JuanFidelJuarezTorres": "Gege1234",
    "MiguelAngelTovarMorales": "1234",
    "YaelAlejandroUrbanoZuniga": "1234"
}

def verificar_Peticion(credenciales: HTTPBasicCredentials = Depends(security)):
    username = credenciales.username
    password = credenciales.password

    if username not in usuariosVerificados:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Credenciales no autorizadas"
        )

    password_correcta = usuariosVerificados[username]
    passAuth = secrets.compare_digest(password, password_correcta)

    if not passAuth:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Credenciales no autorizadas"
        )

    return username