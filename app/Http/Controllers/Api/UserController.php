<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Exception;

class UserController extends Controller
{
    /**
     * Display paginated users.
     */
    public function index()
    {
        try {
            $users = User::select('id', 'name', 'email', 'created_at')->orderBy('id', 'desc')->paginate(15);

            return response()->json([
                'status' => true,
                'message' => 'Users fetched successfully.',
                'data' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ]
            ], 200);
        } catch (Exception $e) {
            return $this->serverError($e);
        }
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed.',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'User created successfully.',
                'data'    => $user,
            ], 201);
        } catch (QueryException $e) {
            return $this->serverError($e, 'Database error occurred.');
        } catch (Exception $e) {
            return $this->serverError($e);
        }
    }

    /**
     * Show user details.
     */
    public function show(User $user)
    {
        return response()->json([
            'status'  => true,
            'message' => 'User fetched successfully.',
            'data'    => $user
        ], 200);
    }

    /**
     * Update a user.
     */
    public function update(Request $request, User $user)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'  => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed.',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $user->update($request->only(['name', 'email']));

            return response()->json([
                'status'  => true,
                'message' => 'User updated successfully.',
                'data'    => $user,
            ], 200);
        } catch (QueryException $e) {
            return $this->serverError($e, 'Database error occurred.');
        } catch (Exception $e) {
            return $this->serverError($e);
        }
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return response()->json([
                'status'  => true,
                'message' => 'User deleted successfully.',
            ], 204);
        } catch (QueryException $e) {
            return $this->serverError($e, 'Database error occurred.');
        } catch (Exception $e) {
            return $this->serverError($e);
        }
    }

    /**
     * Centralized server error handler.
     */
    protected function serverError(Exception $e, $customMessage = 'Something went wrong, please try again later.')
    {
        return response()->json([
            'status'  => false,
            'message' => $customMessage,
            'error'   => $e->getMessage()
        ], 500);
    }
}
