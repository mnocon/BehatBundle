<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\API\ContentData\FieldTypeData;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\Exceptions\NotImplementedException;
use eZ\Publish\API\Repository\Exceptions\UnauthorizedException;
use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Search\SearchHit;
use eZ\Publish\Core\FieldType\Relation\Value;

class ObjectRelationDataProvider implements FieldTypeDataProviderInterface
{
    private $searchService;
    private $contentService;

    public function __construct(SearchService $searchService, ContentService $contentService)
    {
        $this->searchService = $searchService;
        $this->contentService = $contentService;
    }

    public function supports(string $fieldTypeIdentifier): bool
    {
        return $fieldTypeIdentifier === 'ezobjectrelation';
    }

    public function generateData(string $language = 'eng-GB')
    {
        return new Value($this->getRandomContentIds(1));
    }

    protected function getRandomContentIds(int $number)
    {
        $query = new Query();
        $query->limit = 50;

        $results = $this->searchService->findContent($query);

        $contentIDs = array_map(function (SearchHit $result) {
            return $result->valueObject->contentInfo->id;
        }, $results->searchHits);

        $indices = array_rand($contentIDs, $number);

        if ($number === 1) {
            return $contentIDs[$indices];
        }

        $randomContentIDs = [];

        foreach ($indices as $i) {
            $randomContentIDs[] = $contentIDs[$i];
        }

        return $randomContentIDs;
    }

    public function parseFromString(string $value)
    {
        throw new NotImplementedException('Not implemented');
    }
}
