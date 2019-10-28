<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\API\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use EzSystems\Behat\API\Facade\RoleFacade;
use EzSystems\Behat\Core\Behat\ArgumentParser;

class RoleContext implements Context
{
    /** @var \EzSystems\Behat\API\Facade\RoleFacade */
    private $roleFacade;

    /** @var \EzSystems\Behat\Core\Behat\ArgumentParser */
    private $argumentParser;

    /**
     * @injectService $roleFacade @EzSystems\Behat\API\Facade\RoleFacade
     * @injectService $argumentParser @EzSystems\Behat\Core\Behat\ArgumentParser
     */
    public function __construct(RoleFacade $roleFacade, ArgumentParser $argumentParser)
    {
        $this->roleFacade = $roleFacade;
        $this->argumentParser = $argumentParser;
    }

    /**
     * @Given I create a role :roleName
     */
    public function createRole(string $roleName): void
    {
        if ($this->roleFacade->roleExist($roleName)) {
            return;
        }

        $this->roleFacade->createRole($roleName);
    }

    /**
     * @Given I create a role :roleName with policies
     */
    public function createRoleWithPolicies(string $roleName, TableNode $policies): void
    {
        if ($this->roleFacade->roleExist($roleName)) {
            return;
        }

        $this->roleFacade->createRole($roleName);

        foreach ($policies as $policy) {
            $this->roleFacade->addPolicyToRole($roleName, $policy['module'], $policy['function']);
        }
    }

    /**
     * @Given I add policies to :roleName
     */
    public function addPolicyToRole($roleName, TableNode $policies): void
    {
        foreach ($policies as $policy) {
            $this->roleFacade->addPolicyToRole($roleName, $policy['module'], $policy['function']);
        }
    }

    /**
     * @Given I add policy :module :function to :roleName with limitations
     */
    public function addPolicyToRoleWithLimitation(string $module, string $function, $roleName, TableNode $limitations): void
    {
        $parsedLimitations = $this->argumentParser->parseLimitations($limitations);
        $this->roleFacade->addPolicyToRole($roleName, $module, $function, $parsedLimitations);
    }
}