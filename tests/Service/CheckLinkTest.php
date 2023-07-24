<?php

namespace App\Tests\Service;

use App\Entity\Link;
use App\Service\ResponseFromUrlService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CheckLinkTest extends WebTestCase
{
    protected function setUp(): void
    {
        static::bootKernel();

        $this->checkLinkService = static::$kernel->getContainer()->get('App\Service\CheckLinkService');
    }

    public function testCheckLinkServiceReturnDataProperly(): void
    {
        $link = new Link();
        $link->setUrl('http://cnn.com');

        $this->checkLinkService->checkLink($link);

        $this->assertEquals(3, count($link->getRedirects()));
        $this->assertEquals(3, $link->getRedirectAmount());
        $this->assertNotEmpty($link->getKeywords());
        $this->assertNotEmpty($link->getCheckedAt());
    }

    public function test_response_from_url_service_returns_guzzle_http_response()
    {
        $link = new Link();
        $link->setUrl('https://www.whatsmydns.net/example-301-redirect');

        $responseFromUrlService = new ResponseFromUrlService();
        $response = $responseFromUrlService->getResponse($link->getUrl());

        $this->assertInstanceOf('GuzzleHttp\Psr7\Response', $response);
    }

    public function test_the_link_check_service_return_301_status_code_correctly(): void
    {
        $link = new Link();
        $link->setUrl('https://www.whatsmydns.net/example-301-redirect');

        $this->checkLinkService->checkLink($link);

        $this->assertContains('301', $link->getRedirects());
        $this->assertNotContains('302', $link->getRedirects());
    }

    public function test_the_link_check_service_return_302_status_code_correctly(): void
    {
        $link = new Link();
        $link->setUrl('https://www.whatsmydns.net/example-302-redirect');

        $this->checkLinkService->checkLink($link);

        $this->assertContains('302', $link->getRedirects());
        $this->assertNotContains('301', $link->getRedirects());
    }

    public function test_the_link_check_service_return_301_and_302_status_code_correctly(): void
    {
        $link = new Link();
        $link->setUrl('https://www.whatsmydns.net/example-multi-redirect');

        $this->checkLinkService->checkLink($link);

        $this->assertContains('301', $link->getRedirects());
        $this->assertContains('302', $link->getRedirects());
    }

    public function test_the_link_check_service_detect_redirect_amount_correctly(): void
    {
        $link = new Link();
        $link->setUrl('https://www.whatsmydns.net/example-301-redirect');

        $this->checkLinkService->checkLink($link);

        $this->assertEquals(1, $link->getRedirectAmount());

        $link = new Link();
        $link->setUrl('https://www.whatsmydns.net/example-302-redirect');

        $this->checkLinkService->checkLink($link);
        $this->assertEquals(1, $link->getRedirectAmount());

        $link = new Link();
        $link->setUrl('https://www.whatsmydns.net/example-multi-redirect');

        $this->checkLinkService->checkLink($link);
        $this->assertEquals(2, $link->getRedirectAmount());
    }

    public function test_the_link_check_service_return_seo_keywords(): void
    {
        $link = new Link();
        $link->setUrl('https://cnn.com');

        $this->checkLinkService->checkLink($link);

        $this->assertIsString($link->getKeywords());
    }
}
