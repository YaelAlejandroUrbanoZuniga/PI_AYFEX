import React from 'react';
import {
  View,
  Text,
  StyleSheet,
  SafeAreaView,
  TouchableOpacity,
  ScrollView,
  StatusBar,
  Linking,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';

export default function PedidosM_Detalles({ navigation, route }) {
  const { pedidoData } = route.params;

  const handleDownloadPDF = () => {
    console.log('Descargando factura...');
  };

  const handleEvaluateSeller = () => {
    console.log('Evaluar al vendedor...');
  };

  const handleCancelOrder = () => {
    console.log('Cancelar pedido...');
  };

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#FFFFFF" />
      
      <View style={styles.header}>
        <View style={styles.headerLeft}>
        </View>
        <View style={styles.headerCenter}>
          <Text style={styles.headerTitle}>AYFEX</Text>
        </View>
        <View style={styles.headerRight}>
          <Text style={styles.headerGreeting}>Hola, Fidel!</Text>
        </View>
      </View>

      <ScrollView style={styles.content} showsVerticalScrollIndicator={false}>
        <Text style={styles.screenTitle}>Mis Pedidos</Text>
        
        <Text style={styles.orderId}>{pedidoData.id}</Text>

        <TouchableOpacity style={styles.downloadButton} onPress={handleDownloadPDF}>
          <Ionicons name="document-text-outline" size={20} color="#FF6B00" />
          <Text style={styles.downloadButtonText}>Descargar Factura (PDF)</Text>
        </TouchableOpacity>

        <View style={styles.separator} />

        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Enviar a</Text>
          <View style={styles.infoCard}>
            <Text style={styles.recipientName}>{pedidoData.destinatario}</Text>
            <Text style={styles.addressText}>{pedidoData.direccion}</Text>
          </View>
        </View>

        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Método de pago</Text>
          <View style={styles.paymentCard}>
            <View style={styles.paymentIconContainer}>
              <Ionicons name="card-outline" size={24} color="#FF6B00" />
            </View>
            <Text style={styles.paymentText}>{pedidoData.metodoPago}</Text>
          </View>
        </View>

        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Resumen del pedido</Text>
          <View style={styles.summaryCard}>
            <View style={styles.summaryRow}>
              <Text style={styles.summaryLabel}>Pedidos:</Text>
              <Text style={styles.summaryValue}>${pedidoData.pedidosTotal.toFixed(2)}</Text>
            </View>

            {pedidoData.productos.map((producto, index) => (
              <View key={index} style={styles.productRow}>
                <Text style={styles.productQuantity}>x{producto.cantidad}</Text>
                <Text style={styles.productName}>{producto.nombre}</Text>
                {producto.precio > 0 && (
                  <Text style={styles.productPrice}>${producto.precio.toFixed(2)}</Text>
                )}
              </View>
            ))}

            <View style={styles.summaryRow}>
              <Text style={styles.summaryLabel}>Envío:</Text>
              <Text style={styles.summaryValue}>${pedidoData.envio.toFixed(2)}</Text>
            </View>

            <View style={styles.divider} />

            <View style={styles.summaryRow}>
              <Text style={styles.summaryLabel}>Subtotal:</Text>
              <Text style={styles.summaryValue}>${pedidoData.subtotal.toFixed(2)}</Text>
            </View>

            <View style={styles.totalRow}>
              <Text style={styles.totalLabel}>Total (IVA incluido, en caso de ser aplicable):</Text>
              <Text style={styles.totalValue}>${pedidoData.total.toFixed(2)}</Text>
            </View>
          </View>
        </View>

        <View style={styles.actionButtonsContainer}>
          <TouchableOpacity style={styles.evaluateButton} onPress={handleEvaluateSeller}>
            <Text style={styles.evaluateButtonText}>Evaluar al vendedor</Text>
          </TouchableOpacity>

          <TouchableOpacity style={styles.cancelButton} onPress={handleCancelOrder}>
            <Text style={styles.cancelButtonText}>Cancelar Pedido</Text>
          </TouchableOpacity>
        </View>

        <View style={styles.bottomPadding} />
      </ScrollView>
    </SafeAreaView>
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
    marginBottom: 8,
  },
  orderId: {
    fontSize: 18,
    fontWeight: '600',
    color: '#333333',
    marginBottom: 12,
  },
  downloadButton: {
    flexDirection: 'row',
    alignItems: 'center',
    alignSelf: 'flex-start',
    marginBottom: 20,
  },
  downloadButtonText: {
    fontSize: 14,
    color: '#a2c1f7',
    fontWeight: '500',
    marginLeft: 8,
    textDecorationLine: 'underline',
  },
  separator: {
    height: 1,
    backgroundColor: '#F0F0F0',
    marginBottom: 20,
  },
  section: {
    marginBottom: 24,
  },
  sectionTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#000000',
    marginBottom: 12,
  },
  infoCard: {
    backgroundColor: '#F8F9FA',
    borderRadius: 12,
    padding: 16,
    borderWidth: 1,
    borderColor: '#EEEEEE',
  },
  recipientName: {
    fontSize: 15,
    fontWeight: '600',
    color: '#333333',
    marginBottom: 8,
  },
  addressText: {
    fontSize: 14,
    color: '#666666',
    lineHeight: 20,
  },
  paymentCard: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#F8F9FA',
    borderRadius: 12,
    padding: 16,
    borderWidth: 1,
    borderColor: '#EEEEEE',
  },
  paymentIconContainer: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: '#ff6a002d', 
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  paymentText: {
    fontSize: 14,
    color: '#333333',
    fontWeight: '500',
    flex: 1,
  },
  summaryCard: {
    backgroundColor: '#F8F9FA',
    borderRadius: 12,
    padding: 16,
    borderWidth: 1,
    borderColor: '#EEEEEE',
  },
  summaryRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 8,
  },
  summaryLabel: {
    fontSize: 14,
    color: '#666666',
  },
  summaryValue: {
    fontSize: 14,
    color: '#333333',
    fontWeight: '500',
  },
  productRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginLeft: 16,
    marginBottom: 6,
  },
  productQuantity: {
    fontSize: 13,
    color: '#FF6B00',
    fontWeight: '500',
    width: 30,
  },
  productName: {
    fontSize: 13,
    color: '#666666',
    flex: 1,
  },
  productPrice: {
    fontSize: 13,
    color: '#333333',
    fontWeight: '500',
  },
  divider: {
    height: 1,
    backgroundColor: '#EEEEEE',
    marginVertical: 12,
  },
  totalRow: {
    marginTop: 4,
  },
  totalLabel: {
    fontSize: 13,
    color: '#333333',
    fontWeight: '600',
    marginBottom: 4,
  },
  totalValue: {
    fontSize: 18,
    color: '#FF6B00',
    fontWeight: 'bold',
    textAlign: 'right',
  },
  actionButtonsContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: 10,
    marginBottom: 20,
  },
  evaluateButton: {
    flex: 1,
    backgroundColor: '#FF6B00',
    borderRadius: 12,
    paddingVertical: 14,
    alignItems: 'center',
    marginRight: 8,
    shadowColor: '#FF6B00',
    shadowOffset: {
      width: 0,
      height: 2,
    },
    shadowOpacity: 0.1,
    shadowRadius: 3,
    elevation: 2,
  },
  evaluateButtonText: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '600',
  },
  cancelButton: {
    flex: 1,
    backgroundColor: '#FFFFFF',
    borderRadius: 12,
    paddingVertical: 14,
    alignItems: 'center',
    marginLeft: 8,
    borderWidth: 1,
    borderColor: '#FF3B30',
  },
  cancelButtonText: {
    color: '#FF3B30',
    fontSize: 14,
    fontWeight: '600',
  },
  bottomPadding: {
    height: 20,
  },
});