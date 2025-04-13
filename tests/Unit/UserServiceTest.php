<?php

namespace Tests\Unit;

use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Repositories\User\UserRepository;
use App\Services\User\UserService;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    public function test_register_with_success_new_user()
    {
        $user = new User([
            'name' => 'Admin',
            'email' => 'admin@travelorders.com',
            'password' => '12345678',
        ]);
        $mockUserRepository = $this->createMock(UserRepository::class);
        $mockUserRepository
            ->method('create')
            ->willReturn($user);

        $userService = new UserService($mockUserRepository);
        $newUser = $userService->createUser([
            'name' => 'Admin',
            'email' => 'admin@travelorders.com',
            'password' => '12345678',
        ]);

        $this->assertNotNull($newUser);
        $this->assertEquals('Admin', data_get($newUser, 'name'));
        $this->assertEquals('admin@travelorders.com', data_get($newUser, 'email'));
        $this->assertNotNull(data_get($newUser, 'password'));
        $this->assertNotEquals('12345678', data_get($newUser, 'password'));
        $this->assertEquals(60, strlen(data_get($newUser, 'password')));
    }

    public function test_update_user_with_success()
    {
        $userFind = new User([
            'name' => 'Admin',
            'email' => 'admin@travelorders.com',
            'password' => '12345678',
        ]);

        $dataUpdated = [
            'name' => 'Admin versão 2',
            'email' => 'admin@travelorders.com',
            'password' => '12345678',
        ];

        $userUpdate = new User($dataUpdated);

        $mockUserRepository = $this->createMock(UserRepository::class);

        $mockUserRepository
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($userFind);
        $mockUserRepository
            ->method('update')
            ->willReturn($userUpdate);

        $userService = new UserService($mockUserRepository);
        $userUpdated = $userService->updateUser(1, $dataUpdated);

        $this->assertEquals('Admin versão 2', data_get($userUpdated, 'name'));
    }

    public function test_user_not_found()
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User not found');
        $this->expectExceptionCode(404);

        $mockUserRepository = $this->createMock(UserRepository::class);
        $mockUserRepository
            ->method('findById')
            ->willReturn(null);

        $userService = new UserService($mockUserRepository);
        $userService->getUserById(1);
    }

    public function test_update_user_not_found()
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User not found');
        $this->expectExceptionCode(404);

        $mockUserRepository = $this->createMock(UserRepository::class);
        $mockUserRepository
            ->method('findById')
            ->willReturn(null);

        $userService = new UserService($mockUserRepository);
        $userService->updateUser(1, []);
    }

    public function test_delete_user_not_found()
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User not found');
        $this->expectExceptionCode(404);

        $mockUserRepository = $this->createMock(UserRepository::class);
        $mockUserRepository
            ->method('findById')
            ->willReturn(null);

        $userService = new UserService($mockUserRepository);
        $userService->deleteUser(1);
    }
}
