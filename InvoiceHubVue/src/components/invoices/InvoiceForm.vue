<template>
  <form @submit.prevent="createNewInvoice">
    <div class="row">
      <div class="col-4 mb-2">
        <div class="form-group">
          <label for="invoice_number">Número de la factura</label>
          <input
            v-model.number="invoice.invoice_number"
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
            v-model.number="invoice.subtotal"
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
          <label for="tax">IVA (%)</label>
          <input
            v-model.number="invoice.tax"
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
            :value="computedTotal"
            id="total"
            class="form-control"
            type="number"
            step="0.01"
            readonly
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
import { defineProps, defineEmits, computed, watch } from "vue";

// Define las props que se esperan recibir del componente padre
const props = defineProps({
  invoice: {
    type: Object,
    required: true,
  },
  entities: {
    type: Array,
    required: true,
  },
});

// Define los eventos que el componente emitirá
const emit = defineEmits(["submit"]);

// Propiedad calculada para obtener el total
const computedTotal = computed(() => {
  const subtotal = parseFloat(props.invoice.subtotal) || 0;
  const taxPercentage = parseFloat(props.invoice.tax) || 0;
  const taxAmount = (subtotal * taxPercentage) / 100;
  return (subtotal + taxAmount).toFixed(2); // Ajusta el total a dos decimales
});

// Observa cambios en el subtotal y el IVA para actualizar el total
watch([() => props.invoice.subtotal, () => props.invoice.tax], () => {
  props.invoice.total = computedTotal.value;
});

// Función que se ejecuta al enviar el formulario
const createNewInvoice = () => {
  // Actualiza el total antes de emitir el evento
  props.invoice.total = computedTotal.value;
  emit("submit");
};
</script>
