from fastapi import FastAPI, HTTPException, status
from pydantic import BaseModel, Field, StringConstraints
from typing import Annotated
from datetime import datetime


from app.database import engine, Base


Base.metadata.create_all(bind=engine)

from app.routers import pedidos, usuarios, clientes, operadores, rutas, reportes, incidencias

app = FastAPI(
    title="API AYFEX PEDIDOS",
    description="Sistema de gestión de pedidos",
    version="1.0.0"
)

# Incluir los routers
app.include_router(pedidos.router)
app.include_router(usuarios.router)
app.include_router(clientes.router)
app.include_router(operadores.router)
app.include_router(rutas.router)
app.include_router(reportes.router)
app.include_router(incidencias.router)

