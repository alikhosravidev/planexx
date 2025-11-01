## Mappers

Mappers are responsible for transforming HTTP requests into DTOs, separating mapping logic from controllers and DTOs.

### Rules
- Mappers must not contain business logic; only data transformation.
- Use `fromRequest(Request $request)` for creation.
- Use `fromRequestForUpdate(Request $request, Entity $entity)` for updates, filling missing fields from the existing entity.
- Inject dependencies via constructor if needed (e.g., repositories for related data).

### Example
```php
class AddressMapper
{
    public function fromRequest(Request $request): AddressDTO
    {
        return new AddressDTO(...);
    }

    public function fromRequestForUpdate(Request $request, Address $address): AddressDTO
    {
        return new AddressDTO(
            cityId: $request->input('city_id') ?? $address->city_id,
            // ...
        );
    }
}
```

### Usage in Controllers
```php
$dto = $this->mapper->fromRequest($request);
$service->create($dto);
```
