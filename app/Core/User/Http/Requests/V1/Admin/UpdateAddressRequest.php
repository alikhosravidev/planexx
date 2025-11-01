<?php

namespace App\Core\User\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;

class UpdateAddressRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city_id' => 'sometimes|exists:location_cities,id',
            'receiver_name' => 'sometimes|string|max:50',
            'receiver_mobile' => 'sometimes|string|regex:/^09[0-9]{9}$/',
            'address' => 'sometimes|string',
            'postal_code' => 'sometimes|numeric',
            'latitude' => ["sometimes", "regex:/^[-]?((([0-8]?[0-9])(\.(\d{1,8}))?)|(90(\.0+)?))$/"],
            'longitude' => ["sometimes", "regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))(\.(\d{1,8}))?)|180(\.0+)?)$/"],
        ];
    }
}
