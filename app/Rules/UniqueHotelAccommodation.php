<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Room;

class UniqueHotelAccommodation implements Rule
{
    protected $hotelId;
    protected $roomTypeAccommodationId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($hotelId)
    {
        $this->hotelId = $hotelId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->roomTypeAccommodationId = $value;

        return !Room::where('hotel_id', $this->hotelId)
                    ->where('room_type_accommodation_id', $value)
                    ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Esta habitación ya se encuentra asignada.";
    }
}
