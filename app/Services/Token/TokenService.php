<?php

namespace App\Services\Token;

use App\Repositories\Token\TokenRepository;

class TokenService
{
    private TokenRepository $tokenRepository;

    public function getRepository(): TokenRepository
    {
        return $this->tokenRepository;
    }

    public function setRepository(TokenRepository $tokenRepository): void
    {
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * Generate a new token.
     *
     * @return string
     */
    public function generate(): string
    {
        return $this->tokenRepository->create();
    }

    public function revoke(): void
    {
        $this->tokenRepository->delete();
    }
}