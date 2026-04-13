import React, { useState, useEffect } from 'react';
import {
  View, Text, StyleSheet, TouchableOpacity,
  ScrollView, StatusBar, Modal,
  TextInput, Platform
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import * as FileSystem from 'expo-file-system/legacy';
import * as Print from 'expo-print';
import * as Sharing from 'expo-sharing';
import { Asset } from 'expo-asset';
import HeaderNaranjaVolver from '../Components/HeaderNaranjaVolver';
import { API_BASE_URL } from '../config';

const API_URL = `${API_BASE_URL}/v1/pedidos`;

const ESTADO_CONFIG = {
  'EN PREPARACIÓN': { color: '#FF9500', icono: 'construct-outline', label: 'En Preparación' },
  'EN ESPERA': { color: '#3A86FF', icono: 'time-outline', label: 'En Espera' },
  'EN CAMINO': { color: '#FF6B00', icono: 'bicycle-outline', label: 'En Camino' },
  'EN CAMINO AL DESTINO': { color: '#9B59B6', icono: 'navigate-outline', label: 'En Camino al Destino' },
  'ENTREGADO': { color: '#34C759', icono: 'checkmark-circle-outline', label: 'Entregado' },
  'RECHAZADO': { color: '#FF3B30', icono: 'close-circle-outline', label: 'Rechazado' },
};

function ModalConfirmacion({ visible, titulo, mensaje, onConfirmar, onCancelar, confirmText = "Confirmar", peligro = false }) {
  return (
    <Modal visible={visible} transparent animationType="fade">
      <View style={styles.alertOverlay}>
        <View style={styles.alertContainer}>
          <View style={[styles.alertIconContainer, peligro && { backgroundColor: '#FFF0F0' }]}>
            <Ionicons
              name={peligro ? "warning-outline" : "help-circle-outline"}
              size={36}
              color={peligro ? "#FF3B30" : "#FF6B00"}
            />
          </View>
          <Text style={styles.alertTitulo}>{titulo}</Text>
          <Text style={styles.alertMensaje}>{mensaje}</Text>
          <TouchableOpacity
            style={[styles.alertBoton, peligro && { backgroundColor: '#FF3B30' }]}
            onPress={onConfirmar}
          >
            <Text style={styles.alertBotonTexto}>{confirmText}</Text>
          </TouchableOpacity>
          <TouchableOpacity style={styles.alertCancelar} onPress={onCancelar}>
            <Text style={styles.alertCancelarTexto}>Cancelar</Text>
          </TouchableOpacity>
        </View>
      </View>
    </Modal>
  );
}

export default function PedidosM_Detalles({ navigation, route }) {

  const { pedidoData: pedidoInicial } = route.params;
  const [pedido, setPedido] = useState(pedidoInicial);

  const [modalEditarVisible, setModalEditarVisible] = useState(false);
  const [modalEliminarVisible, setModalEliminarVisible] = useState(false);
  const [modalListoVisible, setModalListoVisible] = useState(false);
  const [modalEntregarOperadorVisible, setModalEntregarOperadorVisible] = useState(false);
  const [modalEntregaFinalVisible, setModalEntregaFinalVisible] = useState(false);
  const [generandoPDF, setGenerandoPDF] = useState(false);

  const [origen, setOrigen] = useState(pedido.origen);
  const [destino, setDestino] = useState(pedido.destino);
  const [peso, setPeso] = useState(String(pedido.peso));
  const [tipo, setTipo] = useState(pedido.tipo);
  const [altura, setAltura] = useState(String(pedido.altura));
  const [anchura, setAnchura] = useState(String(pedido.anchura));
  const [descripcion, setDescripcion] = useState(pedido.descripcion || '');

  // Calcular días restantes en tiempo real
  const calcularDiasRestantes = () => {
    if (!pedido.dias_estimados || !pedido.fecha_asignacion) return null;
    const hoy = new Date();
    const asignacion = new Date(pedido.fecha_asignacion);
    const diasTranscurridos = Math.floor((hoy - asignacion) / (1000 * 60 * 60 * 24));
    const restantes = pedido.dias_estimados - diasTranscurridos;
    return restantes > 0 ? restantes : 0;
  };

  const diasRestantes = calcularDiasRestantes();
  const estadoConfig = ESTADO_CONFIG[pedido.estado] || ESTADO_CONFIG['EN PREPARACIÓN'];
  const estadoActual = pedido.estado || 'EN PREPARACIÓN';

  const recargarPedido = async () => {
    try {
      const response = await fetch(`${API_URL}/${pedido.id}`, {
        headers: { "Authorization": `Bearer ${global.authToken}` }
      });
      if (response.ok) {
        const data = await response.json();
        setPedido(data);
      }
    } catch (error) {
      console.log("Error recargando pedido:", error);
    }
  };

  const actualizarPedido = async () => {
    if (!origen || origen.length < 5) return;
    if (!destino || destino.length < 5) return;
    try {
      const response = await fetch(`${API_URL}/${pedido.id}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
          "Authorization": `Bearer ${global.authToken}`
        },
        body: JSON.stringify({
          origen, destino,
          peso: parseFloat(peso), tipo,
          altura: parseFloat(altura),
          anchura: parseFloat(anchura),
          descripcion
        })
      });
      if (!response.ok) throw new Error("Error al actualizar");
      setModalEditarVisible(false);
      navigation.goBack();
    } catch (error) {
      console.log(error);
    }
  };

  const confirmarEliminar = async () => {
    try {
      await fetch(`${API_URL}/${pedido.id}`, {
        method: "DELETE",
        headers: { "Authorization": `Bearer ${global.authToken}` }
      });
      setModalEliminarVisible(false);
      navigation.goBack();
    } catch (error) {
      console.log(error);
    }
  };

  const confirmarListo = async () => {
    try {
      const response = await fetch(`${API_URL}/${pedido.id}/listo`, {
        method: "PATCH",
        headers: { "Authorization": `Bearer ${global.authToken}` }
      });
      if (!response.ok) throw new Error("Error al confirmar");
      const data = await response.json();
      setPedido(data);
      setModalListoVisible(false);
    } catch (error) {
      console.log(error);
    }
  };

  const confirmarEntregaOperador = async () => {
    try {
      const response = await fetch(`${API_URL}/${pedido.id}/entregar-operador`, {
        method: "PATCH",
        headers: { "Authorization": `Bearer ${global.authToken}` }
      });
      if (!response.ok) throw new Error("Error al confirmar");
      const data = await response.json();
      setPedido(data);
      setModalEntregarOperadorVisible(false);
    } catch (error) {
      console.log(error);
    }
  };

  const confirmarEntregaFinal = async () => {
    try {
      const response = await fetch(`${API_URL}/${pedido.id}/confirmar-entrega`, {
        method: "PATCH",
        headers: { "Authorization": `Bearer ${global.authToken}` }
      });
      if (!response.ok) throw new Error("Error al confirmar");
      const data = await response.json();
      setPedido(data);
      setModalEntregaFinalVisible(false);
    } catch (error) {
      console.log(error);
    }
  };

  const generarPDF = async () => {
    try {
      setGenerandoPDF(true);
      const asset = Asset.fromModule(require('../assets/logo.png'));
      await asset.downloadAsync();
      const logoBase64 = await FileSystem.readAsStringAsync(asset.localUri, { encoding: 'base64' });
      const logoSrc = `data:image/png;base64,${logoBase64}`;
      const qrDataString = encodeURIComponent(JSON.stringify({
        id: pedido.id, origen: pedido.origen, destino: pedido.destino,
        peso: pedido.peso, tipo: pedido.tipo, fecha: pedido.fecha, estado: estadoActual,
      }));

      const html = `<!DOCTYPE html><html><head><meta charset="utf-8">
      <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; background:#FAFAFA; padding:32px; color:#1A1A1A; }
        .header { background:linear-gradient(135deg,#FF6B00,#FF8C00); border-radius:16px; padding:28px 32px; margin-bottom:24px; display:flex; justify-content:space-between; align-items:center; }
        .header-left { display:flex; align-items:center; gap:16px; }
        .logo { width:56px; height:56px; border-radius:12px; background:white; padding:6px; object-fit:contain; }
        .header-text h1 { font-size:26px; font-weight:900; color:white; letter-spacing:3px; }
        .header-text p { font-size:12px; color:rgba(255,255,255,0.85); margin-top:3px; }
        .pedido-num { background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.4); color:white; padding:8px 18px; border-radius:30px; font-size:14px; font-weight:700; }
        .fecha-gen { font-size:11px; color:rgba(255,255,255,0.75); margin-top:6px; text-align:right; }
        .estado-bar { background:white; border-radius:12px; padding:14px 20px; margin-bottom:16px; display:flex; align-items:center; justify-content:space-between; border:1px solid #E0E0E0; }
        .estado-label { font-size:13px; color:#555; font-weight:600; }
        .estado-badge { background:#FFF3E6; color:#FF6B00; padding:6px 18px; border-radius:20px; font-size:13px; font-weight:800; border:1px solid #FFD4A8; }
        .card { background:white; border-radius:14px; padding:22px 24px; margin-bottom:16px; border:1px solid #E0E0E0; }
        .card-title { font-size:12px; color:#FF6B00; font-weight:700; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:18px; padding-bottom:12px; border-bottom:2px solid #FFF3E6; }
        .details-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .detail-item { background:#F5F5F5; border-radius:10px; padding:14px 16px; border:1px solid #E8E8E8; }
        .detail-label { font-size:12px; color:#666; margin-bottom:6px; font-weight:600; }
        .detail-value { font-size:17px; color:#111; font-weight:800; }
        .route-item { display:flex; align-items:flex-start; gap:14px; padding:10px 0; }
        .route-dot { width:12px; height:12px; border-radius:50%; margin-top:4px; flex-shrink:0; }
        .dot-origin { background:#FF6B00; } .dot-dest { background:#34C759; }
        .route-connector { width:2px; height:22px; background:#DDDDDD; margin-left:5px; margin-top:-4px; margin-bottom:-4px; }
        .route-label { font-size:12px; color:#777; font-weight:500; margin-bottom:3px; }
        .route-value { font-size:15px; color:#111; font-weight:700; }
        .qr-card { background:white; border-radius:14px; padding:24px; margin-bottom:16px; border:1px solid #E0E0E0; display:flex; align-items:center; gap:24px; }
        .qr-image-box { border:2px dashed #FF6B00; border-radius:12px; padding:12px; flex-shrink:0; }
        .qr-image { width:110px; height:110px; }
        .qr-info h3 { font-size:16px; font-weight:700; color:#111; margin-bottom:8px; }
        .qr-info p { font-size:13px; color:#555; line-height:1.7; }
        .footer { text-align:center; padding-top:20px; border-top:1px solid #E0E0E0; }
        .footer p { font-size:12px; color:#999; line-height:2; }
        .footer strong { color:#FF6B00; }
      </style></head><body>
      <div class="header">
        <div class="header-left">
          <img class="logo" src="${logoSrc}" />
          <div class="header-text"><h1>AYFEX</h1><p>Comprobante oficial de envio</p></div>
        </div>
        <div>
          <div class="pedido-num">Pedido #${pedido.id}</div>
          <div class="fecha-gen">Generado: ${new Date().toLocaleDateString('es-MX', { day: '2-digit', month: 'long', year: 'numeric' })}</div>
        </div>
      </div>
      <div class="estado-bar">
        <span class="estado-label">Estado actual</span>
        <span class="estado-badge">${estadoActual}</span>
      </div>
      <div class="card">
        <div class="card-title">Ruta de envio</div>
        <div class="route-item"><div class="route-dot dot-origin"></div><div><div class="route-label">Origen</div><div class="route-value">${pedido.origen}</div></div></div>
        <div class="route-connector"></div>
        <div class="route-item"><div class="route-dot dot-dest"></div><div><div class="route-label">Destino</div><div class="route-value">${pedido.destino}</div></div></div>
      </div>
      <div class="card">
        <div class="card-title">Detalles del paquete</div>
        <div class="details-grid">
          <div class="detail-item"><div class="detail-label">Peso</div><div class="detail-value">${pedido.peso} kg</div></div>
          <div class="detail-item"><div class="detail-label">Tipo</div><div class="detail-value">${pedido.tipo}</div></div>
          <div class="detail-item"><div class="detail-label">Altura</div><div class="detail-value">${pedido.altura} cm</div></div>
          <div class="detail-item"><div class="detail-label">Anchura</div><div class="detail-value">${pedido.anchura} cm</div></div>
          <div class="detail-item" style="grid-column:span 2"><div class="detail-label">Fecha de registro</div><div class="detail-value">${pedido.fecha ? pedido.fecha.split('T')[0] : 'Sin fecha'}</div></div>
        </div>
        ${pedido.descripcion ? `<div style="background:#FFF8F3;border-radius:10px;padding:16px;margin-top:14px;border-left:4px solid #FF6B00"><div style="font-size:12px;color:#888;margin-bottom:6px;font-weight:600">Descripcion</div><div style="font-size:14px;color:#333;line-height:1.6">${pedido.descripcion}</div></div>` : ''}
      </div>
      ${pedido.ruta_nombre ? `
      <div class="card">
        <div class="card-title">Informacion de envio</div>
        <div class="details-grid">
          <div class="detail-item"><div class="detail-label">Ruta</div><div class="detail-value" style="font-size:14px">${pedido.ruta_nombre}</div></div>
          <div class="detail-item"><div class="detail-label">Operador</div><div class="detail-value" style="font-size:14px">${pedido.operador_nombre || '—'}</div></div>
          ${pedido.dias_estimados ? `<div class="detail-item" style="grid-column:span 2"><div class="detail-label">Dias estimados</div><div class="detail-value">${pedido.dias_estimados} dias</div></div>` : ''}
        </div>
      </div>` : ''}
      <div class="qr-card">
        <div class="qr-image-box">
          <img class="qr-image" src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&data=${qrDataString}&color=111111&bgcolor=FFFFFF&qzone=1&margin=0" />
        </div>
        <div class="qr-info">
          <h3>Codigo de verificacion</h3>
          <p>Escanea este codigo QR para verificar la autenticidad de este pedido.</p>
        </div>
      </div>
      <div class="footer"><p>Comprobante oficial generado por <strong>AYFEX</strong><br>${new Date().getFullYear()} AYFEX Todos los derechos reservados.</p></div>
      </body></html>`;

      const { uri } = await Print.printToFileAsync({ html });
      const nombreArchivo = `${FileSystem.documentDirectory}AYFEX_${pedido.id}.pdf`;
      await FileSystem.moveAsync({ from: uri, to: nombreArchivo });
      await Sharing.shareAsync(nombreArchivo, { mimeType: 'application/pdf', dialogTitle: `Pedido ${pedido.id} - AYFEX`, UTI: 'com.adobe.pdf' });
    } catch (error) {
      console.log(error);
    } finally {
      setGenerandoPDF(false);
    }
  };

  // Botón de acción según el estado actual
  const renderBotonAccion = () => {
    switch (pedido.estado) {
      case 'EN PREPARACIÓN':
        return (
          <TouchableOpacity
            style={styles.accionButton}
            onPress={() => setModalListoVisible(true)}
          >
            <Ionicons name="checkmark-circle-outline" size={20} color="#FFFFFF" />
            <Text style={styles.accionButtonText}>Mi pedido está listo</Text>
          </TouchableOpacity>
        );
      case 'EN CAMINO':
        return (
          <TouchableOpacity
            style={[styles.accionButton, { backgroundColor: '#9B59B6' }]}
            onPress={() => setModalEntregarOperadorVisible(true)}
          >
            <Ionicons name="cube-outline" size={20} color="#FFFFFF" />
            <Text style={styles.accionButtonText}>Entregué al operador</Text>
          </TouchableOpacity>
        );
      case 'EN CAMINO AL DESTINO':
        return (
          <TouchableOpacity
            style={[styles.accionButton, { backgroundColor: '#34C759' }]}
            onPress={() => setModalEntregaFinalVisible(true)}
          >
            <Ionicons name="home-outline" size={20} color="#FFFFFF" />
            <Text style={styles.accionButtonText}>El paquete fue entregado</Text>
          </TouchableOpacity>
        );
      default:
        return null;
    }
  };

  return (
    <View style={styles.container}>
      <StatusBar barStyle="dark-content" />
      <HeaderNaranjaVolver navigation={navigation} />

      <ScrollView style={styles.content} showsVerticalScrollIndicator={false}>

        {/* TÍTULO Y ESTADO */}
        <View style={styles.titleContainer}>
          <View>
            <Text style={styles.screenTitle}>
              Detalle del <Text style={styles.screenTitleAccent}>Pedido</Text>
            </Text>
            <Text style={styles.orderId}>{pedido.id}</Text>
          </View>
          <View style={[styles.estadoBadge, { backgroundColor: estadoConfig.color + '20' }]}>
            <Ionicons name={estadoConfig.icono} size={13} color={estadoConfig.color} />
            <Text style={[styles.estadoText, { color: estadoConfig.color }]}>
              {estadoConfig.label}
            </Text>
          </View>
        </View>

        {/* MENSAJE DE RECHAZO */}
        {pedido.estado === 'RECHAZADO' && pedido.motivo_rechazo && (
          <View style={styles.rechazoCard}>
            <View style={styles.rechazoHeader}>
              <Ionicons name="close-circle" size={20} color="#FF3B30" />
              <Text style={styles.rechazoTitle}>Pedido rechazado</Text>
            </View>
            <Text style={styles.rechazoMotivo}>{pedido.motivo_rechazo}</Text>
          </View>
        )}

        {/* BOTÓN DE ACCIÓN PRINCIPAL */}
        {renderBotonAccion()}

        {/* RUTA */}
        <View style={styles.sectionCard}>
          <View style={styles.sectionCardHeader}>
            <View style={styles.sectionIcon}>
              <Ionicons name="navigate-outline" size={16} color="#FF6B00" />
            </View>
            <Text style={styles.sectionTitle}>Ruta de envío</Text>
          </View>
          <View style={styles.infoRow}>
            <View style={styles.infoIconBox}>
              <Ionicons name="location" size={16} color="#FF6B00" />
            </View>
            <View style={styles.infoContent}>
              <Text style={styles.infoLabel}>Origen</Text>
              <Text style={styles.infoValue}>{pedido.origen}</Text>
            </View>
          </View>
          <View style={styles.routeArrow}>
            <View style={styles.routeArrowLine} />
            <Ionicons name="arrow-down" size={14} color="#CCCCCC" />
            <View style={styles.routeArrowLine} />
          </View>
          <View style={styles.infoRow}>
            <View style={styles.infoIconBox}>
              <Ionicons name="flag" size={16} color="#34C759" />
            </View>
            <View style={styles.infoContent}>
              <Text style={styles.infoLabel}>Destino</Text>
              <Text style={styles.infoValue}>{pedido.destino}</Text>
            </View>
          </View>
        </View>

        {/* DETALLES DEL PAQUETE */}
        <View style={styles.sectionCard}>
          <View style={styles.sectionCardHeader}>
            <View style={styles.sectionIcon}>
              <Ionicons name="cube-outline" size={16} color="#FF6B00" />
            </View>
            <Text style={styles.sectionTitle}>Detalles del paquete</Text>
          </View>
          {[
            { icon: 'barbell-outline', label: 'Peso', value: `${pedido.peso} kg` },
            { icon: 'pricetag-outline', label: 'Tipo', value: pedido.tipo },
            { icon: 'resize-outline', label: 'Dimensiones', value: `${pedido.altura} cm x ${pedido.anchura} cm` },
            { icon: 'calendar-outline', label: 'Fecha', value: pedido.fecha ? pedido.fecha.split('T')[0] : 'Sin fecha' },
          ].map((item, index, arr) => (
            <View key={item.label}>
              <View style={styles.infoRow}>
                <View style={styles.infoIconBox}>
                  <Ionicons name={item.icon} size={16} color="#888" />
                </View>
                <View style={styles.infoContent}>
                  <Text style={styles.infoLabel}>{item.label}</Text>
                  <Text style={styles.infoValue}>{item.value}</Text>
                </View>
              </View>
              {index < arr.length - 1 && <View style={styles.infoDivider} />}
            </View>
          ))}
          {pedido.descripcion ? (
            <>
              <View style={styles.infoDivider} />
              <View style={styles.infoRow}>
                <View style={styles.infoIconBox}>
                  <Ionicons name="document-text-outline" size={16} color="#888" />
                </View>
                <View style={styles.infoContent}>
                  <Text style={styles.infoLabel}>Descripción</Text>
                  <Text style={styles.infoValue}>{pedido.descripcion}</Text>
                </View>
              </View>
            </>
          ) : null}
        </View>

        {/* INFO DE ENVÍO (solo si ya fue asignado) */}
        {pedido.ruta_nombre && (
          <View style={styles.sectionCard}>
            <View style={styles.sectionCardHeader}>
              <View style={styles.sectionIcon}>
                <Ionicons name="car-outline" size={16} color="#FF6B00" />
              </View>
              <Text style={styles.sectionTitle}>Información de envío</Text>
            </View>

            <View style={styles.infoRow}>
              <View style={styles.infoIconBox}>
                <Ionicons name="map-outline" size={16} color="#888" />
              </View>
              <View style={styles.infoContent}>
                <Text style={styles.infoLabel}>Ruta asignada</Text>
                <Text style={styles.infoValue}>{pedido.ruta_nombre}</Text>
                {pedido.ruta_zonas && (
                  <Text style={styles.infoSubValue}>Zonas: {pedido.ruta_zonas}</Text>
                )}
              </View>
            </View>

            {pedido.operador_nombre && (
              <>
                <View style={styles.infoDivider} />
                <View style={styles.infoRow}>
                  <View style={styles.infoIconBox}>
                    <Ionicons name="person-outline" size={16} color="#888" />
                  </View>
                  <View style={styles.infoContent}>
                    <Text style={styles.infoLabel}>Operador</Text>
                    <Text style={styles.infoValue}>{pedido.operador_nombre}</Text>
                    {pedido.operador_telefono && (
                      <Text style={styles.infoSubValue}>{pedido.operador_telefono}</Text>
                    )}
                  </View>
                </View>
              </>
            )}

            {diasRestantes !== null && (
              <>
                <View style={styles.infoDivider} />
                <View style={styles.diasCard}>
                  <View style={styles.diasIconContainer}>
                    <Ionicons name="time-outline" size={24} color="#FF6B00" />
                  </View>
                  <View style={styles.diasContent}>
                    <Text style={styles.diasLabel}>Tiempo estimado de llegada</Text>
                    <Text style={styles.diasNumero}>
                      {diasRestantes === 0
                        ? 'Hoy llega el conductor'
                        : `${diasRestantes} día${diasRestantes !== 1 ? 's' : ''} restante${diasRestantes !== 1 ? 's' : ''}`}
                    </Text>
                    <Text style={styles.diasSublabel}>
                      {pedido.dias_estimados} días estimados desde la asignación
                    </Text>
                  </View>
                </View>
              </>
            )}
          </View>
        )}

        {/* ACCIONES */}
        <View style={styles.actionsContainer}>
          <TouchableOpacity
            style={styles.pdfButton}
            onPress={generarPDF}
            disabled={generandoPDF}
          >
            <Ionicons name="download-outline" size={18} color="#FFFFFF" />
            <Text style={styles.pdfButtonText}>
              {generandoPDF ? "Generando..." : "Descargar PDF"}
            </Text>
          </TouchableOpacity>

          {(pedido.estado === 'EN PREPARACIÓN' || pedido.estado === 'EN ESPERA') && (
            <View style={styles.actionsRow}>
              <TouchableOpacity
                style={styles.updateButton}
                onPress={() => setModalEditarVisible(true)}
              >
                <Ionicons name="pencil-outline" size={16} color="#FFFFFF" />
                <Text style={styles.updateText}>Editar</Text>
              </TouchableOpacity>
              <TouchableOpacity
                style={styles.deleteButton}
                onPress={() => setModalEliminarVisible(true)}
              >
                <Ionicons name="trash-outline" size={16} color="#FFFFFF" />
                <Text style={styles.deleteText}>Eliminar</Text>
              </TouchableOpacity>
            </View>
          )}

          {pedido.estado === 'RECHAZADO' && (
            <TouchableOpacity
              style={styles.deleteButton}
              onPress={() => setModalEliminarVisible(true)}
            >
              <Ionicons name="trash-outline" size={16} color="#FFFFFF" />
              <Text style={styles.deleteText}>Eliminar pedido rechazado</Text>
            </TouchableOpacity>
          )}
        </View>

        <View style={{ height: 120 }} />
      </ScrollView>

      {/* MODALES DE CONFIRMACIÓN DE ESTADO */}
      <ModalConfirmacion
        visible={modalListoVisible}
        titulo="¿Tu pedido está listo?"
        mensaje="Al confirmar, tu pedido pasará a EN ESPERA y será revisado por nuestro equipo para asignarle una ruta."
        confirmText="Sí, está listo"
        onConfirmar={confirmarListo}
        onCancelar={() => setModalListoVisible(false)}
      />

      <ModalConfirmacion
        visible={modalEntregarOperadorVisible}
        titulo="¿Entregaste el paquete al operador?"
        mensaje="Confirma que ya entregaste físicamente el paquete al operador asignado. Esta acción no se puede deshacer."
        confirmText="Sí, lo entregué"
        onConfirmar={confirmarEntregaOperador}
        onCancelar={() => setModalEntregarOperadorVisible(false)}
      />

      <ModalConfirmacion
        visible={modalEntregaFinalVisible}
        titulo="¿El paquete fue entregado?"
        mensaje="Confirma que el paquete llegó correctamente a su destino. Esta acción marcará el pedido como ENTREGADO."
        confirmText="Sí, fue entregado"
        onConfirmar={confirmarEntregaFinal}
        onCancelar={() => setModalEntregaFinalVisible(false)}
      />

      {/* MODAL ELIMINAR */}
      <ModalConfirmacion
        visible={modalEliminarVisible}
        titulo="¿Eliminar pedido?"
        mensaje={`Esta acción no se puede deshacer. El pedido ${pedido.id} será eliminado permanentemente.`}
        confirmText="Sí, eliminar"
        peligro
        onConfirmar={confirmarEliminar}
        onCancelar={() => setModalEliminarVisible(false)}
      />

      {/* MODAL EDITAR */}
      <Modal visible={modalEditarVisible} animationType="slide" transparent>
        <View style={styles.modalOverlay}>
          <View style={styles.modalContainer}>
            <ScrollView showsVerticalScrollIndicator={false}>
              <View style={styles.modalHeader}>
                <Text style={styles.modalTitle}>Editar Pedido</Text>
                <TouchableOpacity onPress={() => setModalEditarVisible(false)}>
                  <Ionicons name="close" size={24} color="#333" />
                </TouchableOpacity>
              </View>

              {[
                { label: 'Origen', value: origen, setter: setOrigen, icon: 'location-outline' },
                { label: 'Destino', value: destino, setter: setDestino, icon: 'flag-outline' },
                { label: 'Tipo', value: tipo, setter: setTipo, icon: 'pricetag-outline' },
              ].map(field => (
                <View key={field.label}>
                  <Text style={styles.inputLabel}>{field.label}</Text>
                  <View style={styles.inputContainer}>
                    <Ionicons name={field.icon} size={16} color="#FF6B00" />
                    <TextInput style={styles.input} value={field.value} onChangeText={field.setter} placeholderTextColor="#BBB" />
                  </View>
                </View>
              ))}

              <View style={styles.rowContainer}>
                <View style={styles.halfInput}>
                  <Text style={styles.inputLabel}>Peso (kg)</Text>
                  <View style={styles.inputContainer}>
                    <Ionicons name="barbell-outline" size={16} color="#FF6B00" />
                    <TextInput style={styles.input} value={peso} onChangeText={setPeso} keyboardType="numeric" placeholderTextColor="#BBB" />
                  </View>
                </View>
                <View style={styles.halfInput}>
                  <Text style={styles.inputLabel}>Altura (cm)</Text>
                  <View style={styles.inputContainer}>
                    <Ionicons name="resize-outline" size={16} color="#FF6B00" />
                    <TextInput style={styles.input} value={altura} onChangeText={setAltura} keyboardType="numeric" placeholderTextColor="#BBB" />
                  </View>
                </View>
              </View>

              <Text style={styles.inputLabel}>Anchura (cm)</Text>
              <View style={styles.inputContainer}>
                <Ionicons name="resize-outline" size={16} color="#FF6B00" />
                <TextInput style={styles.input} value={anchura} onChangeText={setAnchura} keyboardType="numeric" placeholderTextColor="#BBB" />
              </View>

              <Text style={styles.inputLabel}>Descripción</Text>
              <View style={[styles.inputContainer, { alignItems: 'flex-start', minHeight: 80 }]}>
                <Ionicons name="document-text-outline" size={16} color="#FF6B00" style={{ marginTop: 2 }} />
                <TextInput style={[styles.input, { height: 70, textAlignVertical: 'top' }]} value={descripcion} onChangeText={setDescripcion} multiline placeholderTextColor="#BBB" />
              </View>

              <TouchableOpacity style={styles.saveButton} onPress={actualizarPedido}>
                <Text style={styles.saveButtonText}>Guardar cambios</Text>
              </TouchableOpacity>
              <TouchableOpacity style={styles.cancelButton} onPress={() => setModalEditarVisible(false)}>
                <Text style={styles.cancelButtonText}>Cancelar</Text>
              </TouchableOpacity>
              <View style={{ height: 40 }} />
            </ScrollView>
          </View>
        </View>
      </Modal>

    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#FFFFFF' },
  content: { paddingHorizontal: 20 },

  titleContainer: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', paddingTop: 20, paddingBottom: 16 },
  screenTitle: { fontSize: 22, fontWeight: 'bold', color: '#000000' },
  screenTitleAccent: { color: '#FF6B00' },
  orderId: { fontSize: 13, color: '#999999', marginTop: 2 },
  estadoBadge: { flexDirection: 'row', alignItems: 'center', gap: 5, paddingHorizontal: 12, paddingVertical: 6, borderRadius: 20 },
  estadoText: { fontSize: 11, fontWeight: '700' },

  rechazoCard: {
    backgroundColor: '#FFF0F0', borderRadius: 14, padding: 16,
    marginBottom: 14, borderWidth: 1, borderColor: '#FFD0D0',
  },
  rechazoHeader: { flexDirection: 'row', alignItems: 'center', gap: 8, marginBottom: 8 },
  rechazoTitle: { fontSize: 15, fontWeight: '700', color: '#FF3B30' },
  rechazoMotivo: { fontSize: 14, color: '#555', lineHeight: 20 },

  accionButton: {
    backgroundColor: '#FF6B00', borderRadius: 14,
    paddingVertical: 16, alignItems: 'center',
    flexDirection: 'row', justifyContent: 'center', gap: 8,
    marginBottom: 14,
    shadowColor: '#FF6B00', shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.25, shadowRadius: 6, elevation: 5,
  },
  accionButtonText: { color: '#FFFFFF', fontSize: 15, fontWeight: '700' },

  sectionCard: { backgroundColor: '#F8F9FA', borderRadius: 16, padding: 16, marginBottom: 14, borderWidth: 1, borderColor: '#EEEEEE' },
  sectionCardHeader: { flexDirection: 'row', alignItems: 'center', marginBottom: 14 },
  sectionIcon: { width: 30, height: 30, borderRadius: 8, backgroundColor: '#FFF3E6', justifyContent: 'center', alignItems: 'center', marginRight: 10 },
  sectionTitle: { fontSize: 14, fontWeight: '600', color: '#333333' },

  infoRow: { flexDirection: 'row', alignItems: 'center', paddingVertical: 6 },
  infoIconBox: { width: 32, height: 32, borderRadius: 8, backgroundColor: '#FFFFFF', justifyContent: 'center', alignItems: 'center', marginRight: 12, borderWidth: 1, borderColor: '#EEEEEE' },
  infoContent: { flex: 1 },
  infoLabel: { fontSize: 12, color: '#999999' },
  infoValue: { fontSize: 14, color: '#222222', fontWeight: '500', marginTop: 2 },
  infoSubValue: { fontSize: 12, color: '#AAAAAA', marginTop: 2 },
  infoDivider: { height: 1, backgroundColor: '#EEEEEE', marginVertical: 4 },
  routeArrow: { flexDirection: 'row', alignItems: 'center', paddingLeft: 8, marginVertical: 4 },
  routeArrowLine: { width: 16, height: 1, backgroundColor: '#EEEEEE' },

  diasCard: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#FFF3E6', borderRadius: 12, padding: 14, marginTop: 8, gap: 12 },
  diasIconContainer: { width: 44, height: 44, borderRadius: 12, backgroundColor: '#FFFFFF', justifyContent: 'center', alignItems: 'center' },
  diasContent: { flex: 1 },
  diasLabel: { fontSize: 12, color: '#888', marginBottom: 4 },
  diasNumero: { fontSize: 16, fontWeight: '800', color: '#FF6B00' },
  diasSublabel: { fontSize: 11, color: '#AAAAAA', marginTop: 2 },

  actionsContainer: { marginTop: 4 },
  pdfButton: { backgroundColor: '#333333', flexDirection: 'row', justifyContent: 'center', alignItems: 'center', paddingVertical: 14, borderRadius: 14, marginBottom: 10, gap: 8 },
  pdfButtonText: { color: '#FFFFFF', fontSize: 15, fontWeight: '600' },
  actionsRow: { flexDirection: 'row', gap: 10 },
  updateButton: { flex: 1, backgroundColor: "#FF6B00", flexDirection: 'row', justifyContent: 'center', alignItems: 'center', padding: 14, borderRadius: 14, gap: 6 },
  deleteButton: { flex: 1, backgroundColor: "#FF3B30", flexDirection: 'row', justifyContent: 'center', alignItems: 'center', padding: 14, borderRadius: 14, gap: 6 },
  updateText: { color: "#fff", fontWeight: '600' },
  deleteText: { color: "#fff", fontWeight: '600' },

  modalOverlay: { flex: 1, backgroundColor: 'rgba(0,0,0,0.4)', justifyContent: 'flex-end' },
  modalContainer: { backgroundColor: '#FFFFFF', borderTopLeftRadius: 24, borderTopRightRadius: 24, padding: 24, paddingBottom: Platform.OS === 'ios' ? 40 : 24, maxHeight: '90%' },
  modalHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 16 },
  modalTitle: { fontSize: 20, fontWeight: 'bold', color: '#000000' },
  inputLabel: { fontSize: 13, color: '#666', fontWeight: '500', marginBottom: 6, marginTop: 12 },
  inputContainer: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#F8F9FA', borderRadius: 12, paddingHorizontal: 12, paddingVertical: Platform.OS === 'ios' ? 12 : 8, borderWidth: 1, borderColor: '#EEEEEE' },
  input: { flex: 1, marginLeft: 8, fontSize: 14, color: '#333333' },
  rowContainer: { flexDirection: 'row', gap: 10 },
  halfInput: { flex: 1 },
  saveButton: { backgroundColor: '#FF6B00', borderRadius: 12, paddingVertical: 16, alignItems: 'center', marginTop: 20 },
  saveButtonText: { color: '#FFFFFF', fontSize: 16, fontWeight: '600' },
  cancelButton: { alignItems: 'center', paddingVertical: 14 },
  cancelButtonText: { fontSize: 15, color: '#999999' },

  alertOverlay: { flex: 1, backgroundColor: 'rgba(0,0,0,0.45)', justifyContent: 'center', alignItems: 'center', paddingHorizontal: 32 },
  alertContainer: { backgroundColor: '#FFFFFF', borderRadius: 20, padding: 28, alignItems: 'center', width: '100%', shadowColor: '#000', shadowOffset: { width: 0, height: 8 }, shadowOpacity: 0.12, shadowRadius: 16, elevation: 10 },
  alertIconContainer: { width: 70, height: 70, borderRadius: 35, backgroundColor: '#FFF3E6', justifyContent: 'center', alignItems: 'center', marginBottom: 16 },
  alertTitulo: { fontSize: 18, fontWeight: '700', color: '#111111', marginBottom: 8, textAlign: 'center' },
  alertMensaje: { fontSize: 14, color: '#666666', textAlign: 'center', lineHeight: 20, marginBottom: 24 },
  alertBoton: { backgroundColor: '#FF6B00', borderRadius: 12, paddingVertical: 13, paddingHorizontal: 40, marginBottom: 4 },
  alertBotonTexto: { color: '#FFFFFF', fontSize: 15, fontWeight: '700' },
  alertCancelar: { paddingVertical: 12 },
  alertCancelarTexto: { fontSize: 14, color: '#999999' },
});