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

use Llaumgui\CheckToolsFramework\Console\Application;
use Llaumgui\CheckToolsFramework\Command\BomCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * The BomCommand class.
 */
class BomCommandTest extends \PHPUnit_Framework_TestCase
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
        $definitions = array(
            'ctf.checktool_bom' => new Definition('Llaumgui\CheckToolsFramework\CheckTool\BomCheckTool')
        );
        $this->container->setDefinitions($definitions);
    }


    /**
     * Check bom command.
     */
    public function testExecute()
    {
        $application = new Application();
        $application->add(new BomCommand());
        $application->setContainer($this->container);

        $command = $application->find('bom');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'path'    => __DIR__ . '/../files'
        ));

        $this->assertRegExp('/Check BOM on bom_ko.[a-z]+: Failed/', $commandTester->getDisplay());
    }
}
