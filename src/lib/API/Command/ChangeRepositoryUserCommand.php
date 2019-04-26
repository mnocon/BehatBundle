<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\API\CommanD;

use eZ\Publish\API\Repository\PermissionResolver;
use eZ\Publish\API\Repository\UserService;
use EzSystems\Behat\Core\Command\CommandInterface;

class ChangeRepositoryUserCommand implements CommandInterface
{
    private $previousUser;
    private $permissionResolver;
    private $userService;
    private $username;

    public function __construct(UserService $userService, PermissionResolver $permissionResolver, $username)
    {
        $this->userService = $userService;
        $this->permissionResolver = $permissionResolver;
        $this->username = $username;
    }

    public function execute(): void
    {
        $this->previousUser = $this->permissionResolver->getCurrentUserReference();
        $user = $this->userService->loadUserByLogin($this->username);
        $this->permissionResolver->setCurrentUserReference($user);
    }

    public function rollback(): void
    {
        $this->permissionResolver->setCurrentUserReference($this->previousUser);
    }
}