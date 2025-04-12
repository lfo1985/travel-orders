<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository
{
    /**
     * Get all users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return User::all();
    }

    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id)
    {
        return User::find($id);
    }

    /**
     * Find a user by email.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::byEmail($email)->first();
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data)
    {
        return User::create($data);
    }

    /**
     * Update an existing user.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $user = $this->findById($id);
        if ($user) {
            return $user->update($data);
        }
        return false;
    }

    /**
     * Delete a user by ID.
     *
     * @param int $id
     * @return bool|null
     */
    public function delete($id)
    {
        $user = $this->findById($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}
