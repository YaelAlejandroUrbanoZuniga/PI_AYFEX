import React, { useState, useCallback, useMemo } from 'react';
import { useFocusEffect } from '@react-navigation/native';
import { View, Text, StyleSheet, TouchableOpacity, ScrollView, Modal, Platform, TextInput } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { CameraView, useCameraPermissions } from 'expo-camera';
import HeaderNaranja from '../Components/HeaderNaranja';
import { API_BASE_URL } from '../config';

const API_URL = `${API_BASE_URL}/v1/pedidos/`;

const ESTADO_CONFIG = {
  'EN PREPARACIÓN': { color: '#FF9500', icono: 'construct-outline' },
  'EN ESPERA': { color: '#3A86FF', icono: 'time-outline' },
  'EN CAMINO': { color: '#FF6B00', icono: 'bicycle-outline' },
  'EN CAMINO AL DESTINO': { color: '#9B59B6', icono: 'navigate-outline' },
  'ENTREGADO': { color: '#34C759', icono: 'checkmark-circle-outline' },
  'RECHAZADO': { color: '#FF3B30', icono: 'close-circle-outline' },
};

const PRIORIDAD_COLORES = {
  BAJA: '#34C759',
  NORMAL: '#FF6B00',
  ALTA: '#FF9500',
  URGENTE: '#FF3B30',
};

export default function PrincipalM({ navigation }) {

  const [pedidos, setPedidos] = useState([]);
  const [reportes, setReportes] = useState([]);
  const [scannerVisible, setScannerVisible] = useState(false);
  const [escaneando, setEscaneando] = useState(true);
  const [pedidoEscaneado, setPedidoEscaneado] = useState(null);
  const [permission, requestPermission] = useCameraPermissions();
  const [notifVisible, setNotifVisible] = useState(false);
  const [modalReportesVisible, setModalReportesVisible] = useState(false);
  const [busquedaReportes, setBusquedaReportes] = useState('');
  const [filtroReportes, setFiltroReportes] = useState('Recientes');
  const [mostrarFiltrosReportes, setMostrarFiltrosReportes] = useState(false);
  const [notifsVistas, setNotifsVistas] = useState(false);

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

  const cargarReportes = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/v1/reportes/`, {
        headers: { "Authorization": `Bearer ${global.authToken}` }
      });
      const data = await response.json();
      setReportes(Array.isArray(data) ? data : []);
    } catch (error) {
      setReportes([]);
    }
  };

  useFocusEffect(useCallback(() => {
    cargarPedidos();
    cargarReportes();
  }, []));

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

  const verDetalles = (pedido) => {
    navigation.navigate("Pedidos", {
      screen: "PedidosDetalles",
      params: { pedidoData: pedido },
      initial: false,
    });
  };

  // Pedidos activos: excluye ENTREGADO, últimos 2
  const pedidosActivos = pedidos.filter(p => p.estado !== 'ENTREGADO');
  const pedidosMostrar = [...pedidosActivos]
    .sort((a, b) => new Date(b.fecha) - new Date(a.fecha))
    .slice(0, 2);

  // Estadísticas
  const totalActivos = pedidosActivos.length;
  const totalPedidos = pedidos.length;

  const notificaciones = pedidos.filter(p =>
    p.estado === 'RECHAZADO' || p.estado === 'POR_CONFIRMAR_ENTREGA'
  );
  const tieneNotifs = notificaciones.length > 0;


  // Reportes: solo pendientes, últimos 2
  const reportesPendientes = [...reportes]
    .filter(r => r.estado === 'PENDIENTE')
    .sort((a, b) => new Date(b.fecha) - new Date(a.fecha));
  const reportesMostrar = reportesPendientes.slice(0, 2);

  const reportesFiltrados = useMemo(() => {
    let resultado = [...reportes];
    if (busquedaReportes.trim()) {
      const texto = busquedaReportes.toLowerCase();
      resultado = resultado.filter(r =>
        r.tipo?.toLowerCase().includes(texto) ||
        r.descripcion?.toLowerCase().includes(texto) ||
        r.prioridad?.toLowerCase().includes(texto) ||
        r.estado?.toLowerCase().includes(texto)
      );
    }
    switch (filtroReportes) {
      case 'Recientes': resultado.sort((a, b) => new Date(b.fecha) - new Date(a.fecha)); break;
      case 'Antiguos': resultado.sort((a, b) => new Date(a.fecha) - new Date(b.fecha)); break;
      case 'Urgentes primero': resultado.sort((a, b) => {
        const orden = { URGENTE: 0, ALTA: 1, NORMAL: 2, BAJA: 3 };
        return (orden[a.prioridad] ?? 4) - (orden[b.prioridad] ?? 4);
      }); break;
      case 'Pendientes': resultado = resultado.filter(r => r.estado === 'PENDIENTE'); break;
    }
    return resultado;
  }, [reportes, busquedaReportes, filtroReportes]);

  const renderReporteCard = (reporte) => (
    <View key={reporte.id} style={styles.reporteCard}>
      <View style={styles.reporteCardHeader}>
        <View style={[styles.reporteIconBox, { backgroundColor: (PRIORIDAD_COLORES[reporte.prioridad] || '#FF6B00') + '20' }]}>
          <Ionicons name="document-text-outline" size={18} color={PRIORIDAD_COLORES[reporte.prioridad] || '#FF6B00'} />
        </View>
        <View style={{ flex: 1 }}>
          <Text style={styles.reporteTipo} numberOfLines={1}>{reporte.tipo}</Text>
          <Text style={styles.reporteFecha}>{reporte.fecha ? reporte.fecha.split('T')[0] : 'Sin fecha'}</Text>
        </View>
        <View style={[styles.prioridadBadge, { backgroundColor: (PRIORIDAD_COLORES[reporte.prioridad] || '#FF6B00') + '20' }]}>
          <Text style={[styles.prioridadBadgeText, { color: PRIORIDAD_COLORES[reporte.prioridad] || '#FF6B00' }]}>
            {reporte.prioridad}
          </Text>
        </View>
      </View>
      <Text style={styles.reporteDescripcion} numberOfLines={2}>{reporte.descripcion}</Text>
      <View style={styles.reporteFooter}>
        <View style={[styles.estadoReporteBadge, reporte.estado === 'PENDIENTE' && styles.estadoPendiente]}>
          <Text style={[styles.estadoReporteText, reporte.estado === 'PENDIENTE' && styles.estadoPendienteText]}>
            {reporte.estado}
          </Text>
        </View>
      </View>
    </View>
  );

  const renderPedidoCard = (pedido) => {
    const cfg = ESTADO_CONFIG[pedido.estado] || ESTADO_CONFIG['EN PREPARACIÓN'];
    return (
      <TouchableOpacity
        key={pedido.id}
        style={styles.orderCard}
        onPress={() => verDetalles(pedido)}
        activeOpacity={0.8}
      >
        <View style={styles.cardHeader}>
          <View style={[styles.packageIcon, { backgroundColor: cfg.color }]}>
            <Ionicons name="cube" size={18} color="#fff" />
          </View>
          <View style={{ flex: 1 }}>
            <Text style={styles.orderId}>{pedido.id}</Text>
            <Text style={styles.orderDate}>{pedido.fecha ? pedido.fecha.split('T')[0] : 'Sin fecha'}</Text>
          </View>
          <View style={[styles.estadoBadge, { backgroundColor: cfg.color + '20' }]}>
            <Ionicons name={cfg.icono} size={11} color={cfg.color} />
            <Text style={[styles.estadoText, { color: cfg.color }]}>
              {pedido.estado || 'EN PREPARACIÓN'}
            </Text>
          </View>
        </View>

        <View style={styles.routeContainer}>
          <View style={styles.routeRow}>
            <Ionicons name="location" size={14} color="#FF6B00" />
            <Text style={styles.routeText} numberOfLines={1}>{pedido.origen}</Text>
          </View>
          <Ionicons name="arrow-down" size={14} color="#CCC" style={{ marginVertical: 2, marginLeft: 2 }} />
          <View style={styles.routeRow}>
            <Ionicons name="flag" size={14} color="#34C759" />
            <Text style={styles.routeText} numberOfLines={1}>{pedido.destino}</Text>
          </View>
        </View>

        <View style={styles.orderInfo}>
          <View style={styles.infoChip}>
            <Ionicons name="barbell-outline" size={12} color="#FF6B00" />
            <Text style={styles.infoChipText}>{pedido.peso} kg</Text>
          </View>
          <View style={styles.infoChip}>
            <Ionicons name="cube-outline" size={12} color="#FF6B00" />
            <Text style={styles.infoChipText}>{pedido.tipo}</Text>
          </View>
          <View style={styles.detailsButton}>
            <Text style={styles.detailsText}>Ver detalles</Text>
            <Ionicons name="chevron-forward" size={13} color="#FF6B00" />
          </View>
        </View>
      </TouchableOpacity>
    );
  };

  return (
    <View style={styles.container}>
      <HeaderNaranja />

      <TouchableOpacity style={styles.qrButton} onPress={abrirScanner}>
        <Ionicons name="qr-code-outline" size={24} color="#FFFFFF" />
      </TouchableOpacity>

      <TouchableOpacity style={styles.notifButton} onPress={() => { setNotifVisible(true); setNotifsVistas(true); }}>
        <Ionicons name="notifications-outline" size={24} color="#FFFFFF" />
        {tieneNotifs && !notifsVistas && <View style={styles.notifDot} />}
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
            <View style={[styles.statAccent, { backgroundColor: '#FF6B00' }]} />
            <View style={styles.statIconContainer}>
              <Ionicons name="time-outline" size={22} color="#FF6B00" />
            </View>
            <View style={styles.statTexts}>
              <Text style={styles.statNumber}>{totalActivos}</Text>
              <Text style={styles.statLabel}>Pedidos Activos</Text>
            </View>
          </View>
          <View style={styles.statCard}>
            <View style={[styles.statAccent, { backgroundColor: '#04901b' }]} />
            <View style={[styles.statIconContainer, { backgroundColor: '#eeffee' }]}>
              <Ionicons name="cube-outline" size={22} color="#006c11" />
            </View>
            <View style={styles.statTexts}>
              <Text style={styles.statNumber}>{totalPedidos}</Text>
              <Text style={styles.statLabel}>Pedidos Totales</Text>
            </View>
          </View>
        </View>

        <View style={styles.separator} />

        {/* ENVÍOS ACTIVOS */}
        <View style={styles.sectionHeader}>
          <Text style={styles.sectionTitle}>Envíos Activos</Text>
          <TouchableOpacity onPress={() => navigation.navigate("Pedidos")}>
            <Text style={styles.viewAllText}>Ver todos</Text>
          </TouchableOpacity>
        </View>

        {pedidosMostrar.length === 0 ? (
          <View style={styles.emptyContainer}>
            <Ionicons name="cube-outline" size={36} color="#DDDDDD" />
            <Text style={styles.emptyText}>Aquí se reflejarán tus pedidos activos</Text>
            <TouchableOpacity style={styles.emptyButton} onPress={() => navigation.navigate("Crear")}>
              <Text style={styles.emptyButtonText}>Crear mi primer pedido</Text>
            </TouchableOpacity>
          </View>
        ) : (
          pedidosMostrar.map(renderPedidoCard)
        )}

        <View style={styles.separator} />

        {/* REPORTES PENDIENTES */}
        <View style={styles.sectionHeader}>
          <Text style={styles.sectionTitle}>Mis Reportes</Text>
          <TouchableOpacity onPress={() => setModalReportesVisible(true)}>
            <Text style={styles.viewAllText}>Ver todos</Text>
          </TouchableOpacity>
        </View>

        {reportesMostrar.length === 0 ? (
          <View style={styles.emptyContainer}>
            <Ionicons name="document-text-outline" size={36} color="#DDDDDD" />
            <Text style={styles.emptyText}>Aquí aparecerán tus reportes pendientes</Text>
          </View>
        ) : (
          reportesMostrar.map(renderReporteCard)
        )}

        <View style={{ height: 120 }} />
      </ScrollView>

      <Modal visible={notifVisible} animationType="slide">
        <View style={styles.modalReportesContainer}>
          <View style={styles.modalReportesHeader}>
            <View>
              <Text style={styles.modalReportesTitulo}>
                Notifica<Text style={{ color: '#FF6B00' }}>ciones</Text>
              </Text>
              <Text style={styles.modalReportesSubtitulo}>
                {notificaciones.length} notificación{notificaciones.length !== 1 ? 'es' : ''}
              </Text>
            </View>
            <TouchableOpacity style={styles.modalReportesCerrar} onPress={() => setNotifVisible(false)}>
              <Ionicons name="close" size={22} color="#333" />
            </TouchableOpacity>
          </View>

          <View style={styles.modalReportesContent}>
            {notificaciones.length === 0 ? (
              <View style={[styles.emptyContainer, { paddingTop: 60 }]}>
                <View style={styles.notifEmptyIcon}>
                  <Ionicons name="notifications-off-outline" size={36} color="#CCCCCC" />
                </View>
                <Text style={[styles.emptyText, { marginTop: 16 }]}>Sin notificaciones</Text>
                <Text style={[styles.emptyText, { fontSize: 12, marginTop: 0 }]}>
                  Aquí aparecerán actualizaciones sobre tus pedidos
                </Text>
              </View>
            ) : (
              <ScrollView showsVerticalScrollIndicator={false}>
                {notificaciones.map(pedido => {
                  const esRechazo = pedido.estado === 'RECHAZADO';
                  return (
                    <TouchableOpacity
                      key={pedido.id}
                      style={[styles.notifItem, !esRechazo && styles.notifItemEntrega]}
                      activeOpacity={0.8}
                      onPress={() => {
                        setNotifVisible(false);
                        navigation.navigate("Pedidos", {
                          screen: "PedidosDetalles",
                          params: { pedidoData: pedido },
                          initial: false,
                        });
                      }}
                    >
                      <View style={[styles.notifItemIconBox, !esRechazo && styles.notifItemIconBoxEntrega]}>
                        <Ionicons
                          name={esRechazo ? "close-circle" : "checkmark-done-circle"}
                          size={28}
                          color={esRechazo ? "#FF3B30" : "#FF9500"}
                        />
                      </View>
                      <View style={styles.notifItemContent}>
                        <View style={styles.notifItemTitleRow}>
                          <Text style={[styles.notifItemTitle, !esRechazo && { color: '#FF9500' }]}>
                            {esRechazo ? 'Pedido rechazado' : 'Paquete por confirmar'}
                          </Text>
                          <Text style={styles.notifItemFecha}>
                            {pedido.fecha ? pedido.fecha.split('T')[0] : ''}
                          </Text>
                        </View>
                        <Text style={styles.notifItemId}>{pedido.id}</Text>
                        {esRechazo && pedido.motivo_rechazo && (
                          <Text style={styles.notifItemMotivo}>{pedido.motivo_rechazo}</Text>
                        )}
                        {!esRechazo && (
                          <Text style={[styles.notifItemMotivo, { borderColor: '#FFD4A8', backgroundColor: '#FFF8EE' }]}>
                            El operador indica que el paquete llegó a su destino. Confirma si lo recibiste correctamente.
                          </Text>
                        )}
                        <View style={styles.notifItemVerRow}>
                          <Text style={[styles.notifItemVer, !esRechazo && { color: '#FF9500' }]}>Ver detalles</Text>
                          <Ionicons name="chevron-forward" size={14} color={esRechazo ? "#FF3B30" : "#FF9500"} />
                        </View>
                      </View>
                    </TouchableOpacity>
                  );
                })}
                <View style={{ height: 40 }} />
              </ScrollView>
            )}
          </View>
        </View>
      </Modal>

      {/* MODAL TODOS LOS REPORTES */}
      <Modal visible={modalReportesVisible} animationType="slide">
        <View style={styles.modalReportesContainer}>
          <View style={styles.modalReportesHeader}>
            <View>
              <Text style={styles.modalReportesTitulo}>
                Mis <Text style={{ color: '#FF6B00' }}>Reportes</Text>
              </Text>
              <Text style={styles.modalReportesSubtitulo}>
                {reportes.length} reporte{reportes.length !== 1 ? 's' : ''} registrado{reportes.length !== 1 ? 's' : ''}
              </Text>
            </View>
            <TouchableOpacity style={styles.modalReportesCerrar} onPress={() => setModalReportesVisible(false)}>
              <Ionicons name="close" size={22} color="#333" />
            </TouchableOpacity>
          </View>

          <View style={styles.modalReportesContent}>
            <View style={styles.searchContainer}>
              <Ionicons name="search-outline" size={18} color="#999" />
              <TextInput
                style={styles.searchInput}
                placeholder="Buscar por tipo, descripción..."
                placeholderTextColor="#BBBBBB"
                value={busquedaReportes}
                onChangeText={setBusquedaReportes}
              />
              {busquedaReportes.length > 0 && (
                <TouchableOpacity onPress={() => setBusquedaReportes('')}>
                  <Ionicons name="close-circle" size={18} color="#CCCCCC" />
                </TouchableOpacity>
              )}
            </View>

            <View style={styles.filtrosHeader}>
              <Text style={styles.filtrosLabel}>Ordenar por:</Text>
              <TouchableOpacity
                style={styles.filtrosToggle}
                onPress={() => setMostrarFiltrosReportes(!mostrarFiltrosReportes)}
              >
                <Text style={styles.filtrosToggleText}>{filtroReportes}</Text>
                <Ionicons name={mostrarFiltrosReportes ? "chevron-up" : "chevron-down"} size={14} color="#FF6B00" />
              </TouchableOpacity>
            </View>

            {mostrarFiltrosReportes && (
              <View style={styles.filtrosContainer}>
                {['Recientes', 'Antiguos', 'Urgentes primero', 'Pendientes'].map(f => (
                  <TouchableOpacity
                    key={f}
                    style={[styles.filtroChip, filtroReportes === f && styles.filtroChipActivo]}
                    onPress={() => { setFiltroReportes(f); setMostrarFiltrosReportes(false); }}
                  >
                    <Text style={[styles.filtroChipText, filtroReportes === f && styles.filtroChipTextActivo]}>{f}</Text>
                  </TouchableOpacity>
                ))}
              </View>
            )}

            <ScrollView showsVerticalScrollIndicator={false}>
              {reportesFiltrados.length === 0 ? (
                <View style={styles.emptyContainer}>
                  <Ionicons name="document-text-outline" size={40} color="#DDDDDD" />
                  <Text style={styles.emptyText}>
                    {busquedaReportes ? `Sin resultados para "${busquedaReportes}"` : 'No tienes reportes aún'}
                  </Text>
                </View>
              ) : (
                reportesFiltrados.map(renderReporteCard)
              )}
              <View style={{ height: 40 }} />
            </ScrollView>
          </View>
        </View>
      </Modal>

      {/* MODAL ESCÁNER QR */}
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
                <Text style={styles.scannerHint}>Apunta al código QR del comprobante del pedido</Text>
              </View>
            </>
          ) : (
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
                  <View style={[styles.estadoBadge, {
                    backgroundColor: (ESTADO_CONFIG[pedidoEscaneado.estado]?.color || '#FF6B00') + '20',
                    marginTop: 8, flexDirection: 'row', gap: 4
                  }]}>
                    <Ionicons name={ESTADO_CONFIG[pedidoEscaneado.estado]?.icono || 'cube-outline'} size={12} color={ESTADO_CONFIG[pedidoEscaneado.estado]?.color || '#FF6B00'} />
                    <Text style={[styles.estadoText, { color: ESTADO_CONFIG[pedidoEscaneado.estado]?.color || '#FF6B00' }]}>
                      {pedidoEscaneado.estado || 'EN PREPARACIÓN'}
                    </Text>
                  </View>
                </View>

                <View style={styles.resultCard}>
                  <Text style={styles.resultCardTitle}>Ruta de envío</Text>
                  <View style={styles.resultRow}>
                    <View style={styles.resultIconBox}><Ionicons name="location" size={16} color="#FF6B00" /></View>
                    <View style={styles.resultContent}>
                      <Text style={styles.resultLabel}>Origen</Text>
                      <Text style={styles.resultValue}>{pedidoEscaneado.origen}</Text>
                    </View>
                  </View>
                  <View style={styles.resultDivider} />
                  <View style={styles.resultRow}>
                    <View style={styles.resultIconBox}><Ionicons name="flag" size={16} color="#34C759" /></View>
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
                      <Text style={styles.resultValue}>{pedidoEscaneado.fecha ? pedidoEscaneado.fecha.split('T')[0] : 'Sin fecha'}</Text>
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
    position: 'absolute', top: Platform.OS === 'ios' ? 54 : 30,
    left: 20, zIndex: 10, width: 40, height: 40,
    justifyContent: 'center', alignItems: 'center',
  },
  notifButton: {
    position: 'absolute', top: Platform.OS === 'ios' ? 54 : 30,
    right: 20, zIndex: 10, width: 40, height: 40,
    justifyContent: 'center', alignItems: 'center',
  },
  notifDot: {
    position: 'absolute', top: 4, right: 4,
    width: 10, height: 10, borderRadius: 5,
    backgroundColor: '#FF3B30', borderWidth: 2, borderColor: '#FF6B00',
  },

  greetingContainer: { paddingTop: 20, paddingBottom: 16 },
  greetingText: { fontSize: 24, fontWeight: "bold", color: "#000000" },
  greetingName: { color: "#FF6B00", fontSize: 24, fontWeight: "bold" },
  greetingSubtitle: { fontSize: 13, color: "#999999", marginTop: 4 },

  statsContainer: { flexDirection: "row", gap: 12, marginBottom: 20 },
  statCard: {
    flex: 1, backgroundColor: "#F8F9FA",
    borderRadius: 16, padding: 16,
    borderWidth: 1, borderColor: "#EEEEEE",
    flexDirection: 'row', alignItems: 'center', gap: 12,
    overflow: 'hidden',
  },
  statAccent: {
    position: 'absolute', left: 0, top: 0, bottom: 0,
    width: 4, borderTopLeftRadius: 16, borderBottomLeftRadius: 16,
  },
  statIconContainer: { backgroundColor: "#FFF3E6", borderRadius: 12, padding: 10 },
  statTexts: { flex: 1 },
  statNumber: { fontSize: 24, fontWeight: "800", color: "#000000" },
  statLabel: { fontSize: 10, color: "#888", marginTop: 2, fontWeight: '500' },

  separator: { height: 1, backgroundColor: "#EEEEEE", marginBottom: 16, marginTop: 4 },
  sectionHeader: { flexDirection: "row", justifyContent: "space-between", alignItems: "center", marginBottom: 12 },
  sectionTitle: { fontSize: 16, fontWeight: "600", color: '#000000' },
  viewAllText: { color: "#FF6B00", fontWeight: "500", fontSize: 13 },

  emptyContainer: { alignItems: "center", paddingVertical: 30, paddingHorizontal: 20 },
  emptyText: { fontSize: 13, color: "#CCCCCC", textAlign: "center", marginTop: 12, marginBottom: 16, lineHeight: 20 },
  emptyButton: { backgroundColor: "#FFF3E6", borderRadius: 10, paddingVertical: 10, paddingHorizontal: 20, borderWidth: 1, borderColor: "#FF6B00" },
  emptyButtonText: { color: "#FF6B00", fontWeight: "600", fontSize: 13 },

  orderCard: { backgroundColor: "#fff", borderRadius: 16, padding: 16, marginBottom: 14, borderWidth: 1, borderColor: '#F0F0F0', shadowColor: "#000", shadowOffset: { width: 0, height: 2 }, shadowOpacity: 0.06, shadowRadius: 8, elevation: 3 },
  cardHeader: { flexDirection: "row", alignItems: "center", marginBottom: 12 },
  packageIcon: { padding: 8, borderRadius: 10, marginRight: 10 },
  orderId: { fontSize: 14, fontWeight: "700", color: '#000000' },
  orderDate: { fontSize: 11, color: '#999999', marginTop: 2 },
  estadoBadge: { flexDirection: 'row', alignItems: 'center', gap: 4, paddingHorizontal: 10, paddingVertical: 4, borderRadius: 20 },
  estadoText: { fontSize: 11, fontWeight: '700' },
  routeContainer: { marginBottom: 10 },
  routeRow: { flexDirection: "row", alignItems: "center" },
  routeText: { marginLeft: 6, fontSize: 13, color: "#333", flex: 1 },
  orderInfo: { flexDirection: "row", alignItems: 'center', gap: 8 },
  infoChip: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#FFF3E6', paddingHorizontal: 8, paddingVertical: 4, borderRadius: 8, gap: 4 },
  infoChipText: { fontSize: 12, color: '#FF6B00', fontWeight: '500' },
  detailsButton: { flexDirection: "row", alignItems: "center", marginLeft: 'auto' },
  detailsText: { color: "#FF6B00", fontWeight: "600", marginRight: 2, fontSize: 12 },

  // REPORTES
  reporteCard: { backgroundColor: '#FFFFFF', borderRadius: 14, padding: 14, marginBottom: 12, borderWidth: 1, borderColor: '#F0F0F0', shadowColor: '#000', shadowOffset: { width: 0, height: 2 }, shadowOpacity: 0.05, shadowRadius: 6, elevation: 2 },
  reporteCardHeader: { flexDirection: 'row', alignItems: 'center', marginBottom: 8, gap: 10 },
  reporteIconBox: { width: 36, height: 36, borderRadius: 10, justifyContent: 'center', alignItems: 'center' },
  reporteTipo: { fontSize: 14, fontWeight: '700', color: '#111111' },
  reporteFecha: { fontSize: 11, color: '#999999', marginTop: 2 },
  prioridadBadge: { paddingHorizontal: 10, paddingVertical: 4, borderRadius: 20 },
  prioridadBadgeText: { fontSize: 11, fontWeight: '700' },
  reporteDescripcion: { fontSize: 13, color: '#555555', lineHeight: 18, marginBottom: 10 },
  reporteFooter: { flexDirection: 'row', borderTopWidth: 1, borderTopColor: '#F5F5F5', paddingTop: 8 },
  estadoReporteBadge: { paddingHorizontal: 10, paddingVertical: 4, borderRadius: 20, backgroundColor: '#F5F5F5' },
  estadoReporteText: { fontSize: 11, fontWeight: '600', color: '#888888' },
  estadoPendiente: { backgroundColor: '#FFF3E6' },
  estadoPendienteText: { color: '#FF6B00' },



  // NOTIFICACIONES
  notifOverlay: { flex: 1, backgroundColor: 'rgba(0,0,0,0.4)', justifyContent: 'flex-end' },
  notifContainer: { backgroundColor: '#FFFFFF', borderTopLeftRadius: 24, borderTopRightRadius: 24, padding: 24, paddingBottom: Platform.OS === 'ios' ? 40 : 24, maxHeight: '70%' },
  notifHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 20 },
  notifTitle: { fontSize: 20, fontWeight: '700', color: '#000000' },
  notifCloseBtn: { width: 36, height: 36, borderRadius: 18, backgroundColor: '#F5F5F5', justifyContent: 'center', alignItems: 'center' },
  notifEmptyContainer: { alignItems: 'center', paddingVertical: 20 },
  notifEmptyIcon: { width: 72, height: 72, borderRadius: 36, backgroundColor: '#F8F9FA', justifyContent: 'center', alignItems: 'center', marginBottom: 16 },
  notifEmptyTitle: { fontSize: 16, fontWeight: '600', color: '#333333', marginBottom: 8 },
  notifEmptyText: { fontSize: 13, color: '#AAAAAA', textAlign: 'center', lineHeight: 20, paddingHorizontal: 20 },
  notifItem: { flexDirection: 'row', alignItems: 'flex-start', backgroundColor: '#FFF0F0', borderRadius: 14, padding: 14, marginBottom: 10, borderWidth: 1, borderColor: '#FFD0D0', gap: 12 },
  notifItemIcon: { width: 40, height: 40, borderRadius: 20, backgroundColor: '#FFFFFF', justifyContent: 'center', alignItems: 'center' },
  notifItemContent: { flex: 1 },
  notifItemTitle: { fontSize: 14, fontWeight: '700', color: '#FF3B30', marginBottom: 2 },
  notifItemId: { fontSize: 12, color: '#888', marginBottom: 4 },
  notifItemMotivo: { fontSize: 13, color: '#333', lineHeight: 18, marginBottom: 6 },
  notifItemFecha: { fontSize: 11, color: '#AAAAAA' },
  notifEmptyIcon: { width: 72, height: 72, borderRadius: 36, backgroundColor: '#F8F9FA', justifyContent: 'center', alignItems: 'center' },
  notifItem: { flexDirection: 'row', alignItems: 'flex-start', backgroundColor: '#FFF5F5', borderRadius: 16, padding: 16, marginBottom: 12, borderWidth: 1, borderColor: '#FFD0D0', gap: 12 },
  notifItemIconBox: { width: 44, height: 44, borderRadius: 22, backgroundColor: '#FFFFFF', justifyContent: 'center', alignItems: 'center', borderWidth: 1, borderColor: '#FFD0D0' },
  notifItemContent: { flex: 1 },
  notifItemTitleRow: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 4 },
  notifItemTitle: { fontSize: 14, fontWeight: '700', color: '#FF3B30' },
  notifItemFecha: { fontSize: 11, color: '#AAAAAA' },
  notifItemId: { fontSize: 12, color: '#888', marginBottom: 6, fontWeight: '500' },
  notifItemMotivo: { fontSize: 13, color: '#444', lineHeight: 18, marginBottom: 8, backgroundColor: '#FFFFFF', borderRadius: 8, padding: 8, borderWidth: 1, borderColor: '#FFD0D0' },
  notifItemVerRow: { flexDirection: 'row', alignItems: 'center', gap: 2 },
  notifItemVer: { fontSize: 12, color: '#FF3B30', fontWeight: '600' },

  // MODAL REPORTES
  modalReportesContainer: { flex: 1, backgroundColor: '#FFFFFF' },
  modalReportesHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', paddingTop: Platform.OS === 'ios' ? 60 : 40, paddingHorizontal: 20, paddingBottom: 16, borderBottomWidth: 1, borderBottomColor: '#EEEEEE' },
  modalReportesTitulo: { fontSize: 24, fontWeight: '800', color: '#000000' },
  modalReportesSubtitulo: { fontSize: 13, color: '#999999', marginTop: 4 },
  modalReportesCerrar: { width: 40, height: 40, borderRadius: 20, backgroundColor: '#F5F5F5', justifyContent: 'center', alignItems: 'center' },
  modalReportesContent: { flex: 1, paddingHorizontal: 20, paddingTop: 16 },

  searchContainer: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#F8F9FA', borderRadius: 14, paddingHorizontal: 14, paddingVertical: Platform.OS === 'ios' ? 12 : 8, borderWidth: 1, borderColor: '#EEEEEE', marginBottom: 14 },
  searchInput: { flex: 1, marginLeft: 8, fontSize: 14, color: '#333333' },
  filtrosHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 10 },
  filtrosLabel: { fontSize: 13, color: '#999999' },
  filtrosToggle: { flexDirection: 'row', alignItems: 'center', gap: 4, backgroundColor: '#FFF3E6', paddingHorizontal: 12, paddingVertical: 6, borderRadius: 20, borderWidth: 1, borderColor: '#FF6B00' },
  filtrosToggleText: { fontSize: 13, color: '#FF6B00', fontWeight: '600' },
  filtrosContainer: { flexDirection: 'row', flexWrap: 'wrap', gap: 8, marginBottom: 14 },
  filtroChip: { paddingHorizontal: 14, paddingVertical: 7, borderRadius: 20, backgroundColor: '#F8F9FA', borderWidth: 1, borderColor: '#EEEEEE' },
  filtroChipActivo: { backgroundColor: '#FF6B00', borderColor: '#FF6B00' },
  filtroChipText: { fontSize: 13, color: '#666666' },
  filtroChipTextActivo: { color: '#FFFFFF', fontWeight: '600' },

  // SCANNER
  scannerContainer: { flex: 1, backgroundColor: '#000000' },
  scannerHeader: { flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', paddingTop: Platform.OS === 'ios' ? 60 : 40, paddingHorizontal: 20, paddingBottom: 20, backgroundColor: '#000000' },
  scannerCloseBtn: { width: 40, height: 40, borderRadius: 20, backgroundColor: 'rgba(255,255,255,0.15)', justifyContent: 'center', alignItems: 'center' },
  scannerHeaderTitle: { fontSize: 18, fontWeight: '700', color: '#FFFFFF' },
  camera: { flex: 1 },
  scannerOverlay: { position: 'absolute', top: 0, left: 0, right: 0, bottom: 0, justifyContent: 'center', alignItems: 'center', paddingTop: Platform.OS === 'ios' ? 100 : 80 },
  scannerFrame: { width: 240, height: 240, position: 'relative' },
  scannerCorner: { position: 'absolute', width: 30, height: 30, borderColor: '#FF6B00', borderWidth: 3 },
  scannerCornerTL: { top: 0, left: 0, borderRightWidth: 0, borderBottomWidth: 0, borderTopLeftRadius: 4 },
  scannerCornerTR: { top: 0, right: 0, borderLeftWidth: 0, borderBottomWidth: 0, borderTopRightRadius: 4 },
  scannerCornerBL: { bottom: 0, left: 0, borderRightWidth: 0, borderTopWidth: 0, borderBottomLeftRadius: 4 },
  scannerCornerBR: { bottom: 0, right: 0, borderLeftWidth: 0, borderTopWidth: 0, borderBottomRightRadius: 4 },
  scannerHint: { color: 'rgba(255,255,255,0.8)', fontSize: 14, textAlign: 'center', marginTop: 24, paddingHorizontal: 40 },

  resultContainer: { flex: 1, backgroundColor: '#FFFFFF' },
  resultHeader: { flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', paddingTop: Platform.OS === 'ios' ? 60 : 40, paddingHorizontal: 20, paddingBottom: 16, borderBottomWidth: 1, borderBottomColor: '#EEEEEE' },
  resultHeaderTitle: { fontSize: 18, fontWeight: '700', color: '#000000' },
  resultScroll: { flex: 1, paddingHorizontal: 20 },
  resultBadgeContainer: { alignItems: 'center', paddingVertical: 24 },
  resultIconContainer: { width: 72, height: 72, borderRadius: 36, backgroundColor: '#FFF3E6', justifyContent: 'center', alignItems: 'center', marginBottom: 12 },
  resultId: { fontSize: 20, fontWeight: '800', color: '#000000' },
  resultCard: { backgroundColor: '#F8F9FA', borderRadius: 16, padding: 16, marginBottom: 14, borderWidth: 1, borderColor: '#EEEEEE' },
  resultCardTitle: { fontSize: 12, color: '#FF6B00', fontWeight: '700', textTransform: 'uppercase', letterSpacing: 1, marginBottom: 14, paddingBottom: 10, borderBottomWidth: 1, borderBottomColor: '#EEEEEE' },
  resultRow: { flexDirection: 'row', alignItems: 'center', paddingVertical: 6 },
  resultIconBox: { width: 32, height: 32, borderRadius: 8, backgroundColor: '#FFFFFF', justifyContent: 'center', alignItems: 'center', marginRight: 12, borderWidth: 1, borderColor: '#EEEEEE' },
  resultContent: { flex: 1 },
  resultLabel: { fontSize: 12, color: '#999999', marginBottom: 2 },
  resultValue: { fontSize: 14, color: '#111111', fontWeight: '600' },
  resultDivider: { height: 1, backgroundColor: '#EEEEEE', marginVertical: 4 },
  resultGrid: { flexDirection: 'row', flexWrap: 'wrap', gap: 10 },
  resultGridItem: { backgroundColor: '#FFFFFF', borderRadius: 10, padding: 12, borderWidth: 1, borderColor: '#EEEEEE', minWidth: '45%', flex: 1 },
  resultCloseButton: { backgroundColor: '#FF6B00', borderRadius: 14, paddingVertical: 16, alignItems: 'center', marginTop: 8, shadowColor: '#FF6B00', shadowOffset: { width: 0, height: 4 }, shadowOpacity: 0.2, shadowRadius: 4, elevation: 4 },
  resultCloseButtonText: { color: '#FFFFFF', fontSize: 16, fontWeight: '700' },
  notifItemEntrega: { backgroundColor: '#FFF8EE', borderColor: '#FFD4A8' },
  notifItemIconBoxEntrega: { borderColor: '#FFD4A8' },
});