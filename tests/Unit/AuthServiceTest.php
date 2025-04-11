<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\Token\TokenRepository;
use App\Repositories\User\UserRepository;
use App\Services\Token\TokenService;
use App\Services\User\AuthService;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    private function mockAuthService()
    {
        return new AuthService(
            $this->createMock(UserRepository::class),
            $this->createMock(TokenRepository::class),
            $this->createMock(TokenService::class)
        );
    }

    public function test_return_error_where_password_and_hash_no_match(): void
    {
        try {
            $this->mockAuthService()->checkPassword('admin123', '$2y$12$Op9ILYx7PzqY2J8OCBISxe8HL1GoHU/kfxO4k.bpkXk/bYBUXbSgy');
        } catch (AuthenticationException $e) {
            $this->assertInstanceOf(AuthenticationException::class, $e);
            $this->assertEquals('Login is invalid.', $e->getMessage());
        }
    }

    public function test_return_void_where_match_password_and_hash(): void
    {
        $this->mockAuthService()->checkPassword('admin123456', '$2y$12$Op9ILYx7PzqY2J8OCBISxe8HL1GoHU/kfxO4k.bpkXk/bYBUXbSgy');
        $this->assertTrue(true);
    }

    public function test_throw_email_not_found_where_login(): void
    {
        $mockUserRepository = $this->createMock(UserRepository::class);
        $mockUserRepository->method('findByEmail')->willReturn(null);

        $authService = new AuthService(
            $mockUserRepository,
            $this->createMock(TokenRepository::class),
            $this->createMock(TokenService::class)
        );

        try {
            $authService->checkEmail('test@test');
        } catch (AuthenticationException $e) {
            $this->assertInstanceOf(AuthenticationException::class, $e);
            $this->assertEquals('E-mail not found.', $e->getMessage());
        }
    }
}
