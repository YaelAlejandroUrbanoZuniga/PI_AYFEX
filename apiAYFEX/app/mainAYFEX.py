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

<<<<<<< HEAD
# --- ROUTERS ---
from app.routers import pedidosROUTERS
=======
from app.routers.movilROUTERS import pedidosROUTERS
from app.routers.movilROUTERS import authROUTERS
>>>>>>> 623e856e8af5406e81fd91278f0e9c0505c0d377
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
<<<<<<< HEAD
# --- CONFIGURACIÓN DE CORS ---
# Esto le dice a tu API que acepte peticiones desde tu web
=======

@app.get("/")
def read_root():
    return {"mensaje": "¡Bienvenido a la API de AYFEX PEDIDOS!"}

>>>>>>> 623e856e8af5406e81fd91278f0e9c0505c0d377
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # El "*" permite cualquier origen. (En producción se cambia por la URL real de tu web)
    allow_credentials=True,
    allow_methods=["*"],  # Permite GET, POST, PUT, DELETE
    allow_headers=["*"],  # Permite cualquier cabecera
)

<<<<<<< HEAD
@app.get("/")
def read_root():
    return {"mensaje": "¡Bienvenido a la API de AYFEX PEDIDOS!"}



# --- INCLUSIÓN DE ROUTERS EN LA APP ---
=======
>>>>>>> 623e856e8af5406e81fd91278f0e9c0505c0d377
app.include_router(pedidosROUTERS.router)
app.include_router(authROUTERS.router)



app.include_router(operadoresROUTERSW.router)
app.include_router(incidenciasROUTERSW.router)
app.include_router(perfilROUTERSW.router)
app.include_router(loginROUTERSW.router)
app.include_router(registroROUTERSW.router)