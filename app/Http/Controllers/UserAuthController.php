<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\LogoutUserRequest;
use App\Repositories\Token\TokenRepository;
use App\Repositories\User\UserRepository;
use App\Services\Token\TokenService;
use App\Services\User\AuthService;
use Illuminate\Auth\AuthenticationException;

class UserAuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {
        $this->authService = new AuthService(
            new UserRepository(),
            new TokenRepository(),
            new TokenService()
        );   
    }

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
