<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\API\Context\LimitationParser;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\URLAliasService;
use eZ\Publish\API\Repository\Values\User\Limitation;
use eZ\Publish\API\Repository\Values\User\Limitation\LocationLimitation;

class LocationLimitationParser implements LimitationParserInterface
{
    private $locationService;
    private $urlAliasService;

    public function __construct(URLAliasService $urlAliasService, LocationService $locationService)
    {
        $this->urlAliasService = $urlAliasService;
        $this->locationService = $locationService;
    }

    public function supports(string $limitationType): bool
    {
        return $limitationType === Limitation::LOCATION || strtolower($limitationType) === 'location';
    }

    public function parse(string $limitationValues): Limitation
    {
        $values = [];

        foreach (explode(',', $limitationValues) as $limitationValue) {
            $urlAlias = $this->urlAliasService->lookup($limitationValue);
            $location = $this->locationService->loadLocation($urlAlias->destination);
            $values[] = $location->id;
        }

        return new LocationLimitation(
            ['limitationValues' => $values]
        );
    }
}
