<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Friendship\AcceptFriendRequestAction;
use App\Actions\Friendship\SendFriendRequestAction;
use App\Http\Resources\FriendshipResource;
use App\Http\Resources\UserResource;
use App\Repositories\FriendshipRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FriendshipController extends ApiController
{
    public function __construct(
        private FriendshipRepository $friendships,
        private UserRepository $users,
        private SendFriendRequestAction $sendRequest,
        private AcceptFriendRequestAction $acceptRequest
    ) {}

    public function friends(): JsonResponse
    {
        $friends = Auth::user()->friends;

        return $this->success(UserResource::collection($friends));
    }

    public function pending(): JsonResponse
    {
        $requests = $this->friendships->getPending(Auth::id());

        return $this->success(FriendshipResource::collection($requests));
    }

    public function sendRequest(int $friendId): JsonResponse
    {
        try {
            $friendship = $this->sendRequest->execute(Auth::id(), $friendId);

            return $this->created(
                new FriendshipResource($friendship),
                'Friend request sent'
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function acceptRequest(int $friendshipId): JsonResponse
    {
        $friendship = $this->friendships->find($friendshipId);

        if (!$friendship) {
            return $this->notFound('Friend request not found');
        }

        try {
            $friendship = $this->acceptRequest->execute($friendship, Auth::id());

            return $this->success(
                new FriendshipResource($friendship),
                'Friend request accepted'
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function rejectRequest(int $friendshipId): JsonResponse
    {
        $friendship = $this->friendships->find($friendshipId);

        if (!$friendship || $friendship->friend_id !== Auth::id()) {
            return $this->notFound('Friend request not found');
        }

        $this->friendships->update($friendship, ['status' => 'rejected']);

        return $this->success(null, 'Friend request rejected');
    }

    public function removeFriend(int $friendshipId): JsonResponse
    {
        $friendship = $this->friendships->find($friendshipId);

        if (!$friendship) {
            return $this->notFound('Friendship not found');
        }

        if ($friendship->user_id !== Auth::id() && $friendship->friend_id !== Auth::id()) {
            return $this->error('Unauthorized', 403);
        }

        $this->friendships->delete($friendship);

        return $this->noContent();
    }
}
