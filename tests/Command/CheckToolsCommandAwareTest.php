<?php

/*
 * This file is part of the CheckToolsFramework package.
 *
 * Copyright (C) 2015-2016 Guillaume Kulakowski <guillaume@kulakowski.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Llaumgui\CheckToolsFramework\Command;

use Tests\Llaumgui\CheckToolsFramework\PhpUnitHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Llaumgui\CheckToolsFramework\Console\Application;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Output\OutputInterface;
use Llaumgui\CheckToolsFramework\Command\BomCommand;

class CheckToolsCommandAwareTest extends PhpUnitHelper
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Setup container for test.
     */
    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        // Load bom command for test
        $definitions = [
            'ctf.checktool_bom' => new Definition('Llaumgui\CheckToolsFramework\CheckTool\BomCheckTool')
        ];
        $this->container->setDefinitions($definitions);
    }


    /**
     * Check help command.
     */
    public function testExecuteHelp()
    {
        $application = new Application();

        $command = $application->find('help');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'command'       => $command->getName(),
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE
            ]
        );

        $this->assertTrue(!empty($commandTester->getDisplay()));
        $this->assertRegExp('/Display this help message/', $commandTester->getDisplay());
    }


    /**
     * Check list command.
     */
    public function testExecuteList()
    {
        // Load bom command for test
        $application = new Application();
        $application->add(new BomCommand());
        $application->setContainer($this->container);

        $command = $application->find('list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'command'       => 'list',
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE
            ]
        );

        $this->assertRegExp('/Test if files have BOM \(Byte Order Mark\)/', $commandTester->getDisplay());
    }
}
