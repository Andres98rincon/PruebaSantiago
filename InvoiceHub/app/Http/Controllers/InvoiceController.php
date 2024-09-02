<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (FacadesRequest::wantsJson()) {
            try {
                $invoices = Invoice::with('receiver', 'transmitter', 'products')
                    ->orderBy('created_at', 'desc')->get();
                return response()->json([
                    'message' => $invoices ? 'Listado de facturas' : 'No hay facturas',
                    'code' => $invoices ? 200 : 404,
                    'data' => $invoices ? $invoices : Null,
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'Error al obtener los datos',
                    'code' => 500,
                    'error' => $th->getMessage(),
                ], 500);
            }
        }
    }


    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        // Validar la solicitud
        $validatedData = $request->validate([
            'invoice_number' => 'required|integer',
            'date' => 'required|date',
            'receiver_id' => 'required|integer',
            'transmitter_id' => 'required|integer',
            'subtotal' => 'required|numeric',
            'tax' => 'nullable|numeric', // Hacer que sea opcional
            'total' => 'required|numeric',
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric',
            'items.*.total' => 'required|numeric',
        ]);

        // Asegúrate de que 'tax' tenga un valor
        $validatedData['tax'] = $validatedData['tax'] ?? 0;

        // Ejecutar las operaciones dentro de una transacción
        DB::beginTransaction();
        try {
            // Crear la factura
            $invoice = Invoice::create([
                'invoice_number' => $validatedData['invoice_number'],
                'date' => $validatedData['date'],
                'receiver_id' => $validatedData['receiver_id'],
                'transmitter_id' => $validatedData['transmitter_id'],
                'subtotal' => $validatedData['subtotal'],
                'tax' => $validatedData['tax'],
                'total' => $validatedData['total'],
            ]);

            // Crear los productos asociados
            foreach ($validatedData['items'] as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['total'],
                ]);
            }

            // Confirmar la transacción
            DB::commit();

            return response()->json(['message' => 'Factura creada exitosamente.'], 201);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return response()->json(['message' => 'Error al crear la factura.', 'error' => $e->getMessage()], 500);
        }
    }





    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('transmitter', 'receiver');
        if (FacadesRequest::wantsJson()) {
            try {
                return response()->json([
                    'message' => 'Factura encontrada',
                    'code' => 200,
                    'data' => $invoice
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'Error al encontrar la factura',
                    'code' => 404,
                ]);
            }
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        if (FacadesRequest::wantsJson()) {
            try {
                $invoice->update($request->all());
                return response()->json([
                    'message' => 'Factura editada correctamente',
                    'code' => 200,
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'La factura no pudo ser editada',
                    'code' => 200,
                ]);
            }
        }
    }
}
