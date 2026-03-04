import 'react-native-gesture-handler';
import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { Ionicons } from '@expo/vector-icons';

import InicioSesionM from './ScreensM/InicioSesionM';
import RegistrarUsuarioM from './ScreensM/RegistrarUsuarioM';
import PrincipalM from './ScreensM/PrincipalM';
import CrearPedidoM from './ScreensM/CrearPedidoM';
import PerfilM from './ScreensM/PerfilM';
import PedidosM from './ScreensM/PedidosM';
import PedidosM_Detalles from './ScreensM/PedidosM_Detalles';

const Stack = createNativeStackNavigator();
const Tab = createBottomTabNavigator();
const PedidosStack = createNativeStackNavigator();

function PedidosStackScreen() {
  return (
    <PedidosStack.Navigator>
      <PedidosStack.Screen
        name="PedidosLista"
        component={PedidosM}
        options={{ headerShown: false }}
      />
      <PedidosStack.Screen
        name="PedidosDetalles"
        component={PedidosM_Detalles}
        options={{ headerShown: false }}
      />
    </PedidosStack.Navigator>
  );
}

function TabsNavigator() {
  return (
    <Tab.Navigator
      screenOptions={({ route }) => ({
        headerShown: false,
        tabBarActiveTintColor: '#FFFFFF',
        tabBarInactiveTintColor: 'rgba(255,255,255,0.7)',
        tabBarStyle: {
          backgroundColor: '#FF6B00',
          borderTopWidth: 0,
          height: 90,
          paddingBottom: 8,
          paddingTop: 8,
          borderTopLeftRadius: 20,
          borderTopRightRadius: 20,
          position: 'absolute',
          bottom: 0,
          left: 0,
          right: 0,
          elevation: 0,
          shadowOpacity: 0,
        },
        tabBarLabelStyle: {
          fontSize: 11,
          fontWeight: '500',
          marginTop: 2,
        },
        tabBarIcon: ({ focused, color, size }) => {
          let iconName;

          if (route.name === 'Inicio') {
            iconName = focused ? 'home' : 'home-outline';
          } else if (route.name === 'Pedidos') {
            iconName = focused ? 'cube' : 'cube-outline';
          } else if (route.name === 'Crear') {
            iconName = focused ? 'add-circle' : 'add-circle-outline';
          } else if (route.name === 'Perfil') {
            iconName = focused ? 'person' : 'person-outline';
          }

          return <Ionicons name={iconName} size={24} color={color} />;
        }
      })}
    >
      <Tab.Screen
        name="Inicio"
        component={PrincipalM}
      />
      <Tab.Screen
        name="Pedidos"
        component={PedidosStackScreen}
      />
      <Tab.Screen
        name="Crear"
        component={CrearPedidoM}
      />
      <Tab.Screen
        name="Perfil"
        component={PerfilM}
      />
    </Tab.Navigator>
  );
}

export default function App() {
  return (
    <NavigationContainer>
      <Stack.Navigator initialRouteName="InicioSesionM">
        <Stack.Screen
          name="InicioSesionM"
          component={InicioSesionM}
          options={{ headerShown: false }}
        />
        <Stack.Screen
          name="RegistrarUsuarioM"
          component={RegistrarUsuarioM}
          options={{ headerShown: false }}
        />
        <Stack.Screen
          name="PrincipalM"
          component={TabsNavigator}
          options={{ headerShown: false }}
        />
      </Stack.Navigator>
    </NavigationContainer>
  );
}