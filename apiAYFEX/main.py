#Importaciones
from fastapi import FastAPI, status, HTTPException
import asyncio



#Instancia del servidor
app = FastAPI(
    title='API PI AYFEX',
    description='Sistema Gestión de Transporte Logístico de Paquetería',
    version='1.0.0'
    )


#Endpoints
@app.get("/", tags=['Inicio'])
async def bienvenida():
    return{"mensaje": "¡Bienvenido a la API de AYFEX "}

#--------------------------------------- Validaciones------------------------------------------------------------------------------------------------------------------------------------------------------------------
#Modelo de validacion Pydantic

#--------------------------------------- Tablas----------------------------------------------------------------------------------------------------------------------------------------------------------------------------

#-------------------------------------------------------Clientes---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Clientes=[
    {"id":1, "nombre":"Fidel", "apellido_paterno":"Juarez" ,"apellido materno":"Torres", "Telefono":"4481012175","correo":"Fidel@gmail.com","Dirección":"Calle:Jose Maria Morelos y Pavon #11","Codigo_Postal": "76980", "Fecha_registro":"2023-10-25"},
    {"id":2, "nombre":"Abdiel", "apellido_paterno":"Lopez", "apellido_materno":"García", "Telefono":"4423567890","correo":"abdiel.lopez@gmail.com","Dirección":"Calle: Benito Juarez #45","Codigo_Postal":"76000","Fecha_registro":"2023-11-02"},
    {"id":3, "nombre":"Yael", "apellido_paterno":"Hernandez", "apellido_materno":"Martinez", "Telefono":"4429876543","correo":"Yael.hdz@gmail.com","Dirección":"Calle: Ignacio Zaragoza #120","Codigo_Postal":"76130","Fecha_registro":"2023-11-05"}
 
]
#-------------------------------------------------------Paquetes---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

paquetes = [
    {
        "id_paquete":1, "id_envio":1, "peso":2.50, "volumen":0.30, "tipo":"Caja", "Comentario":"Ropa y accesorios", "fragil":"false", "valor_declarado":850.00
    },
    {
        "id_paquete":2, "id_envio":2, "peso":1.20, "volumen":0.15, "tipo":"Sobre", "Comentario":"Documentos importantes", "fragil":"false", "valor_declarado":200.00  
    },
    {
        "id_paquete":3, "id_envio":3, "peso":5.75, "volumen":0.60, "tipo":"Caja", "descripcion":"Articulos electronicos", "fragil":"true", "valor_declarado":3500.00
    },
    
]
#-------------------------------------------------------Envios------------------------------------------------------------------------------------------------------------

Envios = [
    {
       "id_envio":1, "codigo_rastreo":"MXQRO001", "id_cliente":1, "id_ruta":1, "id_estado_envio":1, "fecha_envio":"2023-10-26", "fecha_estimada_entrega":"2023-10-28", "costo_envio":150.00
    },
    {
       "id_envio":2, "codigo_rastreo":"MXQRO002", "id_cliente":2, "id_ruta":1, "id_estado_envio":2, "fecha_envio":"2023-10-27", "fecha_estimada_entrega":"2023-10-29", "costo_envio":220.50
    },
    {
       "id_envio":3, "codigo_rastreo":"MXQRO003", "id_cliente":3, "id_ruta":2, "id_estado_envio":1, "fecha_envio":"2023-10-28", "fecha_estimada_entrega":"2023-10-30", "costo_envio":180.75
    }
]

#-------------------------------------------------------EndPoints-------------------------------------------------------------------------------------------------------
@app.get("/v1/Reporte_General/", tags=['EndPoints'])
async def reporte_general():
    return {
        "status": "200",
        "total_clientes": len(Clientes),
        "data": {
            "clientes": Clientes,
            "envios": Envios,
            "paquetes": paquetes
        }
    }

@app.get("/v1/envio/{id_envio}", tags=['EndPoints'])
async def leer_envio_por_id(id_envio: int):
   
    envio = None
    for e in Envios:
        if e["id_envio"] == id_envio:
            envio = e
            break
    
    if envio is None:
        raise HTTPException(status_code=404, detail="Envío no encontrado")
    paquete = None
    for p in paquetes:
        if p["id_envio"] == id_envio:
            paquete = p
            break
    cliente = None
    for c in Clientes:
        if c["id"] == envio["id_cliente"]:
            cliente = c
            break

    return {
        "status": "200",
        "data": {
            "envio": envio,
            "paquete": paquete,
            "cliente": cliente
        }
    }

@app.post("/v1/clientes/", tags=['Clientes'])
async def registrar_cliente(cliente:cliente_register):

    for cl in Clientes:
        if cl["id_cliente"] == cliente.id_cliente:
            raise HTTPException(
                status_code=409,
                detail="El id_cliente ya existe"
            )

    Clientes.append(cliente)

    return{
        "status":"201",
        "total":len(Clientes),
        "clientes":Clientes
    }

@app.post("/v1/envios/", tags=['Envios'])
async def registrar_envio(envio:envio_register):

    for env in Envios:
        if env["id_envio"] == envio.id_envio:
            raise HTTPException(
                status_code=409,
                detail="El id_envio ya existe"
            )

    Envios.append(envio)

    return{
        "status":"201",
        "total":len(Envios),
        "envios":Envios
    }

@app.post("/v1/paquetes/", tags=['Paquetes'])
async def registrar_paquete(paquete:paquete_register):

    for pq in paquetes:
        if pq["id_paquete"] == paquete.id_paquete:
            raise HTTPException(
                status_code=409,
                detail="El id_paquete ya existe"
            )

    paquetes.append(paquete)

    return{
        "status":"201",
        "total":len(paquetes),
        "paquetes":paquetes
    }

@app.put("/v1/Actualizar_Envio_Paquete/{id}", tags=['EndPoints'], status_code=status.HTTP_200_OK)
async def actualizar_envio_paquete(id: int, tiene_envio: bool):
    for paquete in paquetes:
        if paquete["id"] == id:
            paquete["tiene_envio"] = tiene_envio
            return {
                "Mensaje": "Estado de envío actualizado correctamente",
                "Paquete": paquete
            }
    raise HTTPException(
        status_code=404,
        detail="El paquete no existe"
    )
@app.delete("/v1/envios/{id_envio}", tags=['Envios'], status_code=status.HTTP_200_OK)
async def cancelar_envio(id_envio: int):
    for envio in Envios:
        if envio["id_envio"] == id_envio:

            if envio["id_estado_envio"] == 3:
                raise HTTPException(
                    status_code=status.HTTP_400_BAD_REQUEST,
                    detail="El envío ya se encuentra cancelado"
                )

            envio["id_estado_envio"] = 3

            return {
                "mensaje": f"El envío {id_envio} fue cancelado correctamente",
                "envio": envio
            }

    raise HTTPException(
        status_code=status.HTTP_404_NOT_FOUND,
        detail="El envío no existe"
    )