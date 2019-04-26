<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\API\Facade;

use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\LanguageService;

class LanguageFacade
{
    private $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    public function createLanguageIfNotExists(string $name, string $languageCode)
    {
        if (!$this->languageExists($languageCode)) {
            $this->createLanguage($name, $languageCode);
        }
    }

    public function createLanguage(string $name, string $languageCode)
    {
        $languageCreateStruct = $this->languageService->newLanguageCreateStruct();
        $languageCreateStruct->languageCode = $languageCode;
        $languageCreateStruct->name = $name;

        $this->languageService->createLanguage($languageCreateStruct);
    }

    public function languageExists(string $languageCode)
    {
        try {
            $this->languageService->loadLanguage($languageCode);

            return true;
        } catch (NotFoundException $e) {
            return false;
        }
    }
}
