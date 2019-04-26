<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\API\CommanD;


use EzSystems\Behat\API\Facade\RoleFacade;
use EzSystems\Behat\Core\Command\CommandInterface;

class CreateRoleCommand implements CommandInterface
{

    private $roleFacade;
    private $roleName;

    public function __construct(RoleFacade $roleFacade, string $roleName)
    {
        $this->roleFacade = $roleFacade;
        $this->roleName = $roleName;
    }

    public function execute(): void
    {
        // TODO: Implement execute() method.
    }

    public function rollback(): void
    {
        // TODO: Implement rollback() method.
    }
}