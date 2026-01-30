# Kojevnikov

![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-green)

Modern Laravel 11 application built with clean architecture principles, strict typing, and industry best practices for 2025-2026.

## 🎯 Features

- **PHP 8.2+** with strict types and modern features
- **Laravel 11** latest framework version
- **Clean Architecture** with clear separation of concerns
- **Repository Pattern** for data access abstraction
- **Action Pattern** for single-purpose business logic
- **DTO Pattern** for type-safe data transfer
- **Value Objects** for domain modeling
- **API Resources** for response transformation
- **Docker** support with PostgreSQL & Redis
- **Code Quality Tools** (Pint, PHP-CS-Fixer, Rector)
- **Comprehensive Testing** setup

## 📋 Requirements

- PHP 8.2 or higher
- Composer 2.x
- PostgreSQL 14+ / MySQL 8+
- Redis 7+ (optional)
- Node.js 18+ & NPM (for assets)

## 🚀 Quick Start

### Option 1: Local Development

```bash
# Clone repository
git clone https://github.com/shxpe0x/kojevnikov.git
cd kojevnikov

# Install and setup
make setup

# Configure database in .env file
# Then run migrations
make migrate

# Start development server
make dev
```

Application will be available at `http://localhost:8000`

### Option 2: Docker Development

```bash
# Clone repository
git clone https://github.com/shxpe0x/kojevnikov.git
cd kojevnikov

# Copy environment file
cp .env.example .env

# Start Docker containers
make docker-up

# Run migrations inside container
make docker-shell
php artisan migrate
```

Application will be available at `http://localhost:8000`

## 📖 Documentation

- [Architecture Guide](docs/ARCHITECTURE.md) - Detailed architecture documentation
- [Usage Examples](docs/EXAMPLES.md) - Complete implementation examples

## 🏗️ Project Structure

```
app/
├── Actions/              # Single-purpose business actions
├── Builders/             # Custom Eloquent query builders
├── Contracts/            # Interfaces and contracts
├── DTOs/                 # Data Transfer Objects
├── Enums/                # Application enums
├── Exceptions/           # Custom exception classes
├── Http/
│   ├── Controllers/      # HTTP controllers (thin layer)
│   ├── Middleware/       # HTTP middleware
│   ├── Requests/         # Form request validation
│   └── Resources/        # API resource transformers
├── Models/               # Eloquent models
├── Repositories/         # Data access repositories
├── Services/             # Complex business logic
├── Traits/               # Reusable traits
└── ValueObjects/         # Immutable value objects

docs/                     # Documentation
tests/
├── Feature/              # Integration tests
└── Unit/                 # Unit tests
```

## 🛠️ Development Commands

```bash
make help              # Show all available commands
make install           # Install dependencies
make setup             # Setup application
make dev               # Start development server
make test              # Run tests
make test-coverage     # Run tests with coverage
make lint              # Check code style
make fix               # Fix code style
make clean             # Clear all caches
make optimize          # Optimize for production
```

## 🐳 Docker Commands

```bash
make docker-build      # Build containers
make docker-up         # Start containers
make docker-down       # Stop containers
make docker-logs       # View logs
make docker-shell      # Access container shell
make docker-fresh      # Rebuild everything
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test --filter=TestName

# Run feature tests only
php artisan test tests/Feature

# Run unit tests only
php artisan test tests/Unit
```

## 📝 Code Quality

### Formatting

```bash
# Check code style
./vendor/bin/pint --test

# Fix code style
./vendor/bin/pint

# Or use make commands
make lint
make fix
```

### Static Analysis (optional)

```bash
# Install PHPStan
composer require --dev phpstan/phpstan larastan/larastan

# Run analysis
./vendor/bin/phpstan analyse
```

## 🏛️ Architecture Principles

### 1. Strict Typing

All files use `declare(strict_types=1)` with full type coverage:

```php
public function execute(CreateUserDTO $dto): User
{
    return $this->repository->create($dto->toArray());
}
```

### 2. Single Responsibility

Each class has one clear purpose:

- **Controllers** - Handle HTTP layer
- **Actions** - Execute single operations  
- **Services** - Orchestrate complex logic
- **Repositories** - Manage data access
- **DTOs** - Transfer data between layers

### 3. Dependency Injection

No static calls, proper constructor injection:

```php
public function __construct(
    private UserRepository $users,
    private EmailService $email
) {}
```

### 4. Value Objects

Immutable objects for domain values:

```php
final readonly class Money
{
    public function __construct(
        public int $amount,
        public string $currency = 'RUB'
    ) {}
}
```

## 🎨 Code Examples

### Creating a Feature

```php
// 1. Create DTO
class CreatePostDTO extends DTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $content
    ) {}
}

// 2. Create Action
class CreatePostAction extends Action
{
    public function __construct(
        private PostRepository $posts
    ) {}

    public function execute(CreatePostDTO $dto): Post
    {
        return $this->posts->create($dto->toArray());
    }
}

// 3. Use in Controller
class PostController extends ApiController
{
    public function store(
        CreatePostRequest $request,
        CreatePostAction $action
    ): JsonResponse {
        $dto = CreatePostDTO::fromArray($request->validated());
        $post = $action->execute($dto);
        
        return $this->created(new PostResource($post));
    }
}
```

See [EXAMPLES.md](docs/EXAMPLES.md) for complete implementation examples.

## 🔧 Configuration

### Database

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=kojevnikov
DB_USERNAME=kojevnikov
DB_PASSWORD=secret
```

### Redis (Optional)

```env
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Queue

```env
QUEUE_CONNECTION=redis
```

## 📚 Best Practices Implemented

✅ Strict types everywhere  
✅ Full type hint coverage  
✅ Constructor property promotion  
✅ Readonly properties (PHP 8.2+)  
✅ Enums instead of constants  
✅ Repository pattern  
✅ Action pattern  
✅ DTO pattern  
✅ Value objects  
✅ API resources  
✅ Form requests  
✅ Custom query builders  
✅ Traits for reusability  
✅ Exception handling  
✅ PSR-12 code style  
✅ Comprehensive testing  

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

**Code Standards:**
- Follow PSR-12
- Use strict types
- Write tests
- Run `make fix` before committing

## 📄 License

MIT License - see [LICENSE](LICENSE) file for details.

## 🔗 Links

- [Laravel Documentation](https://laravel.com/docs/11.x)
- [PHP 8.2 Documentation](https://www.php.net/releases/8.2/)
- [Clean Architecture](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)

---

**Built with ❤️ using Laravel 11 and modern PHP practices**
