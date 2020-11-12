<?php

declare(strict_types=1);

namespace App\Tests\Func;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleTest extends AbstractEndPoint
{
    public function testArticles(): array
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/articles',
            '',
            [],
            false
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);

        return $responseDecoded;
    }

    /**
     * @throws \Exception
     * @depends testArticles
     */
    public function testGetArticle(array $res): void
    {
        if (0 === count($res)) {
            throw new \Exception('Use this command => bin/console d:f:l (no data found)', 404);
        }

        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/articles/'.$res[0]->id,
            '',
            [],
            false
        );
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
        self::assertNotSame($res[0], $responseDecoded);
        self::assertContains('author', $responseContent);
    }
}
