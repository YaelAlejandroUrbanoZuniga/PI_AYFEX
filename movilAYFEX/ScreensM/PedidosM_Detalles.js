import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
  StatusBar,
  Alert,
  Modal,
  TextInput,
  Platform
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import HeaderNaranjaVolver from '../Components/HeaderNaranjaVolver';

const API_URL = Platform.OS === "web"
  ? "http://localhost:5000/v1/pedidos"
  : "http://192.168.100.134:5000/v1/pedidos";

export default function PedidosM_Detalles({ navigation, route }) {

  const { pedidoData } = route.params;

  const [modalVisible, setModalVisible] = useState(false);

  const [origen, setOrigen] = useState(pedidoData.origen);
  const [destino, setDestino] = useState(pedidoData.destino);
  const [peso, setPeso] = useState(String(pedidoData.peso));
  const [tipo, setTipo] = useState(pedidoData.tipo);
  const [altura, setAltura] = useState(String(pedidoData.altura));
  const [anchura, setAnchura] = useState(String(pedidoData.anchura));
  const [descripcion, setDescripcion] = useState(pedidoData.descripcion);
  
  if (!origen || origen.length < 5) {
    Alert.alert("Error", "El origen debe tener al menos 5 caracteres");
    return;
  }

  if (!destino || destino.length < 5) {
    Alert.alert("Error", "El destino debe tener al menos 5 caracteres");
    return;
  }
  const actualizarPedido = async () => {

    try {

      const pedidoActualizado = {
        id: pedidoData.id,
        origen,
        destino,
        peso: parseFloat(peso),
        tipo,
        altura: parseFloat(altura),
        anchura: parseFloat(anchura),
        descripcion
      };

      await fetch(`${API_URL}/${pedidoData.id}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(pedidoActualizado)
      });

      Alert.alert("Pedido actualizado");

      setModalVisible(false);

      navigation.goBack();

    } catch (error) {

      console.log(error);
      Alert.alert("Error", "No se pudo actualizar");

    }

  };

  const eliminarPedido = () => {

    Alert.alert(
      "Eliminar pedido",
      "¿Estás seguro que deseas eliminar este pedido?",
      [
        { text: "Cancelar", style: "cancel" },
        {
          text: "Eliminar",
          style: "destructive",
          onPress: async () => {

            try {

              await fetch(`${API_URL}/${pedidoData.id}`, {
                method: "DELETE"
              });

              Alert.alert("Pedido eliminado");

              navigation.goBack();

            } catch (error) {

              Alert.alert("Error", "No se pudo eliminar");

            }

          }
        }
      ]
    );

  };

  return (

    <View style={styles.container}>

      <StatusBar barStyle="dark-content" />

      <HeaderNaranjaVolver navigation={navigation} />

      <ScrollView style={styles.content} showsVerticalScrollIndicator={false}>

        <Text style={styles.screenTitle}>Detalles del Pedido</Text>

        <Text style={styles.orderId}>ID: {pedidoData.id}</Text>

        <View style={styles.card}>

          <Text style={styles.label}>Origen</Text>
          <Text style={styles.value}>{pedidoData.origen}</Text>

          <Text style={styles.label}>Destino</Text>
          <Text style={styles.value}>{pedidoData.destino}</Text>

          <Text style={styles.label}>Peso</Text>
          <Text style={styles.value}>{pedidoData.peso} kg</Text>

          <Text style={styles.label}>Tipo</Text>
          <Text style={styles.value}>{pedidoData.tipo}</Text>

          <Text style={styles.label}>Altura</Text>
          <Text style={styles.value}>{pedidoData.altura} cm</Text>

          <Text style={styles.label}>Anchura</Text>
          <Text style={styles.value}>{pedidoData.anchura} cm</Text>

          <Text style={styles.label}>Descripción</Text>
          <Text style={styles.value}>{pedidoData.descripcion}</Text>

          <Text style={styles.label}>Fecha</Text>
          <Text style={styles.value}>{pedidoData.fecha}</Text>

        </View>

        <View style={styles.buttonsContainer}>

          <TouchableOpacity
            style={styles.updateButton}
            onPress={() => setModalVisible(true)}
          >
            <Text style={styles.updateText}>Actualizar</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.deleteButton}
            onPress={eliminarPedido}
          >
            <Text style={styles.deleteText}>Eliminar</Text>
          </TouchableOpacity>

        </View>

        <View style={{ height: 120 }} />

      </ScrollView>

      <Modal visible={modalVisible} animationType='slide'>

        <View style={styles.modalWrapper}>

          <ScrollView style={styles.modalContainer} showsVerticalScrollIndicator={false}>

            <Text style={styles.modalTitle}>Actualizar Pedido</Text>

            <Text style={styles.inputLabel}>Origen</Text>
            <TextInput
              style={styles.input}
              value={origen}
              onChangeText={setOrigen}
            />

            <Text style={styles.inputLabel}>Destino</Text>
            <TextInput
              style={styles.input}
              value={destino}
              onChangeText={setDestino}
            />

            <Text style={styles.inputLabel}>Peso</Text>
            <TextInput
              style={styles.input}
              value={peso}
              onChangeText={setPeso}
              keyboardType="numeric"
            />

            <Text style={styles.inputLabel}>Tipo</Text>
            <TextInput
              style={styles.input}
              value={tipo}
              onChangeText={setTipo}
            />

            <Text style={styles.inputLabel}>Altura</Text>
            <TextInput
              style={styles.input}
              value={altura}
              onChangeText={setAltura}
              keyboardType="numeric"
            />

            <Text style={styles.inputLabel}>Anchura</Text>
            <TextInput
              style={styles.input}
              value={anchura}
              onChangeText={setAnchura}
              keyboardType="numeric"
            />

            <Text style={styles.inputLabel}>Descripción</Text>
            <TextInput
              style={[styles.input, styles.textArea]}
              value={descripcion}
              onChangeText={setDescripcion}
              multiline
            />

            <TouchableOpacity style={styles.saveButton} onPress={actualizarPedido}>
              <Text style={styles.saveText}>Actualizar Pedido</Text>
            </TouchableOpacity>

            <TouchableOpacity onPress={() => setModalVisible(false)}>
              <Text style={styles.cancelText}>Cancelar</Text>
            </TouchableOpacity>

            <View style={{ height: 60 }} />

          </ScrollView>

        </View>

      </Modal>

    </View>

  );
}

const styles = StyleSheet.create({

  container: { flex: 1, backgroundColor: '#fff' },

  content: { padding: 20 },

  screenTitle: { fontSize: 24, fontWeight: 'bold', marginBottom: 10 },

  orderId: { fontSize: 16, marginBottom: 20, color: "#666" },

  card: {
    backgroundColor: '#F8F9FA',
    borderRadius: 12,
    padding: 16
  },

  label: {
    fontSize: 13,
    color: "#999",
    marginTop: 10
  },

  value: {
    fontSize: 15,
    color: "#333"
  },

  buttonsContainer: {
    flexDirection: 'row',
    marginTop: 30,
    justifyContent: 'space-between'
  },

  updateButton: {
    flex: 1,
    backgroundColor: "#FF6B00",
    padding: 14,
    borderRadius: 10,
    marginRight: 10,
    alignItems: 'center'
  },

  deleteButton: {
    flex: 1,
    backgroundColor: "#FF3B30",
    padding: 14,
    borderRadius: 10,
    alignItems: 'center'
  },

  updateText: { color: "#fff", fontWeight: 'bold' },

  deleteText: { color: "#ffffff", fontWeight: 'bold' },

  modalContainer: {
    padding: 20,
    marginTop: 20
  },

  modalTitle: {
    fontSize: 22,
    fontWeight: 'bold',
    marginBottom: 20
  },

  input: {
    borderWidth: 1,
    borderColor: "#ddd",
    borderRadius: 10,
    padding: 12,
    marginBottom: 15
  },

  saveButton: {
    backgroundColor: "#FF6B00",
    padding: 16,
    borderRadius: 10,
    alignItems: 'center'
  },

  saveText: { color: "#fff", fontWeight: "bold" },
  modalWrapper: {
    flex: 1,
    backgroundColor: "#fff",
    paddingTop: 60
  },

  inputLabel: {
    fontSize: 13,
    color: "#666",
    marginBottom: 6,
    marginTop: 12,
    fontWeight: "500"
  },

  cancelText: {
    textAlign: 'center',
    marginTop: 20,
    fontSize: 15,
    color: "#666"
  },

  textArea: {
    height: 80,
    textAlignVertical: 'top'
  }

});