<template>
  <div>
    <!--componente navbar -->
    <navBar></navBar>
    <!--componente InvoiceForm -->
    <InvoiceForm
      :invoice="invoice"
      :entities="entities"
      @submit="handleFormSubmit"
    />
    <!--componente InvoiceTable -->
    <InvoiceTable :invoices="invoices" />
  </div>
</template>

<script setup>
import navBar from "./template/nav.vue";
import InvoiceForm from "./invoices/InvoiceForm.vue";
import InvoiceTable from "./invoices/InvoiceTable.vue";
import { ref, onMounted } from "vue";
import Swal from "sweetalert2";
import { getInvoices, createInvoice } from "@/services/invoiceService";
import { getEntities } from "@/services/entityService";

const invoices = ref([]);
const entities = ref([]);
const invoice = ref({
  invoice_number: "",
  date: "",
  receiver_id: "",
  transmitter_id: "",
  subtotal: "",
  tax: "",
  total: "",
});

const handleFormSubmit = async () => {
  try {
    await createInvoice(invoice.value);
    invoices.value = await getInvoices();
    Object.keys(invoice.value).forEach((key) => {
      invoice.value[key] = "";
    });
    Swal.fire({
      title: "Éxito",
      text: "Factura creada correctamente",
      icon: "success",
      confirmButtonText: "Aceptar",
    });
  } catch (error) {
    console.error(error.message);
    Swal.fire({
      title: "Error",
      text: "No se pudo crear la factura",
      icon: "error",
      confirmButtonText: "Aceptar",
    });
  }
};

onMounted(async () => {
  try {
    invoices.value = await getInvoices();
    entities.value = await getEntities();
  } catch (error) {
    console.error(error.message);
  }
});
</script>
