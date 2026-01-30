<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends ApiController
{
    public function __construct(
        private UserRepository $users
    ) {}

    public function users(Request $request): JsonResponse
    {
        $query = $request->input('q', '');

        if (empty($query)) {
            return $this->error('Search query is required');
        }

        $users = $this->users->search($query);

        return $this->success(UserResource::collection($users));
    }
}
