import React from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
  StatusBar,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import HeaderNaranja from '../Components/HeaderNaranja';

export default function PerfilM({ navigation }) {

  const handleLogout = () => {
    navigation.replace('InicioSesionM');
  };

  return (
    <View style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#FFFFFF" />

      <HeaderNaranja />

      <ScrollView style={styles.content} showsVerticalScrollIndicator={false}>
        <View style={styles.profileHeader}>
          <View style={styles.avatarContainer}>
            <View style={styles.avatar}>
              <Text style={styles.avatarText}>FJ</Text>
            </View>
          </View>
          <Text style={styles.profileName}>Fidel Juárez</Text>
        </View>

        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Información personal</Text>

          <View style={styles.infoCard}>
            <View style={styles.infoItem}>
              <Text style={styles.infoLabel}>Nombre completo</Text>
              <Text style={styles.infoValue}>Juan Pérez</Text>
            </View>

            <View style={styles.infoDivider} />

            <View style={styles.infoItem}>
              <Text style={styles.infoLabel}>Correo electrónico</Text>
              <Text style={styles.infoValue}>juan.perez@email.com</Text>
            </View>

            <View style={styles.infoDivider} />

            <View style={styles.infoItem}>
              <Text style={styles.infoLabel}>Teléfono</Text>
              <Text style={styles.infoValue}>+34 612 345 679</Text>
            </View>

            <View style={styles.infoDivider} />

            <View style={styles.infoItem}>
              <Text style={styles.infoLabel}>Dirección</Text>
              <Text style={styles.infoValue}>
                Díaz Mirón #1203 Col. Lázaro Cárdenas.{'\n'}
                Cd. Madero, Tamaulipas
              </Text>
            </View>
          </View>
        </View>

        <View style={styles.section}>
          <View style={styles.sectionHeader}>
            <Text style={styles.sectionTitle}>Billetera</Text>
            <Text style={styles.sectionSubtitle}>Tarjetas y cuentas</Text>
          </View>

          <View style={styles.cardsContainer}>
            <View style={styles.cardItem}>
              <View style={styles.cardIconContainer}>
                <Ionicons name="card-outline" size={24} color="#FF6B00" />
              </View>
              <View style={styles.cardInfo}>
                <Text style={styles.cardType}>Visa</Text>
                <Text style={styles.cardDetails}>
                  Tarjeta de débito con terminación en 2003.
                </Text>
              </View>
              <Ionicons name="chevron-forward" size={20} color="#CCCCCC" />
            </View>

            <View style={styles.cardItem}>
              <View style={styles.cardIconContainer}>
                <Ionicons name="card-outline" size={24} color="#FF6B00" />
              </View>
              <View style={styles.cardInfo}>
                <Text style={styles.cardType}>Visa</Text>
                <Text style={styles.cardDetails}>
                  Tarjeta de débito con terminación en 2010.
                </Text>
              </View>
              <Ionicons name="chevron-forward" size={20} color="#CCCCCC" />
            </View>

            <TouchableOpacity style={styles.addCardButton}>
              <View style={styles.addCardIconContainer}>
                <Ionicons name="add-circle-outline" size={24} color="#FF6B00" />
              </View>
              <Text style={styles.addCardText}>Agregar tarjeta</Text>
            </TouchableOpacity>
          </View>
        </View>

        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Opciones</Text>

          <TouchableOpacity style={styles.optionItem}>
            <View style={styles.optionLeft}>
              <Ionicons name="help-circle-outline" size={22} color="#666666" />
              <Text style={styles.optionText}>Ayuda</Text>
            </View>
            <Ionicons name="chevron-forward" size={20} color="#CCCCCC" />
          </TouchableOpacity>

          <TouchableOpacity style={styles.optionItem}>
            <View style={styles.optionLeft}>
              <Ionicons name="settings-outline" size={22} color="#666666" />
              <Text style={styles.optionText}>Configuración</Text>
            </View>
            <Ionicons name="chevron-forward" size={20} color="#CCCCCC" />
          </TouchableOpacity>
        </View>
        <View style={styles.section}>
          <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
            <Ionicons name="log-out-outline" size={20} color="#FFFFFF" />
            <Text style={styles.logoutText}>Cerrar sesión</Text>
          </TouchableOpacity>
        </View>
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
  profileHeader: {
    alignItems: 'center',
    marginTop: 20,
    marginBottom: 24,
  },
  avatarContainer: {
    marginBottom: 12,
  },
  avatar: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: '#FF6B00',
    justifyContent: 'center',
    alignItems: 'center',
  },
  avatarText: {
    fontSize: 32,
    fontWeight: '600',
    color: '#FFFFFF',
  },
  profileName: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#000000',
  },
  section: {
    marginBottom: 24,
  },
  sectionHeader: {
    marginBottom: 12,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: '600',
    color: '#000000',
    marginBottom: 4,
  },
  sectionSubtitle: {
    fontSize: 14,
    color: '#666666',
  },
  infoCard: {
    backgroundColor: '#F8F9FA',
    borderRadius: 12,
    padding: 16,
    borderWidth: 1,
    borderColor: '#EEEEEE',
  },
  infoItem: {
    paddingVertical: 8,
  },
  infoLabel: {
    fontSize: 12,
    color: '#999999',
    marginBottom: 2,
  },
  infoValue: {
    fontSize: 14,
    color: '#333333',
    fontWeight: '500',
  },
  infoDivider: {
    height: 1,
    backgroundColor: '#EEEEEE',
    marginVertical: 4,
  },
  cardsContainer: {
    backgroundColor: '#F8F9FA',
    borderRadius: 12,
    padding: 8,
    borderWidth: 1,
    borderColor: '#EEEEEE',
  },
  cardItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 12,
    paddingHorizontal: 8,
    borderBottomWidth: 1,
    borderBottomColor: '#EEEEEE',
  },
  cardIconContainer: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: '#ff6a002d',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  cardInfo: {
    flex: 1,
  },
  cardType: {
    fontSize: 16,
    fontWeight: '500',
    color: '#333333',
    marginBottom: 2,
  },
  cardDetails: {
    fontSize: 12,
    color: '#666666',
  },
  addCardButton: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 12,
    paddingHorizontal: 8,
  },
  addCardIconContainer: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: '#ff6a002d',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  addCardText: {
    fontSize: 16,
    fontWeight: '500',
    color: '#FF6B00',
  },
  optionItem: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    backgroundColor: '#F8F9FA',
    paddingVertical: 16,
    paddingHorizontal: 16,
    borderRadius: 12,
    marginBottom: 8,
    borderWidth: 1,
    borderColor: '#EEEEEE',
  },
  optionLeft: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  optionText: {
    fontSize: 16,
    color: '#333333',
    marginLeft: 12,
  },
  bottomPadding: {
    height: 20,
  },
  logoutButton: {
  backgroundColor: '#E53935',
  flexDirection: 'row',
  justifyContent: 'center',
  alignItems: 'center',
  paddingVertical: 16,
  borderRadius: 12,
  marginTop: 10,
  shadowColor: '#E53935',
  shadowOffset: {
    width: 0,
    height: 4,
  },
  shadowOpacity: 0.2,
  shadowRadius: 4,
  elevation: 4,
},

logoutText: {
  color: '#FFFFFF',
  fontSize: 16,
  fontWeight: '600',
  marginLeft: 8,
},
});