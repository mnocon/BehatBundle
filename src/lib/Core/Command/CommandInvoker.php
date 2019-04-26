<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\Behat\Core\Command;

class CommandInvoker
{
    private static $commands = [];

    private static $executedCommands = [];

    public static function add(CommandInterface $command)
    {
        self::$commands[] = $command;
    }

    public static function execute(): array
    {
        $commandExecutedThisRun = [];

        while(self::$commands)
        {
            $command = array_shift(self::$commands);
            $command->execute();
            $commandExecutedThisRun[] = $command;
        }

        self::$executedCommands = array_merge(self::$executedCommands, $commandExecutedThisRun);

        return $commandExecutedThisRun;
    }

    public static function rollback(): array
    {
        $rollbackedCommands = [];

        while(self::$executedCommands)
        {
            $command = array_pop(self::$executedCommands);
            $command->rollback();
            $rollbackedCommands[] = $command;
        }

        return $rollbackedCommands;
    }
}
