import React, { useState, useCallback } from 'react';
import { useFocusEffect } from '@react-navigation/native';
import {
  View, Text, StyleSheet, TouchableOpacity,
  ScrollView, Modal, Platform
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { CameraView, useCameraPermissions } from 'expo-camera';
import HeaderNaranja from '../Components/HeaderNaranja';
import { API_BASE_URL } from '../config';

const API_URL = `${API_BASE_URL}/v1/pedidos/`;

export default function PrincipalM({ navigation }) {

  const [pedidos, setPedidos] = useState([]);
  const [scannerVisible, setScannerVisible] = useState(false);
  const [escaneando, setEscaneando] = useState(true);
  const [pedidoEscaneado, setPedidoEscaneado] = useState(null);
  const [permission, requestPermission] = useCameraPermissions();

  const primerNombre = global.usuarioActual?.nombre_completo?.split(' ')[0] || 'Usuario';

  const cargarPedidos = async () => {
    try {
      const response = await fetch(API_URL, {
        headers: { "Authorization": `Bearer ${global.authToken}` }
      });
      const data = await response.json();
      setPedidos(Array.isArray(data) ? data : []);
    } catch (error) {
      setPedidos([]);
    }
  };

  useFocusEffect(useCallback(() => { cargarPedidos(); }, []));

  const abrirScanner = async () => {
    if (!permission?.granted) {
      const result = await requestPermission();
      if (!result.granted) return;
    }
    setEscaneando(true);
    setPedidoEscaneado(null);
    setScannerVisible(true);
  };

  const onQRScaneado = ({ data }) => {
    if (!escaneando) return;
    setEscaneando(false);
    try {
      const pedido = JSON.parse(data);
      setPedidoEscaneado(pedido);
    } catch {
      setPedidoEscaneado(null);
      setEscaneando(true);
    }
  };

  const cerrarScanner = () => {
    setScannerVisible(false);
    setPedidoEscaneado(null);
    setEscaneando(true);
  };

  const getEstadoColor = (estado) => {
    switch (estado) {
      case 'EN CAMINO': return '#3A86FF';
      case 'ENTREGADO': return '#34C759';
      case 'CANCELADO': return '#FF3B30';
      default: return '#FF6B00';
    }
  };

  const verDetalles = (pedido) => {
    navigation.navigate("Pedidos", {
      screen: "PedidosDetalles",
      params: { pedidoData: pedido }
    });
  };

  const pedidosActivos = pedidos.length;
  const totalPedidos = pedidos.length;

  return (
    <View style={styles.container}>
      <HeaderNaranja />

      {/* BOTÓN QR EN EL HEADER */}
      <TouchableOpacity style={styles.qrButton} onPress={abrirScanner}>
        <Ionicons name="qr-code-outline" size={24} color="#FFFFFF" />
      </TouchableOpacity>

      <ScrollView style={styles.content} showsVerticalScrollIndicator={false}>

        <View style={styles.greetingContainer}>
          <Text style={styles.greetingText}>
            Hola, <Text style={styles.greetingName}>{primerNombre}!</Text>
          </Text>
          <Text style={styles.greetingSubtitle}>Aquí tienes un resumen de tus envíos</Text>
        </View>

        <View style={styles.statsContainer}>
          <View style={styles.statCard}>
            <View style={styles.statIconContainer}>
              <Ionicons name="time-outline" size={20} color="#FF6B00" />
            </View>
            <Text style={styles.statNumber}>{pedidosActivos}</Text>
            <Text style={styles.statLabel}>Activos</Text>
          </View>
          <View style={styles.statCard}>
            <View style={styles.statIconContainer}>
              <Ionicons name="cube-outline" size={20} color="#FF6B00" />
            </View>
            <Text style={styles.statNumber}>{totalPedidos}</Text>
            <Text style={styles.statLabel}>Total</Text>
          </View>
        </View>

        <View style={styles.separator} />

        <View style={styles.shipmentsHeader}>
          <Text style={styles.shipmentsTitle}>Envíos Activos</Text>
          <TouchableOpacity onPress={() => navigation.navigate("Pedidos")}>
            <Text style={styles.viewAllText}>Ver todos</Text>
          </TouchableOpacity>
        </View>

        {pedidos.length === 0 && (
          <View style={styles.emptyContainer}>
            <Ionicons name="cube-outline" size={40} color="#DDDDDD" />
            <Text style={styles.emptyText}>Aquí se reflejarán tus pedidos activos</Text>
            <TouchableOpacity style={styles.emptyButton} onPress={() => navigation.navigate("Crear")}>
              <Text style={styles.emptyButtonText}>Crear mi primer pedido</Text>
            </TouchableOpacity>
          </View>
        )}

        {pedidos.map((pedido) => (
          <TouchableOpacity
            key={pedido.id}
            style={styles.orderCard}
            onPress={() => verDetalles(pedido)}
            activeOpacity={0.8}
          >
            <View style={styles.cardHeader}>
              <View style={styles.packageIcon}>
                <Ionicons name="cube" size={20} color="#fff" />
              </View>
              <View style={{ flex: 1 }}>
                <Text style={styles.orderId}>{pedido.id}</Text>
              </View>
              <View style={[styles.estadoBadge, { backgroundColor: getEstadoColor(pedido.estado) + '20' }]}>
                <Text style={[styles.estadoText, { color: getEstadoColor(pedido.estado) }]}>
                  {pedido.estado || 'EN PREPARACIÓN'}
                </Text>
              </View>
            </View>

            <View style={styles.routeContainer}>
              <View style={styles.routeRow}>
                <Ionicons name="location" size={16} color="#FF6B00" />
                <Text style={styles.routeText} numberOfLines={1}>{pedido.origen}</Text>
              </View>
              <Ionicons name="arrow-down" size={16} color="#999" style={{ marginVertical: 4 }} />
              <View style={styles.routeRow}>
                <Ionicons name="flag" size={16} color="#34C759" />
                <Text style={styles.routeText} numberOfLines={1}>{pedido.destino}</Text>
              </View>
            </View>

            <View style={styles.orderInfo}>
              <View style={styles.infoItem}>
                <Ionicons name="barbell-outline" size={16} color="#666" />
                <Text style={styles.infoText}>{pedido.peso} kg</Text>
              </View>
              <View style={styles.infoItem}>
                <Ionicons name="cube-outline" size={16} color="#666" />
                <Text style={styles.infoText}>{pedido.tipo}</Text>
              </View>
            </View>

            <View style={styles.footer}>
              <Text style={styles.date}>{pedido.fecha ? pedido.fecha.split("T")[0] : "Sin fecha"}</Text>
              <View style={styles.detailsButton}>
                <Text style={styles.detailsText}>Ver detalles</Text>
                <Ionicons name="chevron-forward" size={16} color="#FF6B00" />
              </View>
            </View>
          </TouchableOpacity>
        ))}

        <View style={{ height: 100 }} />
      </ScrollView>

      {/* MODAL ESCÁNER */}
      <Modal visible={scannerVisible} animationType="slide">
        <View style={styles.scannerContainer}>

          {!pedidoEscaneado ? (
            <>
              <View style={styles.scannerHeader}>
                <TouchableOpacity onPress={cerrarScanner} style={styles.scannerCloseBtn}>
                  <Ionicons name="close" size={26} color="#FFFFFF" />
                </TouchableOpacity>
                <Text style={styles.scannerHeaderTitle}>Escanear QR</Text>
                <View style={{ width: 40 }} />
              </View>

              <CameraView
                style={styles.camera}
                facing="back"
                barcodeScannerSettings={{ barcodeTypes: ['qr'] }}
                onBarcodeScanned={escaneando ? onQRScaneado : undefined}
              />

              <View style={styles.scannerOverlay}>
                <View style={styles.scannerFrame}>
                  <View style={[styles.scannerCorner, styles.scannerCornerTL]} />
                  <View style={[styles.scannerCorner, styles.scannerCornerTR]} />
                  <View style={[styles.scannerCorner, styles.scannerCornerBL]} />
                  <View style={[styles.scannerCorner, styles.scannerCornerBR]} />
                </View>
                <Text style={styles.scannerHint}>
                  Apunta al código QR del comprobante del pedido
                </Text>
              </View>
            </>
          ) : (
            // RESULTADO DEL ESCANEO
            <View style={styles.resultContainer}>
              <View style={styles.resultHeader}>
                <TouchableOpacity onPress={cerrarScanner} style={styles.scannerCloseBtn}>
                  <Ionicons name="close" size={26} color="#333" />
                </TouchableOpacity>
                <Text style={styles.resultHeaderTitle}>Detalle del Pedido</Text>
                <View style={{ width: 40 }} />
              </View>

              <ScrollView style={styles.resultScroll} showsVerticalScrollIndicator={false}>

                <View style={styles.resultBadgeContainer}>
                  <View style={styles.resultIconContainer}>
                    <Ionicons name="qr-code" size={32} color="#FF6B00" />
                  </View>
                  <Text style={styles.resultId}>{pedidoEscaneado.id}</Text>
                  <View style={[styles.estadoBadge, { backgroundColor: getEstadoColor(pedidoEscaneado.estado) + '20', marginTop: 8 }]}>
                    <Text style={[styles.estadoText, { color: getEstadoColor(pedidoEscaneado.estado) }]}>
                      {pedidoEscaneado.estado || 'EN PREPARACIÓN'}
                    </Text>
                  </View>
                </View>

                <View style={styles.resultCard}>
                  <Text style={styles.resultCardTitle}>Ruta de envío</Text>

                  <View style={styles.resultRow}>
                    <View style={styles.resultIconBox}>
                      <Ionicons name="location" size={16} color="#FF6B00" />
                    </View>
                    <View style={styles.resultContent}>
                      <Text style={styles.resultLabel}>Origen</Text>
                      <Text style={styles.resultValue}>{pedidoEscaneado.origen}</Text>
                    </View>
                  </View>

                  <View style={styles.resultDivider} />

                  <View style={styles.resultRow}>
                    <View style={styles.resultIconBox}>
                      <Ionicons name="flag" size={16} color="#34C759" />
                    </View>
                    <View style={styles.resultContent}>
                      <Text style={styles.resultLabel}>Destino</Text>
                      <Text style={styles.resultValue}>{pedidoEscaneado.destino}</Text>
                    </View>
                  </View>
                </View>

                <View style={styles.resultCard}>
                  <Text style={styles.resultCardTitle}>Detalles del paquete</Text>

                  <View style={styles.resultGrid}>
                    <View style={styles.resultGridItem}>
                      <Text style={styles.resultLabel}>Peso</Text>
                      <Text style={styles.resultValue}>{pedidoEscaneado.peso} kg</Text>
                    </View>
                    <View style={styles.resultGridItem}>
                      <Text style={styles.resultLabel}>Tipo</Text>
                      <Text style={styles.resultValue}>{pedidoEscaneado.tipo}</Text>
                    </View>
                    <View style={styles.resultGridItem}>
                      <Text style={styles.resultLabel}>Fecha</Text>
                      <Text style={styles.resultValue}>
                        {pedidoEscaneado.fecha ? pedidoEscaneado.fecha.split('T')[0] : 'Sin fecha'}
                      </Text>
                    </View>
                  </View>
                </View>

                <TouchableOpacity style={styles.resultCloseButton} onPress={cerrarScanner}>
                  <Text style={styles.resultCloseButtonText}>Cerrar</Text>
                </TouchableOpacity>

                <View style={{ height: 40 }} />
              </ScrollView>
            </View>
          )}
        </View>
      </Modal>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: "#FFFFFF" },
  content: { paddingHorizontal: 20 },

  qrButton: {
    position: 'absolute',
    top: Platform.OS === 'ios' ? 54 : 30,
    right: 20,
    zIndex: 10,
    width: 40,
    height: 40,
    justifyContent: 'center',
    alignItems: 'center',
  },

  greetingContainer: { paddingTop: 20, paddingBottom: 16 },
  greetingText: { fontSize: 24, fontWeight: "bold", color: "#000000" },
  greetingName: { color: "#FF6B00", fontSize: 24, fontWeight: "bold" },
  greetingSubtitle: { fontSize: 13, color: "#999999", marginTop: 4 },

  statsContainer: { flexDirection: "row", gap: 12, marginBottom: 20 },
  statCard: {
    flex: 1, backgroundColor: "#F8F9FA",
    borderRadius: 16, padding: 16, alignItems: "center",
    borderWidth: 1, borderColor: "#EEEEEE",
  },
  statIconContainer: { backgroundColor: "#FFF3E6", borderRadius: 10, padding: 8, marginBottom: 8 },
  statNumber: { fontSize: 26, fontWeight: "bold", color: "#000000" },
  statLabel: { fontSize: 13, color: "#888", marginTop: 2 },

  separator: { height: 1, backgroundColor: "#eee", marginBottom: 16 },
  shipmentsHeader: { flexDirection: "row", justifyContent: "space-between", alignItems: "center", marginBottom: 12 },
  shipmentsTitle: { fontSize: 16, fontWeight: "600" },
  viewAllText: { color: "#FF6B00", fontWeight: "500", fontSize: 13 },

  emptyContainer: { alignItems: "center", paddingVertical: 40, paddingHorizontal: 20 },
  emptyText: { fontSize: 13, color: "#CCCCCC", textAlign: "center", marginTop: 12, marginBottom: 20, lineHeight: 20 },
  emptyButton: { backgroundColor: "#FFF3E6", borderRadius: 10, paddingVertical: 10, paddingHorizontal: 20, borderWidth: 1, borderColor: "#FF6B00" },
  emptyButtonText: { color: "#FF6B00", fontWeight: "600", fontSize: 13 },

  orderCard: {
    backgroundColor: "#fff", borderRadius: 16, padding: 18, marginBottom: 18,
    shadowColor: "#000", shadowOffset: { width: 0, height: 3 },
    shadowOpacity: 0.08, shadowRadius: 6, elevation: 4,
  },
  cardHeader: { flexDirection: "row", alignItems: "center", marginBottom: 14 },
  packageIcon: { backgroundColor: "#FF6B00", padding: 8, borderRadius: 10, marginRight: 10 },
  orderId: { fontSize: 14, fontWeight: "700", color: '#000000' },
  estadoBadge: { paddingHorizontal: 10, paddingVertical: 4, borderRadius: 20 },
  estadoText: { fontSize: 11, fontWeight: '700' },
  routeContainer: { marginBottom: 12 },
  routeRow: { flexDirection: "row", alignItems: "center" },
  routeText: { marginLeft: 6, fontSize: 14, color: "#333", flex: 1 },
  orderInfo: { flexDirection: "row", marginBottom: 12 },
  infoItem: { flexDirection: "row", alignItems: "center", marginRight: 20 },
  infoText: { marginLeft: 6, fontSize: 13, color: "#555" },
  footer: { flexDirection: "row", justifyContent: "space-between", borderTopWidth: 1, borderTopColor: "#eee", paddingTop: 10 },
  date: { fontSize: 12, color: "#888" },
  detailsButton: { flexDirection: "row", alignItems: "center" },
  detailsText: { color: "#FF6B00", fontWeight: "600", marginRight: 4 },

  // SCANNER
  scannerContainer: { flex: 1, backgroundColor: '#000000' },
  scannerHeader: {
    flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between',
    paddingTop: Platform.OS === 'ios' ? 60 : 40,
    paddingHorizontal: 20, paddingBottom: 20,
    backgroundColor: '#000000',
  },
  scannerCloseBtn: {
    width: 40, height: 40, borderRadius: 20,
    backgroundColor: 'rgba(255,255,255,0.15)',
    justifyContent: 'center', alignItems: 'center',
  },
  scannerHeaderTitle: { fontSize: 18, fontWeight: '700', color: '#FFFFFF' },
  camera: { flex: 1 },
  scannerOverlay: {
    position: 'absolute', top: 0, left: 0, right: 0, bottom: 0,
    justifyContent: 'center', alignItems: 'center',
    paddingTop: Platform.OS === 'ios' ? 100 : 80,
  },
  scannerFrame: {
    width: 240, height: 240,
    position: 'relative',
  },
  scannerCorner: {
    position: 'absolute', width: 30, height: 30,
    borderColor: '#FF6B00', borderWidth: 3,
  },
  scannerCornerTL: { top: 0, left: 0, borderRightWidth: 0, borderBottomWidth: 0, borderTopLeftRadius: 4 },
  scannerCornerTR: { top: 0, right: 0, borderLeftWidth: 0, borderBottomWidth: 0, borderTopRightRadius: 4 },
  scannerCornerBL: { bottom: 0, left: 0, borderRightWidth: 0, borderTopWidth: 0, borderBottomLeftRadius: 4 },
  scannerCornerBR: { bottom: 0, right: 0, borderLeftWidth: 0, borderTopWidth: 0, borderBottomRightRadius: 4 },
  scannerHint: { color: 'rgba(255,255,255,0.8)', fontSize: 14, textAlign: 'center', marginTop: 24, paddingHorizontal: 40 },

  // RESULTADO
  resultContainer: { flex: 1, backgroundColor: '#FFFFFF' },
  resultHeader: {
    flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between',
    paddingTop: Platform.OS === 'ios' ? 60 : 40,
    paddingHorizontal: 20, paddingBottom: 16,
    borderBottomWidth: 1, borderBottomColor: '#EEEEEE',
  },
  resultHeaderTitle: { fontSize: 18, fontWeight: '700', color: '#000000' },
  resultScroll: { flex: 1, paddingHorizontal: 20 },
  resultBadgeContainer: { alignItems: 'center', paddingVertical: 24 },
  resultIconContainer: {
    width: 72, height: 72, borderRadius: 36,
    backgroundColor: '#FFF3E6',
    justifyContent: 'center', alignItems: 'center', marginBottom: 12,
  },
  resultId: { fontSize: 20, fontWeight: '800', color: '#000000' },

  resultCard: {
    backgroundColor: '#F8F9FA', borderRadius: 16,
    padding: 16, marginBottom: 14,
    borderWidth: 1, borderColor: '#EEEEEE',
  },
  resultCardTitle: {
    fontSize: 12, color: '#FF6B00', fontWeight: '700',
    textTransform: 'uppercase', letterSpacing: 1,
    marginBottom: 14, paddingBottom: 10,
    borderBottomWidth: 1, borderBottomColor: '#EEEEEE',
  },
  resultRow: { flexDirection: 'row', alignItems: 'center', paddingVertical: 6 },
  resultIconBox: {
    width: 32, height: 32, borderRadius: 8,
    backgroundColor: '#FFFFFF', justifyContent: 'center', alignItems: 'center',
    marginRight: 12, borderWidth: 1, borderColor: '#EEEEEE',
  },
  resultContent: { flex: 1 },
  resultLabel: { fontSize: 12, color: '#999999', marginBottom: 2 },
  resultValue: { fontSize: 14, color: '#111111', fontWeight: '600' },
  resultDivider: { height: 1, backgroundColor: '#EEEEEE', marginVertical: 4 },
  resultGrid: { flexDirection: 'row', flexWrap: 'wrap', gap: 10 },
  resultGridItem: {
    backgroundColor: '#FFFFFF', borderRadius: 10,
    padding: 12, borderWidth: 1, borderColor: '#EEEEEE',
    minWidth: '45%', flex: 1,
  },

  resultCloseButton: {
    backgroundColor: '#FF6B00', borderRadius: 14,
    paddingVertical: 16, alignItems: 'center', marginTop: 8,
    shadowColor: '#FF6B00', shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.2, shadowRadius: 4, elevation: 4,
  },
  resultCloseButtonText: { color: '#FFFFFF', fontSize: 16, fontWeight: '700' },
});