<?php

namespace App\Tests\Service;

use App\Entity\Link;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedirectsServiceTest extends WebTestCase
{
    protected function setUp(): void
    {
        static::bootKernel();

        $this->responseFromUrlService = static::$kernel->getContainer()->get('App\Service\ResponseFromUrlService');
        $this->redirectsService = static::$kernel->getContainer()->get('App\Service\RedirectsService');
    }

    public function test_the_redirect_service_return_301_status_code_correctly(): void
    {
        $link = new Link();
        $link->setUrl('https://www.whatsmydns.net/example-301-redirect');

        $response = $this->responseFromUrlService->getResponse($link->getUrl());
        $redirects = $this->redirectsService->getRedirectHeaders($response);

        $this->assertContains('301', $redirects);
        $this->assertNotContains('302', $redirects);
    }

    public function test_the_redirect_service_return_302_status_code_correctly(): void
    {
        $link = new Link();
        $link->setUrl('https://www.whatsmydns.net/example-302-redirect');

        $response = $this->responseFromUrlService->getResponse($link->getUrl());
        $redirects = $this->redirectsService->getRedirectHeaders($response);

        $this->assertContains('302', $redirects);
        $this->assertNotContains('301', $redirects);
    }

    public function test_the_redirect_service_return_301_and_302_status_code_correctly(): void
    {
        $link = new Link();
        $link->setUrl('https://www.whatsmydns.net/example-multi-redirect');

        $response = $this->responseFromUrlService->getResponse($link->getUrl());
        $redirects = $this->redirectsService->getRedirectHeaders($response);

        $this->assertContains('301', $redirects);
        $this->assertContains('302', $redirects);
    }

    public function test_the_redirects_service_detect_redirect_amount_correctly(): void
    {
        $link = new Link();
        $link->setUrl('https://www.whatsmydns.net/example-301-redirect');

        $response = $this->responseFromUrlService->getResponse($link->getUrl());
        $redirectAmount = $this->redirectsService->getRedirectAmount($response);

        $this->assertEquals(1, $redirectAmount);

        $link = new Link();
        $link->setUrl('https://www.whatsmydns.net/example-302-redirect');

        $response = $this->responseFromUrlService->getResponse($link->getUrl());
        $redirectAmount = $this->redirectsService->getRedirectAmount($response);

        $this->assertEquals(1, $redirectAmount);

        $link = new Link();
        $link->setUrl('https://www.whatsmydns.net/example-multi-redirect');

        $response = $this->responseFromUrlService->getResponse($link->getUrl());
        $redirectAmount = $this->redirectsService->getRedirectAmount($response);

        $this->assertEquals(2, $redirectAmount);
    }
}
