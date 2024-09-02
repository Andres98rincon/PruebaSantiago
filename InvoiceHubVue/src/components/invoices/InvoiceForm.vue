<template>
  <form @submit.prevent="createNewInvoice">
    <div class="row">
      <!-- Mostrar mensaje de éxito -->
      <div v-if="successMessage" class="alert alert-success">
        {{ successMessage }}
      </div>

      <!-- Mostrar mensaje de error -->
      <div v-if="errorMessage" class="alert alert-danger">
        {{ errorMessage }}
      </div>
      <!-- Campos del formulario existente -->
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
            disabled
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
            value="0"
            class="form-control"
            type="number"
            step="0.01"
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
        <!-- Botón para añadir productos -->
        <button
          type="button"
          class="btn btn-secondary mb-3"
          @click="openProductModal"
        >
          Añadir producto
        </button>
        <!-- Botón para crear o editar una factura -->
        <button type="submit" class="btn btn-primary mb-3">
          Crear nueva factura
        </button>
      </div>
    </div>

    <!-- Modal para añadir productos -->
    <div
      v-if="showProductModal"
      class="modal fade show"
      tabindex="-1"
      role="dialog"
      style="display: block"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Añadir Producto</h5>
            <button
              type="button"
              class="close"
              aria-label="Close"
              @click="closeProductModal"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="productSelect">Producto</label>
              <select
                id="productSelect"
                class="form-control"
                v-model="selectedProductId"
              >
                <option
                  v-for="product in products"
                  :key="product.id"
                  :value="product.id"
                >
                  {{ product.name }}
                </option>
              </select>
            </div>
            <div class="form-group">
              <label for="quantity">Cantidad</label>
              <input
                id="quantity"
                class="form-control"
                type="number"
                v-model.number="productQuantity"
                min="1"
                required
              />
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="closeProductModal"
            >
              Cerrar
            </button>
            <button type="button" class="btn btn-primary" @click="addProduct">
              Añadir Producto
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-4">
      <h5>Productos Añadidos</h5>
      <ul class="list-group">
        <li
          v-for="(item, index) in addedProducts"
          :key="index"
          class="list-group-item d-flex justify-content-between align-items-center"
        >
          {{ item.product.name }} - Cantidad: {{ item.quantity }} - Total:
          {{ (item.product.price * item.quantity).toFixed(2) }}
          <div>
            <button
              type="button"
              class="btn btn-warning btn-sm mr-2"
              @click="editProduct(index)"
            >
              Editar
            </button>
            <button
              type="button"
              class="btn btn-danger btn-sm"
              @click="removeProduct(index)"
            >
              Eliminar
            </button>
          </div>
        </li>
      </ul>
    </div>
  </form>
</template>

<script setup>
import apiClient from "@/services/axiosService";
import { defineProps, defineEmits, computed, ref, watch, onMounted } from "vue";

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

// Estados para manejar el modal y la lista de productos añadidos
const showProductModal = ref(false);
const selectedProductId = ref(null);
const productQuantity = ref(1);
const products = ref([]); // Variable reactiva para almacenar los productos
const addedProducts = ref([]); // Lista de productos añadidos a la factura

// Función para abrir el modal
const openProductModal = () => {
  showProductModal.value = true;
};

// Función para cerrar el modal
const closeProductModal = () => {
  showProductModal.value = false;
};

// Función para añadir el producto a la factura
const addProduct = () => {
  const product = products.value.find((p) => p.id === selectedProductId.value);
  if (product) {
    // Verifica si el producto ya está en la lista
    const existingItemIndex = addedProducts.value.findIndex(
      (item) => item.product.id === product.id
    );
    if (existingItemIndex !== -1) {
      // Actualiza la cantidad del producto existente
      addedProducts.value[existingItemIndex].quantity += productQuantity.value;
    } else {
      // Añade un nuevo producto a la lista
      addedProducts.value.push({ product, quantity: productQuantity.value });
    }
    // Actualiza el subtotal
    const itemTotal = product.price * productQuantity.value;
    props.invoice.subtotal =
      parseFloat(props.invoice.subtotal || 0) + itemTotal;
    productQuantity.value = 1;
    selectedProductId.value = null;
    closeProductModal();
  }
};

// Función para editar un producto en la lista
const editProduct = (index) => {
  const item = addedProducts.value[index];
  selectedProductId.value = item.product.id;
  productQuantity.value = item.quantity;
  removeProduct(index); // Elimina el producto para poder volver a añadirlo con la cantidad actualizada
  openProductModal(); // Abre el modal para ajustar la cantidad
};

// Función para eliminar un producto de la lista
const removeProduct = (index) => {
  const item = addedProducts.value[index];
  props.invoice.subtotal -= item.product.price * item.quantity;
  addedProducts.value.splice(index, 1);
};

// Función que se ejecuta al enviar el formulario
const successMessage = ref("");
const errorMessage = ref("");

const createNewInvoice = async () => {
  // Asegúrate de que 'tax' tenga un valor
  if (props.invoice.tax === "") {
    props.invoice.tax = 0;
  }

  // Actualiza el total antes de enviar la solicitud
  props.invoice.total = computedTotal.value;

  // Prepara los datos para enviar
  const invoiceData = {
    ...props.invoice,
    items: addedProducts.value.map((item) => ({
      product_id: item.product.id,
      quantity: item.quantity,
      unit_price: item.product.price,
      total: item.product.price * item.quantity,
    })),
  };

  try {
    const response = await apiClient.post("/invoices/store", invoiceData);
    successMessage.value = "Factura creada exitosamente.";
    // Limpiar el formulario y los productos
    props.invoice = {
      invoice_number: "",
      date: "",
      receiver_id: null,
      transmitter_id: null,
      subtotal: 0,
      tax: 0,
      total: 0,
    };
    addedProducts.value = [];
    emit("submit"); // Emite el evento para notificar al componente padre
  } catch (error) {
    errorMessage.value = "Error al crear la factura.";
    console.error("Error al crear la factura:", error);
  }
};

// Cargar productos desde la API cuando el componente se monte
onMounted(async () => {
  try {
    const response = await apiClient.get("/products/list"); // Reemplaza con la URL de tu API
    products.value = response.data.data; // Ajusta esto según el formato de la respuesta de tu API
  } catch (error) {
    console.error("Error al obtener productos:", error);
  }
});
</script>

<style scoped>
.modal.fade.show {
  display: block;
}
</style>
