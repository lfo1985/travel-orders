<?php

namespace App\Http\Controllers;

use App\Exceptions\UserNotFoundException;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\ShowUserResource;
use App\Http\Resources\UserCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(private \App\Services\User\UserService $userService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return sendData(new UserCollection($this->userService->getAllUsers()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $this->userService->createUser($request->all());

            return sendSuccess(200, 'User created successfully.');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return sendError(500, 'Error creating user.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = $this->userService->getUserById($id);

            return sendData(new ShowUserResource($user));
        } catch (UserNotFoundException $e) {
            return sendError($e->getCode(), $e->getMessage());
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return sendError(500, 'Error fetching order.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            $this->userService->updateUser($id, $request->all());

            return sendSuccess(200, 'User updated successfully.');
        } catch (UserNotFoundException $e) {
            return sendError($e->getCode(), $e->getMessage());
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return sendError(500, 'Error updating user.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->userService->deleteUser($id);

            return sendSuccess(200, 'User deleted successfully.');
        } catch (UserNotFoundException $e) {
            return sendError($e->getCode(), $e->getMessage());
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return sendError(500, 'Error deleting user.');
        }
    }
}
