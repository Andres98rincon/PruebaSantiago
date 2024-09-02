// src/services/authService.js

// Importa apiClient desde el servicio de axios configurado
import apiClient from "./axiosService";

// Función para iniciar sesión
export const loginUser = async (email, password) => {
  try {
    // Envía una solicitud POST al endpoint "/auth/login" con el email y la contraseña
    const response = await apiClient.post("/auth/login", { email, password });

    // Obtiene el token de acceso de la respuesta
    const token = response.data.access_token;

    // Almacena el token en el localStorage para mantener la sesión del usuario
    localStorage.setItem("authToken", token);
  } catch (error) {
    // Define un mensaje de error por defecto
    let errorMessage = "Error de red o del servidor.";

    // Si hay una respuesta de error específica del servidor, actualiza el mensaje de error
    if (error.response && error.response.data) {
      errorMessage = error.response.data.message || errorMessage;
    }

    // Lanza un nuevo error con el mensaje adecuado
    throw new Error(errorMessage);
  }
};

// Función para registrar un nuevo usuario
export const registerUser = async (name, email, password) => {
  try {
    // Envía una solicitud POST al endpoint "/auth/register" con el nombre, email y la contraseña
    const response = await apiClient.post("/auth/register", {
      name,
      email,
      password,
    });

    // Obtiene el token de acceso de la respuesta
    const token = response.data.access_token;

    // Almacena el token en el localStorage para mantener la sesión del usuario
    localStorage.setItem("authToken", token);
  } catch (error) {
    // Define un mensaje de error por defecto
    let errorMessage = "Error de red o del servidor.";

    // Si hay una respuesta de error específica del servidor, actualiza el mensaje de error
    if (error.response && error.response.data) {
      errorMessage = error.response.data.message || errorMessage;
    }

    // Lanza un nuevo error con el mensaje adecuado
    throw new Error(errorMessage);
  }
};
