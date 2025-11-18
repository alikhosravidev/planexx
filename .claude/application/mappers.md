# Mappers

> **Source**: `.windsurf/rules/mappers.md`

## Purpose
Mappers transform HTTP requests into DTOs, separating mapping logic from controllers and DTOs.

## Rules

### 1. No Business Logic
Mappers perform **only data transformation**, no business logic.

### 2. Required Methods

#### `fromRequest()` - For Creation
```php
public function fromRequest(Request $request): AddressDTO
{
    return new AddressDTO(
        cityId: $request->input('city_id'),
        address: $request->input('address'),
        postalCode: $request->input('postal_code'),
    );
}
```

#### `fromRequestForUpdate()` - For Updates
Fills missing fields from existing entity:
```php
public function fromRequestForUpdate(Request $request, Address $address): AddressDTO
{
    return new AddressDTO(
        cityId: $request->input('city_id') ?? $address->city_id,
        address: $request->input('address') ?? $address->address,
        postalCode: $request->input('postal_code') ?? $address->postal_code,
    );
}
```

### 3. Dependency Injection
Inject dependencies via constructor if needed:
```php
class ProductMapper
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ) {}

    public function fromRequest(Request $request): ProductDTO
    {
        $category = $this->categoryRepository->find($request->input('category_id'));

        return new ProductDTO(
            name: $request->input('name'),
            category: $category,
        );
    }
}
```

## Usage in Controllers
```php
class AddressController extends Controller
{
    public function __construct(
        private readonly AddressMapper $mapper,
        private readonly AddressService $service
    ) {}

    public function store(CreateAddressRequest $request): JsonResponse
    {
        $dto = $this->mapper->fromRequest($request);
        $address = $this->service->create($dto);

        return $this->response->success($address);
    }

    public function update(UpdateAddressRequest $request, Address $address): JsonResponse
    {
        $dto = $this->mapper->fromRequestForUpdate($request, $address);
        $updatedAddress = $this->service->update($address, $dto);

        return $this->response->success($updatedAddress);
    }
}
```

## Complete Example
```php
<?php

declare(strict_types=1);

namespace App\Core\User\Mappers;

use App\Core\User\DTOs\AddressDTO;
use App\Core\User\Entities\Address;
use Illuminate\Http\Request;

class AddressMapper
{
    public function fromRequest(Request $request): AddressDTO
    {
        return new AddressDTO(
            cityId: (int) $request->input('city_id'),
            receiverName: $request->input('receiver_name'),
            receiverMobile: $request->input('receiver_mobile'),
            address: $request->input('address'),
            postalCode: $request->input('postal_code'),
            latitude: $request->input('latitude'),
            longitude: $request->input('longitude'),
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
            latitude: $request->input('latitude') ?? $address->latitude,
            longitude: $request->input('longitude') ?? $address->longitude,
        );
    }
}
```

## Best Practices
- Keep mappers simple and focused
- Use type casting when needed
- Handle null values explicitly
- Inject repositories for related data
- Never add business logic

## Full Details
See: `.windsurf/rules/mappers.md`

## Related
- DTOs: `.claude/application/dtos.md`
- Controllers: `.claude/presentation/controllers.md`
