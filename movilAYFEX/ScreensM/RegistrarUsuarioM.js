import React, { useState } from 'react';
import {
    View,
    Text,
    StyleSheet,
    TouchableOpacity,
    TextInput,
    ScrollView,
    StatusBar,
    KeyboardAvoidingView,
    Platform,
    Alert,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import HeaderNaranjaVolver from '../Components/HeaderNaranjaVolver';
import { API_BASE_URL } from '../config';

export default function RegistrarUsuarioM({ navigation }) {

    const [nombreCompleto, setNombreCompleto] = useState('');
    const [correo, setCorreo] = useState('');
    const [telefono, setTelefono] = useState('');
    const [password, setPassword] = useState('');
    const [confirmarPassword, setConfirmarPassword] = useState('');
    const [cargando, setCargando] = useState(false);

    const handleCreateAccount = async () => {

        if (!nombreCompleto || nombreCompleto.length < 3) {
            Alert.alert("Error", "El nombre debe tener al menos 3 caracteres");
            return;
        }

        if (!correo || !correo.includes('@')) {
            Alert.alert("Error", "Ingresa un correo válido");
            return;
        }

        if (!telefono || telefono.length < 7) {
            Alert.alert("Error", "El teléfono debe tener al menos 7 caracteres");
            return;
        }

        if (!password || password.length < 6) {
            Alert.alert("Error", "La contraseña debe tener al menos 6 caracteres");
            return;
        }

        if (password !== confirmarPassword) {
            Alert.alert("Error", "Las contraseñas no coinciden");
            return;
        }

        try {
            setCargando(true);

            const response = await fetch(`${API_BASE_URL}/v1/auth/registro`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    nombre_completo: nombreCompleto,
                    correo_electronico: correo,
                    telefono: telefono,
                    password: password
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.detail || "Error al registrar");
            }

            Alert.alert(
                "¡Cuenta creada!",
                "Tu cuenta fue registrada correctamente. Inicia sesión para continuar.",
                [{ text: "OK", onPress: () => navigation.replace('InicioSesionM') }]
            );

        } catch (error) {
            Alert.alert("Error", error.message || "No se pudo conectar con el servidor");
        } finally {
            setCargando(false);
        }
    };

    return (
        <View style={styles.container}>
            <StatusBar barStyle="light-content" backgroundColor="#FF6B00" />

            <HeaderNaranjaVolver navigation={navigation} />

            <KeyboardAvoidingView
                behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
                style={styles.keyboardView}
            >
                <ScrollView
                    contentContainerStyle={styles.scrollContent}
                    showsVerticalScrollIndicator={false}
                >
                    <View style={styles.titleContainer}>
                        <Text style={styles.title}>Crear Cuenta</Text>
                        <Text style={styles.subtitle}>Regístrate para comenzar a usar AYFEX</Text>
                    </View>

                    <View style={styles.formContainer}>

                        <View style={styles.inputGroup}>
                            <Text style={styles.inputLabel}>Nombre completo</Text>
                            <View style={styles.inputContainer}>
                                <Ionicons name="person-outline" size={20} color="#FF6B00" />
                                <TextInput
                                    style={styles.input}
                                    placeholder="Ej: Juan Pérez"
                                    placeholderTextColor="#999999"
                                    value={nombreCompleto}
                                    onChangeText={setNombreCompleto}
                                />
                            </View>
                        </View>

                        <View style={styles.inputGroup}>
                            <Text style={styles.inputLabel}>Correo electrónico</Text>
                            <View style={styles.inputContainer}>
                                <Ionicons name="mail-outline" size={20} color="#FF6B00" />
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
                            <Text style={styles.inputLabel}>Teléfono</Text>
                            <View style={styles.inputContainer}>
                                <Ionicons name="call-outline" size={20} color="#FF6B00" />
                                <TextInput
                                    style={styles.input}
                                    placeholder="Ej: +52 123 456 7890"
                                    placeholderTextColor="#999999"
                                    keyboardType="phone-pad"
                                    value={telefono}
                                    onChangeText={setTelefono}
                                />
                            </View>
                        </View>

                        <View style={styles.inputGroup}>
                            <Text style={styles.inputLabel}>Contraseña</Text>
                            <View style={styles.inputContainer}>
                                <Ionicons name="lock-closed-outline" size={20} color="#FF6B00" />
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

                        <View style={styles.inputGroup}>
                            <Text style={styles.inputLabel}>Confirmar contraseña</Text>
                            <View style={styles.inputContainer}>
                                <Ionicons name="lock-closed-outline" size={20} color="#FF6B00" />
                                <TextInput
                                    style={styles.input}
                                    placeholder="••••••••"
                                    placeholderTextColor="#999999"
                                    secureTextEntry
                                    value={confirmarPassword}
                                    onChangeText={setConfirmarPassword}
                                />
                            </View>
                        </View>

                        <View style={styles.termsContainer}>
                            <Ionicons name="checkbox-outline" size={20} color="#FF6B00" />
                            <Text style={styles.termsText}>
                                Acepto los <Text style={styles.termsLink}>Términos y Condiciones</Text> y la{' '}
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

                    <View style={styles.bottomPadding} />
                </ScrollView>
            </KeyboardAvoidingView>
        </View>
    );
}

const styles = StyleSheet.create({
    container: { flex: 1, backgroundColor: '#FFFFFF' },
    keyboardView: { flex: 1 },
    scrollContent: { flexGrow: 1, paddingHorizontal: 20, paddingTop: 20, paddingBottom: 20 },
    titleContainer: { marginBottom: 24 },
    title: { fontSize: 24, fontWeight: 'bold', color: '#FF6B00', marginBottom: 8 },
    subtitle: { fontSize: 14, color: '#666666' },
    formContainer: { width: '100%' },
    inputGroup: { marginBottom: 16 },
    inputLabel: { fontSize: 14, fontWeight: '600', color: '#333333', marginBottom: 8 },
    inputContainer: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#FFF3E6',
        borderRadius: 12,
        paddingHorizontal: 12,
        paddingVertical: Platform.OS === 'ios' ? 12 : 8,
        borderWidth: 1,
        borderColor: '#FF6B00',
    },
    input: { flex: 1, marginLeft: 8, fontSize: 14, color: '#333333' },
    termsContainer: { flexDirection: 'row', alignItems: 'center', marginBottom: 24, marginTop: 8 },
    termsText: { flex: 1, fontSize: 13, color: '#666666', marginLeft: 8, lineHeight: 18 },
    termsLink: { color: '#FF6B00', fontWeight: '500' },
    createButton: {
        backgroundColor: '#FF6B00',
        borderRadius: 12,
        paddingVertical: 16,
        alignItems: 'center',
        marginBottom: 16,
        shadowColor: '#FF6B00',
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.2,
        shadowRadius: 4,
        elevation: 4,
    },
    createButtonText: { color: '#FFFFFF', fontSize: 16, fontWeight: '600' },
    bottomPadding: { height: 20 },
});