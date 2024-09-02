// src/config/axiosService.js

// Importa Axios, una biblioteca para hacer solicitudes HTTP, y la configuración de la aplicación
import axios from "axios";
import config from "@/config";

// Configuración de Axios
// Crea una instancia de Axios con la configuración predefinida
const apiClient = axios.create({
  // Establece la URL base para todas las solicitudes API
  baseURL: config.API_BASE_URL,

  // Define los encabezados comunes que se incluirán en cada solicitud
  headers: {
    // Agrega el token de autenticación almacenado en el localStorage al encabezado Authorization
    Authorization: `Bearer ${localStorage.getItem("authToken")}`,
  },
});

// Exporta la instancia configurada de Axios para su uso en otras partes de la aplicación
export default apiClient;
