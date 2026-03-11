import React, { useEffect, useState, useCallback } from 'react';
import { useFocusEffect } from '@react-navigation/native';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  TextInput,
  ScrollView,
  StatusBar,
  Platform
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import HeaderNaranja from '../Components/HeaderNaranja';

const API_URL = Platform.OS === "web"
  ? "http://localhost:5000/v1/pedidos"
  : "http://172.20.10.2:5000/v1/pedidos";

export default function PedidosM({ navigation }) {

  const [pedidos, setPedidos] = useState([]);

  const cargarPedidos = async () => {
    try {

      const response = await fetch(API_URL);
      const data = await response.json();

      setPedidos(data);

    } catch (error) {
      console.log("Error cargando pedidos:", error);
    }
  };

  useFocusEffect(
    useCallback(() => {
      cargarPedidos();
    }, [])
  );

  const verDetalles = (pedido) => {
    navigation.navigate('PedidosDetalles', { pedidoData: pedido });
  };

  return (
    <View style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#FFFFFF" />
      <HeaderNaranja />

      <ScrollView
        style={styles.content}
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContent}
      >

        <Text style={styles.screenTitle}>Mis Pedidos</Text>

        <View style={styles.searchContainer}>
          <View style={styles.searchInputContainer}>
            <Ionicons name="search-outline" size={20} color="#999999" />
            <TextInput
              style={styles.searchInput}
              placeholder="Buscar Pedido"
              placeholderTextColor="#999999"
            />
          </View>
          <Text style={styles.searchExample}>Ej: #PED010101</Text>
        </View>

        <View style={styles.separator} />

        {pedidos.map((pedido, index) => (

          <TouchableOpacity
            key={pedido.id}
            style={styles.orderCard}
            onPress={() => verDetalles(pedido)}
          >

            <View style={styles.orderHeader}>
              <View style={styles.orderTitleContainer}>
                <Text style={styles.orderId}>ID: {pedido.id}</Text>

                <View style={[styles.statusBadge, styles.statusPreparing]}>
                  <Text style={styles.statusText}>En preparación</Text>
                </View>

              </View>
            </View>

            <View style={styles.orderDetails}>

              <View style={styles.locationRow}>
                <Ionicons name="ellipse" size={8} color="#FF3B30" />
                <Text style={styles.locationText}>
                  Origen: <Text style={styles.locationBold}>{pedido.origen}</Text>
                </Text>
              </View>

              <View style={styles.locationRow}>
                <Ionicons name="ellipse" size={8} color="#34C759" />
                <Text style={styles.locationText}>
                  Destino: <Text style={styles.locationBold}>{pedido.destino}</Text>
                </Text>
              </View>

            </View>

            <View style={styles.orderFooter}>

              <Text style={styles.orderDate}>
                {pedido.fecha ? pedido.fecha.split("T")[0] : "Sin fecha"}
              </Text>

              <TouchableOpacity
                style={styles.detailsButton}
                onPress={() => verDetalles(pedido)}
              >
                <Text style={styles.detailsButtonText}>Detalles</Text>
                <Ionicons name="chevron-forward" size={16} color="#FF6B00" />
              </TouchableOpacity>

            </View>

          </TouchableOpacity>

        ))}

        <View style={styles.bottomPadding} />

      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({

  container: { flex: 1, backgroundColor: '#FFFFFF' },

  content: {
    flex: 1,
    backgroundColor: '#FFFFFF',
    paddingHorizontal: 20
  },

  screenTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#000',
    marginTop: 20,
    marginBottom: 16
  },

  searchContainer: { marginBottom: 16 },

  searchInputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#F8F9FA',
    borderRadius: 12,
    paddingHorizontal: 12,
    paddingVertical: 10,
    borderWidth: 1,
    borderColor: '#EEEEEE'
  },

  searchInput: {
    flex: 1,
    marginLeft: 8,
    fontSize: 14,
    color: '#333333'
  },

  searchExample: {
    fontSize: 12,
    color: '#999999',
    marginTop: 6,
    marginLeft: 12
  },

  separator: {
    height: 1,
    backgroundColor: '#F0F0F0',
    marginBottom: 20
  },

  orderCard: {
    backgroundColor: '#FFFFFF',
    marginBottom: 16,
    padding: 16,
    borderRadius: 12,
    borderWidth: 1,
    borderColor: '#F0F0F0',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 3.84,
    elevation: 3
  },

  orderHeader: { marginBottom: 12 },

  orderTitleContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center'
  },

  orderId: {
    fontSize: 14,
    fontWeight: '600',
    color: '#333'
  },

  statusBadge: {
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 12
  },

  statusPreparing: { backgroundColor: '#FFF3E0' },

  statusText: {
    fontSize: 11,
    fontWeight: '500'
  },

  orderDetails: { marginBottom: 12 },

  locationRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 6
  },

  locationText: {
    fontSize: 13,
    color: '#666',
    flex: 1
  },

  locationBold: {
    fontWeight: '500',
    color: '#333'
  },

  orderFooter: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    borderTopWidth: 1,
    borderTopColor: '#F0F0F0',
    paddingTop: 12
  },

  orderDate: {
    fontSize: 12,
    color: '#999'
  },

  detailsButton: {
    flexDirection: 'row',
    alignItems: 'center'
  },

  detailsButtonText: {
    fontSize: 14,
    color: '#FF6B00',
    fontWeight: '500',
    marginRight: 4
  },

  bottomPadding: { height: 120 },

  scrollContent: {
    paddingBottom: 120
  }

});