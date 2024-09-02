<template>
  <form @submit.prevent="createNewInvoice">
    <div class="row">
      <div class="col-4 mb-2">
        <div class="form-group">
          <label for="invoice_number">Número de la factura</label>
          <input
            v-model="invoice.invoice_number"
            id="invoice_number"
            class="form-control"
            type="number"
            required
          />
        </div>
      </div>
      <div class="col-4 mb-2">
        <div class="form-group">
          <label for="date">Fecha de la factura</label>
          <input
            v-model="invoice.date"
            id="date"
            class="form-control"
            type="date"
            required
          />
        </div>
      </div>
      <div class="col-4 mb-2">
        <div class="form-group">
          <label for="entitySelectReceiver">Receptor de la factura:</label>
          <select
            required
            class="form-control"
            id="entitySelectReceiver"
            v-model="invoice.receiver_id"
          >
            <option
              v-for="entity in entities"
              :key="entity.id"
              :value="entity.id"
            >
              {{ entity.name }}
            </option>
          </select>
        </div>
      </div>
      <div class="col-4 mb-2">
        <div class="form-group">
          <label for="entitySelectTransmitter">Emisor de la factura</label>
          <select
            required
            class="form-control"
            id="entitySelectTransmitter"
            v-model="invoice.transmitter_id"
          >
            <option
              v-for="entity in entities"
              :key="entity.id"
              :value="entity.id"
            >
              {{ entity.name }}
            </option>
          </select>
        </div>
      </div>
      <div class="col-4 mb-2">
        <div class="form-group">
          <label for="subtotal">Subtotal</label>
          <input
            v-model="invoice.subtotal"
            id="subtotal"
            class="form-control"
            type="number"
            step="0.01"
            required
          />
        </div>
      </div>
      <div class="col-4 mb-2">
        <div class="form-group">
          <label for="tax">IVA</label>
          <input
            v-model="invoice.tax"
            id="tax"
            class="form-control"
            type="number"
            step="0.01"
            required
          />
        </div>
      </div>
      <div class="col-4 mb-2">
        <div class="form-group">
          <label for="total">Total</label>
          <input
            v-model="invoice.total"
            id="total"
            class="form-control"
            type="number"
            step="0.01"
            required
          />
        </div>
      </div>
      <div class="col-4 mb-2">
        <!-- Botón para crear o editar una factura -->
        <button type="submit" class="btn btn-primary mb-3">
          Crear nueva factura
        </button>
      </div>
    </div>
  </form>
</template>

<script setup>
import { defineProps, defineEmits } from "vue"; // Importa las funciones defineProps y defineEmits para manejar las props y los eventos

// Define las props que se esperan recibir del componente padre
const props = defineProps({
  invoice: {
    type: Object, // La factura es un objeto
    required: true, // Es obligatoria
  },
  entities: {
    type: Array, // Las entidades son un arreglo (lista)
    required: true, // Es obligatoria
  },
});

// Define los eventos que el componente emitirá
const emit = defineEmits(["submit"]); // Emite un evento 'submit' cuando se envía el formulario

// Función que se ejecuta al enviar el formulario
const createNewInvoice = () => {
  emit("submit"); // Emite el evento 'submit' sin pasar datos adicionales
};
</script>
