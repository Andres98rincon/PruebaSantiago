<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (FacadesRequest::wantsJson()) {
            try {
                $entities = Entity::all();
                return response()->json([
                    'message' => $entities ? 'Lista de entidades' : 'No se encontraron entidades',
                    'code' => $entities ? 200 : 404,
                    'data' => $entities ? $entities : null,
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'Error al listar las entidades',
                    'code' => 500,
                ]);
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
                $entity = Entity::create($request->validated());
                return response()->json([
                    'message' => $entity ? 'Entidad creada correctamente' : 'No se pudo crear la entidad',
                    'code' => $entity ? 200 : 404,
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'Error al crear la entidad',
                    'code' => 500,
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Entity $entity)
    {
        if (FacadesRequest::wantsJson()) {
            try {
                return response()->json([
                    'message' => $entity ? 'Entidad encontrada correctamente' : 'No se encontro la entidad',
                    'code' => $entity ? 200 : 404,
                    'data' => $entity ? $entity : 'No se encontro la entidad',
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'Error al crear la entidad',
                    'code' => 500,
                ]);
            }
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entity $entity)
    {
        if (FacadesRequest::wantsJson()) {
            try {
                $entity->update($request->validated());
                return response()->json([
                    'message' => 'Entidad editada correctamente',
                    'code' => 200,
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'Error al editar la entidad',
                    'code' => 500,
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entity $entity)
    {
        if (FacadesRequest::wantsJson()) {
            try {
                $entity->delete();
                return response()->json([
                    'message' => 'Entidad eliminada correctamente',
                    'code' => 200,
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'Error al eliminar la entidad',
                    'code' => 500,
                ]);
            }
        }
    }
}
