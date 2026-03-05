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
                height={260}  
                style={styles.svg}
            >
                <Circle
                    cx={width / 2}
                    cy={-width*0.55}        
                    r={width * 1.03}
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
        height: 190,
        overflow: 'hidden', 
    },
    svg: {
        position: 'absolute',
        top: 0,
    },
    titleContainer: {
        height: 180,
        justifyContent: 'center',
        alignItems: 'center',
        paddingTop:25,
    },
    title: {
        fontSize: 42,
        fontWeight: '900',
        color: '#fff',
        letterSpacing: 4,
    },
});