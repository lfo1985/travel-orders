<?php

namespace Tests\Unit;

use App\Exceptions\AuthEmailNotFoundException;
use App\Exceptions\AuthLoginInvalidException;
use App\Models\User;
use App\Repositories\Token\SanctumRepository;
use App\Repositories\User\UserRepository;
use App\Services\User\AuthService;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    private function mockAuthService()
    {
        return new AuthService(
            $this->createMock(UserRepository::class),
            $this->createMock(SanctumRepository::class)
        );
    }

    public function test_return_error_where_password_and_hash_no_match(): void
    {
        $this->expectException(AuthLoginInvalidException::class);
        $this->expectExceptionMessage('Login is invalid.');
        $this->expectExceptionCode(401);
        $this->mockAuthService()->checkPassword('admin123', '$2y$12$Op9ILYx7PzqY2J8OCBISxe8HL1GoHU/kfxO4k.bpkXk/bYBUXbSgy');
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
            $this->createMock(SanctumRepository::class)
        );

        $this->expectException(AuthEmailNotFoundException::class);
        $this->expectExceptionMessage('E-mail not found.');
        $this->expectExceptionCode(401);
        $authService->checkEmail('test@test');
    }

    public function test_email_exists_where_login(): void
    {
        $mockUserRepository = $this->createMock(UserRepository::class);
        $mockUserRepository->method('findByEmail')->willReturn(new User([
            'email' => 'test@test'
        ]));

        $authService = new AuthService(
            $mockUserRepository,
            $this->createMock(SanctumRepository::class)
        );

        $user = $authService->checkEmail('test@test');

        $this->assertEquals('test@test', $user->email);
    }
}
