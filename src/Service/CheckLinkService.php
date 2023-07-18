<?php

namespace App\Service;

use App\Entity\Link;

class CheckLinkService
{
    public function __construct(
        private ResponseFromUrlService $responseFromUrlService,
        private RedirectsService $redirectService,
        private KeywordsService $keywordsService
    ) {
    }

    public function checkLink(Link $link) : void
    {
        $response = $this->responseFromUrlService->getResponse($link->getUrl());
        $redirects = $this->redirectService->getRedirectHeaders($response);
        if (! empty($redirects)) {
            $link->setRedirects($redirects);
            $link->setRedirectAmount($this->redirectService->getRedirectAmount($response));
            $link->setKeywords($this->keywordsService->getKeywords($response));
            $link->setCheckedAt(new \DateTime());
        }
    }
}