# Kojevnikov - Social Network API

![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-green)

Modern social network REST API built with Laravel 11, clean architecture, and industry best practices.

## 🎯 Features

### Core Functionality
- ✅ **User Authentication** - Registration, login, JWT tokens (Sanctum)
- ✅ **Posts** - Create, read, update, delete with media support
- ✅ **Comments** - Nested comments and replies
- ✅ **Likes** - Like posts and comments
- ✅ **Friendships** - Send/accept/reject friend requests
- ✅ **Follow System** - Follow/unfollow users
- ✅ **User Profiles** - Update profile, avatar, cover photo
- ✅ **Feed** - Personalized content feed
- ✅ **Search** - Search users by username/name
- ✅ **Pagination** - All list endpoints paginated
- ✅ **File Uploads** - Images and media support
- ✅ **Permissions** - Policy-based authorization

### Architecture
- **Repository Pattern** - Data access abstraction
- **Action Pattern** - Single-purpose business logic
- **DTO Pattern** - Type-safe data transfer
- **Policy-based Authorization** - Granular access control
- **API Resources** - Consistent response formatting
- **Form Request Validation** - Centralized validation
- **Strict Types** - Full PHP 8.2+ type coverage

## 📋 Requirements

- PHP 8.2+
- Composer 2.x
- MySQL 8+ or PostgreSQL 14+
- Redis (optional, for caching)

## 🚀 Quick Start

### 1. Clone & Install

```bash
git clone https://github.com/shxpe0x/kojevnikov.git
cd kojevnikov
composer install
```

### 2. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kojevnikov
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Run Migrations

```bash
php artisan migrate
```

### 4. Create Storage Link

```bash
php artisan storage:link
```

### 5. Start Development Server

```bash
php artisan serve
```

API available at: `http://localhost:8000/api`

## 📖 Documentation

- [API Documentation](docs/API.md) - Complete API endpoints reference
- [Architecture Guide](docs/ARCHITECTURE.md) - Project architecture details
- [Usage Examples](docs/EXAMPLES.md) - Implementation examples

## 📌 API Endpoints

### Authentication
```
POST   /api/register          - Register new user
POST   /api/login             - Login
POST   /api/logout            - Logout
GET    /api/me                - Get current user
```

### Posts
```
GET    /api/feed              - Get personalized feed
POST   /api/posts             - Create post
GET    /api/posts/{uuid}      - Get single post
PUT    /api/posts/{uuid}      - Update post
DELETE /api/posts/{uuid}      - Delete post
POST   /api/posts/{uuid}/like - Like/unlike post
```

### Comments
```
GET    /api/posts/{uuid}/comments       - Get post comments
POST   /api/posts/{uuid}/comments       - Create comment
DELETE /api/posts/{uuid}/comments/{id}  - Delete comment
```

### Friendships
```
GET    /api/friends                   - Get friends list
GET    /api/friends/pending           - Get pending requests
POST   /api/friends/request/{id}      - Send friend request
POST   /api/friends/accept/{id}       - Accept request
POST   /api/friends/reject/{id}       - Reject request
DELETE /api/friends/{id}              - Remove friend
```

### Follow System
```
GET    /api/follow/followers   - Get followers
GET    /api/follow/following   - Get following
POST   /api/follow/{id}        - Follow user
DELETE /api/follow/{id}        - Unfollow user
```

### Profile
```
GET    /api/profile/{username}        - Get user profile
PUT    /api/profile                   - Update own profile
GET    /api/profile/{username}/posts  - Get user posts
```

### Search
```
GET    /api/search/users?q={query}   - Search users
```

See [API Documentation](docs/API.md) for detailed request/response examples.

## 🔧 Database Schema

### Tables
- `users` - User accounts and profiles
- `posts` - User posts
- `post_media` - Post attachments (images/videos)
- `comments` - Post comments (nested)
- `likes` - Polymorphic likes (posts/comments)
- `friendships` - Friend relationships
- `follows` - Follow relationships
- `notifications` - User notifications

### Key Features
- UUID support for public IDs
- Soft deletes on posts/comments
- Polymorphic relationships
- Optimized indexes
- Timestamps on all tables

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test --filter=PostTest
```

## 📝 Code Quality

```bash
# Check code style
./vendor/bin/pint --test

# Fix code style
./vendor/bin/pint
```

## 👤 Example Usage

### 1. Register User

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "username": "john_doe",
    "email": "john@example.com",
    "password": "Password123",
    "password_confirmation": "Password123",
    "first_name": "John",
    "last_name": "Doe"
  }'
```

### 2. Login

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "Password123"
  }'
```

Response:
```json
{
  "success": true,
  "data": {
    "token": "1|abc123xyz...",
    "user": {...}
  }
}
```

### 3. Create Post

```bash
curl -X POST http://localhost:8000/api/posts \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "content": "Hello from Kojevnikov!",
    "visibility": "public"
  }'
```

### 4. Get Feed

```bash
curl -X GET http://localhost:8000/api/feed \
  -H "Authorization: Bearer {token}"
```

## 🏗️ Project Structure

```
app/
├── Actions/              # Business logic actions
│   ├── Auth/
│   ├── Post/
│   ├── Comment/
│   └── Friendship/
├── DTOs/                 # Data Transfer Objects
├── Enums/                # Application enums
├── Exceptions/           # Custom exceptions
├── Http/
│   ├── Controllers/Api/  # API controllers
│   ├── Requests/         # Form validations
│   └── Resources/        # API resources
├── Models/               # Eloquent models
├── Policies/             # Authorization policies
└── Repositories/         # Data repositories

database/migrations/      # Database migrations
docs/                     # Documentation
routes/api.php            # API routes
```

## 🔐 Authentication

The API uses Laravel Sanctum for token-based authentication.

1. Register or login to get a token
2. Include token in all requests:
   ```
   Authorization: Bearer {token}
   ```
3. Logout to revoke token

## 📚 Best Practices Used

✅ Strict PHP types (`declare(strict_types=1)`)  
✅ Repository pattern for data access  
✅ Action pattern for business logic  
✅ DTO pattern for data transfer  
✅ Policy-based authorization  
✅ Form Request validation  
✅ API Resource transformers  
✅ UUID for public IDs  
✅ Soft deletes  
✅ Eager loading to prevent N+1  
✅ Database indexes  
✅ PSR-12 code style  
✅ Comprehensive error handling  

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📄 License

MIT License

## 🔗 Links

- [Laravel 11 Documentation](https://laravel.com/docs/11.x)
- [Laravel Sanctum](https://laravel.com/docs/11.x/sanctum)
- [API Documentation](docs/API.md)

---

**Built with ❤️ using Laravel 11 & Clean Architecture**
