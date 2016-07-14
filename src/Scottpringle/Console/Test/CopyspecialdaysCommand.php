<?php

namespace Scottpringle\Console\Test;

use Scottpringle\Console\Command\CopyspecialdaysCommand;
use Scottpringle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class CopyspecialdaysCommandTest
 * @package Scottpringle\Console\Test
 */
class CopyspecialdaysCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Isn't actually implemented but could be used to test the outcome of the command
     */
    public function testExecute()
    {
        $application = new Application();
        $application->add(new CopyspecialdaysCommand());

//        $command = $application->find('specialdays:copy');
//        $commandTester = new CommandTester($command);
//        $commandTester->execute(array('command' => $command->getName()));

    }
}