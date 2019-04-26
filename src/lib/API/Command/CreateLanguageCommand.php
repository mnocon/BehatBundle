<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\API\Command;


use EzSystems\Behat\API\Facade\LanguageFacade;
use EzSystems\Behat\Core\Command\CommandInterface;

class CreateLanguageCommand implements CommandInterface
{
    private $languageFacade;
    private $name;
    private $languageCode;

    public function __construct(LanguageFacade $languageFacade, string $name, string $languageCode)
    {
        $this->languageFacade = $languageFacade;
        $this->name = $name;
        $this->languageCode = $languageCode;
    }

    public function execute(): void
    {
        $this->languageFacade->createLanguage($this->name, $this->languageCode);
    }

    public function rollback(): void
    {
        $this->languageFacade->deleteLanguage($this->name);
    }
}
