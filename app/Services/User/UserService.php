<?php

namespace App\Services\User;

use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Repositories\User\UserRepository;

class UserService
{
    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     * @return void
     */
    public function __construct(private UserRepository $userRepository){}

    /**
     * Get all users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsers()
    {
        return $this->userRepository->getAll();
    }

    /**
     * Get a user by ID.
     * 
     * @param int $id
     * @return User|null
     */
    public function getUserById(int $id): ?User
    {
        $user = $this->userRepository->findById($id);

        $this->checKUserExists($user);

        return $user;
    }

    /**
     * Create a new user.
     * 
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        return $this->userRepository->create($data);
    }

    /**
     * Update an existing user.
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateUser(int $id, array $data): User
    {
        $user = $this->userRepository->findById($id);

        $this->checKUserExists($user);

        return $this->userRepository->update($id, $data);
    }

    /**
     * Delete a user by ID.
     * 
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        $user = $this->userRepository->findById($id);

        $this->checKUserExists($user);

        return $this->userRepository->delete($id);
    }

    /**
     * Check if the user exists.
     * 
     * @param User|null $user
     * @return bool
     * @throws UserNotFoundException
     */
    public function checkUserExists(?User $user): bool
    {
        if (!$user) {
            throw new UserNotFoundException('User not found.', 404);
        }   
        return true;
    }
}