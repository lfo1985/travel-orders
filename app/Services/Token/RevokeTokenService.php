<?php

namespace App\Services\Token;

use App\Repositories\Token\TokenRepository;

class RevokeTokenService
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
        $this->tokenRepository->delete();
        return $this->tokenRepository->create();
    }
}