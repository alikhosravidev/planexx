# AI Agent Instructions

Laravel 12 project with **Hybrid Modular Architecture** and **Domain-Driven Design** principles. All documentation must be in English.

## Quick Start

### Docker Environment (Required)
ALL PHP/Laravel commands MUST run inside Docker:
```bash
docker exec planexx_app php artisan [command]
docker exec planexx_app composer [command]
```

### Essential Commands
```bash
composer setup     # Full setup: install, migrate, build
composer dev       # Start all: server, queue, logs, vite
composer test      # Run test suite
npm run dev        # Vite dev server
npm run build      # Production build
./standards/scripts/pint.sh  # Format PHP code (PSR-12)
```

### Test Execution
Always use `--stop-on-failure --stop-on-error`. Prefer [tests/PureUnitTestBase.php](tests/PureUnitTestBase.php) (fastest, no Laravel container) when possible. See [.windsurf/rules/testing.md](.windsurf/rules/testing.md).

## Code Style

**Formatter**: [standards/formatter/pint.json](standards/formatter/pint.json) - PSR-12 preset
- **Strict types**: `declare(strict_types=1)` on every file
- **String quotes**: Single quotes always
- **Array syntax**: Short `[]`
- **Trailing commas**: Required in multiline arrays/params
- **Line length**: 120 chars max
- **Test methods**: `snake_case` (not camelCase)
- **Strong typing**: All properties, parameters, returns typed

Example from [app/Core/BPMS/Services/WorkflowService.php](app/Core/BPMS/Services/WorkflowService.php):
```php
declare(strict_types=1);

readonly class WorkflowService
{
    public function __construct(
        private WorkflowRepository $repository,
        private FileService $fileService,
    ) {}  // Trailing comma

    public function create(WorkflowUpsertDTO $dto): Workflow  // Strong typing
    {
        // Implementation
    }
}
```

## Architecture

### Module Structure
- **Core Modules**: [app/Core/](app/Core/) (Organization, BPMS, FileManager, FormEngine, Notify) - always loaded
- **Pluggable Modules**: [Modules/](Modules/) (Notification) - optional features
- **Applications**: [Applications/](Applications/) (AdminPanel, PWA) - presentation layers

Example module: [app/Core/Organization](app/Core/Organization)
```
Organization/
├── Entities/          # Eloquent models (lean, $fillable)
├── ValueObjects/      # Immutable validated domain values (Mobile, Email)
├── Enums/             # MUST have label() for Persian labels
├── DTOs/              # Data carriers (readonly, constructor-based)
├── Mappers/           # Request → DTO conversion
├── Services/          # Business logic, orchestration
├── Repositories/      # Data access with Criteria pattern
│   └── Criteria/      # Reusable query specifications
├── Http/
│   ├── Controllers/   # Thin, delegate to services
│   ├── Transformers/  # Pipeline-based response transformation
│   └── Requests/      # Form validation
├── Observers/         # Model lifecycle events
├── Database/          # Migrations (two-phase), Factories
└── Tests/             # Prefer PureUnitTestBase when possible
```

### Request Flow
**Pattern**: `Request → FormRequest → Mapper → DTO → Service → Repository → Entity → Transformer → Response`

See [app/Http/Controllers/V1/Admin/TagController.php](app/Http/Controllers/V1/Admin/TagController.php#L26-L38):
```php
public function store(StoreTagRequest $request): JsonResponse
{
    $dto = $this->mapper->fromRequest($request);        // Mapper
    $tag = $this->service->create($dto);                 // Service

    return $this->response->created(                     // ResponseBuilder
        $this->transformer->transformOne($tag),          // Transformer
    );
}
```

### Layer Responsibilities
1. **Controllers**: Parse HTTP, delegate to services, transform responses
2. **Services**: Business logic, transactions, event firing
3. **Repositories**: Data access via Criteria pattern (see [app/Contracts/Repository/BaseRepository.php](app/Contracts/Repository/BaseRepository.php))
4. **Entities**: Eloquent models (keep lean, use observers for lifecycle)
5. **Transformers**: Multi-step pipeline with virtual fields (see [app/Contracts/Transformer/BaseTransformer.php](app/Contracts/Transformer/BaseTransformer.php))

## Project Conventions

### DTOs (Data Transfer Objects)
See [.claude/application/dtos.md](.claude/application/dtos.md)

**Rules** - example from [app/DTOs/TagDTO.php](app/DTOs/TagDTO.php):
```php
final readonly class TagDTO implements Arrayable
{
    public function __construct(  // Constructor is MANDATORY
        public string $name,                    // At least one mandatory param (no default)
        public ?string $slug = null,           // Optional params
        public ColorEnum $color = ColorEnum::Blue,
    ) {}

    public function toArray(): array {         // Only allowed method
        return [
            'name' => $this->name,
            'color' => $this->color->value,    // Cast enums to primitives
        ];
    }
}
```

**Prohibited**:
- Factory methods (`fromArray()`, `fromRequest()`) - use Mappers instead
- Business logic - DTOs are pure data carriers
- Getters/setters - use `public readonly` properties

### Enums (MUST have label() method)
Example from [app/Core/BPMS/Enums/TaskAction.php](app/Core/BPMS/Enums/TaskAction.php):
```php
enum TaskAction: string
{
    case EDIT = 'edit';
    case FORWARD = 'forward';

    public function label(): string  // MANDATORY for Persian display
    {
        return match ($this) {
            self::EDIT => 'ویرایش',
            self::FORWARD => 'ارجاع',
        };
    }
}

// Validation: Use Rule::enum() not Rule::in()
'type' => ['required', Rule::enum(TaskAction::class)],
```

### ValueObjects (Immutable with Validation)
Example from [app/ValueObjects/Mobile.php](app/ValueObjects/Mobile.php):
```php
final readonly class Mobile
{
    public string $value;

    public function __construct(string $value)
    {
        $value = StringUtility::transformMobile($value);
        if (!preg_match(CustomValidator::MOBILE_REGEX, $value)) {
            throw new InvalidArgumentException('Invalid mobile');
        }
        $this->value = $value;
    }
}
```

**VO vs DTO**:
- **ValueObject**: Domain concepts, self-validating, immutable (Mobile, Email, Money)
- **DTO**: Data carriers across layers, no validation logic

### Repository Pattern with Criteria
Example from [app/Core/BPMS/Repositories/WorkflowRepository.php](app/Core/BPMS/Repositories/WorkflowRepository.php):
```php
class WorkflowRepository extends BaseRepository
{
    public array $fieldSearchable = ['id' => '=', 'name' => 'like'];
    public array $sortableFields = ['id', 'created_at'];

    protected array $customFilters = [
        'user_accessible' => UserAccessibleWorkflowsCriteria::class,
    ];

    public function model(): string { return Workflow::class; }
}

// Usage from API: GET /workflows?filter[user_accessible]=1
// Or in code:
$workflows = $repo->pushCriteria(new UserAccessibleWorkflowsCriteria())->all();
```

### Transformers (Pipeline-based)
Pipeline: `DataExtraction → BlacklistFilter → FieldTransformation → VirtualFieldResolution`

Example from [app/Http/Transformers/UserTransformer.php](app/Http/Transformers/UserTransformer.php):
```php
class UserTransformer extends BaseTransformer
{
    protected array $availableIncludes = ['department', 'posts'];

    protected array $fieldTransformers = [
        'type' => EnumTransformer::class,      // Auto-calls label()
        'mobile' => MobileTransformer::class,
    ];

    protected function getVirtualFieldResolvers(): array
    {
        return [
            'full_name' => fn($user) => $user->first_name . ' ' . $user->last_name,
        ];
    }
}

// Usage
$transformer = app(TransformerFactory::class)->makeFromRequest(UserTransformer::class, $request);
$result = $transformer->transformModel($user);
```

### Error Handling (Safe Field Pattern)
See [.claude/error-handling.md](.claude/error-handling.md)

**Prohibited**:
```php
// ❌ Nested try-catch
try {
    try { } catch (SomeException $e) { }
} catch (AnotherException $e) { }

// ❌ Try-catch for business rules
try {
    if ($balance < $amount) throw new InsufficientFundsException();
} catch (InsufficientFundsException $e) { }
```

**Correct**:
```php
// ✅ Safe Field Pattern for business rules
public function transferMoney(...): TransferResult
{
    if ($from->getBalance() < $amount) {
        return new TransferResult(false, 'Insufficient funds');
    }
    return new TransferResult(true, 'Completed');
}

// ✅ Try-catch only for external systems
try {
    $user = $this->userRepository->find($userId);
} catch (UserNotFoundException $e) {
    $this->logger->error('User not found', ['exception' => $e]);
    return new OrderResult(false, 'User not found');
}
```

**Rules**:
1. No nested try-catch (prohibited)
2. Catch specific exceptions only
3. Try-catch only for external systems (DB, API, files)
4. Safe Field pattern for business rules
5. Always log caught exceptions with context

### Presentation Logic Separation (Rule of Three)
See [.windsurf/rules/presentation-logic-separation.md](.windsurf/rules/presentation-logic-separation.md)

Maximum 3 lines of logic in Controllers/Views/Transformers. Complex logic → Services.

```php
// ❌ Prohibited in Controller
public function index(Request $request) {
    $query = Workflow::query();
    if ($request->has('status')) {
        $query->where('status', $request->status);
    }
    if ($request->has('user_id')) {
        $query->whereHas('users', fn($q) => $q->where('id', $request->user_id));
    }
    // ... 20 more lines
}

// ✅ Correct: Delegate to Service
public function index(Request $request) {
    $dto = $this->mapper->fromRequest($request);
    $workflows = $this->service->getFiltered($dto);
    return $this->response->success($this->transformer->transformCollection($workflows));
}
```

## Integration Points

### API Authentication
Routes use `auth:api` middleware with Sanctum tokens. See [.claude/api/authentication.md](.claude/api/authentication.md).

```php
// Header
Authorization: Bearer {sanctum_token}

// Route definition
Route::middleware('auth:api')->apiResource('users', UserController::class);
```

### BaseAPIController Features
See [.windsurf/rules/controller-architecture.md](.windsurf/rules/controller-architecture.md)

Auto-parses query params:
- **Filtering**: `?filter[name]=value`, `?filter[price][gte]=100`
- **Search**: `?search=term&searchFields=name,description`
- **Sorting**: `?order_by=created_at&order_direction=desc`
- **Pagination**: `?page=2&per_page=20` (max 100)
- **Includes**: `?includes=department,posts`

### Admin Panel (API-First Architecture)
**Critical**: [Applications/Contracts/BaseWebController.php](Applications/Contracts/BaseWebController.php)

**PRIMARY**: Use Axios for all CRUD operations in admin panel:
```javascript
axios.post('/users', formData)
  .then(response => {
    showNotification('User created successfully');
    refreshTable();
  })
  .catch(error => {
    if (error.response?.status === 422) {
      displayValidationErrors(error.response.data.errors);
    }
  });
```

**SECONDARY**: BaseWebController ONLY for view rendering - controllers use `forwardToApi()` for data fetching:
```php
class UserManagementController extends BaseWebController
{
    public function index(Request $request): View
    {
        $response = $this->apiGet('users', [
            'filter' => ['is_active' => 1],
            'sort' => '-created_at',
        ]);
        
        return view('admin.users.index', ['users' => $response['data']]);
    }
    
    // ❌ NO store/update/destroy methods - Axios handles these
}

// ❌ PROHIBITED in Admin Panel Controllers:
User::find($id);              // Direct model access
$this->userService->create(); // Direct service call
```

### Database Patterns
- **Migrations**: Two-phase for foreign keys (create tables first, add FKs separately)
- **Table naming**: `{module_prefix}_{resource_plural}` (e.g., `core_org_users`, `bpms_workflows`)
- **Observers**: For model lifecycle (see [app/Observers/](app/Observers/))
- **Factories**: For test data (see [.windsurf/rules/eloquent_factories.md](.windsurf/rules/eloquent_factories.md))

### Events (Inter-Module Communication)
See [.claude/events.md](.claude/events.md)

**Use for**: Inter-module communication only
**Prohibited**: Intra-module event listeners (same module should use direct service calls)

```php
// ✅ Fire business events
event(new UserCreated($user));
event(new OrderCompleted($order));
```

## Security

### Authentication
- **API**: Sanctum token in `Authorization: Bearer {token}` header
- **OTP**: Mobile-based authentication via SMS (see [app/Core/User/Services/AuthenticationService.php](app/Core/User/Services/AuthenticationService.php))
- **Permissions**: Spatie Laravel Permission package (see [.windsurf/rules/authentication.md](.windsurf/rules/authentication.md))

### Validation
- **Form Requests**: All user input validated via FormRequest classes
- **Enums**: Use `Rule::enum()` not `Rule::in()`
- **ValueObjects**: Self-validating on construction

### Data Sanitization
- **Transformers**: Blacklist sensitive fields (see `$defaultBlacklist` in BaseTransformer)
- **API responses**: Use transformer pipeline, never return raw models

## Frontend Development

### AJAX System (v2.0 - Refactored)
**For ALL form submissions and AJAX requests**, use the declarative HTML-based AJAX system:

- **Modular Architecture**: Separated concerns (actions, handlers, utilities)
- **Secure by Default**: HttpOnly cookies, CSRF protection, no client-side token storage
- **14 built-in actions** for DOM manipulation, custom action registry
- **Testable**: DI container for mocking dependencies

**Quick Example**:
```html
<form data-ajax action="{{ route('users.store') }}" data-on-success="prepend" data-target="#users-list">
  @csrf
  <input type="text" name="name" required />
  <button>Save</button>
</form>
```

**Documentation**: [.claude/ajax-system-overview.md](.claude/ajax-system-overview.md), [.claude/ajax/attributes.md](.claude/ajax/attributes.md), [.claude/ajax/actions.md](.claude/ajax/actions.md), [.claude/ajax/examples.md](.claude/ajax/examples.md)

## Prototype Implementation

**UI Prototypes Available**: Complete admin panel and mobile PWA designs in [docs/prototypes/](docs/prototypes/)

### Implementation Guides
- **Start Here**: [.claude/prototypes/IMPLEMENTATION-SUMMARY.md](.claude/prototypes/IMPLEMENTATION-SUMMARY.md) - Executive summary
- **Full Plan**: [.claude/prototypes/PROTOTYPE-IMPLEMENTATION-PLAN.md](.claude/prototypes/PROTOTYPE-IMPLEMENTATION-PLAN.md) - 16-week roadmap
- **Quick Start**: [.claude/prototypes/QUICK-START-IMPLEMENTATION.md](.claude/prototypes/QUICK-START-IMPLEMENTATION.md) - 3-day foundation
- **Analysis**: [.claude/prototypes/PROTOTYPE-ANALYSIS.md](.claude/prototypes/PROTOTYPE-ANALYSIS.md) - Component inventory

### Available Prototypes
- ✅ Authentication (Mobile OTP)
- ✅ Dashboard & Navigation
- ✅ Organization Module UI (Users, Departments, Positions, Roles)
- ✅ Knowledge Base Module UI (Experiences, Templates)
- ✅ Documents Module UI
- ✅ Mobile PWA (Home, Personalized, Analytics, Profile)

## Critical Rules

1. ✅ **Always use `$fillable`**, never `$guarded` in Eloquent models
2. ✅ **Hardcode enum values** in migrations (never reference enum classes)
3. ✅ **Two-phase migrations** for foreign keys (see Database Patterns)
4. ✅ **No nested try-catch** (see Error Handling)
5. ✅ **No intra-module event listeners** (see Events)
6. ✅ **DTOs have no methods** except `toArray()` (see DTOs)
7. ✅ **Rule of Three**: Max 3 lines logic in Controllers/Views/Transformers (see Presentation Logic Separation)
8. ✅ **Admin panel**: Axios for CRUD, BaseWebController for views (see Admin Panel)
9. ✅ **Run commands inside Docker**: `docker exec planexx_app ...`
10. ✅ **Enums must have `label()` method** (see Enums)
11. ✅ **Use `PureUnitTestBase`** when possible (see Test Execution)
12. ✅ **Transformers use `getVirtualFieldResolvers()`** (see Transformers)

## Common Patterns

### Service Providers
```php
public function register(): void
{
    $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
}

public function boot(): void
{
    $this->loadRoutesFrom(ProviderUtility::corePath('User/Routes/V1/routes.php'));
    $this->loadMigrationsFrom(ProviderUtility::corePath('User/Database/Migrations'));
}
```

## Quick Reference by Task

| Task | Read This |
|------|-----------|
| **Implementing prototypes** | [.claude/prototypes/IMPLEMENTATION-SUMMARY.md](.claude/prototypes/IMPLEMENTATION-SUMMARY.md) |
| **New forms/AJAX requests** | [.claude/ajax-system-overview.md](.claude/ajax-system-overview.md) |
| Creating new module | [.claude/architecture.md](.claude/architecture.md) |
| Working with models | [.claude/domain/entities.md](.claude/domain/entities.md) |
| Creating DTOs | [.claude/application/dtos.md](.claude/application/dtos.md) |
| Writing services | [.claude/application/services.md](.claude/application/services.md) |
| Building APIs | [.claude/api/design.md](.claude/api/design.md) |
| Building admin panel | [.claude/presentation/base-web-controller.md](.claude/presentation/base-web-controller.md) |
| Web panel guidelines | [.claude/presentation/web-panel-best-practices.md](.claude/presentation/web-panel-best-practices.md) |
| Writing tests | [.claude/testing/guide.md](.claude/testing/guide.md) |
| Database migrations | [.claude/infrastructure/database.md](.claude/infrastructure/database.md) |
| Error handling | [.claude/error-handling.md](.claude/error-handling.md) |

## Recent Important Changes

- **Refactored AJAX System (v2.0)**: Modular, testable, secure architecture with 14 built-in actions
- **Implemented BaseWebController**: API-FIRST foundation for admin panel with internal API forwarding
- **Refactored Transformer system**: No more magic methods, use `getVirtualFieldResolvers()`
- **Moved ValueObjects**: From `app/Domain/` to `app/Bus/`
- **Split HasJobPosition**: Into `HasCreator`, `HasManager`, `HasOwner`, `HasUser`
- **Renamed FormWizard**: To `FormEngine`

## Additional Resources

### Comprehensive Documentation
- [CLAUDE.md](CLAUDE.md) - Quick reference (legacy)
- [.claude/](.claude/) - Detailed guides by layer
- [.windsurf/rules/](.windsurf/rules/) - Specific patterns and rules
- [docs/](docs/) - Developer guides, feature documentation

### Key Files to Reference
- [app/Contracts/Repository/BaseRepository.php](app/Contracts/Repository/BaseRepository.php) - Repository pattern
- [app/Contracts/Controller/BaseAPIController.php](app/Contracts/Controller/BaseAPIController.php) - API controllers
- [app/Contracts/Transformer/BaseTransformer.php](app/Contracts/Transformer/BaseTransformer.php) - Response transformation
- [Applications/Contracts/BaseWebController.php](Applications/Contracts/BaseWebController.php) - Admin panel pattern
- [tests/PureUnitTestBase.php](tests/PureUnitTestBase.php) - Fastest test base

### Standards & Tools
- [standards/formatter/pint.json](standards/formatter/pint.json) - PHP CS Fixer config
- [standards/scripts/](standards/scripts/) - Quality assurance tools
- [composer.json](composer.json) - Scripts and dependencies
- [package.json](package.json) - Frontend tooling
