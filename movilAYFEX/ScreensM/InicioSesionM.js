import React, { useState } from 'react';
import {
  View, Text, StyleSheet, SafeAreaView,
  TouchableOpacity, TextInput, ScrollView,
  StatusBar, KeyboardAvoidingView, Platform,
  Image, Modal,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
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

export default function InicioSesionM({ navigation }) {

  const [correo, setCorreo] = useState('');
  const [password, setPassword] = useState('');
  const [cargando, setCargando] = useState(false);
  const [verPassword, setVerPassword] = useState(false);
  const [alertVisible, setAlertVisible] = useState(false);
  const [alertMensaje, setAlertMensaje] = useState('');

  const mostrarAlert = (mensaje) => {
    setAlertMensaje(mensaje);
    setAlertVisible(true);
  };

  const handleLogin = async () => {
    if (!correo || !correo.includes('@')) {
      mostrarAlert("Ingresa un correo electrónico válido.");
      return;
    }
    if (!password) {
      mostrarAlert("Ingresa tu contraseña.");
      return;
    }

    try {
      setCargando(true);
      const response = await fetch(`${API_BASE_URL}/v1/auth/login`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ correo_electronico: correo, password })
      });

      const data = await response.json();
      if (!response.ok) throw new Error(data.detail || "Correo o contraseña incorrectos");

      global.authToken = data.access_token;
      global.usuarioActual = data.usuario;
      navigation.replace('PrincipalM');

    } catch (error) {
      mostrarAlert(error.message || "No se pudo conectar con el servidor.");
    } finally {
      setCargando(false);
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#FFFFFF" />

      <AlertPersonalizado
        visible={alertVisible}
        titulo="Atención"
        mensaje={alertMensaje}
        onAceptar={() => setAlertVisible(false)}
      />

      <KeyboardAvoidingView behavior={Platform.OS === 'ios' ? 'padding' : 'height'} style={{ flex: 1 }}>
        <ScrollView contentContainerStyle={styles.scrollContent} showsVerticalScrollIndicator={false}>

          <View style={styles.topSection}>
            <View style={styles.logoContainer}>
              <Image source={require('../assets/logo.png')} style={styles.logo} />
            </View>
            <Text style={styles.welcomeTitle}>Bienvenido</Text>
            <Text style={styles.welcomeSubtitle}>Inicia sesión para continuar</Text>
          </View>

          <View style={styles.formCard}>

            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>Correo electrónico</Text>
              <View style={styles.inputContainer}>
                <Ionicons name="mail-outline" size={18} color="#FF6B00" />
                <TextInput
                  style={styles.input}
                  placeholder="ejemplo@correo.com"
                  placeholderTextColor="#BBBBBB"
                  keyboardType="email-address"
                  autoCapitalize="none"
                  value={correo}
                  onChangeText={setCorreo}
                />
              </View>
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>Contraseña</Text>
              <View style={styles.inputContainer}>
                <Ionicons name="lock-closed-outline" size={18} color="#FF6B00" />
                <TextInput
                  style={styles.input}
                  placeholder="••••••••"
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

            <TouchableOpacity style={styles.forgotPassword}>
              <Text style={styles.forgotPasswordText}>¿Olvidaste tu contraseña?</Text>
            </TouchableOpacity>

            <TouchableOpacity
              style={[styles.loginButton, cargando && { opacity: 0.7 }]}
              onPress={handleLogin}
              disabled={cargando}
            >
              <Text style={styles.loginButtonText}>
                {cargando ? "Iniciando sesión..." : "Iniciar Sesión"}
              </Text>
            </TouchableOpacity>

            <View style={styles.separatorContainer}>
              <View style={styles.separatorLine} />
              <Text style={styles.separatorText}>O</Text>
              <View style={styles.separatorLine} />
            </View>

            <View style={styles.registerContainer}>
              <Text style={styles.registerText}>¿No tienes una cuenta? </Text>
              <TouchableOpacity onPress={() => navigation.navigate('RegistrarUsuarioM')}>
                <Text style={styles.registerLink}>Regístrate</Text>
              </TouchableOpacity>
            </View>

          </View>

          <View style={{ height: 20 }} />
        </ScrollView>
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#FFFFFF' },
  scrollContent: { flexGrow: 1, paddingHorizontal: 20, paddingTop: 20, paddingBottom: 20 },

  topSection: { alignItems: 'center', marginBottom: 32 },
  logoContainer: {
    width: 140, height: 140, borderRadius: 70,
    backgroundColor: '#FFFFFF', justifyContent: 'center', alignItems: 'center',
    marginBottom: 20,
    shadowColor: '#FF6B00', shadowOffset: { width: 0, height: 6 },
    shadowOpacity: 0.12, shadowRadius: 12, elevation: 6,
  },
  logo: { width: 100, height: 100, resizeMode: 'contain' },
  welcomeTitle: { fontSize: 26, fontWeight: '800', color: '#000000', marginBottom: 6 },
  welcomeSubtitle: { fontSize: 14, color: '#999999' },

  formCard: {
    backgroundColor: '#FFFFFF',
    borderRadius: 20, padding: 24,
    borderWidth: 1, borderColor: '#F0F0F0',
    shadowColor: '#000', shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.06, shadowRadius: 12, elevation: 4,
  },

  inputGroup: { marginBottom: 18 },
  inputLabel: { fontSize: 13, fontWeight: '600', color: '#444444', marginBottom: 8 },
  inputContainer: {
    flexDirection: 'row', alignItems: 'center',
    backgroundColor: '#F8F9FA', borderRadius: 14,
    paddingHorizontal: 14,
    paddingVertical: Platform.OS === 'ios' ? 14 : 10,
    borderWidth: 1, borderColor: '#EEEEEE',
  },
  input: { flex: 1, marginLeft: 10, fontSize: 14, color: '#333333' },

  forgotPassword: { alignSelf: 'flex-end', marginBottom: 20, marginTop: -4 },
  forgotPasswordText: { fontSize: 13, color: '#FF6B00', fontWeight: '600' },

  loginButton: {
    backgroundColor: '#FF6B00', borderRadius: 14,
    paddingVertical: 16, alignItems: 'center', marginBottom: 20,
    shadowColor: '#FF6B00', shadowOffset: { width: 0, height: 6 },
    shadowOpacity: 0.25, shadowRadius: 8, elevation: 5,
  },
  loginButtonText: { color: '#FFFFFF', fontSize: 16, fontWeight: '700' },

  separatorContainer: { flexDirection: 'row', alignItems: 'center', marginBottom: 20 },
  separatorLine: { flex: 1, height: 1, backgroundColor: '#EEEEEE' },
  separatorText: { marginHorizontal: 14, fontSize: 13, color: '#CCCCCC', fontWeight: '500' },

  registerContainer: { flexDirection: 'row', justifyContent: 'center', alignItems: 'center' },
  registerText: { fontSize: 14, color: '#888888' },
  registerLink: { fontSize: 14, color: '#FF6B00', fontWeight: '700' },

  alertOverlay: { flex: 1, backgroundColor: 'rgba(0,0,0,0.45)', justifyContent: 'center', alignItems: 'center', paddingHorizontal: 32 },
  alertContainer: { backgroundColor: '#FFFFFF', borderRadius: 20, padding: 28, alignItems: 'center', width: '100%', shadowColor: '#000', shadowOffset: { width: 0, height: 8 }, shadowOpacity: 0.12, shadowRadius: 16, elevation: 10 },
  alertIconContainer: { width: 70, height: 70, borderRadius: 35, backgroundColor: '#FFF3E6', justifyContent: 'center', alignItems: 'center', marginBottom: 16 },
  alertTitulo: { fontSize: 18, fontWeight: '700', color: '#111111', marginBottom: 8, textAlign: 'center' },
  alertMensaje: { fontSize: 14, color: '#666666', textAlign: 'center', lineHeight: 20, marginBottom: 24 },
  alertBoton: { backgroundColor: '#FF6B00', borderRadius: 12, paddingVertical: 13, paddingHorizontal: 40 },
  alertBotonTexto: { color: '#FFFFFF', fontSize: 15, fontWeight: '700' },
});