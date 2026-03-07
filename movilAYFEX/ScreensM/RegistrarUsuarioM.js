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
    KeyboardAvoidingView,
    Platform,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import HeaderNaranjaVolver from '../Components/HeaderNaranjaVolver';

export default function RegistrarUsuarioM({ navigation }) {
    const handleCreateAccount = () => {
        navigation.replace('InicioSesionM');
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
                                />
                            </View>
                        </View>

                        <View style={styles.inputGroup}>
                            <Text style={styles.inputLabel}>Dirección</Text>
                            <View style={[styles.inputContainer, styles.textAreaContainer]}>
                                <Ionicons name="location-outline" size={20} color="#FF6B00" style={styles.textAreaIcon} />
                                <TextInput
                                    style={[styles.input, styles.textArea]}
                                    placeholder="Ej: Díaz Mirón #1203 Col. Lázaro Cárdenas, Cd. Madero, Tamaulipas"
                                    placeholderTextColor="#999999"
                                    multiline
                                    numberOfLines={3}
                                    textAlignVertical="top"
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

                        <TouchableOpacity style={styles.createButton} onPress={handleCreateAccount}>
                            <Text style={styles.createButtonText}>Crear Cuenta</Text>
                        </TouchableOpacity>
                    </View>

                    <View style={styles.bottomPadding} />
                </ScrollView>
            </KeyboardAvoidingView>
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#FFFFFF',
    },
    keyboardView: {
        flex: 1,
    },
    scrollContent: {
        flexGrow: 1,
        paddingHorizontal: 20,
        paddingTop: 20,
        paddingBottom: 20,
    },
    titleContainer: {
        marginBottom: 24,
    },
    title: {
        fontSize: 24,
        fontWeight: 'bold',
        color: '#FF6B00',
        marginBottom: 8,
    },
    subtitle: {
        fontSize: 14,
        color: '#666666',
    },
    formContainer: {
        width: '100%',
    },
    inputGroup: {
        marginBottom: 16,
    },
    inputLabel: {
        fontSize: 14,
        fontWeight: '600',
        color: '#333333',
        marginBottom: 8,
    },
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
    input: {
        flex: 1,
        marginLeft: 8,
        fontSize: 14,
        color: '#333333',
    },
    textAreaContainer: {
        alignItems: 'flex-start',
        minHeight: 80,
    },
    textAreaIcon: {
        marginTop: 2,
    },
    textArea: {
        height: 60,
        textAlignVertical: 'top',
    },
    termsContainer: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 24,
        marginTop: 8,
    },
    termsText: {
        flex: 1,
        fontSize: 13,
        color: '#666666',
        marginLeft: 8,
        lineHeight: 18,
    },
    termsLink: {
        color: '#FF6B00',
        fontWeight: '500',
    },
    createButton: {
        backgroundColor: '#FF6B00',
        borderRadius: 12,
        paddingVertical: 16,
        alignItems: 'center',
        marginBottom: 16,
        shadowColor: '#FF6B00',
        shadowOffset: {
            width: 0,
            height: 4,
        },
        shadowOpacity: 0.2,
        shadowRadius: 4,
        elevation: 4,
    },
    createButtonText: {
        color: '#FFFFFF',
        fontSize: 16,
        fontWeight: '600',
    },
    bottomPadding: {
        height: 20,
    },
});