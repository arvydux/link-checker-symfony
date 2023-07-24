<?php

namespace App\Service;

use GuzzleHttp\Psr7\Response;

class RedirectsService
{

    public function getRedirectHeaders(Response $response): array
    {
        $redirects = $this->getResponseWithHeaders($response);

        return array_combine($redirects['X-Guzzle-Redirect-History'], $redirects['X-Guzzle-Redirect-Status-History']);
    }

    public function getRedirectAmount(Response $response): int
    {
        return count($this->getRedirectHeaders($response));
    }

    protected function getResponseWithHeaders(Response $response): ?array
    {
        return $response?->getHeaders();
    }
}