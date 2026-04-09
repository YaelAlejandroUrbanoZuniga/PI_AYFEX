import React, { useState, useCallback } from 'react';
import { useFocusEffect } from '@react-navigation/native';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
  Platform
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import HeaderNaranja from '../Components/HeaderNaranja';
import { API_BASE_URL } from '../config';

const API_URL = `${API_BASE_URL}/v1/pedidos/`;

export default function PrincipalM({ navigation }) {

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

    navigation.navigate("Pedidos", {
      screen: "PedidosDetalles",
      params: { pedidoData: pedido }
    });

  };

  const pedidosActivos = pedidos.length; // por ahora todos son activos
  const totalPedidos = pedidos.length;

  return (

    <View style={styles.container}>

      <HeaderNaranja />

      <ScrollView style={styles.content} showsVerticalScrollIndicator={false}>

        <View style={styles.greetingContainer}>
          <Text style={styles.greetingText}>Hola, Fidel!</Text>
        </View>

        {/* ESTADÍSTICAS */}

        <View style={styles.statsContainer}>

          <View style={styles.statCard}>
            <Text style={styles.statNumber}>{pedidosActivos}</Text>
            <Text style={styles.statLabel}>Activos</Text>
          </View>

          <View style={styles.statCard}>
            <Text style={styles.statNumber}>{totalPedidos}</Text>
            <Text style={styles.statLabel}>Total</Text>
          </View>

        </View>

        <View style={styles.separator} />

        {/* HEADER */}

        <View style={styles.shipmentsHeader}>

          <Text style={styles.shipmentsTitle}>Envíos Activos</Text>

          <TouchableOpacity
            onPress={() => navigation.navigate("Pedidos")}
          >
            <Text style={styles.viewAllText}>Ver todos</Text>
          </TouchableOpacity>

        </View>

        {/* PEDIDOS */}

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

              <Text style={styles.orderId}>
                #{pedido.id}
              </Text>

            </View>

            <View style={styles.routeContainer}>

              <View style={styles.routeRow}>
                <Ionicons name="location" size={16} color="#FF6B00" />
                <Text style={styles.routeText}>{pedido.origen}</Text>
              </View>

              <Ionicons
                name="arrow-down"
                size={16}
                color="#999"
                style={{ marginVertical: 4 }}
              />

              <View style={styles.routeRow}>
                <Ionicons name="flag" size={16} color="#34C759" />
                <Text style={styles.routeText}>{pedido.destino}</Text>
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

              <Text style={styles.date}>
                {pedido.fecha ? pedido.fecha.split("T")[0] : "Sin fecha"}
              </Text>

              <View style={styles.detailsButton}>
                <Text style={styles.detailsText}>Ver detalles</Text>
                <Ionicons name="chevron-forward" size={16} color="#FF6B00" />
              </View>

            </View>

          </TouchableOpacity>

        ))}

        <View style={{ height: 100 }} />

      </ScrollView>

    </View>

  );

}

const styles = StyleSheet.create({

  container: {
    flex: 1,
    backgroundColor: "#FFFFFF"
  },

  content: {
    paddingHorizontal: 20
  },

  greetingContainer: {
    paddingVertical: 16
  },

  greetingText: {
    fontSize: 22,
    fontWeight: "bold"
  },

  statsContainer: {
    flexDirection: "row",
    gap: 12,
    marginBottom: 20
  },

  statCard: {
    flex: 1,
    backgroundColor: "#F5F5F5",
    borderRadius: 12,
    padding: 16,
    alignItems: "center"
  },

  statNumber: {
    fontSize: 26,
    fontWeight: "bold"
  },

  statLabel: {
    fontSize: 13,
    color: "#666"
  },

  separator: {
    height: 1,
    backgroundColor: "#eee",
    marginBottom: 16
  },

  shipmentsHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 12
  },

  shipmentsTitle: {
    fontSize: 16,
    fontWeight: "600"
  },

  viewAllText: {
    color: "#FF6B00",
    fontWeight: "500"
  },

  orderCard: {
    backgroundColor: "#fff",
    borderRadius: 16,
    padding: 18,
    marginBottom: 18,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 3 },
    shadowOpacity: 0.08,
    shadowRadius: 6,
    elevation: 4
  },

  cardHeader: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 14
  },

  packageIcon: {
    backgroundColor: "#FF6B00",
    padding: 8,
    borderRadius: 10,
    marginRight: 10
  },

  orderId: {
    fontSize: 15,
    fontWeight: "600"
  },

  routeContainer: {
    marginBottom: 12
  },

  routeRow: {
    flexDirection: "row",
    alignItems: "center"
  },

  routeText: {
    marginLeft: 6,
    fontSize: 14,
    color: "#333"
  },

  orderInfo: {
    flexDirection: "row",
    marginBottom: 12
  },

  infoItem: {
    flexDirection: "row",
    alignItems: "center",
    marginRight: 20
  },

  infoText: {
    marginLeft: 6,
    fontSize: 13,
    color: "#555"
  },

  footer: {
    flexDirection: "row",
    justifyContent: "space-between",
    borderTopWidth: 1,
    borderTopColor: "#eee",
    paddingTop: 10
  },

  date: {
    fontSize: 12,
    color: "#888"
  },

  detailsButton: {
    flexDirection: "row",
    alignItems: "center"
  },

  detailsText: {
    color: "#FF6B00",
    fontWeight: "600",
    marginRight: 4
  }

});