<?php

namespace App\Service;

use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UrlListParserService
{
    public function __construct(
        private readonly ValidatorInterface $validator
    ) {
    }

    public function getUrlListFromTextFile($textFile): ?array
    {
        $urlList = [];

        while (($data = fgetcsv($textFile)) !== false) {
            if ($this->isValidUrl($data[0])) {
                $urlList[] = $data[0];
            }
        }

        return $urlList;
    }

    private function isValidUrl(string $url): bool
    {
        $violations = $this->validator->validate($url, [
            new Url()
        ]);

        if ($violations->count() === 0) {
            return true;
        }

        return false;
    }
}