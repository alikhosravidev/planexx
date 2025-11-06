---
trigger: manual
---

# Modular Development Guide in Laravel Planexx Project

## Introduction

The Planexx project uses modular architecture in Laravel, which helps in logical separation of code based on features. Modules are divided into two main types:

- **Core Modules**: Core modules like User located in `app/Core/` that include the main system logic.
- **Feature Modules**: Feature modules like BPMS and Notification located in `Modules/` that implement specific features.

This guide is prepared based on examining the User module structure and provides a complete guide for creating, developing, and editing new modules.

## Overall Structure of a Module

Each module is organized in the following structure:

```
ModuleName/
├── Contracts/
├── DTOs/
├── Database/
│   ├── Factories/
│   └── Migrations/
├── Entities/
├── Enums/
├── Events/
├── Http/
│   ├── Controllers/
│   │   ├── V1/
│   │   │   ├── Admin/
│   │   │   └── Client/
│   ├── Requests/
│   ├── Rules/
│   └── Transformers/
├── Listeners/
├── Mappers/
├── Observers/
├── Providers/
├── Repositories/
│   └── Criteria/
├── Resources/
├── Routes/
│   └── V1/
├── Services/
├── Tests/
│   ├── Integration/
│   └── Unit/
├── Traits/
└── ValueObjects/
```

### Responsibility Explanation for Each Section

#### Contracts
- **Task**: Define contracts (interfaces) for classes and services.
- **Example**: `UserRepositoryInterface` for defining repository methods.
- **Note**: Ensure compliance with SOLID principles.

#### DTOs (Data Transfer Objects)
- **Task**: Transfer data between layers without business logic.
- **Example**: Classes for transferring form or API data.

#### Database
- **Task**: Manage database structure.
- **Subsections**:
    - **Factories**: Create test data with Faker.
    - **Migrations**: Create and modify database tables.
- **Example**: `UserFactory` for creating test users, migrations for users table.

#### Entities
- **Task**: Eloquent models that represent database entities.
- **Example**: `User.php` that includes fields, relationships, and business methods.
- **Note**: Use traits like `HasRoles`, `HasPermissions` for additional capabilities.

#### Enums
- **Task**: Define constant and enumerable values.
- **Example**: `UserTypeEnum`, `GenderEnum` for user types and gender.

#### Events
- **Task**: Define system events for decoupling.
- **Example**: Events like UserCreated or UserUpdated.

#### Http
- **Task**: Manage HTTP requests and responses.
- **Subsections**:
    - **Controllers**: API controllers that manage request logic.
    - **Requests**: Request validation classes.
    - **Rules**: Custom validation rules.
    - **Transformers**: Transform data for API responses (like Fractal).
- **Example**: `UserController` in `V1/Admin/` and `V1/Client/`.

#### Listeners
- **Task**: Event listeners for executing logic after events.
- **Example**: Listener for sending email after user registration.

#### Mappers
- **Task**: Transform data between layers (e.g., from Entity to DTO).
- **Example**: Classes for mapping data.

#### Observers
- **Task**: Monitor model changes and execute automatic logic.
- **Example**: Observer for User that performs specific operations after saving.

#### Providers
- **Task**: Register services, bindings, and events in the Service Container.
- **Example**: `UserServiceProvider` for registering repositories and services.

#### Repositories
- **Task**: Data access layer, separate from business logic.
- **Subsections**:
    - **Criteria**: Classes for complex filtering (like advanced Repository Pattern).
- **Example**: `UserRepository` with methods like `findByMobile`.

#### Resources
- **Task**: API Resource resources in Laravel for converting models to JSON arrays.
- **Example**: `UserResource` for API responses.

#### Routes
- **Task**: Define API routes.
- **Subsections**:
    - **V1/**: API version 1.
- **Example**: `api.php` file for defining User routes.

#### Services
- **Task**: Complex business logic that doesn't fit in controllers.
- **Example**: `Auth/` for authentication services, `OTPService` for one-time codes.

#### Tests
- **Task**: Unit and integration tests.
- **Subsections**:
    - **Unit**: Test classes in isolation.
    - **Integration**: Test interaction between components.
- **Example**: Tests for UserRepository and UserController.

#### Traits
- **Task**: Reusable capabilities for models or classes.
- **Example**: `HasApiTokens` for managing API tokens.

#### ValueObjects
- **Task**: Immutable value objects that have business logic.
- **Example**: Classes like `Email` or `PhoneNumber`.

## Steps to Create a New Module

### 1. Decide Module Type
- If the module is a core system module: in `app/Core/ModuleName/`
- If the module is a feature module: in `Modules/ModuleName/`

### 2. Create Folder Structure
- Follow the structure above.
- Only create folders you need.
- **Important**: For empty directories that need to be tracked in Git, add a `.gitkeep` file to ensure the directory structure is maintained in version control. This is especially useful for directories like `Criteria/`, `Factories/`, or any other folder that might initially be empty but is part of the module architecture.

### 3. Create Main Entity
- Create the Eloquent model in `Entities/`.
- Define `fillable`, `casts`, `hidden` fields.
- Add relationships (relations).
- Inherit from BaseModel.

### 4. Create Migration and Factory
- Create migration in `Database/Migrations/`.
- Define table fields.
- Create factory in `Database/Factories/` for test data.

### 5. Create Repository
- Inherit from BaseRepository.
- Implement the corresponding interface.
- Add search and create methods.

### 6. Create Services
- Place business logic in services.
- Use Dependency Injection.

### 7. Create Controllers
- Inherit from BaseController.
- Implement hook methods like `beforeIndex`, `customizeQuery`.
- Separate for Admin and Client.

### 8. Create Routes and Transformers
- Define routes in `Routes/V1/`.
- Create transformers for data transformation.

### 9. Create Tests
- Unit tests for classes.
- Integration tests for API endpoints.

### 10. Register in Service Provider
- Create the provider.
- Bind repositories and services.

### 11. Add to composer.json (if needed)
- If a new namespace is added, update autoload.

## Development and Editing Tips for Modules

- **Single Responsibility Principle**: Each class should have only one responsibility.
- **Dependency Injection**: Use constructor injection.
- **Logging**: Use logger() for logging important events.
- **Authorization**: Check permissions in controllers.
- **Versioning**: Place APIs in V1/ and prepare for future versions.
- **Testing**: Always write and run tests.
- **Documentation**: Document code with meaningful comments, but avoid meaningless comments.
- **Naming Conventions**: Use PascalCase for classes, camelCase for methods.
- **Error Handling**: Use custom exceptions and handle errors.
- **Performance**: Use Eager Loading for relationships.
- **Security**: Take input validation seriously.

## Example from User Module

The User module includes user management, authentication, addresses, cities, etc. Its structure fully follows the guide above.

- Entity: `User.php` with fields like mobile, email, roles, etc.
- Repository: `UserRepository` with methods like `findByMobile`.
- Service: `Auth/` for authentication.
- Controller: `UserControllerExample` that inherits from BaseController.

For further development, you can add new sections like Notifications or Payments.

## Conclusion

This guide provides a foundation for modular development in Planexx. By following this structure, the code will be maintainable, testable, and scalable. For each new module, first plan the structure and implement step by step.
