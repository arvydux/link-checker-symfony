<?php

namespace App\Service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class ResponseFromUrlService
{
    private ?Response $response;

    public function __construct(private $url = "")
    {
        $client = new Client(['allow_redirects' => ['track_redirects' => true], 'verify' => false]);
        try {
            return $this->response = $client->request('GET', $this->url);
        } catch (Exception) {

            return $this->response = null;
        }
    }

    public function getResponse(): Response|\Psr\Http\Message\ResponseInterface|null
    {
        return $this->response;
    }

    public function getResponseWithHeaders() : ?array
    {
        return $this->getResponse()?->getHeaders();
    }

    public function getResponseWithBody() : ?string
    {
        return $this->getFullResponse()?->getBody()->getContents();
    }
}