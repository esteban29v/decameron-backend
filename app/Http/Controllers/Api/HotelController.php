<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $hotels = Hotel::all();
        } catch (\Throwable $th) {
            return ApiResponse::error(
                "Hubo un error al realizar la solicitud.",
                500,
                [$th->getMessage()]
            );
        }

        return ApiResponse::success(
            $hotels,
            "Hoteles obtenidos exitosamente.",
            200,
        );
    }

    public function show($id)
    {
        try {
            $hotel = Hotel::find($id);

            if (!$hotel) {
                return ApiResponse::error("Hotel no encontrado.", 404);
            }

        } catch (\Throwable $th) {
            return ApiResponse::error(
                "Hubo un error al realizar la solicitud.",
                500,
                [$th->getMessage()]
            );
        }

        return ApiResponse::success(
            $hotel,
            "Hotel obtenido exitosamente.",
            200,
        );
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->only('name', 'city', 'number_of_rooms', 'address', 'nit'), [
                'name' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'number_of_rooms' => 'required|integer|min:1|max:99',
                'address' => 'required|string|max:255',
                'nit' => 'required|string|max:20|unique:hotels,nit',
            ], attributes: [
                'name' => 'nombre',
                'city' => 'ciudad',
                'number_of_rooms' => 'número de habitaciones',
                'address' => 'dirección',
                'nit' => 'NIT',
            ]);
    
            if ($validator->fails()) {
                return ApiResponse::error(
                    "Errores de validación.",
                    422,
                    $validator->errors()
                );
            }
    
            $hotel = Hotel::create($request->only(['name', 'city', 'number_of_rooms', 'address', 'nit']));
        } catch (\Throwable $th) {
            return ApiResponse::error(
                "Hubo un error al realizar la solicitud.",
                500,
                [$th->getMessage()]
            );
        }
        

        return ApiResponse::success(
            $hotel,
            "Hotel creado exitosamente.",
            201
        );
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'number_of_rooms' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
            'nit' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validación fallida',
                'errors' => $validator->errors(),
            ], 400);
        }

        $hotel = Hotel::find($id);

        if (!$hotel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hotel no encontrado',
            ], 404);
        }

        // Actualizar los datos del hotel
        $hotel->name = $request->input('name');
        $hotel->city = $request->input('city');
        $hotel->number_of_rooms = $request->input('number_of_rooms');
        $hotel->address = $request->input('address');
        $hotel->nit = $request->input('nit');
        $hotel->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel actualizado correctamente',
            'data' => $hotel,
        ], 200);
    }

    public function destroy($id)
    {
        $hotel = Hotel::find($id);

        if (!$hotel) {
            return ApiResponse::error(
                "Hotel no encontrado",
                404
            );
        }

        $hotel->delete();

        return ApiResponse::success(
            null,
            "Hotel eliminado correctamente!"
        );
    } 
}
