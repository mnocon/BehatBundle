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
    private $contentTypeFacade;
    private $contentTypeName;
    private $contentTypeIdentifier;
    private $contentTypeGroup;
    private $mainLanguageCode;
    private $fieldDefinitions;

    public function __construct(ContentTypeFacade $contentTypeFacade, string $contentTypeName, string $contentTypeIdentifier, string $contentTypeGroupName, string $mainLanguageCode, array $fieldDefinitions)
    {
        $this->contentTypeFacade = $contentTypeFacade;
        $this->contentTypeName = $contentTypeName;
        $this->contentTypeIdentifier = $contentTypeIdentifier;
        $this->contentTypeGroup = $contentTypeGroupName;
        $this->mainLanguageCode = $mainLanguageCode;
        $this->fieldDefinitions = $fieldDefinitions;
    }

    public function execute(): void
    {
        $this->contentTypeFacade->createContentType($this->contentTypeName, $this->contentTypeIdentifier, $this->contentTypeGroup, $this->mainLanguageCode, $this->fieldDefinitions);
    }

    public function rollback(): void
    {
        $this->contentTypeFacade->removeContentType($this->contentTypeIdentifier);
    }
}
