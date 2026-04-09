from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session

# Importamos nuestros modelos de DB y Pydantic
from app.data.webDATA.crear_perfilDATAW import UsuarioDB
from app.models.webMODELS.perfilMODELSW import PerfilResponse, PerfilUpdate
from app.data.dbDATA import get_db

# IMPORTAMOS TU SEGURIDAD REAL
from app.security.authSECURITY import verificar_Peticion

router = APIRouter(
    prefix="/v1/mi-perfil",
    tags=["Perfil Administrativo"]
)

@router.get("/", response_model=PerfilResponse)
def leer_mi_perfil(
    # Recibimos directamente el objeto UsuarioDB desde tu función de seguridad
    usuario_db: UsuarioDB = Depends(verificar_Peticion)
):
    # ¡Ya no necesitamos hacer db.query() aquí!
    # El usuario ya fue encontrado y validado por verificar_Peticion.

    return PerfilResponse(
        id=usuario_db.id,
        nombre_completo=usuario_db.nombre_completo,
        correo_electronico=usuario_db.correo_electronico,
        telefono=usuario_db.telefono,
        # Usamos getattr por si la columna foto_url aún no existe en tu base de datos
        foto_url=getattr(usuario_db, 'foto_url', ""), 
        fecha_registro=usuario_db.fecha_registro,
        esta_activo=True, # Forzado a True
        # Nivel de acceso forzado a completo por el momento
        tiene_acceso_envios=True,
        tiene_acceso_clientes=True,
        tiene_acceso_operadores=True,
        tiene_acceso_reportes=True
    )

@router.put("/", response_model=PerfilResponse)
def actualizar_mi_perfil(
    datos_actualizados: PerfilUpdate,
    # Recibimos el usuario directamente
    usuario_db: UsuarioDB = Depends(verificar_Peticion),
    # Aquí SÍ necesitamos la sesión de la BD para guardar los cambios
    db: Session = Depends(get_db)
):
    # Validar que el nuevo correo no choque con otro usuario en la BD
    if datos_actualizados.correo_electronico != usuario_db.correo_electronico:
        correo_existe = db.query(UsuarioDB).filter(UsuarioDB.correo_electronico == datos_actualizados.correo_electronico).first()
        if correo_existe:
            raise HTTPException(status_code=400, detail="Este correo ya está en uso.")

    # Actualizamos los datos del objeto que nos dio la seguridad
    usuario_db.nombre_completo = datos_actualizados.nombre_completo
    usuario_db.correo_electronico = datos_actualizados.correo_electronico
    usuario_db.telefono = datos_actualizados.telefono
    
    # Guardamos los cambios en la base de datos
    db.commit()
    db.refresh(usuario_db)
    
    return PerfilResponse(
        id=usuario_db.id,
        nombre_completo=usuario_db.nombre_completo,
        correo_electronico=usuario_db.correo_electronico,
        telefono=usuario_db.telefono,
        foto_url=getattr(usuario_db, 'foto_url', ""),
        fecha_registro=usuario_db.fecha_registro,
        esta_activo=True,
        tiene_acceso_envios=True,
        tiene_acceso_clientes=True,
        tiene_acceso_operadores=True,
        tiene_acceso_reportes=True
    )