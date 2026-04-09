import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
  StatusBar,
  Modal,
  TextInput,
  Alert,
  Platform,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import HeaderNaranja from '../Components/HeaderNaranja';
import { API_BASE_URL } from '../config';

export default function PerfilM({ navigation }) {

  const usuario = global.usuarioActual || {};
  const iniciales = usuario.nombre_completo
    ? usuario.nombre_completo.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase()
    : 'U';

  // --- ESTADO MODAL EDITAR ---
  const [modalEditarVisible, setModalEditarVisible] = useState(false);
  const [nombreEditar, setNombreEditar] = useState(usuario.nombre_completo || '');
  const [correoEditar, setCorreoEditar] = useState(usuario.correo_electronico || '');
  const [telefonoEditar, setTelefonoEditar] = useState(usuario.telefono || '');
  const [cargandoEditar, setCargandoEditar] = useState(false);

  // --- ESTADO MODAL REPORTE ---
  const [modalReporteVisible, setModalReporteVisible] = useState(false);
  const [reporteTipo, setReporteTipo] = useState('');
  const [reporteDescripcion, setReporteDescripcion] = useState('');
  const [reportePrioridad, setReportePrioridad] = useState('NORMAL');

  const prioridades = ['BAJA', 'NORMAL', 'ALTA', 'URGENTE'];

  const handleLogout = () => {
    global.authToken = null;
    global.usuarioActual = null;
    navigation.replace('InicioSesionM');
  };

  const guardarPerfil = async () => {
    if (!nombreEditar || nombreEditar.length < 3) {
      Alert.alert("Error", "El nombre debe tener al menos 3 caracteres");
      return;
    }
    if (!correoEditar || !correoEditar.includes('@')) {
      Alert.alert("Error", "Ingresa un correo válido");
      return;
    }
    if (!telefonoEditar || telefonoEditar.length < 7) {
      Alert.alert("Error", "El teléfono debe tener al menos 7 caracteres");
      return;
    }

    try {
      setCargandoEditar(true);

      const response = await fetch(`${API_BASE_URL}/v1/auth/perfil`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
          "Authorization": `Bearer ${global.authToken}`
        },
        body: JSON.stringify({
          nombre_completo: nombreEditar,
          correo_electronico: correoEditar,
          telefono: telefonoEditar,
        })
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.detail || "Error al actualizar");
      }

      global.usuarioActual = {
        ...global.usuarioActual,
        nombre_completo: nombreEditar,
        correo_electronico: correoEditar,
        telefono: telefonoEditar,
      };

      Alert.alert("¡Listo!", "Tu perfil fue actualizado correctamente");
      setModalEditarVisible(false);

    } catch (error) {
      Alert.alert("Error", error.message || "No se pudo actualizar el perfil");
    } finally {
      setCargandoEditar(false);
    }
  };

  return (
    <View style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#FFFFFF" />
      <HeaderNaranja />

      <ScrollView style={styles.content} showsVerticalScrollIndicator={false}>

        {/* AVATAR Y NOMBRE */}
        <View style={styles.profileHeader}>
          <View style={styles.avatar}>
            <Text style={styles.avatarText}>{iniciales}</Text>
          </View>
          <Text style={styles.profileName}>{usuario.nombre_completo || 'Usuario'}</Text>
          <Text style={styles.profileEmail}>{usuario.correo_electronico || ''}</Text>
        </View>

        {/* INFORMACIÓN PERSONAL */}
        <View style={styles.sectionHeader}>
          <Text style={styles.sectionTitle}>Información personal</Text>
          <TouchableOpacity
            style={styles.editButton}
            onPress={() => setModalEditarVisible(true)}
          >
            <Ionicons name="pencil-outline" size={16} color="#FF6B00" />
            <Text style={styles.editButtonText}>Editar</Text>
          </TouchableOpacity>
        </View>

        <View style={styles.infoCard}>
          <View style={styles.infoRow}>
            <View style={styles.infoIconContainer}>
              <Ionicons name="person-outline" size={18} color="#FF6B00" />
            </View>
            <View style={styles.infoContent}>
              <Text style={styles.infoLabel}>Nombre completo</Text>
              <Text style={styles.infoValue}>{usuario.nombre_completo || '—'}</Text>
            </View>
          </View>

          <View style={styles.infoDivider} />

          <View style={styles.infoRow}>
            <View style={styles.infoIconContainer}>
              <Ionicons name="mail-outline" size={18} color="#FF6B00" />
            </View>
            <View style={styles.infoContent}>
              <Text style={styles.infoLabel}>Correo electrónico</Text>
              <Text style={styles.infoValue}>{usuario.correo_electronico || '—'}</Text>
            </View>
          </View>

          <View style={styles.infoDivider} />

          <View style={styles.infoRow}>
            <View style={styles.infoIconContainer}>
              <Ionicons name="call-outline" size={18} color="#FF6B00" />
            </View>
            <View style={styles.infoContent}>
              <Text style={styles.infoLabel}>Teléfono</Text>
              <Text style={styles.infoValue}>{usuario.telefono || '—'}</Text>
            </View>
          </View>
        </View>

        {/* ACCIONES */}
        <Text style={[styles.sectionTitle, { marginBottom: 12 }]}>Acciones</Text>

        <TouchableOpacity
          style={styles.actionItem}
          onPress={() => setModalReporteVisible(true)}
        >
          <View style={styles.actionLeft}>
            <View style={[styles.actionIconContainer, { backgroundColor: '#FFF3E6' }]}>
              <Ionicons name="document-text-outline" size={22} color="#FF6B00" />
            </View>
            <View>
              <Text style={styles.actionText}>Generar reporte</Text>
              <Text style={styles.actionSubtext}>Reporta un problema o incidencia</Text>
            </View>
          </View>
          <Ionicons name="chevron-forward" size={20} color="#CCCCCC" />
        </TouchableOpacity>

        <TouchableOpacity style={styles.actionItem}>
          <View style={styles.actionLeft}>
            <View style={[styles.actionIconContainer, { backgroundColor: '#F0F8FF' }]}>
              <Ionicons name="help-circle-outline" size={22} color="#3A86FF" />
            </View>
            <View>
              <Text style={styles.actionText}>Ayuda y soporte</Text>
              <Text style={styles.actionSubtext}>Preguntas frecuentes</Text>
            </View>
          </View>
          <Ionicons name="chevron-forward" size={20} color="#CCCCCC" />
        </TouchableOpacity>

        {/* CERRAR SESIÓN */}
        <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
          <Ionicons name="log-out-outline" size={20} color="#FFFFFF" />
          <Text style={styles.logoutText}>Cerrar sesión</Text>
        </TouchableOpacity>

        <View style={{ height: 100 }} />
      </ScrollView>

      {/* MODAL EDITAR PERFIL */}
      <Modal visible={modalEditarVisible} animationType="slide" transparent>
        <View style={styles.modalOverlay}>
          <View style={styles.modalContainer}>

            <View style={styles.modalHeader}>
              <Text style={styles.modalTitle}>Editar perfil</Text>
              <TouchableOpacity onPress={() => setModalEditarVisible(false)}>
                <Ionicons name="close" size={24} color="#333" />
              </TouchableOpacity>
            </View>

            <Text style={styles.inputLabel}>Nombre completo</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="person-outline" size={18} color="#FF6B00" />
              <TextInput
                style={styles.input}
                value={nombreEditar}
                onChangeText={setNombreEditar}
                placeholder="Tu nombre completo"
                placeholderTextColor="#999"
              />
            </View>

            <Text style={styles.inputLabel}>Correo electrónico</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="mail-outline" size={18} color="#FF6B00" />
              <TextInput
                style={styles.input}
                value={correoEditar}
                onChangeText={setCorreoEditar}
                placeholder="Tu correo"
                placeholderTextColor="#999"
                keyboardType="email-address"
                autoCapitalize="none"
              />
            </View>

            <Text style={styles.inputLabel}>Teléfono</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="call-outline" size={18} color="#FF6B00" />
              <TextInput
                style={styles.input}
                value={telefonoEditar}
                onChangeText={setTelefonoEditar}
                placeholder="Tu teléfono"
                placeholderTextColor="#999"
                keyboardType="phone-pad"
              />
            </View>

            <TouchableOpacity
              style={[styles.saveButton, cargandoEditar && { opacity: 0.7 }]}
              onPress={guardarPerfil}
              disabled={cargandoEditar}
            >
              <Text style={styles.saveButtonText}>
                {cargandoEditar ? "Guardando..." : "Guardar cambios"}
              </Text>
            </TouchableOpacity>

            <TouchableOpacity
              style={styles.cancelButton}
              onPress={() => setModalEditarVisible(false)}
            >
              <Text style={styles.cancelButtonText}>Cancelar</Text>
            </TouchableOpacity>

          </View>
        </View>
      </Modal>

      {/* MODAL REPORTE */}
      <Modal visible={modalReporteVisible} animationType="slide" transparent>
        <View style={styles.modalOverlay}>
          <View style={styles.modalContainer}>

            <View style={styles.modalHeader}>
              <Text style={styles.modalTitle}>Generar reporte</Text>
              <TouchableOpacity onPress={() => setModalReporteVisible(false)}>
                <Ionicons name="close" size={24} color="#333" />
              </TouchableOpacity>
            </View>

            <Text style={styles.inputLabel}>Tipo de reporte</Text>
            <View style={styles.inputContainer}>
              <Ionicons name="alert-circle-outline" size={18} color="#FF6B00" />
              <TextInput
                style={styles.input}
                value={reporteTipo}
                onChangeText={setReporteTipo}
                placeholder="Ej: Pedido dañado, Entrega tardía..."
                placeholderTextColor="#999"
              />
            </View>

            <Text style={styles.inputLabel}>Descripción</Text>
            <View style={[styles.inputContainer, { alignItems: 'flex-start', minHeight: 100 }]}>
              <Ionicons name="document-text-outline" size={18} color="#FF6B00" style={{ marginTop: 2 }} />
              <TextInput
                style={[styles.input, { height: 80, textAlignVertical: 'top' }]}
                value={reporteDescripcion}
                onChangeText={setReporteDescripcion}
                placeholder="Describe el problema con detalle..."
                placeholderTextColor="#999"
                multiline
              />
            </View>

            <Text style={styles.inputLabel}>Prioridad</Text>
            <View style={styles.prioridadContainer}>
              {prioridades.map((p) => (
                <TouchableOpacity
                  key={p}
                  style={[
                    styles.prioridadChip,
                    reportePrioridad === p && styles.prioridadChipActivo
                  ]}
                  onPress={() => setReportePrioridad(p)}
                >
                  <Text style={[
                    styles.prioridadChipText,
                    reportePrioridad === p && styles.prioridadChipTextActivo
                  ]}>
                    {p}
                  </Text>
                </TouchableOpacity>
              ))}
            </View>

            <TouchableOpacity style={styles.saveButton}>
              <Text style={styles.saveButtonText}>Enviar reporte</Text>
            </TouchableOpacity>

            <TouchableOpacity
              style={styles.cancelButton}
              onPress={() => setModalReporteVisible(false)}
            >
              <Text style={styles.cancelButtonText}>Cancelar</Text>
            </TouchableOpacity>

          </View>
        </View>
      </Modal>

    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#FFFFFF' },
  content: { flex: 1, paddingHorizontal: 20 },

  profileHeader: {
    alignItems: 'center',
    paddingTop: 24,
    paddingBottom: 28,
  },
  avatar: {
    width: 90,
    height: 90,
    borderRadius: 45,
    backgroundColor: '#FF6B00',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 14,
    shadowColor: '#FF6B00',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 8,
    elevation: 6,
  },
  avatarText: { fontSize: 34, fontWeight: '700', color: '#FFFFFF' },
  profileName: { fontSize: 22, fontWeight: 'bold', color: '#000000', marginBottom: 4 },
  profileEmail: { fontSize: 13, color: '#999999' },

  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
  },
  sectionTitle: { fontSize: 16, fontWeight: '600', color: '#000000' },
  editButton: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#FFF3E6',
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 20,
    borderWidth: 1,
    borderColor: '#FF6B00',
  },
  editButtonText: { fontSize: 13, color: '#FF6B00', fontWeight: '600', marginLeft: 4 },

  infoCard: {
    backgroundColor: '#F8F9FA',
    borderRadius: 16,
    padding: 16,
    borderWidth: 1,
    borderColor: '#EEEEEE',
    marginBottom: 24,
  },
  infoRow: { flexDirection: 'row', alignItems: 'center', paddingVertical: 10 },
  infoIconContainer: {
    width: 36,
    height: 36,
    borderRadius: 10,
    backgroundColor: '#FFF3E6',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  infoContent: { flex: 1 },
  infoLabel: { fontSize: 12, color: '#999999', marginBottom: 2 },
  infoValue: { fontSize: 14, color: '#333333', fontWeight: '500' },
  infoDivider: { height: 1, backgroundColor: '#EEEEEE', marginVertical: 2 },

  actionItem: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    backgroundColor: '#F8F9FA',
    paddingVertical: 14,
    paddingHorizontal: 16,
    borderRadius: 14,
    marginBottom: 10,
    borderWidth: 1,
    borderColor: '#EEEEEE',
  },
  actionLeft: { flexDirection: 'row', alignItems: 'center' },
  actionIconContainer: {
    width: 42,
    height: 42,
    borderRadius: 12,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 14,
  },
  actionText: { fontSize: 15, color: '#333333', fontWeight: '500' },
  actionSubtext: { fontSize: 12, color: '#999999', marginTop: 2 },

  logoutButton: {
    backgroundColor: '#E53935',
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    paddingVertical: 16,
    borderRadius: 14,
    marginTop: 14,
    shadowColor: '#E53935',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.2,
    shadowRadius: 4,
    elevation: 4,
  },
  logoutText: { color: '#FFFFFF', fontSize: 16, fontWeight: '600', marginLeft: 8 },

  // MODALES
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.4)',
    justifyContent: 'flex-end',
  },
  modalContainer: {
    backgroundColor: '#FFFFFF',
    borderTopLeftRadius: 24,
    borderTopRightRadius: 24,
    padding: 24,
    paddingBottom: Platform.OS === 'ios' ? 40 : 24,
  },
  modalHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 20,
  },
  modalTitle: { fontSize: 20, fontWeight: 'bold', color: '#000000' },

  inputLabel: { fontSize: 13, color: '#666666', fontWeight: '500', marginBottom: 6, marginTop: 12 },
  inputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#F8F9FA',
    borderRadius: 12,
    paddingHorizontal: 12,
    paddingVertical: Platform.OS === 'ios' ? 12 : 8,
    borderWidth: 1,
    borderColor: '#EEEEEE',
  },
  input: { flex: 1, marginLeft: 8, fontSize: 14, color: '#333333' },

  prioridadContainer: {
    flexDirection: 'row',
    gap: 8,
    marginTop: 4,
    flexWrap: 'wrap',
  },
  prioridadChip: {
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 20,
    borderWidth: 1,
    borderColor: '#EEEEEE',
    backgroundColor: '#F8F9FA',
  },
  prioridadChipActivo: {
    backgroundColor: '#FF6B00',
    borderColor: '#FF6B00',
  },
  prioridadChipText: { fontSize: 13, color: '#666666', fontWeight: '500' },
  prioridadChipTextActivo: { color: '#FFFFFF' },

  saveButton: {
    backgroundColor: '#FF6B00',
    borderRadius: 12,
    paddingVertical: 16,
    alignItems: 'center',
    marginTop: 20,
    shadowColor: '#FF6B00',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.2,
    shadowRadius: 4,
    elevation: 4,
  },
  saveButtonText: { color: '#FFFFFF', fontSize: 16, fontWeight: '600' },
  cancelButton: { alignItems: 'center', paddingVertical: 14 },
  cancelButtonText: { fontSize: 15, color: '#999999' },
});