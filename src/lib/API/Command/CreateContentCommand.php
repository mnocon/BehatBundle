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
    public function __construct(ContentFacade $contentFacade, string $contentTypeIdentifier, string $parentUrl, string $language, array $contentItemData = null)
    {

    }

    public function execute(): void
    {
    }

    public function rollback(): void
    {
    }
}
