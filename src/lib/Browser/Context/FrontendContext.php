<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\Browser\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use EzSystems\Behat\Browser\Factory\PageObjectFactory;
use EzSystems\Behat\Browser\Page\Preview\FolderPreview;
use EzSystems\Behat\Core\Behat\ArgumentParser;
use PHPUnit\Framework\Assert;

class FrontendContext implements Context
{
    /** @var BrowserContext */
    protected $browserContext;

    private $argumentParser;

    public function __construct(ArgumentParser $argumentParser)
    {
        $this->argumentParser = $argumentParser;
    }

    /** @BeforeScenario
     * @param BeforeScenarioScope $scope Behat scope
     */
    public function getUtilityContext(BeforeScenarioScope $scope): void
    {
        $this->browserContext = $scope->getEnvironment()->getContext(BrowserContext::class);
    }

    /**
     * @Given I viewing the pages on siteaccess :siteaccess as :username
     */
    public function iAmLoggedAsUserOnSiteaccess(string $username, string $siteaccess)
    {
        $this->browserContext->visit(sprintf('/%s/%s', $siteaccess, 'logout'));

        if ($username === 'anonymous') {
            return;
        }

        $this->browserContext->visit(sprintf('/%s/%s', $siteaccess, 'login'));
        $this->browserContext->findElement('#username')->setValue($username);
        $password = $username === 'admin' ? 'publish' : 'Passw0rd42';
        $this->browserContext->findElement('#password')->setValue($password);
        $this->browserContext->getElementByText('Login', 'button')->click();
    }

    /**
     * @Given I visit :url on siteaccess :siteaccess
     */
    public function iVisitItemOnSiteaccess(string $url, string $siteaccess): void
    {
        // TODO: Generate URL using reverse SA matching
        $url = $this->argumentParser->parseUrl($url);
        $this->browserContext->visit(sprintf('/%s%s', $siteaccess, $url));
        $this->browserContext->printCurrentUrl();
    }

    /**
     * @Given I see correct preview data for :contentTypeName Content Type
     */
    public function iSeeCorrectPreviewDataFor(string $contentType, TableNode $previewData): void
    {
        $folderPreviewPage = new FolderPreview($this->browserContext);
        Assert::assertEquals($previewData->getHash()[0]['value'], $folderPreviewPage->getPageTitle());
    }
}
