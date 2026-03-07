import React from 'react';
import { View, Text, StyleSheet, Dimensions, StatusBar, TouchableOpacity } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import Svg, { Circle } from 'react-native-svg';

const { width } = Dimensions.get('window');

export default function HeaderNaranjaVolver({ navigation }) {

    return (
        <View style={styles.container}>
            <StatusBar barStyle="light-content" backgroundColor="#FF6B00" />

            <Svg
                width={width}
                height={190}
                style={styles.svg}
            >
                <Circle
                    cx={width / 2}
                    cy={-width * 0.57}
                    r={width * 1.05}
                    fill="#FF6B00"
                />
            </Svg>

            <TouchableOpacity 
                style={styles.backContainer}
                onPress={() => navigation.goBack()}
            >
                <Ionicons name="arrow-back" size={26} color="#FFFFFF" />
                <Text style={styles.backText}>Volver</Text>
            </TouchableOpacity>

        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        backgroundColor: '#fff',
        height: 157,
        overflow: 'hidden',
    },

    svg: {
        position: 'absolute',
        top: -30,
    },

    backContainer: {
        flexDirection: 'row',
        alignItems: 'center',
        paddingTop: 80,
        paddingLeft: 30,
    },

    backText: {
        fontSize: 22,
        color: '#FFFFFF',
        marginLeft: 8,
        fontWeight: '600',
    },
});
