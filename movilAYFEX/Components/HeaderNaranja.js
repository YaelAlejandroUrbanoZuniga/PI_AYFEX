import React from 'react';
import { View, Text, StyleSheet, Dimensions, StatusBar } from 'react-native';
import Svg, { Circle } from 'react-native-svg';

const { width } = Dimensions.get('window');

export default function HeaderNaranja() {

    const radius = width;

    return (
        <View style={styles.container}>
            <StatusBar barStyle="light-content" backgroundColor="#FF6B00" />

            <Svg
                width={width}
                height={200}  
                style={styles.svg}
            >
                <Circle
                    cx={width / 2}
                    cy={-210}        
                    r={radius}
                    fill="#FF6B00"
                />
            </Svg>

            <View style={styles.titleContainer}>
                <Text style={styles.title}>AYFEX</Text>
            </View>
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        backgroundColor: '#fff', 
    },
    svg: {
        position: 'absolute',
        top: -20,
    },
    titleContainer: {
        height: 170,
        justifyContent: 'center',
        alignItems: 'center',
    },
    title: {
        fontSize: 42,
        fontWeight: '900',
        color: '#fff',
        letterSpacing: 4,
    },
});