<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\UsersListRequest;
use App\Http\Resources\Users\UserResource;
use App\Models\User;

class UserController extends Controller
{
    /**
     * List users
     */
    public function index(UsersListRequest $request)
    {
        $tasks = User::when($request->has('query'), function ($query) use ($request) {
            return $query->where('name', 'LIKE', '%'.$request->get('query').'%');
        })
            ->orderBy('created_at', 'DESC')
            ->paginate($request->per_page ?? 15);

        return response()->json($tasks->through(fn ($user) => new UserResource($user)));
    }
}
