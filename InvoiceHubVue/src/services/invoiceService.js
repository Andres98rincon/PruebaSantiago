// src/services/invoiceService.js
// Obtén el token desde el almacenamiento local
import apiClient from "./axiosService";

// Obtener todas las facturas
export const getInvoices = async () => {
  try {
    const response = await apiClient.get("/invoices/list");
    return response.data.data || [];
  } catch (error) {
    throw new Error("Error al cargar las facturas");
  }
};

// Obtener detalles de una factura específica
export const getInvoice = async (id) => {
  try {
    const response = await apiClient.get(`/invoices/${id}/show`);
    return response.data;
  } catch (error) {
    throw new Error("Error al obtener los detalles de la factura");
  }
};

// Actualizar una factura específica
export const updateInvoice = async (id, invoice) => {
  try {
    const response = await apiClient.put(`/invoices/${id}/update`, invoice);
    return response.data;
  } catch (error) {
    throw new Error("Error al actualizar la factura");
  }
};

// crea una factura
export const createInvoice = async (invoice) => {
  try {
    const response = await apiClient.post("/invoices/store", invoice);
    return response.data;
  } catch (error) {
    throw new Error("Error al crear la factura");
  }
};
