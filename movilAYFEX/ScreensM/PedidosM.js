import React, { useState, useCallback, useMemo } from 'react';
import { useFocusEffect } from '@react-navigation/native';
import {
  View, Text, StyleSheet, TouchableOpacity,
  ScrollView, StatusBar, Platform, RefreshControl, TextInput
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import HeaderNaranja from '../Components/HeaderNaranja';
import { API_BASE_URL } from '../config';

const API_URL = `${API_BASE_URL}/v1/pedidos/`;

const FILTROS = ['Recientes', 'Antiguos', 'Mayor peso', 'Menor peso', 'A-Z Origen'];

export default function PedidosM({ navigation }) {

  const [pedidos, setPedidos] = useState([]);
  const [refreshing, setRefreshing] = useState(false);
  const [busqueda, setBusqueda] = useState('');
  const [filtroActivo, setFiltroActivo] = useState('Recientes');
  const [mostrarFiltros, setMostrarFiltros] = useState(false);

  const cargarPedidos = async () => {
    try {
      const response = await fetch(API_URL, {
        headers: { "Authorization": `Bearer ${global.authToken}` }
      });
      const data = await response.json();
      setPedidos(Array.isArray(data) ? data : []);
    } catch (error) {
      console.log("Error cargando pedidos:", error);
      setPedidos([]);
    }
  };

  const onRefresh = async () => {
    setRefreshing(true);
    await cargarPedidos();
    setRefreshing(false);
  };

  useFocusEffect(useCallback(() => { cargarPedidos(); }, []));

  const pedidosFiltrados = useMemo(() => {
    let resultado = [...pedidos];

    if (busqueda.trim()) {
      const texto = busqueda.toLowerCase();
      resultado = resultado.filter(p =>
        String(p.id).includes(texto) ||
        p.origen?.toLowerCase().includes(texto) ||
        p.destino?.toLowerCase().includes(texto) ||
        p.tipo?.toLowerCase().includes(texto) ||
        p.descripcion?.toLowerCase().includes(texto)
      );
    }

    switch (filtroActivo) {
      case 'Recientes':
        resultado.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));
        break;
      case 'Antiguos':
        resultado.sort((a, b) => new Date(a.fecha) - new Date(b.fecha));
        break;
      case 'Mayor peso':
        resultado.sort((a, b) => b.peso - a.peso);
        break;
      case 'Menor peso':
        resultado.sort((a, b) => a.peso - b.peso);
        break;
      case 'A-Z Origen':
        resultado.sort((a, b) => a.origen?.localeCompare(b.origen));
        break;
    }

    return resultado;
  }, [pedidos, busqueda, filtroActivo]);

  const ESTADO_CONFIG = {
  'EN PREPARACIÓN':       { color: '#FF9500', icono: 'construct-outline' },
  'EN ESPERA':            { color: '#3A86FF', icono: 'time-outline' },
  'EN CAMINO':            { color: '#FF6B00', icono: 'bicycle-outline' },
  'EN CAMINO AL DESTINO': { color: '#9B59B6', icono: 'navigate-outline' },
  'ENTREGADO':            { color: '#34C759', icono: 'checkmark-circle-outline' },
  'RECHAZADO':            { color: '#FF3B30', icono: 'close-circle-outline' },
};

  return (
    <View style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#FFFFFF" />
      <HeaderNaranja />

      <ScrollView
        style={styles.content}
        showsVerticalScrollIndicator={false}
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor="#FF6B00" />}
      >
        {/* TÍTULO */}
        <View style={styles.titleContainer}>
          <Text style={styles.screenTitle}>
            Mis <Text style={styles.screenTitleAccent}>Pedidos</Text>
          </Text>
          <Text style={styles.screenSubtitle}>{pedidos.length} envío{pedidos.length !== 1 ? 's' : ''} registrado{pedidos.length !== 1 ? 's' : ''}</Text>
        </View>

        {/* BUSCADOR */}
        <View style={styles.searchContainer}>
          <Ionicons name="search-outline" size={18} color="#999" />
          <TextInput
            style={styles.searchInput}
            placeholder="Buscar por origen, destino, tipo..."
            placeholderTextColor="#BBBBBB"
            value={busqueda}
            onChangeText={setBusqueda}
          />
          {busqueda.length > 0 && (
            <TouchableOpacity onPress={() => setBusqueda('')}>
              <Ionicons name="close-circle" size={18} color="#CCCCCC" />
            </TouchableOpacity>
          )}
        </View>

        {/* FILTROS */}
        <View style={styles.filtrosHeader}>
          <Text style={styles.filtrosLabel}>Ordenar por:</Text>
          <TouchableOpacity
            style={styles.filtrosToggle}
            onPress={() => setMostrarFiltros(!mostrarFiltros)}
          >
            <Text style={styles.filtrosToggleText}>{filtroActivo}</Text>
            <Ionicons
              name={mostrarFiltros ? "chevron-up" : "chevron-down"}
              size={14}
              color="#FF6B00"
            />
          </TouchableOpacity>
        </View>

        {mostrarFiltros && (
          <View style={styles.filtrosContainer}>
            {FILTROS.map(f => (
              <TouchableOpacity
                key={f}
                style={[styles.filtroChip, filtroActivo === f && styles.filtroChipActivo]}
                onPress={() => { setFiltroActivo(f); setMostrarFiltros(false); }}
              >
                <Text style={[styles.filtroChipText, filtroActivo === f && styles.filtroChipTextActivo]}>
                  {f}
                </Text>
              </TouchableOpacity>
            ))}
          </View>
        )}

        <View style={styles.separator} />

        {/* VACÍO */}
        {pedidosFiltrados.length === 0 && (
          <View style={styles.emptyContainer}>
            <Ionicons name="search-outline" size={44} color="#DDDDDD" />
            <Text style={styles.emptyText}>
              {busqueda ? `Sin resultados para "${busqueda}"` : 'No tienes pedidos aún'}
            </Text>
            {!busqueda && (
              <TouchableOpacity
                style={styles.emptyButton}
                onPress={() => navigation.navigate("Crear")}
              >
                <Text style={styles.emptyButtonText}>Crear pedido</Text>
              </TouchableOpacity>
            )}
          </View>
        )}

        {/* CARDS */}
        {pedidosFiltrados.map((pedido) => (
          <TouchableOpacity
            key={pedido.id}
            style={styles.orderCard}
            onPress={() => navigation.navigate('PedidosDetalles', { pedidoData: pedido })}
            activeOpacity={0.8}
          >
            <View style={styles.cardHeader}>
              <View style={[styles.packageIcon, { backgroundColor: (ESTADO_CONFIG[pedido.estado]?.color || '#FF6B00') }]}>
                <Ionicons name="cube" size={18} color="#fff" />
              </View>
              <View style={styles.cardHeaderInfo}>
                <Text style={styles.orderId}>Pedido {pedido.id}</Text>
                <Text style={styles.orderDate}>
                  {pedido.fecha ? pedido.fecha.split("T")[0] : "Sin fecha"}
                </Text>
              </View>
              <View style={[styles.estadoBadge, { backgroundColor: (ESTADO_CONFIG[pedido.estado]?.color || '#FF6B00') + '20', flexDirection: 'row', gap: 4 }]}>
                <Ionicons name={ESTADO_CONFIG[pedido.estado]?.icono || 'cube-outline'} size={11} color={ESTADO_CONFIG[pedido.estado]?.color || '#FF6B00'} />
                <Text style={[styles.estadoText, { color: ESTADO_CONFIG[pedido.estado]?.color || '#FF6B00' }]}>
                  {pedido.estado || 'EN PREPARACIÓN'}
                </Text>
              </View>
            </View>

            <View style={styles.routeContainer}>
              <View style={styles.routeRow}>
                <Ionicons name="location" size={14} color="#FF6B00" />
                <Text style={styles.routeText} numberOfLines={1}>{pedido.origen}</Text>
              </View>
              <View style={styles.routeDivider}>
                <View style={styles.routeDividerLine} />
                <Ionicons name="arrow-down" size={12} color="#CCC" />
                <View style={styles.routeDividerLine} />
              </View>
              <View style={styles.routeRow}>
                <Ionicons name="flag" size={14} color="#34C759" />
                <Text style={styles.routeText} numberOfLines={1}>{pedido.destino}</Text>
              </View>
            </View>

            <View style={styles.orderInfo}>
              <View style={styles.infoChip}>
                <Ionicons name="barbell-outline" size={13} color="#FF6B00" />
                <Text style={styles.infoChipText}>{pedido.peso} kg</Text>
              </View>
              <View style={styles.infoChip}>
                <Ionicons name="cube-outline" size={13} color="#FF6B00" />
                <Text style={styles.infoChipText}>{pedido.tipo}</Text>
              </View>
              <View style={styles.detailsButton}>
                <Text style={styles.detailsText}>Ver más</Text>
                <Ionicons name="chevron-forward" size={14} color="#FF6B00" />
              </View>
            </View>
          </TouchableOpacity>
        ))}

        <View style={{ height: 120 }} />
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: "#FFFFFF" },
  content: { paddingHorizontal: 20 },

  titleContainer: { paddingTop: 20, paddingBottom: 16 },
  screenTitle: { fontSize: 26, fontWeight: 'bold', color: '#000000' },
  screenTitleAccent: { color: '#FF6B00' },
  screenSubtitle: { fontSize: 13, color: '#999999', marginTop: 4 },

  searchContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#F8F9FA',
    borderRadius: 14,
    paddingHorizontal: 14,
    paddingVertical: Platform.OS === 'ios' ? 12 : 8,
    borderWidth: 1,
    borderColor: '#EEEEEE',
    marginBottom: 14,
  },
  searchInput: { flex: 1, marginLeft: 8, fontSize: 14, color: '#333333' },

  filtrosHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 10,
  },
  filtrosLabel: { fontSize: 13, color: '#999999' },
  filtrosToggle: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
    backgroundColor: '#FFF3E6',
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 20,
    borderWidth: 1,
    borderColor: '#FF6B00',
  },
  filtrosToggleText: { fontSize: 13, color: '#FF6B00', fontWeight: '600' },
  filtrosContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 8,
    marginBottom: 14,
  },
  filtroChip: {
    paddingHorizontal: 14,
    paddingVertical: 7,
    borderRadius: 20,
    backgroundColor: '#F8F9FA',
    borderWidth: 1,
    borderColor: '#EEEEEE',
  },
  filtroChipActivo: { backgroundColor: '#FF6B00', borderColor: '#FF6B00' },
  filtroChipText: { fontSize: 13, color: '#666666' },
  filtroChipTextActivo: { color: '#FFFFFF', fontWeight: '600' },

  separator: { height: 1, backgroundColor: "#EEEEEE", marginBottom: 16 },

  emptyContainer: { alignItems: 'center', paddingVertical: 50 },
  emptyText: { fontSize: 14, color: '#CCCCCC', marginTop: 12, marginBottom: 16, textAlign: 'center' },
  emptyButton: {
    backgroundColor: '#FFF3E6',
    borderRadius: 10,
    paddingVertical: 10,
    paddingHorizontal: 20,
    borderWidth: 1,
    borderColor: '#FF6B00',
  },
  emptyButtonText: { color: '#FF6B00', fontWeight: '600', fontSize: 13 },

  orderCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 16,
    padding: 16,
    marginBottom: 14,
    borderWidth: 1,
    borderColor: '#F0F0F0',
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.06,
    shadowRadius: 8,
    elevation: 3,
  },

  cardHeader: { flexDirection: "row", alignItems: "center", marginBottom: 14 },
  packageIcon: {
    backgroundColor: "#FF6B00",
    padding: 8,
    borderRadius: 10,
    marginRight: 10,
  },
  cardHeaderInfo: { flex: 1 },
  orderId: { fontSize: 14, fontWeight: "700", color: '#000000' },
  orderDate: { fontSize: 12, color: '#999999', marginTop: 2 },

  estadoBadge: {
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 20,
  },
  estadoText: { fontSize: 11, fontWeight: '700' },

  routeContainer: { marginBottom: 12 },
  routeRow: { flexDirection: "row", alignItems: "center" },
  routeText: { marginLeft: 6, fontSize: 13, color: "#333", flex: 1 },
  routeDivider: { flexDirection: 'row', alignItems: 'center', paddingLeft: 4, marginVertical: 2 },
  routeDividerLine: { width: 1, height: 8, backgroundColor: '#EEEEEE', marginHorizontal: 2 },

  orderInfo: { flexDirection: "row", alignItems: 'center', gap: 8 },
  infoChip: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#FFF3E6',
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 8,
    gap: 4,
  },
  infoChipText: { fontSize: 12, color: '#FF6B00', fontWeight: '500' },
  detailsButton: { flexDirection: "row", alignItems: "center", marginLeft: 'auto' },
  detailsText: { color: "#FF6B00", fontWeight: "600", marginRight: 2, fontSize: 13 },
});