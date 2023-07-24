<?php

namespace App\Service;

use http\Exception\RuntimeException;
use PHPHtmlParser\Dom;
use GuzzleHttp\Psr7\Response;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;

class KeywordsService
{
    /**
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function getKeywords(Response $response): ?string
    {
        $dom = new Dom;
        ini_set("mbstring.regex_retry_limit", "10000000");
        $dom->loadStr($response->getBody()->getContents());
        $tags = $dom->find('meta');
        foreach ($tags as $tag) {
            if ($tag->getAttribute('name') === "keywords") {
                return $tag->getAttribute('content') ?: null;
            }
        }

        return '';
    }
}