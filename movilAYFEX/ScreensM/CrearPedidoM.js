import React from 'react';
import {
  View,
  Text,
  StyleSheet,
  SafeAreaView,
  TouchableOpacity,
  TextInput,
  ScrollView,
  StatusBar,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';

export default function CrearPedidoM({ navigation }) {
  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#FFFFFF" />

      <View style={styles.header}>
        <View style={styles.headerLeft}>
          <Ionicons name="menu-outline" size={24} color="#000000" />
        </View>
        <View style={styles.headerCenter}>
          <Text style={styles.headerTitle}>AYFEX</Text>
        </View>
        <View style={styles.headerRight}>
          <Text style={styles.headerGreeting}>Hola, Fidel!</Text>
        </View>
      </View>

      <ScrollView style={styles.content} showsVerticalScrollIndicator={false}>
        <Text style={styles.screenTitle}>Crear Pedido</Text>

        <View style={styles.inputGroup}>
          <Text style={styles.inputLabel}>Dirección de Origen</Text>
          <View style={styles.inputContainer}>
            <Ionicons name="location-outline" size={20} color="#999999" />
            <TextInput
              style={styles.input}
              placeholder="Ej: Calle Madero 225, Amealco"
              placeholderTextColor="#999999"
            />
          </View>
        </View>

        <View style={styles.inputGroup}>
          <Text style={styles.inputLabel}>Dirección de Destino</Text>
          <View style={styles.inputContainer}>
            <Ionicons name="location-outline" size={20} color="#999999" />
            <TextInput
              style={styles.input}
              placeholder="Ej: Calle Venus 13, Monterrey"
              placeholderTextColor="#999999"
            />
          </View>
        </View>

        <View style={styles.separator} />

        <View style={styles.rowContainer}>
          <View style={[styles.inputGroup, styles.halfWidth]}>
            <Text style={styles.inputLabel}>KG</Text>
            <Text style={styles.subLabel}>Peso (kg)</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="barbell-outline" size={20} color="#999999" />
              <TextInput
                style={styles.input}
                placeholder="Ej: 10.0"
                placeholderTextColor="#999999"
                keyboardType="numeric"
              />
            </View>
          </View>

          <View style={[styles.inputGroup, styles.halfWidth]}>
            <Text style={styles.inputLabel}>Tipo</Text>
            <Text style={styles.subLabel}>Tipo de paquete</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="cube-outline" size={20} color="#999999" />
              <TextInput
                style={styles.input}
                placeholder="Ej: Estándar"
                placeholderTextColor="#999999"
              />
            </View>
          </View>
        </View>

        <View style={styles.rowContainer}>
          <View style={[styles.inputGroup, styles.halfWidth]}>
            <Text style={styles.inputLabel}>Altura (cm)</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="resize-outline" size={20} color="#999999" />
              <TextInput
                style={styles.input}
                placeholder="Ej: 50.0"
                placeholderTextColor="#999999"
                keyboardType="numeric"
              />
            </View>
          </View>

          <View style={[styles.inputGroup, styles.halfWidth]}>
            <Text style={styles.inputLabel}>Anchura (cm)</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="resize-outline" size={20} color="#999999" />
              <TextInput
                style={styles.input}
                placeholder="Ej: 50.0"
                placeholderTextColor="#999999"
                keyboardType="numeric"
              />
            </View>
          </View>
        </View>

        <View style={styles.inputGroup}>
          <Text style={styles.inputLabel}>Descripción (opcional)</Text>
          <View style={[styles.inputContainer, styles.textAreaContainer]}>
            <Ionicons name="document-text-outline" size={20} color="#999999" style={styles.textAreaIcon} />
            <TextInput
              style={[styles.input, styles.textArea]}
              placeholder="Ej: Paquete frágil que debe entregarse en las manos del destinatario."
              placeholderTextColor="#999999"
              multiline
              numberOfLines={4}
              textAlignVertical="top"
            />
          </View>
        </View>

        <TouchableOpacity style={styles.confirmButton}>
          <Text style={styles.confirmButtonText}>Confirmar Pedido</Text>
        </TouchableOpacity>

        <View style={styles.bottomPadding} />
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#FFFFFF',
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 16,
    paddingVertical: 12,
    backgroundColor: '#FFFFFF',
    borderBottomWidth: 1,
    borderBottomColor: '#F0F0F0',
  },
  headerLeft: {
    width: 40,
  },
  headerCenter: {
    alignItems: 'center',
  },
  headerTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#000000',
  },
  headerRight: {
    width: 40,
    alignItems: 'flex-end',
  },
  headerGreeting: {
    fontSize: 14,
    color: '#666666',
  },
  content: {
    flex: 1,
    backgroundColor: '#FFFFFF',
    paddingHorizontal: 20,
  },
  screenTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#000000',
    marginTop: 20,
    marginBottom: 24,
  },
  inputGroup: {
    marginBottom: 20,
  },
  inputLabel: {
    fontSize: 16,
    fontWeight: '600',
    color: '#333333',
    marginBottom: 4,
  },
  subLabel: {
    fontSize: 12,
    color: '#999999',
    marginBottom: 8,
    marginTop: -2,
  },
  inputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#F8F9FA',
    borderRadius: 12,
    paddingHorizontal: 12,
    paddingVertical: 10,
    borderWidth: 1,
    borderColor: '#EEEEEE',
  },
  input: {
    flex: 1,
    marginLeft: 8,
    fontSize: 14,
    color: '#333333',
  },
  separator: {
    height: 1,
    backgroundColor: '#F0F0F0',
    marginVertical: 20,
  },
  rowContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 20,
  },
  halfWidth: {
    width: '48%',
  },
  textAreaContainer: {
    alignItems: 'flex-start',
    minHeight: 100,
  },
  textAreaIcon: {
    marginTop: 2,
  },
  textArea: {
    height: 80,
    textAlignVertical: 'top',
  },
  confirmButton: {
    backgroundColor: '#FF6B00',
    borderRadius: 12,
    paddingVertical: 16,
    alignItems: 'center',
    marginTop: 10,
    marginBottom: 20,
    shadowColor: '#FF6B00',
    shadowOffset: {
      width: 0,
      height: 4,
    },
    shadowOpacity: 0.2,
    shadowRadius: 4,
    elevation: 4,
  },
  confirmButtonText: {
    color: '#FFFFFF',
    fontSize: 16,
    fontWeight: '600',
  },
  bottomPadding: {
    height: 20,
  },
});