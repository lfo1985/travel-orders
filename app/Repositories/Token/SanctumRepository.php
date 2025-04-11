<?php

namespace App\Repositories\Token;

use App\Contracts\TokenRepositoryInterface;
use App\Models\User;

class SanctumRepository implements TokenRepositoryInterface
{
    /**
     * Create a new token for the user.
     *
     * @param User $user
     * @return string
     */
    public function create(User $user): string
    {
        $id = data_get($user, 'id');
        $name = data_get($user, 'name');
        $tokenName = padLeft($id, 0, 10) . '-' . slug($name) . '-auth-token';
        $authToken = $user->createToken($tokenName);

        return data_get($authToken, 'plainTextToken');
    }

    /**
     * Delete all tokens for the user.
     *
     * @param User $user
     * @return void
     */
    public function delete(User $user): void
    {
        $user->tokens()->delete();
    }
}
