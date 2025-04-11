<?php

namespace App\Repositories\Token;

use App\Models\User;

class TokenRepository
{
    /**
     * The user instance.
     * 
     * @var User
     */
    private User $user;

    /**
     * Get the user instance.
     * 
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Set the user instance.
     * 
     * @param User $user
     * @return void
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * Create a new token for the user.
     *
     * @return string
     */
    public function create()
    {
        $id = data_get($this->getUser(), 'id');
        $name = data_get($this->getUser(), 'name');
        $userName = slug($name);
        $authToken = $this->getUser()->createToken(padLeft($id, 0, 10) . '-' . $userName . '-auth-token');
        return data_get($authToken, 'plainTextToken');
    }

    /**
     * Delete all tokens for the user.
     * 
     * @return void
     */
    public function delete()
    {
        $this->getUser()->tokens()->delete();
    }
}