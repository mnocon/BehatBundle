<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\Browser\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use EzSystems\Behat\Core\Behat\ArgumentParser;
use EzSystems\EzPlatformAdminUi\Behat\Helper\UtilityContext;
use EzSystems\EzPlatformAdminUi\Behat\PageObject\PageObjectFactory;
use PHPUnit\Framework\Assert;

// TODO: Merge it with UtilityContext
class FrontendContext extends RawMinkContext
{
    /** @var UtilityContext */
    protected $utilityContext;

    /** @BeforeScenario
     * @param BeforeScenarioScope $scope Behat scope
     */
    public function getUtilityContext(BeforeScenarioScope $scope): void
    {
        $environment = $scope->getEnvironment();
        $this->utilityContext = $environment->getContext(UtilityContext::class);
    }

    private $argumentParser;

    public function __construct(ArgumentParser $argumentParser)
    {
        $this->argumentParser = $argumentParser;
    }

    /**
     * @Given I visit :url on siteaccess :siteaccess
     */
    public function iVisitItemOnSiteaccess(string $url, string $siteaccess): void
    {
        // TODO: Generate URL using reverse SA matching
        $url = $this->argumentParser->parseUrl($url);
        $this->utilityContext->visit(sprintf('/%s%s', $siteaccess, $url));
        $this->utilityContext->printCurrentUrl();
    }

    /**
     * @Given I see correct preview data for :contentTypeName Content Type
     */
    public function iSeeCorrectPreviewDataFor(string $contentType, TableNode $previewData): void
    {
        // TODO: Move AdminUI factories here, convert Pages and Elements to services?
        $folderPreviewPage = PageObjectFactory::createPage($this->utilityContext, PageObjectFactory::getPreviewType($contentType));
        Assert::assertEquals($previewData->getHash()[0]['value'], $folderPreviewPage->getPageTitle());
    }

    /**
     * @Given response headers contain
     */
    public function responseHeadersContain(TableNode $expectedHeadersData): void
    {
        $responseHeaders = $this->getSession()->getDriver()->getResponseHeaders();

        foreach ($expectedHeadersData->getHash() as $row) {
            Assert::assertEquals($row['Value'], $responseHeaders[$row['Header']][0]);
        }
    }
}
