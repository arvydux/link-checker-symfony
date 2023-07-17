<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class RedirectsService
{
    private array $redirects = [];

    public function getRedirectHeaders(Response $response): array
    {
        $this->redirects = $this->getResponseWithHeaders($response);

        return array_combine($this->redirects['X-Guzzle-Redirect-History'], $this->redirects['X-Guzzle-Redirect-Status-History']);
    }

    public function getRedirectAmount(Response $response) : int
    {
        return count($this->getRedirectHeaders($response));
    }

    protected function getResponseWithHeaders(Response $response): ?array
    {
        return $response?->getHeaders();
    }
}