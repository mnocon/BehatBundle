<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\Browser\Context;


use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

class CacheContext implements Context
{
    /** @var BrowserContext */
    protected $browserContext;

    /** @BeforeScenario
     * @param BeforeScenarioScope $scope Behat scope
     */
    public function getUtilityContext(BeforeScenarioScope $scope): void
    {
        $this->browserContext = $scope->getEnvironment()->getContext(BrowserContext::class);
    }

    /**
     * @Given response headers contain
     */
    public function responseHeadersContain(TableNode $expectedHeadersData): void
    {
        $responseHeaders = $this->browserContext->getSession()->getDriver()->getResponseHeaders();

        var_dump($this->browserContext->getSession()->getPage()->getContent());

        foreach ($expectedHeadersData->getHash() as $row) {
            Assert::assertEquals($row['Value'], $responseHeaders[$row['Header']][0]);
        }
    }

    /**
     * @Given :headerName response header has value greater than 0
     */
    public function headerHasValueGreaterThanZero(string $headerName): void
    {
        $responseHeaders = $this->browserContext->getSession()->getDriver()->getResponseHeaders();

        Assert::assertGreaterThan(0,$responseHeaders[$headerName][0]);
    }
}