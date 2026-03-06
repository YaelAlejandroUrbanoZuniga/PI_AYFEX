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
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';

export default function PedidosM({ navigation }) {
    const verDetalles = (pedidoId) => {
        let pedidoData = {};

        if (pedidoId === '#PED022604') {
            pedidoData = {
                id: '#PED022604',
                destinatario: 'PablitoCompraPartes',
                direccion: 'Sacro Imperio Romano 400A.\n187A, Ciudad Maderas.\nMerida, Yucatan,\n89430.\nMéxico',
                metodoPago: 'Visa que termina en 2003',
                productos: [
                    { nombre: 'Espejo delantero. Chevrolet.', cantidad: 1, precio: 8000.00 },
                    { nombre: 'Faros delanteros. Toyota.', cantidad: 2, precio: 0 },
                ],
                pedidosTotal: 8000.00,
                envio: 120.00,
                subtotal: 8120.00,
                total: 8120.00,
            };
        } else if (pedidoId === '#PED022603') {
            pedidoData = {
                id: '#PED022603',
                destinatario: 'MariaAutomotriz',
                direccion: 'Av. Reforma 505.\nCol. Centro.\nMérida, Yucatan,\n97000.\nMéxico',
                metodoPago: 'Mastercard que termina en 4567',
                productos: [
                    { nombre: 'Llantas 205/55 R16.', cantidad: 4, precio: 3500.00 },
                ],
                pedidosTotal: 14000.00,
                envio: 200.00,
                subtotal: 14200.00,
                total: 14200.00,
            };
        } else if (pedidoId === '#PED022602') {
            pedidoData = {
                id: '#PED022602',
                destinatario: 'TallerMecánico López',
                direccion: 'Calle 60 #345.\nCol. Itzimná.\nMérida, Yucatan,\n97100.\nMéxico',
                metodoPago: 'Visa que termina en 2010',
                productos: [
                    { nombre: 'Filtro de aceite.', cantidad: 3, precio: 120.00 },
                    { nombre: 'Bujías NGK.', cantidad: 6, precio: 80.00 },
                ],
                pedidosTotal: 840.00,
                envio: 0.00,
                subtotal: 840.00,
                total: 840.00,
            };
        }

        navigation.navigate('PedidosDetalles', { pedidoData });
    };

    return (
        <SafeAreaView style={styles.container}>
            <StatusBar barStyle="dark-content" backgroundColor="#FFFFFF" />

            {/* Header */}
            <View style={styles.header}>
                <View style={styles.headerLeft}>
                    <Ionicons name="menu-outline" size={24} color="#000000" />
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

                <View style={styles.searchContainer}>
                    <View style={styles.searchInputContainer}>
                        <Ionicons name="search-outline" size={20} color="#999999" />
                        <TextInput
                            style={styles.searchInput}
                            placeholder="Buscar Pedido"
                            placeholderTextColor="#999999"
                        />
                    </View>
                    <Text style={styles.searchExample}>Ej: #PED010101</Text>
                </View>

                <View style={styles.separator} />

                <TouchableOpacity
                    style={styles.orderCard}
                    onPress={() => verDetalles('#PED022604')}
                >
                    <View style={styles.orderHeader}>
                        <View style={styles.orderTitleContainer}>
                            <Text style={styles.orderId}>ID: #PED022604</Text>
                            <View style={[styles.statusBadge, styles.statusPreparing]}>
                                <Text style={styles.statusText}>En preparación</Text>
                            </View>
                        </View>
                    </View>

                    <View style={styles.orderDetails}>
                        <View style={styles.locationRow}>
                            <View style={styles.locationIcon}>
                                <Ionicons name="ellipse" size={8} color="#FF3B30" />
                            </View>
                            <Text style={styles.locationText}>
                                Origen: <Text style={styles.locationBold}>San Juan del Río, Querétaro</Text>
                            </Text>
                        </View>

                        <View style={styles.locationRow}>
                            <View style={styles.locationIcon}>
                                <Ionicons name="ellipse" size={8} color="#34C759" />
                            </View>
                            <Text style={styles.locationText}>
                                Destino: <Text style={styles.locationBold}>Tampico, Tamaulipas</Text>
                            </Text>
                        </View>
                    </View>

                    <View style={styles.orderFooter}>
                        <Text style={styles.orderDate}>17 - 02 - 2026</Text>
                        <TouchableOpacity
                            style={styles.detailsButton}
                            onPress={() => verDetalles('#PED022604')}
                        >
                            <Text style={styles.detailsButtonText}>Detalles</Text>
                            <Ionicons name="chevron-forward" size={16} color="#FF6B00" />
                        </TouchableOpacity>
                    </View>
                </TouchableOpacity>

                <TouchableOpacity
                    style={styles.orderCard}
                    onPress={() => verDetalles('#PED022603')}
                >
                    <View style={styles.orderHeader}>
                        <View style={styles.orderTitleContainer}>
                            <Text style={styles.orderId}>ID: #PED022603</Text>
                            <View style={[styles.statusBadge, styles.statusInTransit]}>
                                <Text style={styles.statusText}>En camino</Text>
                            </View>
                        </View>
                    </View>

                    <View style={styles.orderDetails}>
                        <View style={styles.locationRow}>
                            <View style={styles.locationIcon}>
                                <Ionicons name="ellipse" size={8} color="#FF3B30" />
                            </View>
                            <Text style={styles.locationText}>
                                Origen: <Text style={styles.locationBold}>Mérida, Yucatán</Text>
                            </Text>
                        </View>

                        <View style={styles.locationRow}>
                            <View style={styles.locationIcon}>
                                <Ionicons name="ellipse" size={8} color="#34C759" />
                            </View>
                            <Text style={styles.locationText}>
                                Destino: <Text style={styles.locationBold}>Pedro Escobedo, Querétaro</Text>
                            </Text>
                        </View>
                    </View>

                    <View style={styles.orderFooter}>
                        <Text style={styles.orderDate}>11 - 02 - 2026</Text>
                        <TouchableOpacity
                            style={styles.detailsButton}
                            onPress={() => verDetalles('#PED022603')}
                        >
                            <Text style={styles.detailsButtonText}>Detalles</Text>
                            <Ionicons name="chevron-forward" size={16} color="#FF6B00" />
                        </TouchableOpacity>
                    </View>
                </TouchableOpacity>

                <TouchableOpacity
                    style={styles.orderCard}
                    onPress={() => verDetalles('#PED022602')}
                >
                    <View style={styles.orderHeader}>
                        <View style={styles.orderTitleContainer}>
                            <Text style={styles.orderId}>ID: #PED022602</Text>
                            <View style={[styles.statusBadge, styles.statusDelivered]}>
                                <Text style={styles.statusText}>Entregado</Text>
                            </View>
                        </View>
                    </View>

                    <View style={styles.orderDetails}>
                        <View style={styles.locationRow}>
                            <View style={styles.locationIcon}>
                                <Ionicons name="ellipse" size={8} color="#FF3B30" />
                            </View>
                            <Text style={styles.locationText}>
                                Origen: <Text style={styles.locationBold}>Tampico, Tamaulipas</Text>
                            </Text>
                        </View>

                        <View style={styles.locationRow}>
                            <View style={styles.locationIcon}>
                                <Ionicons name="ellipse" size={8} color="#34C759" />
                            </View>
                            <Text style={styles.locationText}>
                                Destino: <Text style={styles.locationBold}>San Juan del Río, Querétaro</Text>
                            </Text>
                        </View>
                    </View>

                    <View style={styles.orderFooter}>
                        <Text style={styles.orderDate}>03 - 02 - 2026</Text>
                        <TouchableOpacity
                            style={styles.detailsButton}
                            onPress={() => verDetalles('#PED022602')}
                        >
                            <Text style={styles.detailsButtonText}>Detalles</Text>
                            <Ionicons name="chevron-forward" size={16} color="#FF6B00" />
                        </TouchableOpacity>
                    </View>
                </TouchableOpacity>

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
        marginBottom: 16,
    },
    searchContainer: {
        marginBottom: 16,
    },
    searchInputContainer: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#F8F9FA',
        borderRadius: 12,
        paddingHorizontal: 12,
        paddingVertical: 10,
        borderWidth: 1,
        borderColor: '#EEEEEE',
    },
    searchInput: {
        flex: 1,
        marginLeft: 8,
        fontSize: 14,
        color: '#333333',
    },
    searchExample: {
        fontSize: 12,
        color: '#999999',
        marginTop: 6,
        marginLeft: 12,
    },
    separator: {
        height: 1,
        backgroundColor: '#F0F0F0',
        marginBottom: 20,
    },
    orderCard: {
        backgroundColor: '#FFFFFF',
        marginBottom: 16,
        padding: 16,
        borderRadius: 12,
        borderWidth: 1,
        borderColor: '#F0F0F0',
        shadowColor: '#000',
        shadowOffset: {
            width: 0,
            height: 2,
        },
        shadowOpacity: 0.05,
        shadowRadius: 3.84,
        elevation: 3,
    },
    orderHeader: {
        marginBottom: 12,
    },
    orderTitleContainer: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
    },
    orderId: {
        fontSize: 14,
        fontWeight: '600',
        color: '#333333',
    },
    statusBadge: {
        paddingHorizontal: 10,
        paddingVertical: 4,
        borderRadius: 12,
    },
    statusPreparing: {
        backgroundColor: '#FFF3E0',
    },
    statusInTransit: {
        backgroundColor: '#E3F2FD',
    },
    statusDelivered: {
        backgroundColor: '#E8F5E9',
    },
    statusText: {
        fontSize: 11,
        fontWeight: '500',
    },
    orderDetails: {
        marginBottom: 12,
    },
    locationRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 6,
    },
    locationIcon: {
        width: 20,
        alignItems: 'center',
    },
    locationText: {
        fontSize: 13,
        color: '#666666',
        flex: 1,
    },
    locationBold: {
        fontWeight: '500',
        color: '#333333',
    },
    orderFooter: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        borderTopWidth: 1,
        borderTopColor: '#F0F0F0',
        paddingTop: 12,
    },
    orderDate: {
        fontSize: 12,
        color: '#999999',
    },
    detailsButton: {
        flexDirection: 'row',
        alignItems: 'center',
    },
    detailsButtonText: {
        fontSize: 14,
        color: '#FF6B00',
        fontWeight: '500',
        marginRight: 4,
    },
    bottomPadding: {
        height: 20,
    },
});