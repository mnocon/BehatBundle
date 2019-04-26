<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\API\CommanD;


use EzSystems\Behat\API\Facade\ContentTypeFacade;
use EzSystems\Behat\Core\Command\CommandInterface;

class CreateContentTypeCommand implements CommandInterface
{
    public function __construct(ContentTypeFacade $contentTypeFacade, string $contentTypeName, string $contentTypeIdentifier, string $contentTypeGroupName, string $mainLanguageCode, array $fieldDefinitions)
    {
    }

    public function execute(): void
    {
        // TODO: Implement execute() method.
    }

    public function rollback(): void
    {
        // TODO: Implement rollback() method.
    }
}
