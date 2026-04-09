import React, { useState, useEffect, useRef } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  TextInput,
  ScrollView,
  StatusBar,
  Platform,
  Modal,
  Animated,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import HeaderNaranja from '../Components/HeaderNaranja';
import { API_BASE_URL } from '../config';

const API_URL = `${API_BASE_URL}/v1/pedidos/`;

// COMPONENTE ALERT PERSONALIZADO
function AlertPersonalizado({ visible, titulo, mensaje, onAceptar }) {
  return (
    <Modal visible={visible} transparent animationType="fade">
      <View style={styles.alertOverlay}>
        <View style={styles.alertContainer}>
          <View style={styles.alertIconContainer}>
            <Ionicons name="alert-circle" size={36} color="#FF6B00" />
          </View>
          <Text style={styles.alertTitulo}>{titulo}</Text>
          <Text style={styles.alertMensaje}>{mensaje}</Text>
          <TouchableOpacity style={styles.alertBoton} onPress={onAceptar}>
            <Text style={styles.alertBotonTexto}>Entendido</Text>
          </TouchableOpacity>
        </View>
      </View>
    </Modal>
  );
}

// COMPONENTE SPINNER
function Spinner() {
  const rotacion = useRef(new Animated.Value(0)).current;

  useEffect(() => {
    Animated.loop(
      Animated.timing(rotacion, {
        toValue: 1,
        duration: 900,
        useNativeDriver: true,
      })
    ).start();
  }, []);

  const girar = rotacion.interpolate({
    inputRange: [0, 1],
    outputRange: ['0deg', '360deg'],
  });

  return (
    <Animated.View style={{ transform: [{ rotate: girar }] }}>
      <Ionicons name="reload-outline" size={52} color="#FF6B00" />
    </Animated.View>
  );
}

export default function CrearPedidoM({ navigation }) {

  const [origen, setOrigen] = useState('');
  const [destino, setDestino] = useState('');
  const [peso, setPeso] = useState('');
  const [tipo, setTipo] = useState('');
  const [altura, setAltura] = useState('');
  const [anchura, setAnchura] = useState('');
  const [descripcion, setDescripcion] = useState('');
  const [cargando, setCargando] = useState(false);
  const [exitoso, setExitoso] = useState(false);

  const [alertVisible, setAlertVisible] = useState(false);
  const [alertTitulo, setAlertTitulo] = useState('');
  const [alertMensaje, setAlertMensaje] = useState('');

  const mostrarAlert = (titulo, mensaje) => {
    setAlertTitulo(titulo);
    setAlertMensaje(mensaje);
    setAlertVisible(true);
  };

  const limpiarFormulario = () => {
    setOrigen('');
    setDestino('');
    setPeso('');
    setTipo('');
    setAltura('');
    setAnchura('');
    setDescripcion('');
  };

  const crearPedido = async () => {

    if (!origen || origen.length < 5) {
      mostrarAlert("Campo inválido", "La dirección de origen debe tener al menos 5 caracteres.");
      return;
    }
    if (!destino || destino.length < 5) {
      mostrarAlert("Campo inválido", "La dirección de destino debe tener al menos 5 caracteres.");
      return;
    }
    if (!peso || parseFloat(peso) <= 0) {
      mostrarAlert("Campo inválido", "El peso debe ser un número mayor a 0.");
      return;
    }
    if (!tipo || tipo.length < 3) {
      mostrarAlert("Campo inválido", "El tipo de paquete debe tener al menos 3 caracteres.");
      return;
    }
    if (!altura || parseFloat(altura) <= 0) {
      mostrarAlert("Campo inválido", "La altura debe ser un número mayor a 0.");
      return;
    }
    if (!anchura || parseFloat(anchura) <= 0) {
      mostrarAlert("Campo inválido", "La anchura debe ser un número mayor a 0.");
      return;
    }

    try {
      setCargando(true);

      const pedido = {
        origen,
        destino,
        peso: parseFloat(peso),
        tipo,
        altura: parseFloat(altura),
        anchura: parseFloat(anchura),
        descripcion,
      };

      const response = await fetch(API_URL, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Authorization": `Bearer ${global.authToken}`
        },
        body: JSON.stringify(pedido)
      });

      const data = await response.json();

      if (!response.ok) {
        let mensaje = "Revisa los datos ingresados e intenta de nuevo.";
        if (data.detail && Array.isArray(data.detail)) {
          const error = data.detail[0];
          const campo = error.loc[1];
          if (campo === "peso") mensaje = "El peso debe ser un número mayor a 0.";
          else if (campo === "altura") mensaje = "La altura debe ser un número mayor a 0.";
          else if (campo === "anchura") mensaje = "La anchura debe ser un número mayor a 0.";
          else if (campo === "tipo") mensaje = "El tipo debe tener entre 3 y 50 caracteres.";
          else if (campo === "descripcion") mensaje = "La descripción no puede superar 300 caracteres.";
        }
        throw new Error(mensaje);
      }

      await new Promise(resolve => setTimeout(resolve, 4000));

      setCargando(false);
      setExitoso(true);
      limpiarFormulario();

      setTimeout(() => {
        setExitoso(false);
        navigation.navigate("Pedidos");
      }, 2500);

    } catch (error) {
      setCargando(false);
      mostrarAlert("No se pudo crear el pedido", error.message || "No se pudo conectar con el servidor.");
    }
  };

  // PANTALLA DE CARGA
  if (cargando) {
    return (
      <View style={styles.container}>
        <StatusBar barStyle="dark-content" backgroundColor="#FFFFFF" />
        <HeaderNaranja />
        <View style={styles.feedbackContainer}>
          <View style={styles.feedbackIconContainer}>
            <Spinner />
          </View>
          <Text style={styles.feedbackTitle}>Creando tu pedido</Text>
          <Text style={styles.feedbackSubtitle}>
            Estamos registrando tu envío,{'\n'}por favor espera un momento...
          </Text>
        </View>
      </View>
    );
  }

  // PANTALLA DE ÉXITO
  if (exitoso) {
    return (
      <View style={styles.container}>
        <StatusBar barStyle="dark-content" backgroundColor="#FFFFFF" />
        <HeaderNaranja />
        <View style={styles.feedbackContainer}>
          <View style={[styles.feedbackIconContainer, styles.successIconContainer]}>
            <Ionicons name="checkmark-circle" size={70} color="#34C759" />
          </View>
          <Text style={styles.feedbackTitle}>¡Pedido creado!</Text>
          <Text style={styles.feedbackSubtitle}>
            Tu pedido fue registrado correctamente.{'\n'}Te redirigimos a tus envíos.
          </Text>
        </View>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#FFFFFF" />
      <HeaderNaranja />

      <AlertPersonalizado
        visible={alertVisible}
        titulo={alertTitulo}
        mensaje={alertMensaje}
        onAceptar={() => setAlertVisible(false)}
      />

      <ScrollView
        style={styles.content}
        showsVerticalScrollIndicator={false}
        keyboardShouldPersistTaps="handled"
        contentContainerStyle={styles.scrollContent}
      >

        <View style={styles.titleContainer}>
          <Text style={styles.screenTitle}>Nuevo <Text style={styles.screenTitleAccent}>Pedido</Text></Text>
          <Text style={styles.screenSubtitle}>Completa los datos de tu envío</Text>
        </View>

        {/* SECCIÓN RUTA */}
        <View style={styles.sectionCard}>
          <View style={styles.sectionCardHeader}>
            <View style={styles.sectionCardIcon}>
              <Ionicons name="navigate-outline" size={18} color="#FF6B00" />
            </View>
            <Text style={styles.sectionCardTitle}>Ruta de envío</Text>
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.inputLabel}>Dirección de Origen</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="location" size={18} color="#FF6B00" />
              <TextInput
                style={styles.input}
                placeholder="Ej: Calle Madero 225, Amealco"
                placeholderTextColor="#BBBBBB"
                value={origen}
                onChangeText={setOrigen}
              />
            </View>
          </View>

          <View style={styles.routeArrow}>
            <View style={styles.routeArrowLine} />
            <Ionicons name="arrow-down" size={16} color="#CCCCCC" />
            <View style={styles.routeArrowLine} />
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.inputLabel}>Dirección de Destino</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="flag" size={18} color="#34C759" />
              <TextInput
                style={styles.input}
                placeholder="Ej: Calle Venus 13, Monterrey"
                placeholderTextColor="#BBBBBB"
                value={destino}
                onChangeText={setDestino}
              />
            </View>
          </View>
        </View>

        {/* SECCIÓN PAQUETE */}
        <View style={styles.sectionCard}>
          <View style={styles.sectionCardHeader}>
            <View style={styles.sectionCardIcon}>
              <Ionicons name="cube-outline" size={18} color="#FF6B00" />
            </View>
            <Text style={styles.sectionCardTitle}>Detalles del paquete</Text>
          </View>

          <View style={styles.rowContainer}>
            <View style={[styles.inputGroup, styles.halfWidth]}>
              <Text style={styles.inputLabel}>Peso (kg)</Text>
              <View style={styles.inputContainer}>
                <Ionicons name="barbell-outline" size={18} color="#999999" />
                <TextInput
                  style={styles.input}
                  placeholder="Ej: 10"
                  keyboardType="numeric"
                  placeholderTextColor="#BBBBBB"
                  value={peso}
                  onChangeText={setPeso}
                />
              </View>
            </View>

            <View style={[styles.inputGroup, styles.halfWidth]}>
              <Text style={styles.inputLabel}>Tipo de paquete</Text>
              <View style={styles.inputContainer}>
                <Ionicons name="pricetag-outline" size={18} color="#999999" />
                <TextInput
                  style={styles.input}
                  placeholder="Ej: Estándar"
                  placeholderTextColor="#BBBBBB"
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
                <Ionicons name="resize-outline" size={18} color="#999999" />
                <TextInput
                  style={styles.input}
                  placeholder="Ej: 50"
                  keyboardType="numeric"
                  placeholderTextColor="#BBBBBB"
                  value={altura}
                  onChangeText={setAltura}
                />
              </View>
            </View>

            <View style={[styles.inputGroup, styles.halfWidth]}>
              <Text style={styles.inputLabel}>Anchura (cm)</Text>
              <View style={styles.inputContainer}>
                <Ionicons name="resize-outline" size={18} color="#999999" />
                <TextInput
                  style={styles.input}
                  placeholder="Ej: 50"
                  keyboardType="numeric"
                  placeholderTextColor="#BBBBBB"
                  value={anchura}
                  onChangeText={setAnchura}
                />
              </View>
            </View>
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.inputLabel}>
              Descripción <Text style={styles.optionalLabel}>(opcional)</Text>
            </Text>
            <View style={[styles.inputContainer, styles.textAreaContainer]}>
              <Ionicons
                name="document-text-outline"
                size={18}
                color="#999999"
                style={{ marginTop: 2 }}
              />
              <TextInput
                style={[styles.input, styles.textArea]}
                placeholder="Ej: Paquete frágil, entregar en manos del destinatario"
                placeholderTextColor="#BBBBBB"
                multiline
                numberOfLines={4}
                value={descripcion}
                onChangeText={setDescripcion}
              />
            </View>
          </View>
        </View>

        <TouchableOpacity
          style={styles.confirmButton}
          onPress={crearPedido}
        >
          <Ionicons name="checkmark-circle-outline" size={20} color="#FFFFFF" style={{ marginRight: 8 }} />
          <Text style={styles.confirmButtonText}>Confirmar Pedido</Text>
        </TouchableOpacity>

        <View style={styles.bottomPadding} />
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#FFFFFF' },
  content: { flex: 1, backgroundColor: '#FFFFFF', paddingHorizontal: 20 },
  scrollContent: { paddingBottom: 120 },
  titleContainer: { paddingTop: 20, paddingBottom: 20 },
  screenTitle: { fontSize: 26, fontWeight: 'bold', color: '#000000' },
  screenTitleAccent: { color: '#FF6B00' },
  screenSubtitle: { fontSize: 13, color: '#999999', marginTop: 4 },

  sectionCard: {
    backgroundColor: '#F8F9FA',
    borderRadius: 16,
    padding: 16,
    marginBottom: 16,
    borderWidth: 1,
    borderColor: '#EEEEEE',
  },
  sectionCardHeader: { flexDirection: 'row', alignItems: 'center', marginBottom: 16 },
  sectionCardIcon: {
    width: 32, height: 32, borderRadius: 8,
    backgroundColor: '#FFF3E6',
    justifyContent: 'center', alignItems: 'center', marginRight: 10,
  },
  sectionCardTitle: { fontSize: 15, fontWeight: '600', color: '#333333' },

  routeArrow: {
    flexDirection: 'row', alignItems: 'center',
    marginVertical: 4, marginBottom: 16, paddingLeft: 8,
  },
  routeArrowLine: { width: 20, height: 1, backgroundColor: '#EEEEEE' },

  inputGroup: { marginBottom: 12 },
  inputLabel: { fontSize: 13, fontWeight: '600', color: '#555555', marginBottom: 6 },
  optionalLabel: { fontSize: 12, color: '#BBBBBB', fontWeight: '400' },
  inputContainer: {
    flexDirection: 'row', alignItems: 'center',
    backgroundColor: '#FFFFFF', borderRadius: 12,
    paddingHorizontal: 12,
    paddingVertical: Platform.OS === 'ios' ? 12 : 8,
    borderWidth: 1, borderColor: '#E0E0E0',
  },
  input: { flex: 1, marginLeft: 8, fontSize: 14, color: '#333333' },
  rowContainer: { flexDirection: 'row', justifyContent: 'space-between' },
  halfWidth: { width: '48%' },
  textAreaContainer: { alignItems: 'flex-start', minHeight: 100 },
  textArea: { height: 80, textAlignVertical: 'top' },

  confirmButton: {
    backgroundColor: '#FF6B00', borderRadius: 14,
    paddingVertical: 16, alignItems: 'center',
    flexDirection: 'row', justifyContent: 'center',
    marginTop: 6,
    shadowColor: '#FF6B00',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.25, shadowRadius: 6, elevation: 5,
  },
  confirmButtonText: { color: '#FFFFFF', fontSize: 16, fontWeight: '700' },
  bottomPadding: { height: 20 },

  // FEEDBACK
  feedbackContainer: {
    flex: 1, justifyContent: 'center',
    alignItems: 'center', paddingHorizontal: 40,
  },
  feedbackIconContainer: {
    width: 120, height: 120, borderRadius: 60,
    backgroundColor: '#FFF3E6',
    justifyContent: 'center', alignItems: 'center', marginBottom: 28,
  },
  successIconContainer: { backgroundColor: '#F0FFF4' },
  feedbackTitle: {
    fontSize: 24, fontWeight: 'bold',
    color: '#000000', marginBottom: 12, textAlign: 'center',
  },
  feedbackSubtitle: {
    fontSize: 15, color: '#888888',
    textAlign: 'center', lineHeight: 22,
  },

  // ALERT PERSONALIZADO
  alertOverlay: {
    flex: 1, backgroundColor: 'rgba(0,0,0,0.45)',
    justifyContent: 'center', alignItems: 'center',
    paddingHorizontal: 32,
  },
  alertContainer: {
    backgroundColor: '#FFFFFF',
    borderRadius: 20, padding: 28,
    alignItems: 'center', width: '100%',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 8 },
    shadowOpacity: 0.12, shadowRadius: 16, elevation: 10,
  },
  alertIconContainer: {
    width: 70, height: 70, borderRadius: 35,
    backgroundColor: '#FFF3E6',
    justifyContent: 'center', alignItems: 'center', marginBottom: 16,
  },
  alertTitulo: {
    fontSize: 18, fontWeight: '700',
    color: '#111111', marginBottom: 8, textAlign: 'center',
  },
  alertMensaje: {
    fontSize: 14, color: '#666666',
    textAlign: 'center', lineHeight: 20, marginBottom: 24,
  },
  alertBoton: {
    backgroundColor: '#FF6B00', borderRadius: 12,
    paddingVertical: 13, paddingHorizontal: 40,
    shadowColor: '#FF6B00',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.2, shadowRadius: 4, elevation: 4,
  },
  alertBotonTexto: { color: '#FFFFFF', fontSize: 15, fontWeight: '700' },
});