<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Comment\CreateCommentAction;
use App\DTOs\Comment\CreateCommentDTO;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends ApiController
{
    public function __construct(
        private CommentRepository $comments,
        private PostRepository $posts,
        private CreateCommentAction $createComment
    ) {}

    public function index(string $postUuid): JsonResponse
    {
        $post = $this->posts->findBy('uuid', $postUuid);

        if (!$post) {
            return $this->notFound('Post not found');
        }

        $comments = $this->comments->getPostComments($post->id);

        return $this->success(CommentResource::collection($comments));
    }

    public function store(CreateCommentRequest $request, string $postUuid): JsonResponse
    {
        $post = $this->posts->findBy('uuid', $postUuid);

        if (!$post) {
            return $this->notFound('Post not found');
        }

        $this->authorize('create', Comment::class);

        $dto = new CreateCommentDTO(
            post_id: $post->id,
            user_id: Auth::id(),
            content: $request->input('content'),
            parent_id: $request->input('parent_id')
        );

        $comment = $this->createComment->execute($dto);
        $comment->load('user');

        return $this->created(new CommentResource($comment), 'Comment created successfully');
    }

    public function destroy(string $postUuid, int $commentId): JsonResponse
    {
        $comment = $this->comments->find($commentId);

        if (!$comment) {
            return $this->notFound('Comment not found');
        }

        $this->authorize('delete', $comment);

        $this->comments->delete($comment);

        return $this->noContent();
    }
}
