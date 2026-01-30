# Usage Examples

## Creating a New Feature

### 1. Create Model

```php
<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use App\Traits\HasUuid;

class Post extends BaseModel
{
    use HasUuid;

    protected $fillable = [
        'title',
        'content',
        'status',
        'user_id',
    ];

    protected $casts = [
        'status' => Status::class,
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

### 2. Create Migration

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->uuid('uuid')->unique();
    $table->string('title');
    $table->text('content');
    $table->string('status')->default('pending');
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->timestamp('published_at')->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    $table->index(['status', 'published_at']);
});
```

### 3. Create DTO

```php
<?php

declare(strict_types=1);

namespace App\DTOs\Post;

use App\DTOs\DTO;
use App\Enums\Status;

class CreatePostDTO extends DTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $content,
        public readonly Status $status,
        public readonly int $userId
    ) {}
}
```

### 4. Create Repository

```php
<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

class PostRepository extends BaseRepository
{
    public function __construct(Post $model)
    {
        parent::__construct($model);
    }

    public function findPublished(): Collection
    {
        return $this->model
            ->where('status', 'active')
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->get();
    }

    public function findByUserId(int $userId): Collection
    {
        return $this->findManyBy('user_id', $userId);
    }
}
```

### 5. Create Action

```php
<?php

declare(strict_types=1);

namespace App\Actions\Post;

use App\Actions\Action;
use App\DTOs\Post\CreatePostDTO;
use App\Models\Post;
use App\Repositories\PostRepository;

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
```

### 6. Create Request

```php
<?php

declare(strict_types=1);

namespace App\Http\Requests\Post;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'status' => ['required', Rule::enum(Status::class)],
        ];
    }
}
```

### 7. Create Resource

```php
<?php

declare(strict_types=1);

namespace App\Http\Resources\Post;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class PostResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status->value,
            'author' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
```

### 8. Create Controller

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Post\CreatePostAction;
use App\DTOs\Post\CreatePostDTO;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Resources\Post\PostResource;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;

class PostController extends ApiController
{
    public function __construct(
        private PostRepository $posts,
        private CreatePostAction $createPost
    ) {}

    public function index(): JsonResponse
    {
        $posts = $this->posts->paginate();

        return $this->success(
            PostResource::collection($posts)
        );
    }

    public function store(CreatePostRequest $request): JsonResponse
    {
        $dto = new CreatePostDTO(
            title: $request->input('title'),
            content: $request->input('content'),
            status: $request->enum('status', Status::class),
            userId: $request->user()->id
        );

        $post = $this->createPost->execute($dto);

        return $this->created(
            new PostResource($post),
            'Post created successfully'
        );
    }

    public function show(string $uuid): JsonResponse
    {
        $post = $this->posts->findBy('uuid', $uuid);

        if (!$post) {
            return $this->notFound('Post not found');
        }

        return $this->success(new PostResource($post));
    }
}
```

### 9. Register Routes

```php
// routes/api.php
Route::middleware(['api', 'auth:sanctum'])->group(function () {
    Route::apiResource('posts', PostController::class);
});
```

### 10. Write Tests

```php
<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use Tests\Feature\BaseFeatureTest;

class PostControllerTest extends BaseFeatureTest
{
    public function test_can_create_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/posts', [
                'title' => 'Test Post',
                'content' => 'Test content',
                'status' => 'active',
            ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'uuid',
                    'title',
                    'content',
                ],
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'user_id' => $user->id,
        ]);
    }
}
```

## Using Value Objects

```php
// In your model
protected $casts = [
    'price' => Money::class,
    'email' => Email::class,
];

// Usage
$product->price = Money::fromFloat(99.99);
echo $product->price->format(); // "99.99 RUB"

$user->email = new Email('user@example.com');
echo $user->email->domain(); // "example.com"
```

## Using Custom Query Builder

```php
class Post extends BaseModel
{
    public function newEloquentBuilder($query)
    {
        return new PostBuilder($query);
    }
}

class PostBuilder extends BaseBuilder
{
    public function published(): self
    {
        return $this->where('status', 'active')
            ->whereNotNull('published_at');
    }
}

// Usage
$posts = Post::query()
    ->published()
    ->dateRange('published_at', '2026-01-01', '2026-01-31')
    ->get();
```
