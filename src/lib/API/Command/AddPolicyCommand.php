<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\API\CommanD;


use EzSystems\Behat\API\Facade\RoleFacade;
use EzSystems\Behat\Core\Command\CommandInterface;

class AddPolicyCommand implements CommandInterface
{
    private $limitations;
    private $function;
    private $module;
    private $roleName;
    private $roleFacade;

    public function __construct(RoleFacade $roleFacade, string $roleName, string $module, string $function, $limitations = null)
    {
        $this->roleFacade = $roleFacade;
        $this->roleName = $roleName;
        $this->module = $module;
        $this->function = $function;
        $this->limitations = $limitations;
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