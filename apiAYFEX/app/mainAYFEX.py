from fastapi import FastAPI, HTTPException, status
from pydantic import BaseModel, Field, StringConstraints
from typing import Annotated
from datetime import datetime
from fastapi.middleware.cors import CORSMiddleware

# --- MODELOS DE BASE DE DATOS ---
from app.data.crear_operadoresDATAW import Crear_Operadores
from app.data.dbDATA import engine, Base
from app.data.crear_pedidosDATA import Crear_Pedidos
from app.data.crear_incidenciasDATAW import Crear_Incidencias
# 1. Agregamos la importación del modelo de Perfil para que SQLAlchemy sepa que existe
from app.data.crear_perfilDATAW import UsuarioDB 

# Esto crea todas las tablas en la base de datos
Base.metadata.create_all(bind=engine)

from apiAYFEX.app.routers.movilROUTERS import pedidosROUTERS
from app.routers import operadoresROUTERSW
from app.routers import incidenciasROUTERSW
# 2. Importamos el router del Perfil
from app.routers import perfilROUTERSW 
# Importamos el router del Login
from app.routers import loginROUTERSW
#  Importamos el router de Registro (Asegúrate de que el archivo se llame registroROUTERSW.py)
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
app.include_router(operadoresROUTERSW.router)
app.include_router(incidenciasROUTERSW.router)
# 3. Encendemos los endpoints del perfil en la API
app.include_router(perfilROUTERSW.router)
# Encendemos el endpoint de login en la API
app.include_router(loginROUTERSW.router)
# Encendemos el endpoint de registro en la API
app.include_router(registroROUTERSW.router)