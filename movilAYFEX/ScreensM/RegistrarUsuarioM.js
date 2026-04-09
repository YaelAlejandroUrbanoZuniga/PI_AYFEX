import React, { useState } from 'react';
import {
  View, Text, StyleSheet, TouchableOpacity,
  TextInput, ScrollView, StatusBar,
  KeyboardAvoidingView, Platform, Modal,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import HeaderNaranjaVolver from '../Components/HeaderNaranjaVolver';
import { API_BASE_URL } from '../config';

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

export default function RegistrarUsuarioM({ navigation }) {

  const [nombreCompleto, setNombreCompleto] = useState('');
  const [correo, setCorreo] = useState('');
  const [telefono, setTelefono] = useState('');
  const [password, setPassword] = useState('');
  const [confirmarPassword, setConfirmarPassword] = useState('');
  const [verPassword, setVerPassword] = useState(false);
  const [verConfirmar, setVerConfirmar] = useState(false);
  const [cargando, setCargando] = useState(false);
  const [alertVisible, setAlertVisible] = useState(false);
  const [alertMensaje, setAlertMensaje] = useState('');

  const mostrarAlert = (mensaje) => {
    setAlertMensaje(mensaje);
    setAlertVisible(true);
  };

  const handleCreateAccount = async () => {
    if (!nombreCompleto || nombreCompleto.length < 3) { mostrarAlert("El nombre debe tener al menos 3 caracteres."); return; }
    if (!correo || !correo.includes('@')) { mostrarAlert("Ingresa un correo electrónico válido."); return; }
    if (!telefono || telefono.length < 7) { mostrarAlert("El teléfono debe tener al menos 7 caracteres."); return; }
    if (!password || password.length < 6) { mostrarAlert("La contraseña debe tener al menos 6 caracteres."); return; }
    if (password !== confirmarPassword) { mostrarAlert("Las contraseñas no coinciden."); return; }

    try {
      setCargando(true);
      const response = await fetch(`${API_BASE_URL}/v1/auth/registro`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ nombre_completo: nombreCompleto, correo_electronico: correo, telefono, password })
      });

      const data = await response.json();
      if (!response.ok) throw new Error(data.detail || "Error al registrar");

      setAlertMensaje("¡Tu cuenta fue creada correctamente! Inicia sesión para continuar.");
      setAlertVisible(true);

    } catch (error) {
      mostrarAlert(error.message || "No se pudo conectar con el servidor.");
    } finally {
      setCargando(false);
    }
  };

  return (
    <View style={styles.container}>
      <StatusBar barStyle="light-content" backgroundColor="#FF6B00" />
      <HeaderNaranjaVolver navigation={navigation} />

      <AlertPersonalizado
        visible={alertVisible}
        titulo={alertMensaje.includes("correctamente") ? "¡Cuenta creada!" : "Atención"}
        mensaje={alertMensaje}
        onAceptar={() => {
          setAlertVisible(false);
          if (alertMensaje.includes("correctamente")) navigation.replace('InicioSesionM');
        }}
      />

      <KeyboardAvoidingView behavior={Platform.OS === 'ios' ? 'padding' : 'height'} style={{ flex: 1 }}>
        <ScrollView contentContainerStyle={styles.scrollContent} showsVerticalScrollIndicator={false}>

          <View style={styles.titleContainer}>
            <Text style={styles.title}>Crear <Text style={styles.titleAccent}>Cuenta</Text></Text>
            <Text style={styles.subtitle}>Regístrate para comenzar a usar AYFEX</Text>
          </View>

          <View style={styles.formCard}>

            {[
              { label: 'Nombre completo', value: nombreCompleto, setter: setNombreCompleto, icon: 'person-outline', placeholder: 'Ej: Juan Pérez', keyboard: 'default' },
              { label: 'Correo electrónico', value: correo, setter: setCorreo, icon: 'mail-outline', placeholder: 'ejemplo@correo.com', keyboard: 'email-address', autoCapitalize: 'none' },
              { label: 'Teléfono', value: telefono, setter: setTelefono, icon: 'call-outline', placeholder: 'Ej: +52 123 456 7890', keyboard: 'phone-pad' },
            ].map(field => (
              <View key={field.label} style={styles.inputGroup}>
                <Text style={styles.inputLabel}>{field.label}</Text>
                <View style={styles.inputContainer}>
                  <Ionicons name={field.icon} size={18} color="#FF6B00" />
                  <TextInput
                    style={styles.input}
                    placeholder={field.placeholder}
                    placeholderTextColor="#BBBBBB"
                    keyboardType={field.keyboard}
                    autoCapitalize={field.autoCapitalize || 'sentences'}
                    value={field.value}
                    onChangeText={field.setter}
                  />
                </View>
              </View>
            ))}

            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>Contraseña</Text>
              <View style={styles.inputContainer}>
                <Ionicons name="lock-closed-outline" size={18} color="#FF6B00" />
                <TextInput
                  style={styles.input}
                  placeholder="Mínimo 6 caracteres"
                  placeholderTextColor="#BBBBBB"
                  secureTextEntry={!verPassword}
                  value={password}
                  onChangeText={setPassword}
                />
                <TouchableOpacity onPress={() => setVerPassword(!verPassword)}>
                  <Ionicons name={verPassword ? "eye-off-outline" : "eye-outline"} size={18} color="#BBBBBB" />
                </TouchableOpacity>
              </View>
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>Confirmar contraseña</Text>
              <View style={styles.inputContainer}>
                <Ionicons name="lock-closed-outline" size={18} color="#FF6B00" />
                <TextInput
                  style={styles.input}
                  placeholder="Repite tu contraseña"
                  placeholderTextColor="#BBBBBB"
                  secureTextEntry={!verConfirmar}
                  value={confirmarPassword}
                  onChangeText={setConfirmarPassword}
                />
                <TouchableOpacity onPress={() => setVerConfirmar(!verConfirmar)}>
                  <Ionicons name={verConfirmar ? "eye-off-outline" : "eye-outline"} size={18} color="#BBBBBB" />
                </TouchableOpacity>
              </View>
            </View>

            <View style={styles.termsContainer}>
              <Ionicons name="shield-checkmark-outline" size={18} color="#FF6B00" />
              <Text style={styles.termsText}>
                Al crear tu cuenta aceptas los{' '}
                <Text style={styles.termsLink}>Términos y Condiciones</Text> y la{' '}
                <Text style={styles.termsLink}>Política de Privacidad</Text>
              </Text>
            </View>

            <TouchableOpacity
              style={[styles.createButton, cargando && { opacity: 0.7 }]}
              onPress={handleCreateAccount}
              disabled={cargando}
            >
              <Text style={styles.createButtonText}>
                {cargando ? "Creando cuenta..." : "Crear Cuenta"}
              </Text>
            </TouchableOpacity>

          </View>

          <View style={{ height: 20 }} />
        </ScrollView>
      </KeyboardAvoidingView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#FFFFFF' },
  scrollContent: { flexGrow: 1, paddingHorizontal: 20, paddingTop: 20, paddingBottom: 20 },

  titleContainer: { marginBottom: 24 },
  title: { fontSize: 26, fontWeight: '800', color: '#000000', marginBottom: 6 },
  titleAccent: { color: '#FF6B00' },
  subtitle: { fontSize: 14, color: '#999999' },

  formCard: {
    backgroundColor: '#FFFFFF', borderRadius: 20, padding: 24,
    borderWidth: 1, borderColor: '#F0F0F0',
    shadowColor: '#000', shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.06, shadowRadius: 12, elevation: 4,
  },

  inputGroup: { marginBottom: 16 },
  inputLabel: { fontSize: 13, fontWeight: '600', color: '#444444', marginBottom: 8 },
  inputContainer: {
    flexDirection: 'row', alignItems: 'center',
    backgroundColor: '#F8F9FA', borderRadius: 14,
    paddingHorizontal: 14,
    paddingVertical: Platform.OS === 'ios' ? 14 : 10,
    borderWidth: 1, borderColor: '#EEEEEE',
  },
  input: { flex: 1, marginLeft: 10, fontSize: 14, color: '#333333' },

  termsContainer: { flexDirection: 'row', alignItems: 'flex-start', marginBottom: 20, marginTop: 4, gap: 8 },
  termsText: { flex: 1, fontSize: 12, color: '#888888', lineHeight: 18 },
  termsLink: { color: '#FF6B00', fontWeight: '600' },

  createButton: {
    backgroundColor: '#FF6B00', borderRadius: 14,
    paddingVertical: 16, alignItems: 'center',
    shadowColor: '#FF6B00', shadowOffset: { width: 0, height: 6 },
    shadowOpacity: 0.25, shadowRadius: 8, elevation: 5,
  },
  createButtonText: { color: '#FFFFFF', fontSize: 16, fontWeight: '700' },

  alertOverlay: { flex: 1, backgroundColor: 'rgba(0,0,0,0.45)', justifyContent: 'center', alignItems: 'center', paddingHorizontal: 32 },
  alertContainer: { backgroundColor: '#FFFFFF', borderRadius: 20, padding: 28, alignItems: 'center', width: '100%', shadowColor: '#000', shadowOffset: { width: 0, height: 8 }, shadowOpacity: 0.12, shadowRadius: 16, elevation: 10 },
  alertIconContainer: { width: 70, height: 70, borderRadius: 35, backgroundColor: '#FFF3E6', justifyContent: 'center', alignItems: 'center', marginBottom: 16 },
  alertTitulo: { fontSize: 18, fontWeight: '700', color: '#111111', marginBottom: 8, textAlign: 'center' },
  alertMensaje: { fontSize: 14, color: '#666666', textAlign: 'center', lineHeight: 20, marginBottom: 24 },
  alertBoton: { backgroundColor: '#FF6B00', borderRadius: 12, paddingVertical: 13, paddingHorizontal: 40 },
  alertBotonTexto: { color: '#FFFFFF', fontSize: 15, fontWeight: '700' },
});