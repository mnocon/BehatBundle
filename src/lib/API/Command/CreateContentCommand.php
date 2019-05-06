<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\API\CommanD;


use EzSystems\Behat\API\Facade\ContentFacade;
use EzSystems\Behat\Core\Command\CommandInterface;

class CreateContentCommand implements CommandInterface
{
    private $contentFacade;
    private $contentTypeIdentifier;
    private $parentUrl;
    private $language;
    private $contentItemData;
    private $createdContentLocationId;

    public function __construct(ContentFacade $contentFacade, string $contentTypeIdentifier, string $parentUrl, string $language, array $contentItemData = null)
    {
        $this->contentFacade = $contentFacade;
        $this->contentTypeIdentifier = $contentTypeIdentifier;
        $this->parentUrl = $parentUrl;
        $this->language = $language;
        $this->contentItemData = $contentItemData;
    }

    public function execute(): void
    {
        $this->createdContentLocationId = $this->contentFacade->createContent($this->contentTypeIdentifier, $this->parentUrl, $this->language, $this->contentItemData);
    }

    public function rollback(): void
    {
        $this->contentFacade->deleteContentById($this->createdContentLocationId);
    }
}
