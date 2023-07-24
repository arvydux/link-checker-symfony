<?php

namespace App\Tests\Service;

use App\Entity\Link;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResponseFromUrlServiceTest extends WebTestCase
{
    protected function setUp(): void
    {
        static::bootKernel();

        $this->responseFromUrlService = static::$kernel->getContainer()->get('App\Service\ResponseFromUrlService');
    }

    public function test_response_from_url_service_returns_guzzle_http_response(): void
    {
        $link = new Link();
        $link->setUrl('https://www.whatsmydns.net/example-301-redirect');

        $response = $this->responseFromUrlService->getResponse($link->getUrl());

        $this->assertInstanceOf('GuzzleHttp\Psr7\Response', $response);

    }

}
