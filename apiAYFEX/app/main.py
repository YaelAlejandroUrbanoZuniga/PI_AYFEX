from fastapi import FastAPI, HTTPException, status
from pydantic import BaseModel, Field, StringConstraints
from typing import Annotated
from datetime import datetime

app = FastAPI(
    title="API AYFEX PEDIDOS",
    description="Sistema de gestión de pedidos",
    version="1.0.0"
)

pedidos = []

class Pedido(BaseModel):
    id: int = Field(..., gt=0)

    origen: Annotated[str, StringConstraints(min_length=5, max_length=150)]
    destino: Annotated[str, StringConstraints(min_length=5, max_length=150)]

    peso: float = Field(..., gt=0, le=999.99)

    tipo: Annotated[str, StringConstraints(min_length=3, max_length=50)]

    altura: float = Field(..., gt=0, le=999.99)
    anchura: float = Field(..., gt=0, le=999.99)

    descripcion: Annotated[str, StringConstraints(max_length=300)] = ""

@app.get("/v1/pedidos")
async def obtener_pedidos():
    return pedidos


@app.post("/v1/pedidos", status_code=status.HTTP_201_CREATED)
async def crear_pedido(pedido: Pedido):

    for p in pedidos:
        if p["id"] == pedido.id:
            raise HTTPException(
                status_code=400,
                detail="El pedido ya existe"
            )

    nuevo = pedido.dict()
    nuevo["fecha"] = datetime.now().isoformat()

    pedidos.append(nuevo)

    return nuevo


@app.put("/v1/pedidos/{id}")
async def actualizar_pedido(id:int, pedido:Pedido):

    for p in pedidos:

        if p["id"] == id:

            p["origen"] = pedido.origen
            p["destino"] = pedido.destino
            p["peso"] = pedido.peso
            p["tipo"] = pedido.tipo
            p["altura"] = pedido.altura
            p["anchura"] = pedido.anchura
            p["descripcion"] = pedido.descripcion

            return {
                "mensaje":"Pedido actualizado",
                "pedido":p
            }

    raise HTTPException(
        status_code=404,
        detail="Pedido no encontrado"
    )


@app.delete("/v1/pedidos/{id}")
async def eliminar_pedido(id:int):

    for p in pedidos:

        if p["id"] == id:
            pedidos.remove(p)

            return {
                "mensaje":"Pedido eliminado"
            }

    raise HTTPException(
        status_code=404,
        detail="Pedido no encontrado"
    )