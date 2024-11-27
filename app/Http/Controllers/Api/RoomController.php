<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Http\Helpers\ApiResponse;
use App\Models\Hotel;
use Illuminate\Support\Facades\Validator;
use App\Rules\UniqueHotelAccommodation;

class RoomController extends Controller
{
    private $accommodationsTypeRoomKeyMapping;

    public function __construct()
    {
        $this->accommodationsTypeRoomKeyMapping = [
            'standard_simple' => 1,
            'standard_double' => 2,
            'junior_triple' => 3,
            'junior_quad' => 4,
            'suite_double' => 5,
            'suite_simple' => 6,
            'suite_triple' => 7,
        ];
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hotel_id' => 'required|exists:hotels,id',
            'room_type_accommodation_id' => [
                'required',
                'exists:room_type_accommodation,id',
                new UniqueHotelAccommodation($request->hotel_id),
            ],
            'number_of_rooms' => 'required|integer',
        ], attributes: [
            'hotel_id' => 'hotel',
            'room_type_accommodation_id' => 'tipo de habitación',
            'number_of_rooms' => 'número de habitaciones'
        ]);
        
        if ($validator->fails()) {
            return ApiResponse::error(
                "Errores de validación",
                422,
                $validator->errors()
            );
        }

        $room = Room::create($request->only([
            'hotel_id', 'room_type_accommodation_id', 'number_of_rooms'
        ]));

        return ApiResponse::success(
            $room,
            "Habitación agregada correctamente!",
            201
        );
    }

    public function destroy($id)
    {
        $room = Room::find($id);

        if (!$room) {
            return ApiResponse::error(
                __('api.error.not_found'),
                404
            );
        }

        $room->delete();

        return ApiResponse::success(
            null,
            __('api.success.delete')
        );
    }

    public function addRoom(Request $request) {

        /**
         *  1 = Estándar - Sencilla
         *  2 = Estándar - Doble
         *  3 = Junior - Triple
         *  4 = Junior - Cuádruple
         *  5 = Suite - Doble
         *  6 = Suite - Sencilla
         *  7 = Suite - Triple
         **/

        $validator = Validator::make($request->all(), [
            'hotel_id' => 'required|exists:hotels,id',
            'standard_simple' => 'required|numeric|min:0|max:99',
            'standard_double' => 'required|numeric|min:0|max:99',
            'junior_triple' => 'required|numeric|min:0|max:99',
            'junior_quad' => 'required|numeric|min:0|max:99',
            'suite_double' => 'required|numeric|min:0|max:99',
            'suite_simple' => 'required|numeric|min:0|max:99',
            'suite_triple' => 'required|numeric|min:0|max:99',
        ], attributes: [
            'hotel_id' => 'hotel',
            'standard_simple' => 'Estándar - Sencilla',
            'standard_double' => 'Estándar - Doble',
            'junior_triple' => 'Junior - Triple',
            'junior_quad' => 'Junior - Cuádruple',
            'suite_double' => 'Suite - Doble',
            'suite_simple' => 'Suite - Sencilla',
            'suite_triple' => 'Suite - Triple',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error(
                "Errores de validación",
                422,
                $validator->errors()
            );
        }

        $accommodations = $request->except('hotel_id');

        $sumOfAccommodations = array_sum($accommodations);

        $hotelId = $request->only('hotel_id')['hotel_id'];

        $hotel = Hotel::find($hotelId);

        if ($sumOfAccommodations > $hotel->number_of_rooms) {
            return ApiResponse::error(
                "Errores de validación",
                422,
                ['max_rooms_per_hotel' => 'No puede exceder el maximo de habitaciones establecidas por el hotel.']
            );
        }

        foreach ($accommodations as $key => $value) {
            Room::updateOrCreate(
                ['hotel_id' => $hotelId, 'room_type_accommodation_id' => $this->accommodationsTypeRoomKeyMapping[$key]],
                ['number_of_rooms' => $value]
            );
        }

        $rooms = Room::where('hotel_id', $hotelId)->get();

        return ApiResponse::success(
            $rooms,
            "Habitaciones agregadas correctamente",
            201
        );        
    }

    public function getRoomsByHotelId(Request $request, $hotelId) {
    
        $rooms = Room::where('hotel_id', $hotelId)->get();

        $accommodationsMappings = array_flip($this->accommodationsTypeRoomKeyMapping);

        foreach ($rooms as $key) {
            $key->desc = $accommodationsMappings[$key->room_type_accommodation_id];
        }

        return ApiResponse::success(
            $rooms,
            201
        );
    }
}
