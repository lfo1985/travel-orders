<?php

namespace App\Contracts;

use App\Models\User;

interface TokenRepositoryInterface
{
    /**
     * Create a new token for the user.
     * 
     * @param User $user
     * @return string
     */
    public function create(User $user): string;
    
    /**
     * Delete all tokens for the user.
     * 
     * @param User $user
     * @return void
     */
    public function delete(User $user): void;
}
