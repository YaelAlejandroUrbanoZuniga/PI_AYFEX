import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  SafeAreaView,
  TouchableOpacity,
  TextInput,
  ScrollView,
  StatusBar,
  KeyboardAvoidingView,
  Platform,
  Image,
  Alert,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { API_BASE_URL } from '../config';

export default function InicioSesionM({ navigation }) {

  const [correo, setCorreo] = useState('');
  const [password, setPassword] = useState('');
  const [cargando, setCargando] = useState(false);

  const handleLogin = async () => {

    if (!correo || !correo.includes('@')) {
      Alert.alert("Error", "Ingresa un correo válido");
      return;
    }

    if (!password) {
      Alert.alert("Error", "Ingresa tu contraseña");
      return;
    }

    try {
      setCargando(true);

      const response = await fetch(`${API_BASE_URL}/v1/auth/login`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          correo_electronico: correo,
          password: password
        })
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.detail || "Correo o contraseña incorrectos");
      }

      // Guardamos el token y datos del usuario para usarlos en toda la app
      global.authToken = data.access_token;
      global.usuarioActual = data.usuario;

      navigation.replace('PrincipalM');

    } catch (error) {
      Alert.alert("Error", error.message || "No se pudo conectar con el servidor");
    } finally {
      setCargando(false);
    }
  };

  const handleRegister = () => {
    navigation.navigate('RegistrarUsuarioM');
  };

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#FFFFFF" />

      <KeyboardAvoidingView
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
        style={styles.keyboardView}
      >
        <ScrollView
          contentContainerStyle={styles.scrollContent}
          showsVerticalScrollIndicator={false}
        >
          <View style={styles.welcomeContainer}>
            <View style={styles.logoContainer}>
              <Image
                source={require('../assets/logo.png')}
                style={styles.logo}
              />
            </View>
            <Text style={styles.welcomeTitle}>Bienvenido</Text>
            <Text style={styles.welcomeSubtitle}>Inicia sesión para continuar</Text>
          </View>

          <View style={styles.formContainer}>

            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>Correo electrónico</Text>
              <View style={styles.inputContainer}>
                <Ionicons name="mail-outline" size={20} color="#999999" />
                <TextInput
                  style={styles.input}
                  placeholder="ejemplo@correo.com"
                  placeholderTextColor="#999999"
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
                <Ionicons name="lock-closed-outline" size={20} color="#999999" />
                <TextInput
                  style={styles.input}
                  placeholder="••••••••"
                  placeholderTextColor="#999999"
                  secureTextEntry
                  value={password}
                  onChangeText={setPassword}
                />
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
              <TouchableOpacity onPress={handleRegister}>
                <Text style={styles.registerLink}>Regístrate</Text>
              </TouchableOpacity>
            </View>

          </View>

          <View style={styles.bottomPadding} />
        </ScrollView>
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#FFFFFF' },
  keyboardView: { flex: 1 },
  scrollContent: { flexGrow: 1, paddingHorizontal: 20, paddingTop: 20, paddingBottom: 20 },
  welcomeContainer: { alignItems: 'center', marginBottom: 40 },
  logoContainer: {
    width: 160, height: 160, borderRadius: 80,
    backgroundColor: '#ffffff', justifyContent: 'center', alignItems: 'center',
    marginBottom: 20, shadowColor: '#FF6B00',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.1, shadowRadius: 8, elevation: 4,
  },
  logo: { width: 110, height: 110, resizeMode: 'contain' },
  welcomeTitle: { fontSize: 24, fontWeight: 'bold', color: '#000000', marginBottom: 8 },
  welcomeSubtitle: { fontSize: 14, color: '#666666' },
  formContainer: { width: '100%' },
  inputGroup: { marginBottom: 20 },
  inputLabel: { fontSize: 14, fontWeight: '600', color: '#333333', marginBottom: 8 },
  inputContainer: {
    flexDirection: 'row', alignItems: 'center',
    backgroundColor: '#F8F9FA', borderRadius: 12,
    paddingHorizontal: 12,
    paddingVertical: Platform.OS === 'ios' ? 12 : 8,
    borderWidth: 1, borderColor: '#EEEEEE',
  },
  input: { flex: 1, marginLeft: 8, fontSize: 14, color: '#333333' },
  forgotPassword: { alignSelf: 'flex-end', marginBottom: 24 },
  forgotPasswordText: { fontSize: 12, color: '#FF6B00', fontWeight: '500' },
  loginButton: {
    backgroundColor: '#FF6B00', borderRadius: 12,
    paddingVertical: 16, alignItems: 'center', marginBottom: 20,
    shadowColor: '#FF6B00', shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.2, shadowRadius: 4, elevation: 4,
  },
  loginButtonText: { color: '#FFFFFF', fontSize: 16, fontWeight: '600' },
  separatorContainer: { flexDirection: 'row', alignItems: 'center', marginBottom: 20 },
  separatorLine: { flex: 1, height: 1, backgroundColor: '#F0F0F0' },
  separatorText: { marginHorizontal: 16, fontSize: 14, color: '#999999' },
  registerContainer: { flexDirection: 'row', justifyContent: 'center', alignItems: 'center' },
  registerText: { fontSize: 14, color: '#666666' },
  registerLink: { fontSize: 14, color: '#FF6B00', fontWeight: '600' },
  bottomPadding: { height: 20 },
});