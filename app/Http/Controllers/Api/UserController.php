<?php

namespace App\Http\Controllers\Api;

use App\Events\PublicMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::select('id', 'name', 'email', 'avatar_path', 'created_at')
            ->orderByDesc('id')
            ->paginate(15);

        return response()->json([
            'status' => true,
            'message' => 'Users fetched successfully.',
            'data' => UserResource::collection($users->getCollection())->resolve($request),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $imagePath = $this->storeImage($request);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'avatar_path' => $imagePath,
            ]);
        } catch (Throwable $exception) {
            if ($imagePath) {
                Storage::delete($imagePath);
            }

            throw $exception;
        }

        event(new PublicMessageSent('New user created: '.$user->name));

        return response()->json([
            'status' => true,
            'message' => 'User created successfully.',
            'data' => new UserResource($user),
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json([
            'status' => true,
            'message' => 'User fetched successfully.',
            'data' => new UserResource($user),
        ]);
    }

    public function avatar(User $user)
    {
        abort_unless($user->avatar_path && Storage::exists($user->avatar_path), 404);

        return Storage::response($user->avatar_path);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $oldImagePath = $user->avatar_path;
        $imagePath = $this->storeImage($request);

        try {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'avatar_path' => $imagePath ?? $user->avatar_path,
            ]);
        } catch (Throwable $exception) {
            if ($imagePath) {
                Storage::delete($imagePath);
            }

            throw $exception;
        }

        if ($imagePath && $oldImagePath) {
            Storage::delete($oldImagePath);
        }

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully.',
            'data' => new UserResource($user),
        ]);
    }

    public function destroy(User $user)
    {
        $avatarPath = $user->avatar_path;
        $user->delete();

        if ($avatarPath) {
            Storage::delete($avatarPath);
        }

        return response()->noContent();
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $path = Storage::putFile('users', $request->file('image'));

        if (! is_string($path)) {
            throw new RuntimeException('Unable to store the uploaded user image.');
        }

        return $path;
    }
}
