<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\Behat\Test;

use EzSystems\Behat\API\CommanD\CreateContentCommand;
use EzSystems\Behat\API\Command\CreateLanguageCommand;
use EzSystems\Behat\Core\Command\CommandInvoker;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class CommandIInvokerTest extends TestCase
{
    public function testExecutesCommand()
    {
        $commandMock = $this->getMockBuilder(CreateLanguageCommand::class)
        ->disableOriginalConstructor()
        ->setMethods(['execute'])
        ->getMock();

        $commandMock->expects($this->once())
            ->method('execute');

        CommandInvoker::add($commandMock);

        CommandInvoker::execute();
    }

    public function testExecutesCommandOnce()
    {
        $commandMock = $this->getMockBuilder(CreateLanguageCommand::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();

        $commandMock->expects($this->once())
            ->method('execute');

        CommandInvoker::add($commandMock);

        CommandInvoker::execute();
        CommandInvoker::execute();

    }

    public function testExecutesMultipleCommandsInCorrectOrder()
    {
        $commandMock1 = $this->getMockBuilder(CreateLanguageCommand::class)
            ->disableOriginalConstructor()
            ->setMockClassName('Command1')
            ->setMethods(['execute'])
            ->getMock();

        $commandMock2 = $this->getMockBuilder(CreateContentCommand::class)
            ->disableOriginalConstructor()
            ->setMockClassName('Command2')
            ->setMethods(['execute'])
            ->getMock();

        $commandMock1->expects($this->once())
            ->method('execute');

        $commandMock2->expects($this->once())
            ->method('execute');

        CommandInvoker::add($commandMock1);
        CommandInvoker::add($commandMock2);

        $executedCommands = CommandInvoker::execute();

        Assert::assertCount(2, $executedCommands);
        Assert::assertEquals(get_class($executedCommands[0]), 'Command1');
        Assert::assertEquals(get_class($executedCommands[1]), 'Command2');
    }

    public function testDoesNotRollbackNotExecutedCommands()
    {
        $commandMock = $this->getMockBuilder(CreateLanguageCommand::class)
            ->disableOriginalConstructor()
            ->setMethods(['rollback'])
            ->getMock();

        $commandMock->expects($this->never())
            ->method('rollback');

        CommandInvoker::add($commandMock);

        CommandInvoker::rollback();
    }

    public function testRollbacksExecutedCommands()
    {
        $commandMock = $this->getMockBuilder(CreateLanguageCommand::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute','rollback'])
            ->getMock();

        $commandMock->expects($this->once())
            ->method('rollback');

        CommandInvoker::add($commandMock);
        CommandInvoker::execute();

        CommandInvoker::rollback();
    }

    public function testRollbacksCommandOnce()
    {
        $commandMock = $this->getMockBuilder(CreateLanguageCommand::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute','rollback'])
            ->getMock();

        $commandMock->expects($this->once())
            ->method('rollback');

        CommandInvoker::add($commandMock);
        CommandInvoker::execute();

        CommandInvoker::rollback();
        CommandInvoker::rollback();
    }

    public function testRollbacksMultipleCommandSInCorrectOrder()
    {
        $commandMock1 = $this->getMockBuilder(CreateLanguageCommand::class)
            ->disableOriginalConstructor()
            ->setMockClassName('Command1')
            ->setMethods(['execute'])
            ->getMock();

        $commandMock2 = $this->getMockBuilder(CreateContentCommand::class)
            ->disableOriginalConstructor()
            ->setMockClassName('Command2')
            ->setMethods(['execute'])
            ->getMock();

        $commandMock1->expects($this->once())
            ->method('execute');

        $commandMock2->expects($this->once())
            ->method('execute');

        CommandInvoker::add($commandMock1);
        CommandInvoker::add($commandMock2);
        CommandInvoker::execute();

        $rollbackedCommands = CommandInvoker::rollback();

        Assert::assertCount(2, $rollbackedCommands);
        Assert::assertEquals(get_class($rollbackedCommands[0]), 'Command2');
        Assert::assertEquals(get_class($rollbackedCommands[1]), 'Command1');
    }
}
