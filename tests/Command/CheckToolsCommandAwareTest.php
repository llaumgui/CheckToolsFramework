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
use org\bovigo\vfs\vfsStream;

/**
 * Use to test all globals options.
 */
class CheckToolsCommandAwareTest extends PhpUnitHelper
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var org\bovigo\vfs\vfsStreamDirectory
     */
    protected $mockedFileSystem;


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
        $this->mockedFileSystem = vfsStream::setup('root', 0000); // Read only filesystem
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


    /**
     * Check bom command.
     */
    public function testExecuteStatusCode()
    {
        $application = new Application();
        $application->add(new BomCommand());
        $application->setContainer($this->container);

        $command = $application->find('bom');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'command'               => $command->getName(),
                'path'                  => __DIR__ . '/../files',
                '--filename'            => '/bom_(.*).php$/',
                '--filename-exclusion'  => '/ko/',
                '--path-exclusion'      => '/bomToExclude/'
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE
            ]
        );

        // Test stdout output
        $this->assertRegExp('/Check BOM on bom_ok.[a-z]+: Succeeded/', $commandTester->getDisplay());

        // Test status code
        $this->assertEquals(0, $commandTester->getStatusCode());
    }


    /**
     * Check bom command.
     */
    public function testExecuteIOException()
    {
        $application = new Application();
        $application->add(new BomCommand());
        $application->setContainer($this->container);

        $command = $application->find('bom');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'command'               => $command->getName(),
                'path'                  => __DIR__ . '/../files',
                '--filename'            => '/bom_(.*).php$/',
                '--filename-exclusion'  => '/ko/',
                '--path-exclusion'      => '/bomToExclude/',
                 '--output'      => $this->mockedFileSystem->url() . '/junit.xml'
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE
            ]
        );

        // Test stdout output
        $this->assertRegExp('/Error writing in (.*)junit.xml/', $commandTester->getDisplay());
    }
}
