# API Documentation

## Base URL

```
http://localhost:8000/api
```

## Authentication

The API uses Laravel Sanctum for authentication. Include the token in the Authorization header:

```
Authorization: Bearer {token}
```

## Endpoints

### Authentication

#### Register
```http
POST /register
```

**Body:**
```json
{
  "username": "john_doe",
  "email": "john@example.com",
  "password": "Password123",
  "password_confirmation": "Password123",
  "first_name": "John",
  "last_name": "Doe"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Registration successful",
  "data": {
    "user": {...},
    "token": "1|abc123..."
  }
}
```

#### Login
```http
POST /login
```

**Body:**
```json
{
  "email": "john@example.com",
  "password": "Password123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {...},
    "token": "2|xyz789..."
  }
}
```

#### Logout
```http
POST /logout
```

**Headers:** `Authorization: Bearer {token}`

#### Get Current User
```http
GET /me
```

**Headers:** `Authorization: Bearer {token}`

---

### Posts

#### Get Feed
```http
GET /feed?page=1
```

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "uuid": "550e8400-e29b-41d4-a716-446655440000",
      "content": "Hello world!",
      "type": "text",
      "visibility": "public",
      "likes_count": 10,
      "comments_count": 5,
      "author": {...},
      "created_at": "2026-01-30T12:00:00Z"
    }
  ]
}
```

#### Create Post
```http
POST /posts
```

**Headers:** `Authorization: Bearer {token}`

**Body:**
```json
{
  "content": "This is my new post!",
  "type": "text",
  "visibility": "public"
}
```

#### Get Single Post
```http
GET /posts/{uuid}
```

#### Update Post
```http
PUT /posts/{uuid}
```

**Body:**
```json
{
  "content": "Updated content",
  "visibility": "friends"
}
```

#### Delete Post
```http
DELETE /posts/{uuid}
```

#### Like/Unlike Post
```http
POST /posts/{uuid}/like
```

**Response:**
```json
{
  "success": true,
  "message": "Post liked",
  "data": {
    "liked": true,
    "likes_count": 11
  }
}
```

---

### Comments

#### Get Post Comments
```http
GET /posts/{postUuid}/comments
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "uuid": "...",
      "content": "Great post!",
      "likes_count": 3,
      "author": {...},
      "replies": [...],
      "created_at": "2026-01-30T12:30:00Z"
    }
  ]
}
```

#### Create Comment
```http
POST /posts/{postUuid}/comments
```

**Body:**
```json
{
  "content": "Nice post!",
  "parent_id": null
}
```

#### Delete Comment
```http
DELETE /posts/{postUuid}/comments/{commentId}
```

---

### Friendships

#### Get Friends List
```http
GET /friends
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "username": "jane_smith",
      "full_name": "Jane Smith",
      "avatar": "..."
    }
  ]
}
```

#### Get Pending Friend Requests
```http
GET /friends/pending
```

#### Send Friend Request
```http
POST /friends/request/{userId}
```

#### Accept Friend Request
```http
POST /friends/accept/{friendshipId}
```

#### Reject Friend Request
```http
POST /friends/reject/{friendshipId}
```

#### Remove Friend
```http
DELETE /friends/{friendshipId}
```

---

### Follows

#### Get Followers
```http
GET /follow/followers
```

#### Get Following
```http
GET /follow/following
```

#### Follow User
```http
POST /follow/{userId}
```

#### Unfollow User
```http
DELETE /follow/{userId}
```

---

### Profile

#### Get User Profile
```http
GET /profile/{username}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "uuid": "...",
    "username": "john_doe",
    "full_name": "John Doe",
    "bio": "Software developer",
    "avatar": "...",
    "location": "Izhevsk, Russia",
    "is_online": true
  }
}
```

#### Update Profile
```http
PUT /profile
```

**Body (multipart/form-data):**
```
first_name: John
last_name: Doe
bio: Updated bio
avatar: [file]
cover_photo: [file]
```

#### Get User Posts
```http
GET /profile/{username}/posts?page=1
```

---

### Search

#### Search Users
```http
GET /search/users?q=john
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "username": "john_doe",
      "full_name": "John Doe",
      "avatar": "..."
    }
  ]
}
```

---

## Response Codes

- `200` - Success
- `201` - Created
- `204` - No Content
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## Error Response Format

```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["validation error"]
  }
}
```

## Pagination

Endpoints that return lists support pagination:

```
?page=1&per_page=15
```

Response includes pagination metadata:

```json
{
  "data": [...],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 73
  }
}
```
