<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
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
                $invoices = Invoice::with('receiver', 'transmitter')
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
        if (FacadesRequest::wantsJson()) {
            try {
                Invoice::create($request->all());
                return response()->json([
                    'message' => 'Factura creada conrrectamente',
                    'code' => 200,
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'La factura no pudo ser creada',
                    'code' => 500,
                ]);
            }
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
