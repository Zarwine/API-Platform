<?php

declare(strict_types=1);

namespace App\Tests\Func;

use App\DataFixtures\AppFixtures;
use Faker\Factory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserTest extends AbstractEndPoint
{
    private string $userPayload = '{"username": "%s", "password": "123456"}';

    public function testGetUsers(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/users',
            '',
            [],
            false
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    public function testPostUser(): int
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/users',
            $this->getPayload(),
            [],
            false
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);

        return $responseDecoded['id'];
    }

    public function testGetDefaultUser(): int
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/users',
            '',
            ['email' => AppFixtures::DEFAULT_USER['email']],
            false
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);

        return $responseDecoded[0]['id'];
    }

    /**
     * @depends testGetDefaultUser
     */
    public function testPutDefaultUser(int $id): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_PUT,
            '/api/users/'.$id,
            $this->getPayload(),
            [],
            false
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @depends testGetDefaultUser
     */
    public function testPatchDefaultUser(int $id): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_PATCH,
            '/api/users/'.$id,
            $this->getPayload(),
            [],
            false
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @depends testGetDefaultUser
     */
    public function testDeleteDefaultUser(int $id): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_DELETE,
            '/api/users/'.$id,
            '',
            [],
            false
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @depends testPostUser
     */
    public function testDeleteOtherUserWithJWT(int $id): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_DELETE,
            '/api/users/'.$id
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
        self::assertEquals($this->notYourResource, $responseDecoded['message']);
    }

    /**
     * @depends testGetDefaultUser
     */
    public function testDeleteDefaultUserWithJWT(int $id): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_DELETE,
            '/api/users/'.$id
        );

        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testPostDefaultUser(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/users',
            json_encode(AppFixtures::DEFAULT_USER),
            [],
            false
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    public function testPostSameDefaultUser(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/users',
            json_encode(AppFixtures::DEFAULT_USER),
            [],
            false
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    private function getPayload(): string
    {
        $faker = Factory::create();

        return sprintf($this->userPayload, $faker->email);
    }
}
