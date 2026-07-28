import { Platform } from 'react-native';

export const API_BASE_URL = Platform.OS === "web"
  ? "http://localhost:5000"
  : "http://192.168.1.58:5000";