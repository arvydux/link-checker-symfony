<?php

namespace App\Service;

use App\Entity\Link;

class CheckLinkService
{
    public function __construct(
        private readonly ResponseFromUrlService $responseFromUrlService,
        private readonly RedirectsService $redirectService,
        private readonly KeywordsService $keywordsService
    ) {
    }

    public function checkLink(Link $link): void
    {
        $response = $this->responseFromUrlService->getResponse($link->getUrl());
        $redirects = $this->redirectService->getRedirectHeaders($response);
        if (!empty($redirects)) {
            $link->setRedirects($redirects);
            $link->setRedirectAmount($this->redirectService->getRedirectAmount($response));
            $link->setKeywords($this->keywordsService->getKeywords($response));
            $link->setCheckedAt(new \DateTime());
        }
    }
}