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

class FromConfigFileCommandTest extends PhpUnitHelper
{
    /**
     * Setup container for test.
     */
    protected function setUp()
    {
        $this->outputStub = $this
            ->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutputInterface')
            ->getMock();
        $this->outputStub
            ->method('getErrorOutput')
            ->will($this->returnValue($this->outputStub));

        $this->applicationStub = $this
            ->getMockBuilder('Llaumgui\CheckToolsFramework\Console\Application')
            ->getMock();

        $this->buildContainer();
        $this->mockFileSystem();
    }


    /**
     * Check from_config_file command.
     */
    public function testExecute()
    {
        $application = new Application();
        $application->add($this->container->get('ctf.command.from_config_file'));
        $application->setContainer($this->container);

        $command = $application->find('from_config_file');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'command'           => $command->getName(),
                'path'              => PATH_TESING_FILES,
                '--output'          => $this->mockedFileSystem->url() . '/junit.xml',
                '--path-exclusion'  => '/config_[a-z]+_error/'
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE
            ]
        );

        // Test stdout output
        $this->assertRegExp('/_ko.[a-z]+: Failed/', $commandTester->getDisplay());
        $this->assertRegExp('/_ok.[a-z]+: Succeeded/', $commandTester->getDisplay());

        // Test status code
        $this->assertEquals(1, $commandTester->getStatusCode());

        // Test file output format
        $this->assertTrue($this->mockedFileSystem->hasChild('junit.xml'));
        $this->assertTrue(JunitXmlValidation::validateXsdFromString(
            $this->mockedFileSystem->getChild('junit.xml')->getContent()
        ));

        // Test file output content
        $this->assertXmlStringEqualsXmlFile(
            $this->xmlResultPath('from_config_file_command_test.xml'),
            JunitXmlValidation::getTestableXmlOutput($this->mockedFileSystem->getChild('junit.xml')->getContent())
        );
    }


    /**
     * Check from_config_file command with error code.
     */
    public function testExecuteReturnCode()
    {
        // change path with no config
        chdir(PATH_TESING_FILES);

        $application = new Application();
        $application->add($this->container->get('ctf.command.from_config_file'));
        $application->setContainer($this->container);

        $command = $application->find('from_config_file');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'command'       => $command->getName(),
                'path'          => PATH_TESING_FILES,
                '--output'      => $this->mockedFileSystem->url() . '/junit.xml'
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE
            ]
        );

        // Test status code
        $this->assertEquals(2, $commandTester->getStatusCode());
    }


    /**
     * Check from_config_file command with no exists checker.
     */
    public function testExecuteWithNoExistChecker()
    {
        // change path with no existing checker
        chdir(PATH_TESING_FILES . '/config_checker_error');

        $application = new Application();
        $application->add($this->container->get('ctf.command.from_config_file'));
        $application->setContainer($this->container);

        $command = $application->find('from_config_file');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'command'       => $command->getName(),
                'path'          => '.',
                '--output'      => $this->mockedFileSystem->url() . '/junit.xml'
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE
            ]
        );

        // Test stdout output
        $this->assertRegExp(
            '/No "noexist" check tool avalaible. Please check your configuration in phpct.yml./',
            $commandTester->getDisplay()
        );

        // Test status code
        $this->assertEquals(0, $commandTester->getStatusCode());
    }


    /**
     * Test loadYamlConfig with good configuration.
     */
    public function testLoadYamlConfigOk()
    {
        // Save current path
        $fromConfigFileCommand = $this->container->get('ctf.command.from_config_file');

        // Call
        $this
            ->getPrivateMethod($fromConfigFileCommand, 'loadYamlConfig')
            ->invokeArgs($fromConfigFileCommand, [])
        ;

        // Test call
        $this->assertEquals(
            'phpct.yml',
            $this->getPrivateProperty($fromConfigFileCommand, 'yamlConfigFile')->getValue($fromConfigFileCommand)
        );
        $this->assertTrue(is_array(
            $this->getPrivateProperty($fromConfigFileCommand, 'yamlConfig')->getValue($fromConfigFileCommand)
        ));
    }


    /**
     * Test loadYamlConfig with no configuration.
     */
    public function testLoadYamlConfigEmpty()
    {
        // change path with no config
        chdir(PATH_TESING_FILES);

        // Mock output
        $fromConfigFileCommand = $this->container->get('ctf.command.from_config_file');
        $this->getPrivateProperty($fromConfigFileCommand, 'output')
            ->setValue($fromConfigFileCommand, $this->outputStub);

        // Call
        $returnCode = $this
            ->getPrivateMethod($fromConfigFileCommand, 'loadYamlConfig')
            ->invokeArgs($fromConfigFileCommand, [])
        ;

        // Test Call
        $this->assertEquals(2, $returnCode);
        $this->assertFalse($this->getPrivateProperty($fromConfigFileCommand, 'yamlConfigFile')
            ->getValue($fromConfigFileCommand));
        $this->assertFalse(is_array(
            $this->getPrivateProperty($fromConfigFileCommand, 'yamlConfig')->getValue($fromConfigFileCommand)
        ));
    }


    /**
     * Test loadYamlConfig with configuration with parse error.
     */
    public function testLoadYamlConfigParseError()
    {
        // change path with no config
        chdir(PATH_TESING_FILES . '/config_parse_error');

        // Mock output & application
        $fromConfigFileCommand = $this->container->get('ctf.command.from_config_file');
        $this->getPrivateProperty($fromConfigFileCommand, 'output')
            ->setValue($fromConfigFileCommand, $this->outputStub);
        $this->getPrivateProperty($fromConfigFileCommand, 'application')
            ->setValue($fromConfigFileCommand, $this->applicationStub);

        // Call
        $returnCode = $this
            ->getPrivateMethod($fromConfigFileCommand, 'loadYamlConfig')
            ->invokeArgs($fromConfigFileCommand, [])
        ;

        // Test Call
        $this->assertEquals(3, $returnCode);
        $this->assertEquals(
            'phpct.yml',
            $this->getPrivateProperty($fromConfigFileCommand, 'yamlConfigFile')->getValue($fromConfigFileCommand)
        );
        $this->assertFalse(is_array(
            $this->getPrivateProperty($fromConfigFileCommand, 'yamlConfig')->getValue($fromConfigFileCommand)
        ));
    }


    /**
     * Test loadYamlConfig with configuration with schema validation error.
     */
    public function testLoadYamlConfigValidationError()
    {
        // change path with no config
        chdir(PATH_TESING_FILES . '/config_schema_error');

        // Mock output & application
        $fromConfigFileCommand = $this->container->get('ctf.command.from_config_file');
        $this->getPrivateProperty($fromConfigFileCommand, 'output')
            ->setValue($fromConfigFileCommand, $this->outputStub);
        $this->getPrivateProperty($fromConfigFileCommand, 'application')
            ->setValue($fromConfigFileCommand, $this->applicationStub);

        // Call
        $returnCode = $this
            ->getPrivateMethod($fromConfigFileCommand, 'loadYamlConfig')
            ->invokeArgs($fromConfigFileCommand, [])
        ;

        // Test Call
        $this->assertEquals(4, $returnCode);
        $this->assertEquals(
            'phpct.yml',
            $this->getPrivateProperty($fromConfigFileCommand, 'yamlConfigFile')->getValue($fromConfigFileCommand)
        );
        $this->assertTrue(is_array(
            $this->getPrivateProperty($fromConfigFileCommand, 'yamlConfig')->getValue($fromConfigFileCommand)
        ));
    }


    /**
     * Test overrideConfigFromCmdLine
     */
    public function testOverrideConfigFromCmdLine()
    {
        $fromConfigFileCommand = $this->container->get('ctf.command.from_config_file');

        /*
         * Datas to tests
         */
        // Setup input to test
        $toTestArray = [
            [
                'property_name'     => 'path',
                'option_name'       => 'path',
                'config_key'        => 'path',
                'is_argument'       => true,
                'config_section'    => 'global_config',
                'expected'          => 'path_from_argument'
            ],
            [
                'property_name'     => 'path',
                'option_name'       => 'path_false',
                'config_key'        => 'path',
                'is_argument'       => true,
                'config_section'    => 'global_config',
                'expected'          => 'path_from_config'
            ],
            [
                'property_name'     => 'outputFile',
                'option_name'       => 'output',
                'config_key'        => 'output',
                'is_argument'       => false,
                'config_section'    => 'global_config',
                'expected'          => 'output_from_config'
            ],
            [
                'property_name'     => 'pathPaternExclusion',
                'option_name'       => 'path-exclusion',
                'config_key'        => 'path_exclusion',
                'is_argument'       => false,
                'config_section'    => 'global_config',
                'expected'          => 'path_exclusion_from_option'
            ],
            [
                'property_name'     => 'fileNamePaternExclusion',
                'option_name'       => 'filename-exclusion',
                'config_key'        => 'filename_exclusion',
                'is_argument'       => false,
                'config_section'    => 'global_config',
                'expected'          => 'filename_exclusion_from_config'
            ],
        ];

        // Mapping for mocked $this->input->getArgument()
        $mapInputArgument = [
            ['path',                'path_from_argument'],
        ];

        $mapInputOption = [
            //['output',              'output_from_option'],
            ['path-exclusion',      'path_exclusion_from_option'],
            //['filename-exclusion',  'path_override'],
        ];

        // Configuration loaded by phpct.yml
        $this->getPrivateProperty($fromConfigFileCommand, 'yamlConfig')
            ->setValue(
                $fromConfigFileCommand,
                [
                    'global_config' => [
                        'path'                  => 'path_from_config',
                        'path_false'            => 'path_from_config',
                        'output'                => 'output_from_config',
                        'path_exclusion'        => 'path_exclusion_from_config',
                        'filename_exclusion'    => 'filename_exclusion_from_config'
                    ]
                ]
            );


        /*
         * Mock input
         */
        $inputStub = $this
            ->getMockBuilder('Symfony\Component\Console\Input\InputInterface')
            ->getMock();
        $inputStub->method('getArgument')
             ->will($this->returnValueMap($mapInputArgument));
        $inputStub->method('getOption')
             ->will($this->returnValueMap($mapInputOption));
        $this->getPrivateProperty($fromConfigFileCommand, 'input')
            ->setValue($fromConfigFileCommand, $inputStub);


        /*
         * Test call
         */
        foreach ($toTestArray as $toTest) {
            $this
                ->getPrivateMethod($fromConfigFileCommand, 'overrideConfigFromCmdLine')
                ->invokeArgs(
                    $fromConfigFileCommand,
                    [$toTest['property_name'], $toTest['option_name'], $toTest['config_key'], $toTest['is_argument']]
                )
            ;
            $this->assertEquals(
                $toTest['expected'],
                $this->getPrivateProperty($fromConfigFileCommand, $toTest['property_name'])
                    ->getValue($fromConfigFileCommand)
            );

        }
    }
}
