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
use Llaumgui\JunitXml\JunitXmlValidation;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Output\OutputInterface;

class JsonCommandTest extends PhpUnitHelper
{
    /**
     * Setup container for test.
     */
    protected function setUp()
    {
        $this->buildContainer();
        $this->mockFileSystem();
    }


    /**
     * Check bom command.
     */
    public function testExecute()
    {
        $application = new Application();
        $application->add($this->container->get('ctf.command.json'));
        $application->setContainer($this->container);

        $command = $application->find('json');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'command'       => $command->getName(),
                'path'          => PATH_TESING_FILES,
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

        // Test file output format
        $this->assertTrue($this->mockedFileSystem->hasChild('junit.xml'));
        $this->assertTrue(JunitXmlValidation::validateXsdFromString(
            $this->mockedFileSystem->getChild('junit.xml')->getContent()
        ));

        // Test file output content
        $this->assertXmlStringEqualsXmlFile(
            $this->xmlResultPath('json_command_test.xml'),
            JunitXmlValidation::getTestableXmlOutput($this->mockedFileSystem->getChild('junit.xml')->getContent())
        );
    }
}
