<?php

namespace App\Tests\Service;

use App\Entity\Link;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class KeywordsServiceTest extends WebTestCase
{
    protected function setUp(): void
    {
        static::bootKernel();

        $this->responseFromUrlService = static::$kernel->getContainer()->get('App\Service\ResponseFromUrlService');
        $this->keywordsService = static::$kernel->getContainer()->get('App\Service\KeywordsService');
    }

    public function test_the_keywords_service_return_seo_keywords(): void
    {
        $link = new Link();
        $link->setUrl('https://cnn.com');

        $response = $this->responseFromUrlService->getResponse($link->getUrl());
        $keywords = $this->keywordsService->getKeywords($response);

        $this->assertIsString($keywords);
    }
}
