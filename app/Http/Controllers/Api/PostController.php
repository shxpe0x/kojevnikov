<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Post\CreatePostAction;
use App\Actions\Post\LikePostAction;
use App\DTOs\Post\CreatePostDTO;
use App\Enums\PostType;
use App\Enums\PostVisibility;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PostController extends ApiController
{
    public function __construct(
        private PostRepository $posts,
        private CreatePostAction $createPost,
        private LikePostAction $likePost
    ) {}

    public function index(): JsonResponse
    {
        $posts = $this->posts->getFeed(Auth::user());

        return $this->success(PostResource::collection($posts));
    }

    public function store(CreatePostRequest $request): JsonResponse
    {
        $this->authorize('create', Post::class);

        $dto = new CreatePostDTO(
            user_id: Auth::id(),
            content: $request->input('content'),
            type: PostType::from($request->input('type', 'text')),
            visibility: PostVisibility::from($request->input('visibility', 'public'))
        );

        $post = $this->createPost->execute($dto);
        $post->load(['user', 'media']);

        return $this->created(new PostResource($post), 'Post created successfully');
    }

    public function show(string $uuid): JsonResponse
    {
        $post = $this->posts->findBy('uuid', $uuid);

        if (!$post) {
            return $this->notFound('Post not found');
        }

        $this->authorize('view', $post);

        $post->load(['user', 'media', 'comments.user']);

        return $this->success(new PostResource($post));
    }

    public function update(UpdatePostRequest $request, string $uuid): JsonResponse
    {
        $post = $this->posts->findBy('uuid', $uuid);

        if (!$post) {
            return $this->notFound('Post not found');
        }

        $this->authorize('update', $post);

        $post = $this->posts->update($post, $request->validated());

        return $this->success(new PostResource($post), 'Post updated successfully');
    }

    public function destroy(string $uuid): JsonResponse
    {
        $post = $this->posts->findBy('uuid', $uuid);

        if (!$post) {
            return $this->notFound('Post not found');
        }

        $this->authorize('delete', $post);

        $this->posts->delete($post);

        return $this->noContent();
    }

    public function like(string $uuid): JsonResponse
    {
        $post = $this->posts->findBy('uuid', $uuid);

        if (!$post) {
            return $this->notFound('Post not found');
        }

        $liked = $this->likePost->execute(Auth::id(), $post);

        return $this->success([
            'liked' => $liked,
            'likes_count' => $post->fresh()->likes_count,
        ], $liked ? 'Post liked' : 'Post unliked');
    }
}
