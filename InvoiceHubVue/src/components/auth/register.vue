<template>
  <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px">
      <div class="card-body">
        <h3 class="text-center mb-4">Registro</h3>
        <form @submit.prevent="register">
          <div class="mb-3">
            <label for="email" class="form-label">Nombre</label>
            <input
              v-model="name"
              id="name"
              type="text"
              class="form-control"
              required
              placeholder="Ingrese su nombre"
            />
          </div>

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
          <div class="mb-3">
            <label for="confirmPassword" class="form-label"
              >Confirmar Contraseña</label
            >
            <input
              v-model="confirmPassword"
              id="confirmPassword"
              type="password"
              class="form-control"
              required
              placeholder="Confirme su contraseña"
            />
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-block">
              Registrarse
            </button>
          </div>
        </form>
        <div v-if="error" class="alert alert-danger mt-3 text-center">
          {{ error }}
        </div>
        <div class="mt-3 text-center">
          <router-link to="/login"
            >¿Ya tienes una cuenta? Inicia sesión</router-link
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import Swal from "sweetalert2";
import { registerUser } from "@/services/authService"; // Importa la función registerUser desde el servicio de autenticación

const name = ref("");
const email = ref("");
const password = ref("");
const confirmPassword = ref("");
const error = ref("");

const router = useRouter();

const register = async () => {
  error.value = ""; // Limpia cualquier error previo

  if (password.value !== confirmPassword.value) {
    error.value = "Las contraseñas no coinciden.";
    return;
  }

  try {
    // Intenta registrar al usuario llamando a la función registerUser
    await registerUser(name.value, email.value, password.value);

    // Redirige al usuario a la página de dashboard si el registro es exitoso
    router.push("/dashboard");

    // Muestra una alerta de éxito utilizando SweetAlert2
    Swal.fire({
      title: "Éxito",
      text: "Registro exitoso",
      icon: "success",
      confirmButtonText: "Aceptar",
    });
  } catch (err) {
    console.error("Error al registrarse:", err);

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
.register-container {
  max-width: 400px;
  margin: auto;
  padding: 20px;
}
</style>
