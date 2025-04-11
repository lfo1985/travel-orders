<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\Token\TokenRepository;
use App\Repositories\User\UserRepository;
use App\Services\Token\TokenService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * LoginService constructor.
     * 
     * @param UserRepository $userRepository
     * @param TokenRepository $tokenRepository
     * @param TokenService $tokenService
     * @return void
     */
    public function __construct(
        private UserRepository $userRepository,
        private TokenRepository $tokenRepository,
        private TokenService $tokenService
    ) {
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
        $this->tokenService = $tokenService;
    }

    /**
     * Login a user with the given email and password.
     *
     * @param string $email
     * @param string $password
     * @return array
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function login(string $email, string $password): array
    {
        $user = $this->checkUser($email, $password);
        $this->setUserTokenService($user);

        return [
            'token' => $this->tokenService->generate(),
            'user' => [
                'id' => data_get($user, 'id'),
                'name' => data_get($user, 'name'),
                'email' => data_get($user, 'email'),
            ]
        ];
    }

    /**
     * Logout a user by revoking their token.
     * 
     * @param int $id
     * @return void
     */
    public function logout(int $id): void
    {
        $user = $this->userRepository->findById($id);
        $this->setUserTokenService($user);
        $this->tokenService->revoke();
    }

    /**
     * Check if the user exists and validate the password.
     * 
     * @param string $email
     * @param string $password
     * @return User
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function checkUser($email, $password): User
    {
        $user = $this->checkEmail($email);
        $this->checkPassword($password, data_get($user, 'password'));
        return $user;
    }

    /**
     * Check if the email exists in the database.
     * 
     * @param string $email
     * @return User
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function checkEmail(string $email): User
    {
        $user = $this->userRepository->findByEmail($email);

        if(!$user) {
            except('E-mail not found.', AuthenticationException::class);
        }

        return $user;
    }

    /**
     * Check if the password matches the hashed password.
     * 
     * @param string $password
     * @param string $hashedPassword
     * @return void
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function checkPassword(string $password, string $hashedPassword)
    {
        if (!Hash::check($password, $hashedPassword)) {
            except( 'Login is invalid.', AuthenticationException::class );
        }
    }

    /**
     * Set the user for the token service.
     * 
     * @param User $user
     * @return TokenService
     */
    private function setUserTokenService(User $user): TokenService
    {
        $this->tokenRepository->setUser($user);
        $this->tokenService->setRepository($this->tokenRepository);
        return $this->tokenService;
    }
}
