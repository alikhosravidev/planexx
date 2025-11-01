<?php

declare(strict_types=1);

namespace App\Core\User\Services\Mappers;

use App\Core\User\Entities\Address;
use App\Core\User\Services\DTOs\AddressDTO;
use Illuminate\Http\Request;

/**
 * AddressMapper
 *
 * Responsible for mapping HTTP requests to AddressDTO objects.
 * Separates data transformation logic from the DTO itself.
 */
class AddressMapper
{
    public function fromRequest(Request $request): AddressDTO
    {
        return new AddressDTO(
            cityId: $request->input('city_id'),
            receiverName: $request->input('receiver_name'),
            receiverMobile: $request->input('receiver_mobile'),
            address: $request->input('address'),
            postalCode: $request->input('postal_code'),
            latitude: (float) $request->input('latitude'),
            longitude: (float) $request->input('longitude'),
            userId: auth()->id(),
        );
    }

    public function fromRequestForUpdate(Request $request, Address $address): AddressDTO
    {
        return new AddressDTO(
            cityId: $request->input('city_id') ?? $address->city_id,
            receiverName: $request->input('receiver_name') ?? $address->receiver_name,
            receiverMobile: $request->input('receiver_mobile') ?? $address->receiver_mobile,
            address: $request->input('address') ?? $address->address,
            postalCode: $request->input('postal_code') ?? $address->postal_code,
            latitude: (float) ($request->input('latitude') ?? $address->latitude),
            longitude: (float) ($request->input('longitude') ?? $address->longitude),
            userId: $address->user_id,
        );
    }
}
