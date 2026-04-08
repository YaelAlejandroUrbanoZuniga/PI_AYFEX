from fastapi import FastAPI, HTTPException, status
from pydantic import BaseModel, Field, StringConstraints
from typing import Annotated
from datetime import datetime
from fastapi.middleware.cors import CORSMiddleware


from app.data.crear_operadoresDATAW import Crear_Operadores
from app.data.dbDATA import engine, Base
from app.data.movilDATA.crear_pedidosDATA import Crear_Pedidos
from app.data.crear_incidenciasDATAW import Crear_Incidencias
from app.data.crear_perfilDATAW import UsuarioDB
from app.data.movilDATA.crear_usuarioDATA import UsuarioMDB 

Base.metadata.create_all(bind=engine)

from app.routers.movilROUTERS import pedidosROUTERS
from app.routers.movilROUTERS import authROUTERS
from app.routers import operadoresROUTERSW
from app.routers import incidenciasROUTERSW
from app.routers import perfilROUTERSW 
from app.routers import loginROUTERSW
from app.routers import registroROUTERSW

app = FastAPI(
    title="API AYFEX PEDIDOS",
    description="Sistema de gestión de pedidos",
    version="1.0.0"
)

@app.get("/")
def read_root():
    return {"mensaje": "¡Bienvenido a la API de AYFEX PEDIDOS!"}

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  
    allow_credentials=True,
    allow_methods=["*"], 
    allow_headers=["*"],
)

app.include_router(pedidosROUTERS.router)
app.include_router(authROUTERS.router)



app.include_router(operadoresROUTERSW.router)
app.include_router(incidenciasROUTERSW.router)
app.include_router(perfilROUTERSW.router)
app.include_router(loginROUTERSW.router)
app.include_router(registroROUTERSW.router)