<template>
  <div class="table-responsive">
    <!-- Campo de búsqueda -->
    <div class="mb-3">
      <input
        v-model="searchQuery"
        type="text"
        class="form-control"
        placeholder="Buscar por número de factura, emisor o receptor"
      />
    </div>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Número de factura</th>
          <th>Fecha y hora</th>
          <th>Emisor</th>
          <th>Receptor o comprador</th>
          <th>Valor antes de IVA</th>
          <th>IVA</th>
          <th>Valor a pagar</th>
          <th>Items facturados</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="invoice in filteredInvoices" :key="invoice.id">
          <td>{{ invoice.invoice_number }}</td>
          <td>{{ invoice.date }}</td>
          <td>{{ invoice.transmitter.name }}</td>
          <td>{{ invoice.receiver.name }}</td>
          <td>{{ invoice.subtotal }}</td>
          <td>{{ invoice.tax }}</td>
          <td>{{ invoice.total }}</td>
          <td>{{ invoice.items_count }}</td>
          <td>
            <button
              class="btn col-12 text-white btn-success btn-sm"
              @click="viewDetails(invoice)"
            >
              ver
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { defineProps, ref, computed } from "vue";
import { useRouter } from "vue-router";

const props = defineProps({
  invoices: {
    type: Array,
    required: true,
  },
});

const router = useRouter();

// definimos  ref para el buscador
const searchQuery = ref("");

// Propiedad calculada para filtrar facturas según la consulta de búsqueda
const filteredInvoices = computed(() => {
  const query = searchQuery.value.toLowerCase();
  return props.invoices.filter((invoice) => {
    return (
      invoice.invoice_number.toLowerCase().includes(query) ||
      invoice.transmitter.name.toLowerCase().includes(query) ||
      invoice.receiver.name.toLowerCase().includes(query)
    );
  });
});

const viewDetails = (invoice) => {
  router.push({ path: `/details/${invoice.id}` });
};
</script>
