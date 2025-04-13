<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\LogoutUserRequest;
use App\Repositories\Token\SanctumRepository;
use App\Repositories\User\UserRepository;
use App\Services\User\AuthService;
use Illuminate\Auth\AuthenticationException;

class UserAuthController extends Controller
{
    /**
     * @var AuthService
     */
    private AuthService $authService;
    /**
     * UserAuthController constructor.
     *
     * @param AuthService $authService
     */
    public function __construct() {
        $this->authService = new AuthService(
            new UserRepository(),
            new SanctumRepository()
        );
    }

    /**
     * Login a user.
     * 
     * @param LoginUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    public function login(LoginUserRequest $request)
    {
        try {
            $login = $this->authService->login($request->email, $request->password);

            return sendSuccess(
                200,
                'Logged in successfully',
                [
                    'token' => data_get($login, 'token'),
                    'user' => data_get($login, 'user')
                ]
            );
        } catch (AuthenticationException $e) {
            return sendError(401, $e->getMessage());
        }
    }

    /**
     * Logout a user.
     * 
     * @param LogoutUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    public function logout(LogoutUserRequest $request)
    {
        try {
            $this->authService->logout(data_get($request, 'id'));
            return sendSuccess(200, 'Logout successfully');
        } catch (AuthenticationException $e) {
            return sendError(401, $e->getMessage());
        }
    }
}
