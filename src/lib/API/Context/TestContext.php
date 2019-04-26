<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\API\Context;

use Behat\Behat\Context\Context;
use eZ\Publish\API\Repository\PermissionResolver;
use eZ\Publish\API\Repository\UserService;
use EzSystems\Behat\API\CommanD\ChangeRepositoryUserCommand;
use EzSystems\Behat\Core\Command\CommandInvoker;

class TestContext implements Context
{
    private $permissionResolver;
    private $userService;

    public function __construct(UserService $userService, PermissionResolver $permissionResolver)
    {
        $this->userService = $userService;
        $this->permissionResolver = $permissionResolver;
    }

    /**
     * @Given I am using the API as :username
     */
    public function iAmLoggedAsUser(string $username)
    {
        CommandInvoker::add(new ChangeRepositoryUserCommand($this->userService, $this->permissionResolver, $username));
    }

    /**
     * @BeforeScenario @admin
     */
    public function loginAdminBeforeScenarioHook()
    {
        $this->iAmLoggedAsUser('admin');
    }

    /**
     * @AfterStep
     */
    public function executeCommands()
    {
        CommandInvoker::execute();
    }

    /**
     * @AfterScenario
     */
    public function rollbackTest()
    {
        CommandInvoker::rollback();
    }
}
