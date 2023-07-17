<?php

namespace App\Service;

use App\Entity\Link;

class CheckLinkService
{
    public function checkLink(Link $link) : void
    {
        $responseFromUrlService = new ResponseFromUrlService($link->getUrl());
        $redirectService = new RedirectsService();
        $keywordsService = new KeywordsService();
        $response = $responseFromUrlService->getResponse();
        $redirects = $redirectService->getRedirectHeaders($response);
        if (! empty($redirects)) {
            $link->setRedirects($redirects);
            $link->setRedirectAmount($redirectService->getRedirectAmount($response));
            $link->setKeywords($keywordsService->getKeywords($response));
            $link->setCheckedAt(new \DateTime());
        }
    }
}