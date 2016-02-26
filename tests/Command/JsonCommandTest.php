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
use Llaumgui\CheckToolsFramework\Console\Application;
use Llaumgui\CheckToolsFramework\Command\JsonCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Console\Output\OutputInterface;
use org\bovigo\vfs\vfsStream;
use Llaumgui\JunitXml\JunitXmlValidation;

class JsonCommandTest extends PhpUnitHelper
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
        $definitions = [
            'ctf.checktool_json' => new Definition('Llaumgui\CheckToolsFramework\CheckTool\JsonCheckTool')
        ];
        $this->container->setDefinitions($definitions);
        $this->mockedFileSystem = vfsStream::setup();
    }


    /**
     * Check bom command.
     */
    public function testExecute()
    {
        $application = new Application();
        $application->add(new JsonCommand());
        $application->setContainer($this->container);

        $command = $application->find('json');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'command'       => $command->getName(),
                'path'          => __DIR__ . '/../files',
                '--filename'    => '/json_(ok|ko)(.*)$/',
                '--output'      => $this->mockedFileSystem->url() . '/junit.xml'
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE
            ]
        );

        // Test stdout output
        $this->assertRegExp('/Check the JSON syntax of json_ko.json: Failed/', $commandTester->getDisplay());
        $this->assertRegExp('/Check the JSON syntax of json_ok.json: Succeeded/', $commandTester->getDisplay());

        // Test status code
        $this->assertEquals(1, $commandTester->getStatusCode());

        // test file output
        $this->assertTrue($this->mockedFileSystem->hasChild('junit.xml'));
        $this->assertTrue(JunitXmlValidation::validateXsdFromString(
            $this->mockedFileSystem->getChild('junit.xml')->getContent()
        ));
    }
}
