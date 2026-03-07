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

Paquetes = [
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

