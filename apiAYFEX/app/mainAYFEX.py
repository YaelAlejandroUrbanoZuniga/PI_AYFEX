from fastapi import FastAPI, HTTPException, status
from pydantic import BaseModel, Field, StringConstraints
from typing import Annotated
from datetime import datetime


from app.data.dbDATA import engine, Base


Base.metadata.create_all(bind=engine)

from app.routers import pedidosROUTERS

app = FastAPI(
    title="API AYFEX PEDIDOS",
    description="Sistema de gestión de pedidos",
    version="1.0.0"
)

# Incluir los routers
app.include_router(pedidosROUTERS.router)


