# Architecture Documentation

## Overview

This Laravel application follows modern architectural patterns and best practices for maintainable, testable, and scalable code.

## Core Principles

### 1. Strict Typing

All PHP files use `declare(strict_types=1)` at the top. All methods have explicit return types and parameter types.

```php
public function execute(UserDTO $dto): User
{
    // implementation
}
```

### 2. Single Responsibility

Each class has one clear purpose:

- **Controllers** - Handle HTTP requests/responses
- **Actions** - Execute single business operations  
- **Services** - Orchestrate complex business logic
- **Repositories** - Handle data access
- **DTOs** - Transfer data between layers
- **Resources** - Transform data for API responses

### 3. Dependency Injection

No static method calls. All dependencies injected via constructor:

```php
public function __construct(
    private UserRepository $users,
    private EmailService $email
) {}
```

## Directory Structure

```
app/
├── Actions/              # Single-purpose business actions
│   └── User/
│       └── CreateUserAction.php
├── Builders/             # Custom query builders
│   └── BaseBuilder.php
├── Contracts/            # Interfaces
│   └── RepositoryInterface.php
├── DTOs/                 # Data Transfer Objects
│   ├── DTO.php
│   └── User/
│       └── CreateUserDTO.php
├── Enums/                # Application enums
│   └── Status.php
├── Exceptions/           # Custom exceptions
│   ├── BusinessException.php
│   └── NotFoundException.php
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── ApiController.php
│   │       └── UserController.php
│   ├── Middleware/       # HTTP middleware
│   │   ├── ForceJsonResponse.php
│   │   └── ApiVersion.php
│   ├── Requests/         # Form request validation
│   │   └── CreateUserRequest.php
│   └── Resources/        # API resources
│       ├── BaseResource.php
│       └── UserResource.php
├── Models/               # Eloquent models
│   ├── BaseModel.php
│   └── User.php
├── Repositories/         # Data repositories
│   ├── BaseRepository.php
│   └── UserRepository.php
├── Services/             # Complex business logic
│   ├── BaseService.php
│   └── UserService.php
├── Traits/               # Reusable traits
│   └── HasUuid.php
└── ValueObjects/         # Immutable value objects
    ├── Email.php
    └── Money.php
```

## Patterns

### Repository Pattern

Abstracts data layer from business logic:

```php
interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByEmail(string $email): ?User;
}

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->findBy('email', $email);
    }
}
```

### Action Pattern

Single-purpose classes for business logic:

```php
class CreateUserAction extends Action
{
    public function __construct(
        private UserRepository $users
    ) {}

    public function execute(CreateUserDTO $dto): User
    {
        return $this->users->create($dto->toArray());
    }
}
```

### DTO Pattern

Type-safe data containers:

```php
class CreateUserDTO extends DTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password
    ) {}
}
```

### Service Pattern

Orchestrate complex operations:

```php
class UserService extends BaseService
{
    public function __construct(
        private UserRepository $users,
        private CreateUserAction $createUser,
        private EmailService $email
    ) {}

    public function registerUser(CreateUserDTO $dto): User
    {
        $user = $this->createUser->execute($dto);
        $this->email->sendWelcomeEmail($user);
        
        return $user;
    }
}
```

### Value Objects

Immutable objects representing values:

```php
final readonly class Email
{
    public function __construct(
        public string $value
    ) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email');
        }
    }
}
```

## Testing

### Feature Tests

Extend `BaseFeatureTest` for integration tests:

```php
class UserControllerTest extends BaseFeatureTest
{
    public function test_can_create_user(): void
    {
        $response = $this->postJson('/api/users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $response->assertCreated();
    }
}
```

### Unit Tests

Extend `BaseUnitTest` for isolated tests:

```php
class CreateUserActionTest extends BaseUnitTest
{
    public function test_creates_user(): void
    {
        $repository = $this->createMock(UserRepository::class);
        $action = new CreateUserAction($repository);
        
        // test implementation
    }
}
```

## API Standards

### Response Format

All API responses use consistent format:

```json
{
    "success": true,
    "message": "Success",
    "data": {}
}
```

### Error Format

```json
{
    "success": false,
    "message": "Error message",
    "errors": {}
}
```

## Code Quality Tools

- **Laravel Pint** - Code formatting (PSR-12)
- **PHP CS Fixer** - Additional code style fixes
- **Rector** - Automated refactoring
- **PHPUnit** - Testing framework
- **PHPStan/Larastan** - Static analysis (optional)

## Best Practices

1. Always use strict types
2. Type hint everything
3. Use readonly properties when possible
4. Prefer constructor property promotion
5. Use enums instead of constants
6. Avoid magic methods
7. Keep controllers thin
8. Extract logic to actions/services
9. Use DTOs for data transfer
10. Write tests for critical logic
