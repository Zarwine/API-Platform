<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Article;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
    }

    public function testGetEmail(): void
    {
        $value = 'test@test.com';

        $response = $this->user->setEmail($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getUsername());
        self::assertEquals($value, $this->user->getEmail());
    }

    public function testGetRoles(): void
    {
        $value = ['ROLE_ADMIN'];
        $response = $this->user->setRoles($value);

        self::assertInstanceOf(User::class, $response);
        self::assertContains('ROLE_USER', $this->user->getRoles());
        self::assertContains('ROLE_ADMIN', $this->user->getRoles());
    }

    public function testGetPassword(): void
    {
        $value = '123456';
        $response = $this->user->setPassword($value);

        self::assertInstanceOf(User::class, $response);
        self::assertContains($value, $this->user->getPassword());
    }

    public function testGetArticle(): void
    {
        $value = new Article();
        $response = $this->user->addArticle($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(1, $this->user->getArticles());
        self::assertTrue($this->user->getArticles()->contains($value));

        $response = $this->user->removeArticle($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(0, $this->user->getArticles());
        self::assertFalse($this->user->getArticles()->contains($value));
    }
}
