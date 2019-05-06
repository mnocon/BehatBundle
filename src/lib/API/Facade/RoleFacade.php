<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\API\Facade;

use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\RoleService;
use EzSystems\Behat\API\Context\LimitationParser\LimitationParsersCollector;
use EzSystems\Behat\API\Context\LimitationParser\LimitationParserInterface;

class RoleFacade
{
    private $roleService;

    /** @var LimitationParsersCollector */
    private $limitationParsersCollector;

    public function __construct(RoleService $roleService, LimitationParsersCollector $limitationParsersCollector)
    {
        $this->roleService = $roleService;
        $this->limitationParsersCollector = $limitationParsersCollector;
    }

    public function createRole(string $roleName)
    {
        $roleCreateStruct = $this->roleService->newRoleCreateStruct($roleName);
        $roleDraft = $this->roleService->createRole($roleCreateStruct);
        $this->roleService->publishRoleDraft($roleDraft);
    }

    public function addPolicyToRole(string $roleName, string $module, string $function, $limitations = null)
    {

        $role = $this->roleService->loadRoleByIdentifier($roleName);
        $roleDraft = $this->roleService->createRoleDraft($role);
        $policyCreateStruct = $this->roleService->newPolicyCreateStruct($module, $function);

        $currentPolicies = $role->getPolicies();

        if ($limitations !== null) {
            foreach ($limitations as $limitation) {
                $policyCreateStruct->addLimitation($limitation);
            }
        }

        $updatedRoleDraft = $this->roleService->addPolicyByRoleDraft($roleDraft, $policyCreateStruct);

        $this->roleService->publishRoleDraft($updatedRoleDraft);

        $updatedPolicies = $updatedRoleDraft->getPolicies();
        $newPolicy = array_diff($updatedPolicies, $currentPolicies)[0];

        return $newPolicy->id;
    }

    public function deleteRole(string $roleName): void
    {
        $role = $this->roleService->loadRoleByIdentifier($roleName);
        $this->roleService->deleteRole($role);
    }

    public function roleExist(string $roleName): bool
    {
        try {
            $this->roleService->loadRoleByIdentifier($roleName);

            return true;
        } catch (NotFoundException $e) {
            return false;
        }
    }

    /**
     * @return LimitationParserInterface[]
     */
    public function getLimitationParsers(): array
    {
        return $this->limitationParsersCollector->getLimitationParsers();
    }

    public function removePolicyFromRoleById($roleName, $policyID)
    {
        $role = $this->roleService->loadRoleByIdentifier($roleName);
        $draft = $this->roleService->createRoleDraft($role);

        foreach ($draft->getPolicies() as $policyDraft) {
            if ($policyDraft->originalId == $policyID) {
                $this->roleService->removePolicyByRoleDraft($draft, $policyDraft);
                $this->roleService->publishRoleDraft($draft);
                return;
            }
        }
    }
}
