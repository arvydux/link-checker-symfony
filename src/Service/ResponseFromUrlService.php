<?php

namespace App\Service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Response;

class ResponseFromUrlService
{
    public function getResponse($url): Response|Exception|ConnectException|null
    {
        $client = new Client(['allow_redirects' => ['track_redirects' => true], 'verify' => false]);
        try {
            return $client->request('GET', $url);
        } catch (ConnectException $e) {
            return $e;
        }
    }
}