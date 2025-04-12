<?php

namespace App\Services\User;

use App\Exceptions\AuthEmailNotFoundException;
use App\Exceptions\AuthLoginInvalidException;
use App\Models\User;
use App\Repositories\Token\SanctumRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * LoginService constructor.
     *
     * @param UserRepository $userRepository
     * @param SanctumRepository $tokenRepository
     * @return void
     */
    public function __construct(
        private UserRepository $userRepository,
        private SanctumRepository $sanctumRepository
    ) {
        $this->userRepository = $userRepository;
        $this->sanctumRepository = $sanctumRepository;
    }

    /**
     * Login a user with the given email and password.
     *
     * @param string $email
     * @param string $password
     * @return array
     */
    public function login(string $email, string $password): array
    {
        $user = $this->checkUser($email, $password);

        return [
            'token' => $this->sanctumRepository->create($user),
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
        $this->sanctumRepository->delete($user);
    }

    /**
     * Check if the user exists and validate the password.
     *
     * @param string $email
     * @param string $password
     * @return User
     * @throws AuthenticationException|AuthEmailNotFoundException
     */
    public function checkUser(string $email, string $password): User
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
     * @throws AuthEmailNotFoundException
     */
    public function checkEmail(string $email): User
    {
        $user = $this->userRepository->findByEmail($email);

        if(!$user) {
            except('E-mail not found.', 401, AuthEmailNotFoundException::class);
        }

        return $user;
    }

    /**
     * Check if the password matches the hashed password.
     *
     * @param string $password
     * @param string $hashedPassword
     * @return void
     * @throws AuthLoginInvalidException
     */
    public function checkPassword(string $password, string $hashedPassword): void
    {
        if (!Hash::check($password, $hashedPassword)) {
            except('Login is invalid.', 401, AuthLoginInvalidException::class);
        }
    }
}
