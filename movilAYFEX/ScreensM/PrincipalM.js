import React from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  TextInput,
  ScrollView,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import HeaderNaranja from '../Components/HeaderNaranja';

export default function PrincipalM({ navigation }) {
  return (
    <View style={styles.container}>
      <HeaderNaranja />

      <ScrollView style={styles.content} showsVerticalScrollIndicator={false}>
        <View style={styles.greetingContainer}>
          <Text style={styles.greetingText}>Hola, Fidel!</Text>
        </View>

        <View style={styles.statsContainer}>
          <View style={styles.statCard}>
            <Text style={styles.statNumber}>2</Text>
            <Text style={styles.statLabel}>Activos</Text>
          </View>
          <View style={styles.statCard}>
            <Text style={styles.statNumber}>10</Text>
            <Text style={styles.statLabel}>Total</Text>
          </View>
        </View>

        <View style={styles.searchContainer}>
          <View style={styles.searchInputContainer}>
            <Ionicons name="location-outline" size={20} color="#FF6B00" />
            <TextInput
              style={styles.searchInput}
              placeholder="Rastrear Pedido"
              placeholderTextColor="#999999"
            />
          </View>
          <Text style={styles.searchExample}>Ej: #PED010101</Text>
        </View>

        <View style={styles.separator} />

        <View style={styles.shipmentsHeader}>
          <Text style={styles.shipmentsTitle}>Envíos Activos</Text>
          <TouchableOpacity>
            <Text style={styles.viewAllText}>Ver todos</Text>
          </TouchableOpacity>
        </View>

        <TouchableOpacity style={styles.orderCard}>
          <Text style={styles.orderId}>ID: #PED022604</Text>
          <Text style={styles.orderLocation}>Tampico, Tamaulipas</Text>
          <View style={styles.orderFooter}>
            <Ionicons name="location" size={16} color="#FF6B00" />
            <Text style={styles.estimatedText}>Llegada estimada: 8 días</Text>
          </View>
        </TouchableOpacity>

        <TouchableOpacity style={styles.orderCard}>
          <Text style={styles.orderId}>ID: #PED022603</Text>
          <Text style={styles.orderLocation}>Pedro Escobedo, Querétaro</Text>
          <View style={styles.orderFooter}>
            <Ionicons name="location" size={16} color="#4CAF50" />
            <Text style={[styles.estimatedText, styles.greenText]}>Llegada estimada: 3 días</Text>
          </View>
        </TouchableOpacity>

        <View style={styles.bottomPadding} />
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#FFFFFF',
  },
  content: {
    flex: 1,
    backgroundColor: '#FFFFFF',
    paddingHorizontal: 16,
  },
  greetingContainer: {
    paddingVertical: 16,
    paddingHorizontal: 4,
  },
  greetingText: {
    fontSize: 18,
    fontWeight: '500',
    color: '#333333',
  },
  statsContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 20,
    gap: 12,
  },
  statCard: {
    flex: 1,
    alignItems: 'center',
    backgroundColor: '#F5F5F5',
    paddingVertical: 12,
    borderRadius: 10,
  },
  statNumber: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#000000',
  },
  statLabel: {
    fontSize: 13,
    color: '#666666',
    marginTop: 2,
  },
  searchContainer: {
    marginBottom: 16,
  },
  searchInputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#F5F5F5',
    borderRadius: 10,
    paddingHorizontal: 12,
    paddingVertical: 10,
    borderWidth: 1,
    borderColor: '#EEEEEE',
  },
  searchInput: {
    flex: 1,
    marginLeft: 8,
    fontSize: 14,
    color: '#333333',
  },
  searchExample: {
    fontSize: 12,
    color: '#999999',
    marginTop: 4,
    marginLeft: 12,
  },
  separator: {
    height: 1,
    backgroundColor: '#F0F0F0',
    marginBottom: 16,
  },
  shipmentsHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
  },
  shipmentsTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#000000',
  },
  viewAllText: {
    fontSize: 13,
    color: '#FF6B00',
    fontWeight: '500',
  },
  orderCard: {
    backgroundColor: '#FFFFFF',
    marginBottom: 12,
    padding: 14,
    borderRadius: 10,
    borderWidth: 1,
    borderColor: '#F0F0F0',
  },
  orderId: {
    fontSize: 14,
    fontWeight: '600',
    color: '#333333',
    marginBottom: 6,
  },
  orderLocation: {
    fontSize: 13,
    color: '#666666',
    marginBottom: 8,
  },
  orderFooter: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  estimatedText: {
    fontSize: 12,
    color: '#FF6B00',
    marginLeft: 4,
  },
  greenText: {
    color: '#4CAF50',
  },
  bottomPadding: {
    height: 80,
  },
});