import React, { useState } from 'react';
import {
  View, Text, StyleSheet, TouchableOpacity,
  ScrollView, StatusBar, Alert, Modal,
  TextInput, Platform
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import QRCode from 'react-native-qrcode-svg';
import * as Print from 'expo-print';
import * as Sharing from 'expo-sharing';
import HeaderNaranjaVolver from '../Components/HeaderNaranjaVolver';
import { API_BASE_URL } from '../config';

const API_URL = `${API_BASE_URL}/v1/pedidos`;

export default function PedidosM_Detalles({ navigation, route }) {

  const { pedidoData } = route.params;

  const [modalEditarVisible, setModalEditarVisible] = useState(false);
  const [modalEliminarVisible, setModalEliminarVisible] = useState(false);
  const [generandoPDF, setGenerandoPDF] = useState(false);

  const [origen, setOrigen] = useState(pedidoData.origen);
  const [destino, setDestino] = useState(pedidoData.destino);
  const [peso, setPeso] = useState(String(pedidoData.peso));
  const [tipo, setTipo] = useState(pedidoData.tipo);
  const [altura, setAltura] = useState(String(pedidoData.altura));
  const [anchura, setAnchura] = useState(String(pedidoData.anchura));
  const [descripcion, setDescripcion] = useState(pedidoData.descripcion || '');

  const getEstadoColor = (estado) => {
    switch (estado) {
      case 'EN CAMINO': return '#3A86FF';
      case 'ENTREGADO': return '#34C759';
      case 'CANCELADO': return '#FF3B30';
      default: return '#FF6B00';
    }
  };

  const estadoActual = pedidoData.estado || 'EN PREPARACIÓN';
  const qrData = JSON.stringify({
    id: pedidoData.id,
    origen: pedidoData.origen,
    destino: pedidoData.destino,
    peso: pedidoData.peso,
    tipo: pedidoData.tipo,
    fecha: pedidoData.fecha,
    estado: estadoActual,
  });

  const actualizarPedido = async () => {
    if (!origen || origen.length < 5) {
      Alert.alert("Error", "El origen debe tener al menos 5 caracteres");
      return;
    }
    if (!destino || destino.length < 5) {
      Alert.alert("Error", "El destino debe tener al menos 5 caracteres");
      return;
    }

    try {
      const response = await fetch(`${API_URL}/${pedidoData.id}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
          "Authorization": `Bearer ${global.authToken}`
        },
        body: JSON.stringify({
          origen, destino,
          peso: parseFloat(peso),
          tipo,
          altura: parseFloat(altura),
          anchura: parseFloat(anchura),
          descripcion
        })
      });

      if (!response.ok) throw new Error("Error al actualizar");

      Alert.alert("¡Listo!", "Pedido actualizado correctamente");
      setModalEditarVisible(false);
      navigation.goBack();

    } catch (error) {
      Alert.alert("Error", "No se pudo actualizar el pedido");
    }
  };

  const confirmarEliminar = async () => {
    try {
      await fetch(`${API_URL}/${pedidoData.id}`, {
        method: "DELETE",
        headers: { "Authorization": `Bearer ${global.authToken}` }
      });
      setModalEliminarVisible(false);
      navigation.goBack();
    } catch (error) {
      Alert.alert("Error", "No se pudo eliminar el pedido");
    }
  };

  const generarPDF = async () => {
    try {
      setGenerandoPDF(true);

      // Generamos el QR como SVG string para el PDF
      const qrSvg = `
        <svg xmlns="http://www.w3.org/2000/svg" width="150" height="150">
          <rect width="150" height="150" fill="white"/>
          <text x="75" y="80" text-anchor="middle" font-size="10" fill="#333">QR-${pedidoData.id}</text>
          <rect x="10" y="10" width="130" height="130" fill="none" stroke="#FF6B00" stroke-width="2"/>
        </svg>
      `;

      const html = `
        <!DOCTYPE html>
        <html>
        <head>
          <meta charset="utf-8">
          <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: Arial, sans-serif; padding: 40px; color: #333; }
            .header { background: #FF6B00; color: white; padding: 24px; border-radius: 12px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; }
            .header h1 { font-size: 28px; letter-spacing: 4px; }
            .header p { font-size: 13px; opacity: 0.85; margin-top: 4px; }
            .badge { background: white; color: #FF6B00; padding: 6px 16px; border-radius: 20px; font-weight: bold; font-size: 13px; }
            .section { background: #F8F9FA; border-radius: 12px; padding: 20px; margin-bottom: 16px; border: 1px solid #EEEEEE; }
            .section-title { font-size: 13px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 14px; font-weight: 600; }
            .row { display: flex; justify-content: space-between; margin-bottom: 10px; }
            .label { font-size: 13px; color: #888; }
            .value { font-size: 14px; color: #222; font-weight: 500; text-align: right; max-width: 60%; }
            .divider { height: 1px; background: #EEEEEE; margin: 8px 0; }
            .qr-section { display: flex; justify-content: center; align-items: center; flex-direction: column; padding: 24px; }
            .qr-box { border: 2px dashed #FF6B00; padding: 16px; border-radius: 12px; margin-bottom: 10px; }
            .qr-label { font-size: 12px; color: #999; text-align: center; }
            .footer { text-align: center; font-size: 11px; color: #CCC; margin-top: 24px; }
            .estado { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: bold; background: #FFF3E6; color: #FF6B00; }
          </style>
        </head>
        <body>
          <div class="header">
            <div>
              <h1>AYFEX</h1>
              <p>Comprobante de envío</p>
            </div>
            <div class="badge">Pedido #${pedidoData.id}</div>
          </div>

          <div class="section">
            <div class="section-title">Estado del pedido</div>
            <span class="estado">${estadoActual}</span>
          </div>

          <div class="section">
            <div class="section-title">Ruta</div>
            <div class="row">
              <span class="label">📍 Origen</span>
              <span class="value">${pedidoData.origen}</span>
            </div>
            <div class="divider"></div>
            <div class="row">
              <span class="label">🏁 Destino</span>
              <span class="value">${pedidoData.destino}</span>
            </div>
          </div>

          <div class="section">
            <div class="section-title">Detalles del paquete</div>
            <div class="row">
              <span class="label">Peso</span>
              <span class="value">${pedidoData.peso} kg</span>
            </div>
            <div class="divider"></div>
            <div class="row">
              <span class="label">Tipo</span>
              <span class="value">${pedidoData.tipo}</span>
            </div>
            <div class="divider"></div>
            <div class="row">
              <span class="label">Dimensiones</span>
              <span class="value">${pedidoData.altura} cm × ${pedidoData.anchura} cm</span>
            </div>
            <div class="divider"></div>
            <div class="row">
              <span class="label">Fecha</span>
              <span class="value">${pedidoData.fecha ? pedidoData.fecha.split('T')[0] : 'Sin fecha'}</span>
            </div>
            ${pedidoData.descripcion ? `
            <div class="divider"></div>
            <div class="row">
              <span class="label">Descripción</span>
              <span class="value">${pedidoData.descripcion}</span>
            </div>` : ''}
          </div>

          <div class="section qr-section">
            <div class="section-title" style="text-align:center">Código de verificación</div>
            <div class="qr-box">
              ${qrSvg}
            </div>
            <p class="qr-label">Escanea para verificar este pedido</p>
            <p class="qr-label" style="margin-top:6px; font-size:10px;">ID: ${pedidoData.id} · AYFEX</p>
          </div>

          <div class="footer">
            Generado por AYFEX · ${new Date().toLocaleDateString('es-MX')}
          </div>
        </body>
        </html>
      `;

      const { uri } = await Print.printToFileAsync({ html });
      await Sharing.shareAsync(uri, {
        mimeType: 'application/pdf',
        dialogTitle: `Pedido #${pedidoData.id} - AYFEX`,
      });

    } catch (error) {
      Alert.alert("Error", "No se pudo generar el PDF");
    } finally {
      setGenerandoPDF(false);
    }
  };

  return (
    <View style={styles.container}>
      <StatusBar barStyle="dark-content" />
      <HeaderNaranjaVolver navigation={navigation} />

      <ScrollView style={styles.content} showsVerticalScrollIndicator={false}>

        {/* TÍTULO */}
        <View style={styles.titleContainer}>
          <View>
            <Text style={styles.screenTitle}>Detalle del <Text style={styles.screenTitleAccent}>Pedido</Text></Text>
            <Text style={styles.orderId}>#{pedidoData.id}</Text>
          </View>
          <View style={[styles.estadoBadge, { backgroundColor: getEstadoColor(estadoActual) + '20' }]}>
            <Text style={[styles.estadoText, { color: getEstadoColor(estadoActual) }]}>{estadoActual}</Text>
          </View>
        </View>

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
              <Text style={styles.infoValue}>{pedidoData.origen}</Text>
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
              <Text style={styles.infoValue}>{pedidoData.destino}</Text>
            </View>
          </View>
        </View>

        {/* DETALLES */}
        <View style={styles.sectionCard}>
          <View style={styles.sectionCardHeader}>
            <View style={styles.sectionIcon}>
              <Ionicons name="cube-outline" size={16} color="#FF6B00" />
            </View>
            <Text style={styles.sectionTitle}>Detalles del paquete</Text>
          </View>

          {[
            { icon: 'barbell-outline', label: 'Peso', value: `${pedidoData.peso} kg` },
            { icon: 'pricetag-outline', label: 'Tipo', value: pedidoData.tipo },
            { icon: 'resize-outline', label: 'Dimensiones', value: `${pedidoData.altura} cm × ${pedidoData.anchura} cm` },
            { icon: 'calendar-outline', label: 'Fecha', value: pedidoData.fecha ? pedidoData.fecha.split('T')[0] : 'Sin fecha' },
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

          {pedidoData.descripcion ? (
            <>
              <View style={styles.infoDivider} />
              <View style={styles.infoRow}>
                <View style={styles.infoIconBox}>
                  <Ionicons name="document-text-outline" size={16} color="#888" />
                </View>
                <View style={styles.infoContent}>
                  <Text style={styles.infoLabel}>Descripción</Text>
                  <Text style={styles.infoValue}>{pedidoData.descripcion}</Text>
                </View>
              </View>
            </>
          ) : null}
        </View>

        {/* QR */}
        <View style={styles.sectionCard}>
          <View style={styles.sectionCardHeader}>
            <View style={styles.sectionIcon}>
              <Ionicons name="qr-code-outline" size={16} color="#FF6B00" />
            </View>
            <Text style={styles.sectionTitle}>Código de verificación</Text>
          </View>
          <View style={styles.qrContainer}>
            <QRCode value={qrData} size={150} color="#333333" backgroundColor="white" />
            <Text style={styles.qrLabel}>Escanea para verificar este pedido</Text>
          </View>
        </View>

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
        </View>

        <View style={{ height: 120 }} />
      </ScrollView>

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
                    <TextInput
                      style={styles.input}
                      value={field.value}
                      onChangeText={field.setter}
                      placeholderTextColor="#BBB"
                    />
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
                <TextInput
                  style={[styles.input, { height: 70, textAlignVertical: 'top' }]}
                  value={descripcion}
                  onChangeText={setDescripcion}
                  multiline
                  placeholderTextColor="#BBB"
                />
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

      {/* MODAL ELIMINAR */}
      <Modal visible={modalEliminarVisible} animationType="fade" transparent>
        <View style={styles.modalOverlay}>
          <View style={[styles.modalContainer, styles.modalEliminar]}>
            <View style={styles.eliminarIconContainer}>
              <Ionicons name="trash-outline" size={36} color="#FF3B30" />
            </View>
            <Text style={styles.eliminarTitle}>¿Eliminar pedido?</Text>
            <Text style={styles.eliminarSubtitle}>
              Esta acción no se puede deshacer. El pedido #{pedidoData.id} será eliminado permanentemente.
            </Text>
            <TouchableOpacity style={styles.deleteButtonModal} onPress={confirmarEliminar}>
              <Text style={styles.deleteButtonModalText}>Sí, eliminar</Text>
            </TouchableOpacity>
            <TouchableOpacity style={styles.cancelButton} onPress={() => setModalEliminarVisible(false)}>
              <Text style={styles.cancelButtonText}>Cancelar</Text>
            </TouchableOpacity>
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
  estadoBadge: { paddingHorizontal: 12, paddingVertical: 6, borderRadius: 20 },
  estadoText: { fontSize: 12, fontWeight: '700' },

  sectionCard: {
    backgroundColor: '#F8F9FA',
    borderRadius: 16,
    padding: 16,
    marginBottom: 14,
    borderWidth: 1,
    borderColor: '#EEEEEE',
  },
  sectionCardHeader: { flexDirection: 'row', alignItems: 'center', marginBottom: 14 },
  sectionIcon: {
    width: 30, height: 30, borderRadius: 8,
    backgroundColor: '#FFF3E6',
    justifyContent: 'center', alignItems: 'center', marginRight: 10,
  },
  sectionTitle: { fontSize: 14, fontWeight: '600', color: '#333333' },

  infoRow: { flexDirection: 'row', alignItems: 'center', paddingVertical: 6 },
  infoIconBox: {
    width: 32, height: 32, borderRadius: 8,
    backgroundColor: '#FFFFFF',
    justifyContent: 'center', alignItems: 'center',
    marginRight: 12, borderWidth: 1, borderColor: '#EEEEEE',
  },
  infoContent: { flex: 1 },
  infoLabel: { fontSize: 12, color: '#999999' },
  infoValue: { fontSize: 14, color: '#222222', fontWeight: '500', marginTop: 2 },
  infoDivider: { height: 1, backgroundColor: '#EEEEEE', marginVertical: 4 },

  routeArrow: { flexDirection: 'row', alignItems: 'center', paddingLeft: 8, marginVertical: 4 },
  routeArrowLine: { width: 16, height: 1, backgroundColor: '#EEEEEE' },

  qrContainer: { alignItems: 'center', paddingVertical: 8 },
  qrLabel: { fontSize: 12, color: '#AAAAAA', marginTop: 12, textAlign: 'center' },

  actionsContainer: { marginTop: 4 },
  pdfButton: {
    backgroundColor: '#333333',
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    paddingVertical: 14,
    borderRadius: 14,
    marginBottom: 10,
    gap: 8,
  },
  pdfButtonText: { color: '#FFFFFF', fontSize: 15, fontWeight: '600' },

  actionsRow: { flexDirection: 'row', gap: 10 },
  updateButton: {
    flex: 1, backgroundColor: "#FF6B00",
    flexDirection: 'row', justifyContent: 'center', alignItems: 'center',
    padding: 14, borderRadius: 14, gap: 6,
  },
  deleteButton: {
    flex: 1, backgroundColor: "#FF3B30",
    flexDirection: 'row', justifyContent: 'center', alignItems: 'center',
    padding: 14, borderRadius: 14, gap: 6,
  },
  updateText: { color: "#fff", fontWeight: '600' },
  deleteText: { color: "#fff", fontWeight: '600' },

  // MODALES
  modalOverlay: { flex: 1, backgroundColor: 'rgba(0,0,0,0.4)', justifyContent: 'flex-end' },
  modalContainer: {
    backgroundColor: '#FFFFFF',
    borderTopLeftRadius: 24, borderTopRightRadius: 24,
    padding: 24, paddingBottom: Platform.OS === 'ios' ? 40 : 24,
    maxHeight: '90%',
  },
  modalEliminar: { justifyContent: 'center', alignItems: 'center', borderRadius: 24, margin: 24, paddingVertical: 32 },
  modalHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 16 },
  modalTitle: { fontSize: 20, fontWeight: 'bold', color: '#000000' },

  inputLabel: { fontSize: 13, color: '#666', fontWeight: '500', marginBottom: 6, marginTop: 12 },
  inputContainer: {
    flexDirection: 'row', alignItems: 'center',
    backgroundColor: '#F8F9FA', borderRadius: 12,
    paddingHorizontal: 12, paddingVertical: Platform.OS === 'ios' ? 12 : 8,
    borderWidth: 1, borderColor: '#EEEEEE',
  },
  input: { flex: 1, marginLeft: 8, fontSize: 14, color: '#333333' },
  rowContainer: { flexDirection: 'row', gap: 10 },
  halfInput: { flex: 1 },

  saveButton: {
    backgroundColor: '#FF6B00', borderRadius: 12,
    paddingVertical: 16, alignItems: 'center', marginTop: 20,
  },
  saveButtonText: { color: '#FFFFFF', fontSize: 16, fontWeight: '600' },
  cancelButton: { alignItems: 'center', paddingVertical: 14 },
  cancelButtonText: { fontSize: 15, color: '#999999' },

  eliminarIconContainer: {
    width: 72, height: 72, borderRadius: 36,
    backgroundColor: '#FFF0F0', justifyContent: 'center',
    alignItems: 'center', marginBottom: 16,
  },
  eliminarTitle: { fontSize: 20, fontWeight: 'bold', color: '#000', marginBottom: 10 },
  eliminarSubtitle: { fontSize: 14, color: '#888', textAlign: 'center', lineHeight: 20, marginBottom: 24 },
  deleteButtonModal: {
    backgroundColor: '#FF3B30', borderRadius: 12,
    paddingVertical: 14, paddingHorizontal: 40, alignItems: 'center', width: '100%',
  },
  deleteButtonModalText: { color: '#FFFFFF', fontSize: 16, fontWeight: '600' },
});