from fastapi import FastAPI, HTTPException, status
from pydantic import BaseModel, Field, StringConstraints
from typing import Annotated
from datetime import datetime
from fastapi.middleware.cors import CORSMiddleware


from app.data.webDATA.crear_operadoresDATAW import Crear_Operadores
from app.data.dbDATA import engine, Base
from app.data.movilDATA.crear_pedidosDATA import Crear_Pedidos
from app.data.movilDATA.crear_reporteDATA import Crear_Reporte
from app.data.webDATA.crear_incidenciasDATAW import Crear_Incidencias
from app.data.webDATA.crear_perfilDATAW import UsuarioDB
from app.data.movilDATA.crear_usuarioDATA import UsuarioMDB 
from app.data.webDATA.rutasDATAW import RutaDB

Base.metadata.create_all(bind=engine)

from app.routers.movilROUTERS import pedidosROUTERS
from app.routers.movilROUTERS import reporteROUTERS
from app.routers.movilROUTERS import authROUTERS
from app.routers.webROUTERS import operadoresROUTERSW
from app.routers.webROUTERS import incidenciasROUTERSW
from app.routers.webROUTERS import perfilROUTERSW 
from app.routers.webROUTERS import loginROUTERSW
from app.routers.webROUTERS import registroROUTERSW
from app.routers.webROUTERS import rutasROUTERSW
from app.routers.webROUTERS import pedidosROUTERSW
from app.routers.webROUTERS import clientesROUTERSW


app = FastAPI(
    title="API AYFEX PEDIDOS",
    description="Sistema de gestión de pedidos",
    version="1.0.0"
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=[
        "http://127.0.0.1:8000", 
        "http://localhost:8000"
    ],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"], 
)

@app.get("/")
def read_root():
    return {"mensaje": "¡Bienvenido a la API de AYFEX PEDIDOS!"}





app.include_router(pedidosROUTERS.router)
app.include_router(authROUTERS.router)
app.include_router(reporteROUTERS.router)




app.include_router(operadoresROUTERSW.router)
app.include_router(incidenciasROUTERSW.router)
app.include_router(perfilROUTERSW.router)
app.include_router(loginROUTERSW.router)
app.include_router(registroROUTERSW.router)
app.include_router(rutasROUTERSW.router)
app.include_router(pedidosROUTERSW.router)
app.include_router(clientesROUTERSW.router)