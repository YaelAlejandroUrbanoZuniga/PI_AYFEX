from fastapi import FastAPI, HTTPException, status
from pydantic import BaseModel, Field, StringConstraints
from typing import Annotated
from datetime import datetime
from fastapi.middleware.cors import CORSMiddleware
from app.data.crear_operadoresDATAW import Crear_Operadores
from app.data.dbDATA import engine, Base
from app.data.crear_pedidosDATA import Crear_Pedidos
from app.data.crear_incidenciasDATAW import Crear_Incidencias

Base.metadata.create_all(bind=engine)

from app.routers import pedidosROUTERS
from app.routers import operadoresROUTERSW
from app.routers import incidenciasROUTERSW

app = FastAPI(
    title="API AYFEX PEDIDOS",
    description="Sistema de gestión de pedidos",
    version="1.0.0"
)
@app.get("/")
def read_root():
    return {"mensaje": "¡Bienvenido a la API de AYFEX PEDIDOS!"}
# --- CONFIGURACIÓN DE CORS ---
# Esto le dice a tu API que acepte peticiones desde tu web Laravel
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # El "*" permite cualquier origen. (En producción se cambia por la URL real de tu web)
    allow_credentials=True,
    allow_methods=["*"],  # Permite GET, POST, PUT, DELETE
    allow_headers=["*"],  # Permite cualquier cabecera
)

# Incluir los routers
app.include_router(pedidosROUTERS.router)
app.include_router(operadoresROUTERSW.router)
app.include_router(incidenciasROUTERSW.router)


