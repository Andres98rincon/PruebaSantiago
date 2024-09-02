<template>
  <div>
    <h1>Detalles de la factura</h1>

    <!-- Mostrar datos si están disponibles -->
    <div v-if="data">
      <div class="row">
        <div class="col-3">
          <div class="form-group">
            <label for="invoice_number"><strong>Number:</strong></label>
            <input
              id="invoice_number"
              type="text"
              v-model="data.invoice_number"
              :disabled="!isEditing"
              class="form-control"
            />
          </div>
        </div>
        <div class="col-3">
          <div class="form-group">
            <label for="date"><strong>Date:</strong></label>
            <input
              id="date"
              type="text"
              :value="formatDate(data.date)"
              :disabled="!isEditing"
              class="form-control"
            />
          </div>
        </div>
        <div class="col-3">
          <div class="form-group">
            <label for="receiver"><strong>Receiver:</strong></label>
            <input
              id="receiver"
              type="text"
              v-model="data.receiver.name"
              :disabled="!isEditing"
              class="form-control"
            />
          </div>
        </div>
        <div class="col-3">
          <div class="form-group">
            <label for="transmitter"><strong>Transmitter:</strong></label>
            <input
              id="transmitter"
              type="text"
              v-model="data.transmitter.name"
              :disabled="!isEditing"
              class="form-control"
            />
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="subtotal"><strong>Subtotal:</strong></label>
        <input
          id="subtotal"
          type="text"
          v-model="data.subtotal"
          :disabled="!isEditing"
          class="form-control"
        />
      </div>
      <div class="form-group">
        <label for="tax"><strong>Tax:</strong></label>
        <input
          id="tax"
          type="text"
          v-model="data.tax"
          :disabled="!isEditing"
          class="form-control"
        />
      </div>
      <div class="form-group">
        <label for="total"><strong>Total:</strong></label>
        <input
          id="total"
          type="text"
          v-model="data.total"
          :disabled="!isEditing"
          class="form-control"
        />
      </div>
      <div class="form-group">
        <label for="items_count"><strong>Items Count:</strong></label>
        <input
          id="items_count"
          type="text"
          v-model="data.items_count"
          :disabled="!isEditing"
          class="form-control"
        />
      </div>
    </div>

    <!-- Mostrar mensaje de error si ocurre un error -->
    <div v-if="error" class="alert alert-danger">
      <p>Error: {{ error }}</p>
    </div>

    <!-- Mensaje de carga -->
    <div v-if="!data && !error">
      <p>Loading...</p>
    </div>

    <button @click="goBack" class="btn btn-success">Volver</button>
    <button @click="handleEditSave" class="btn btn-info text-white mx-2">
      {{ isEditing ? "Guardar" : "Editar" }}
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"; // Importa ref para variables reactivas y onMounted para ejecutar código al montar el componente
import { useRoute, useRouter } from "vue-router"; // Importa useRoute para acceder a los parámetros de la ruta y useRouter para la navegación programática
import { getInvoice, updateInvoice } from "@/services/invoiceService"; // Importa las funciones de servicio para obtener y actualizar la factura

// Obtiene el ID de la factura desde los parámetros de la ruta
const route = useRoute();
const router = useRouter();
const id = ref(route.params.id); // Almacena el ID de la factura en una variable reactiva
const data = ref(null); // Variable reactiva para almacenar los datos de la factura
const error = ref(null); // Variable reactiva para almacenar mensajes de error
const isEditing = ref(false); // Variable reactiva para controlar si el formulario está en modo edición

// Función para obtener los datos de la factura desde el servidor
const fetchData = async () => {
  try {
    const response = await getInvoice(id.value); // Llama a la función getInvoice pasando el ID de la factura
    data.value = response.data; // Almacena los datos de la factura en la variable reactiva
  } catch (err) {
    console.error("Error fetching data:", err); // Muestra el error en la consola para depuración
    error.value = err.message; // Almacena el mensaje de error en la variable reactiva
  }
};

// Función para formatear la fecha en un formato más legible
const formatDate = (date) => {
  const options = { year: "numeric", month: "2-digit", day: "2-digit" }; // Define opciones de formato
  return new Date(date).toLocaleDateString(undefined, options); // Retorna la fecha formateada
};

// Función para volver al dashboard
const goBack = () => {
  router.push("/dashboard"); // Navega de regreso al dashboard
};

// Función para manejar la edición y guardado de los datos de la factura
const handleEditSave = async () => {
  if (isEditing.value) {
    // Si está en modo edición, intenta guardar los cambios
    try {
      await updateInvoice(id.value, data.value); // Llama a la función updateInvoice con el ID y los datos actualizados
      alert("Registro editado correctamentee"); // Muestra una alerta de éxito
      await fetchData(); // Opcional: Recargar los datos después de guardar
    } catch (err) {
      console.error("Error saving data:", err); // Muestra el error en la consola
      error.value = err.message; // Almacena el mensaje de error
    }
  }
  isEditing.value = !isEditing.value; // Cambia el modo de edición
};

// Ejecuta la función fetchData al montar el componente
onMounted(() => {
  fetchData();
});
</script>
