from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session


from app.data.webDATA.crear_perfilDATAW import UsuarioDB
from app.models.webMODELS.perfilMODELSW import PerfilResponse, PerfilUpdate
from app.data.dbDATA import get_db


from app.security.authSECURITY import verificar_Peticion

router = APIRouter(
    prefix="/v1/mi-perfil",
    tags=["Web | Perfil Administrativo"]
)

@router.get("/", response_model=PerfilResponse)
def leer_mi_perfil(
    
    usuario_db: UsuarioDB = Depends(verificar_Peticion)
):
    

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

@router.put("/", response_model=PerfilResponse)
def actualizar_mi_perfil(
    datos_actualizados: PerfilUpdate,
    
    usuario_db: UsuarioDB = Depends(verificar_Peticion),
    
    db: Session = Depends(get_db)
):
    
    if datos_actualizados.correo_electronico != usuario_db.correo_electronico:
        correo_existe = db.query(UsuarioDB).filter(UsuarioDB.correo_electronico == datos_actualizados.correo_electronico).first()
        if correo_existe:
            raise HTTPException(status_code=400, detail="Este correo ya está en uso.")

    
    usuario_db.nombre_completo = datos_actualizados.nombre_completo
    usuario_db.correo_electronico = datos_actualizados.correo_electronico
    usuario_db.telefono = datos_actualizados.telefono
    
    
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