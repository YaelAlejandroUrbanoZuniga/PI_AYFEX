import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  TextInput,
  ScrollView,
  StatusBar,
  Alert,
  Platform
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import HeaderNaranja from '../Components/HeaderNaranja';
import { API_BASE_URL } from '../config';

const API_URL = `${API_BASE_URL}/v1/pedidos/`;

export default function CrearPedidoM({ navigation }) {

  const [origen, setOrigen] = useState('');
  const [destino, setDestino] = useState('');
  const [peso, setPeso] = useState('');
  const [tipo, setTipo] = useState('');
  const [altura, setAltura] = useState('');
  const [anchura, setAnchura] = useState('');
  const [descripcion, setDescripcion] = useState('');

  const crearPedido = async () => {

    if (!origen || origen.length < 5) {
      Alert.alert("Error", "El origen debe tener al menos 5 caracteres");
      return;
    }

    if (!destino || destino.length < 5) {
      Alert.alert("Error", "El destino debe tener al menos 5 caracteres");
      return;
    }

    if (peso && parseFloat(peso) <= 0) {
      Alert.alert("Error", "El peso debe ser mayor a 0");
      return;
    }

    if (altura && parseFloat(altura) <= 0) {
      Alert.alert("Error", "La altura debe ser mayor a 0");
      return;
    }

    if (anchura && parseFloat(anchura) <= 0) {
      Alert.alert("Error", "La anchura debe ser mayor a 0");
      return;
    }

    try {

      const pedido = {
        origen: origen,
        destino: destino,
        peso: peso ? parseFloat(peso) : 1,
        tipo: tipo,
        altura: altura ? parseFloat(altura) : 0,
        anchura: anchura ? parseFloat(anchura) : 0,
        descripcion: descripcion
      };

      console.log("Enviando pedido:", pedido);

      const response = await fetch(API_URL, {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(pedido)
      });

      let data;

      try {
        data = await response.json();
      } catch {
        throw new Error("El servidor no respondió correctamente");
      }

      console.log("Respuesta API:", data);

      if (!response.ok) {

        let mensaje = "Error en los datos ingresados";

        if (data.detail && Array.isArray(data.detail)) {

          const error = data.detail[0];
          const campo = error.loc[1];
          const tipo = error.type;

          if (campo === "peso") {
            mensaje = "El peso debe ser un número mayor a 0";
          }

          else if (campo === "altura") {
            mensaje = "La altura debe ser un número mayor a 0";
          }

          else if (campo === "anchura") {
            mensaje = "La anchura debe ser un número mayor a 0";
          }

          else if (campo === "tipo") {
            mensaje = "El tipo de paquete debe tener entre 3 y 50 caracteres";
          }

          else if (campo === "descripcion") {
            mensaje = "La descripción no puede superar 300 caracteres";
          }

        }

        throw new Error(mensaje);
      }

      Alert.alert(
        "Pedido creado",
        "El pedido se registró correctamente",
        [
          {
            text: "OK",
            onPress: () => navigation.navigate("Pedidos")
          }
        ]
      );

      setOrigen('');
      setDestino('');
      setPeso('');
      setTipo('');
      setAltura('');
      setAnchura('');
      setDescripcion('');

    } catch (error) {

      console.log(error);

      Alert.alert(
        "Error",
        error.message || "No se pudo conectar con el servidor"
      );

    }

  }

  return (
    <View style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#FFFFFF" />
      <HeaderNaranja />

      <ScrollView
        style={styles.content}
        showsVerticalScrollIndicator={false}
        keyboardShouldPersistTaps="handled"
        contentContainerStyle={styles.scrollContent}
      >

        <Text style={styles.screenTitle}>Crear Pedido</Text>

        <View style={styles.inputGroup}>
          <Text style={styles.inputLabel}>Dirección de Origen</Text>
          <View style={styles.inputContainer}>
            <Ionicons name="location-outline" size={20} color="#999999" />
            <TextInput
              style={styles.input}
              placeholder="Ej: Calle Madero 225, Amealco"
              placeholderTextColor="#999999"
              value={origen}
              onChangeText={setOrigen}
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
              value={destino}
              onChangeText={setDestino}
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
                placeholder="Ej: 10"
                keyboardType="numeric"
                placeholderTextColor="#999999"
                value={peso}
                onChangeText={setPeso}
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
                value={tipo}
                onChangeText={setTipo}
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
                placeholder="Ej: 50"
                keyboardType="numeric"
                placeholderTextColor="#999999"
                value={altura}
                onChangeText={setAltura}
              />
            </View>
          </View>

          <View style={[styles.inputGroup, styles.halfWidth]}>
            <Text style={styles.inputLabel}>Anchura (cm)</Text>

            <View style={styles.inputContainer}>
              <Ionicons name="resize-outline" size={20} color="#999999" />
              <TextInput
                style={styles.input}
                placeholder="Ej: 50"
                keyboardType="numeric"
                placeholderTextColor="#999999"
                value={anchura}
                onChangeText={setAnchura}
              />
            </View>
          </View>

        </View>

        <View style={styles.inputGroup}>
          <Text style={styles.inputLabel}>Descripción (opcional)</Text>

          <View style={[styles.inputContainer, styles.textAreaContainer]}>
            <Ionicons
              name="document-text-outline"
              size={20}
              color="#999999"
              style={styles.textAreaIcon}
            />

            <TextInput
              style={[styles.input, styles.textArea]}
              placeholder="Ej: Paquete frágil que debe entregarse en manos del destinatario"
              placeholderTextColor="#999999"
              multiline
              numberOfLines={4}
              value={descripcion}
              onChangeText={setDescripcion}
            />
          </View>
        </View>

        <TouchableOpacity
          style={styles.confirmButton}
          onPress={crearPedido}
        >
          <Text style={styles.confirmButtonText}>
            Confirmar Pedido
          </Text>
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
  scrollContent: {
    paddingBottom: 120
  }
});