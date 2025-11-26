<?php

namespace App\Core\Organization\Http\Requests\V1\Admin;

use App\Contracts\Requests\BaseRequest;
use App\Core\Organization\Entities\City;
use Illuminate\Validation\Rule;

class StoreAddressRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city_id' => 'required|exists:core_org_cities,id',
            'city_id' => ['required', Rule::exists(City::class, 'id')],
            'receiver_name' => 'required|string|max:50',
            'receiver_mobile' => 'required|string|regex:/^09[0-9]{9}$/',
            'address' => 'required',
            'postal_code' => 'required|numeric',
            'latitude' => ["required", "regex:/^[-]?((([0-8]?[0-9])(\.(\d{1,8}))?)|(90(\.0+)?))$/"],
            'longitude' => ["required", "regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))(\.(\d{1,8}))?)|180(\.0+)?)$/"],
        ];
    }
}
