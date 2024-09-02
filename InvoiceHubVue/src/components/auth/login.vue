<template>
  <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px">
      <div class="card-body">
        <h3 class="text-center mb-4">Iniciar sesión</h3>
        <form @submit.prevent="login">
          <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input
              v-model="email"
              id="email"
              type="email"
              class="form-control"
              required
              placeholder="Ingrese su correo electrónico"
            />
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input
              v-model="password"
              id="password"
              type="password"
              class="form-control"
              required
              placeholder="Ingrese su contraseña"
            />
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-block">
              Iniciar sesión
            </button>
          </div>
        </form>
        <div v-if="error" class="alert alert-danger mt-3 text-center">
          {{ error }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue"; // Importa la función ref de Vue para crear variables reactivas
import { useRouter } from "vue-router"; // Importa useRouter para la navegación programática
import Swal from "sweetalert2"; // Importa SweetAlert2 para mostrar alertas personalizadas
import { loginUser } from "@/services/authService"; // Importa la función loginUser desde el servicio de autenticación

// Definición de variables reactivas para almacenar el correo, la contraseña y los errores
const email = ref("");
const password = ref("");
const error = ref("");

// Inicializa el enrutador de Vue para redireccionar a otras rutas
const router = useRouter();

// Función de inicio de sesión que se ejecuta cuando el usuario envía el formulario
const login = async () => {
  error.value = ""; // Limpia cualquier error previo

  try {
    // Intenta iniciar sesión llamando a la función loginUser
    await loginUser(email.value, password.value);

    // Redirige al usuario a la página de dashboard si el inicio de sesión es exitoso
    router.push("/dashboard");

    // Muestra una alerta de éxito utilizando SweetAlert2
    Swal.fire({
      title: "Éxito",
      text: "Inicio de sesión exitoso",
      icon: "success",
      confirmButtonText: "Aceptar",
    });
  } catch (err) {
    console.error("Error al iniciar sesión:", err); // Muestra el error en la consola para depuración

    // Muestra una alerta de error utilizando SweetAlert2
    Swal.fire({
      title: "Error",
      text: err.message || "Error de red o del servidor.",
      icon: "error",
      confirmButtonText: "Aceptar",
    });

    // Almacena el mensaje de error en la variable reactiva error
    error.value = err.message || "Error de red o del servidor.";
  }
};
</script>

<style scoped>
.login-container {
  max-width: 400px;
  margin: auto;
  padding: 20px;
}
</style>
