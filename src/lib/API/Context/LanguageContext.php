<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\API\Context;

use Behat\Behat\Context\Context;
use EzSystems\Behat\API\Command\CreateLanguageCommand;
use EzSystems\Behat\API\Facade\LanguageFacade;
use EzSystems\Behat\Core\Command\CommandInvoker;

class LanguageContext implements Context
{
    private $languageFacade;

    public function __construct(LanguageFacade $languageFacade)
    {
        $this->languageFacade = $languageFacade;
    }

    /**
     * @Given Language :name with code :languageCode exists
     */
    public function createLanguageIfNotExists(string $name, string $languageCode)
    {
        if (!$this->languageFacade->languageExists($languageCode)) {
            CommandInvoker::add(new CreateLanguageCommand($this->languageFacade, $name, $languageCode));
        }
    }
}
