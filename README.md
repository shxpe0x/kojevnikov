# Kojevnikov

Modern Laravel 11 application built with best practices and clean architecture principles.

## Tech Stack

- **PHP 8.2+** - Latest PHP version with typed properties and enums
- **Laravel 11** - Latest Laravel framework
- **MySQL/PostgreSQL** - Relational database
- **Redis** - Caching and queues (optional)

## Architecture Principles

This project follows modern Laravel best practices:

### Code Organization

- **Actions** - Single-purpose classes for business logic
- **DTOs (Data Transfer Objects)** - Type-safe data containers
- **Repository Pattern** - Data access abstraction
- **Service Layer** - Complex business logic
- **Form Requests** - Input validation
- **API Resources** - Response transformation
- **Events & Listeners** - Decoupled event handling
- **Jobs** - Asynchronous task processing
- **Policies** - Authorization logic

### Code Quality

- Strict types (`declare(strict_types=1)`)
- Typed properties and return types
- PHPDoc for complex logic
- Laravel Pint for code formatting
- PHPUnit for testing

## Project Structure

```
app/
├── Actions/          # Business logic actions
├── DTOs/            # Data Transfer Objects
├── Enums/           # Application enums
├── Events/          # Event classes
├── Exceptions/      # Custom exceptions
├── Http/
│   ├── Controllers/ # HTTP controllers (thin)
│   ├── Requests/    # Form request validation
│   ├── Resources/   # API resources
│   └── Middleware/  # HTTP middleware
├── Jobs/            # Queue jobs
├── Listeners/       # Event listeners
├── Models/          # Eloquent models
├── Policies/        # Authorization policies
├── Repositories/    # Data repositories
└── Services/        # Service layer
```

## Installation

```bash
# Clone repository
git clone https://github.com/shxpe0x/kojevnikov.git
cd kojevnikov

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Start development server
php artisan serve
```

## Development

```bash
# Code formatting
./vendor/bin/pint

# Run tests
php artisan test

# Run specific test
php artisan test --filter=TestName
```

## Best Practices Implemented

1. **Strict Types** - All files use `declare(strict_types=1)`
2. **Type Hints** - Full type coverage for parameters and returns
3. **Single Responsibility** - Each class has one clear purpose
4. **Dependency Injection** - No static calls, proper DI
5. **Interface Segregation** - Small, focused interfaces
6. **Repository Pattern** - Data access abstraction
7. **DTO Pattern** - Type-safe data transfer
8. **Action Pattern** - Single-purpose business logic
9. **Event-Driven** - Decoupled domain events
10. **API Resources** - Clean response transformation

## Contributing

Follow PSR-12 coding standards and ensure all tests pass before submitting PRs.

## License

MIT License
