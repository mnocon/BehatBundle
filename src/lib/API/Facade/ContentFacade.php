<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\API\Facade;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\TrashService;
use eZ\Publish\API\Repository\URLAliasService;
use eZ\Publish\API\Repository\Values\Content\URLAlias;
use eZ\Publish\Core\REST\Server\Values\Trash;
use EzSystems\Behat\API\ContentData\ContentDataProvider;
use PHPUnit\Framework\Assert;

class ContentFacade
{
    /** @var ContentService */
    private $contentService;

    /** @var LocationService */
    private $locationService;

    /** @var URLAliasService */
    private $urlAliasService;

    /** @var ContentDataProvider */
    private $contentDataProvider;

    /** @var TrashService */
    private $trashService;

    public function __construct(ContentService $contentService, LocationService $locationService, URLAliasService $urlAliasService, TrashService $trashService, ContentDataProvider $contentDataProvider)
    {
        $this->contentService = $contentService;
        $this->locationService = $locationService;
        $this->urlAliasService = $urlAliasService;
        $this->trashService = $trashService;
        $this->contentDataProvider = $contentDataProvider;
    }

    public function createContent(string $contentTypeIdentifier, string $parentUrl, string $language, array $contentItemData = null)
    {
        $parentUrlAlias = $this->urlAliasService->lookup($parentUrl);
        Assert::assertEquals(URLAlias::LOCATION, $parentUrlAlias->type);

        $parentLocationId = $parentUrlAlias->destination;
        $locationCreateStruct = $this->locationService->newLocationCreateStruct($parentLocationId);

        $this->contentDataProvider->setContentTypeIdentifier($contentTypeIdentifier);

        $contentCreateStruct = $this->contentDataProvider->getRandomContentData($language);

        if ($contentItemData) {
            $contentCreateStruct = $this->contentDataProvider->getFilledContentDataStruct($contentCreateStruct, $contentItemData, $language);
        }

        $draft = $this->contentService->createContent($contentCreateStruct, [$locationCreateStruct]);
        $publishedContent = $this->contentService->publishVersion($draft->versionInfo);

        return $publishedContent->getVersionInfo()->getContentInfo()->mainLocationId;
    }

    public function deleteContentById(string $mainLocationId)
    {
        $location = $this->locationService->loadLocation($mainLocationId);
        $trashItem = $this->trashService->trash($location);
        $this->trashService->deleteTrashItem($trashItem);
    }
}
