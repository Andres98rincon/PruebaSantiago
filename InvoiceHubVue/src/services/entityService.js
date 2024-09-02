// src/services/entityService.js

import apiClient from "./axiosService";

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
