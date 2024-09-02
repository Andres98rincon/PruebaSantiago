// src/services/entityService.js

// Importa Axios y la configuración de la aplicación
import axios from "axios";
import config from "../config";

// Obtén el token desde el almacenamiento local
const token = localStorage.getItem("authToken");

// Crea una instancia de Axios con la configuración predefinida
const apiClient = axios.create({
  // Establece la URL base para todas las solicitudes API
  baseURL: config.API_BASE_URL,

  // Define los encabezados comunes que se incluirán en cada solicitud
  headers: {
    // Agrega el token de autenticación almacenado en el localStorage al encabezado Authorization
    Authorization: `Bearer ${token}`,
  },
});

// Función para obtener la lista de entidades
export const getEntities = async () => {
  try {
    // Realiza una solicitud GET al endpoint /entities/list usando la instancia de Axios configurada
    const response = await apiClient.get("/entities/list");

    // Devuelve los datos de la respuesta o un arreglo vacío si no hay datos
    return response.data.data || [];
  } catch (error) {
    // Lanza un error con un mensaje personalizado si algo sale mal en la solicitud
    throw new Error("Error al obtener las entidades");
  }
};
