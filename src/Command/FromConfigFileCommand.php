<?php

/*
 * This file is part of the CheckToolsFramework package.
 *
 * Copyright (C) 2015-2016 Guillaume Kulakowski <guillaume@kulakowski.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Llaumgui\CheckToolsFramework\Command;

use Llaumgui\CheckToolsFramework\Command\CheckToolsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Llaumgui\JunitXml\JunitXmlTestSuites;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use RomaricDrigon\MetaYaml\MetaYaml;
use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

/**
 * The BomCommand class.
 */
class FromConfigFileCommand extends CheckToolsCommand
{
    /**
     * @var string
     */
    private $yamlConfigFile = false;
    /**
     * @var array
     */
    private $yamlConfig = false;


    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setDescription('Launch checktools from config files')
            // @codingStandardsIgnoreStart
            ->setHelp('This command allow to use a phpct.yml config file to launch several tests.'
                    . 'Your phpct.yml must be at the root of your projet and you must use phpct command from the root of your project also.')
            ->setAliases(['fcf'])
            // @codingStandardsIgnoreEnd
        ;

        parent::configure();
    }


    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract method is not implemented
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->fileNamePatern = $input->getOption('filename');

        if (($returnCode = $this->loadYamlConfig()) !== true) {
            return $returnCode;
        }

        // Init CheckToolsCommand
        $this->overrideConfigFromCmdLine('path', 'path', 'path', true);
        $this->overrideConfigFromCmdLine('outputFile', 'output', 'output');
        $this->overrideConfigFromCmdLine('pathPaternExclusion', 'path-exclusion', 'path_exclusion');
        $this->overrideConfigFromCmdLine('fileNamePaternExclusion', 'filename-exclusion', 'filename_exclusion');
        $this->overrideConfigFromCmdLine('ignoreVcs', 'noignore-vcs', 'noignore_vcs');

        // Invers ignore / noignore
        $this->ignoreVcs = ($this->ignoreVcs ? false : true);


        // Write information
        $this->output->writeln($this->getApplication()->getLongVersion());

        return $this->doTestSuites();
    }


    /**
     * Override the doTestSuites function.
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function doTestSuites()
    {
        $this->output->writeln('Use configuration file: ' . $this->yamlConfigFile);

        // Init Junit log
        $checkTool = $this->getCheckTool();
        $this->testSuites = new JunitXmlTestSuites($checkTool->getTestSuitesDescription());

        foreach ($this->yamlConfig['checker'] as $checker) {
            try {
                $checkToolFromYaml = $this->getApplication()->getContainer()->get('ctf.checktool.' . $checker);
            } catch (InvalidArgumentException $e) {
                $this->output->writeln('<error>No "' . $checker . '" check tool avalaible. ' .
                    'Please check your configuration in ' . $this->yamlConfigFile . '.</error>');
                continue;
            }

            $this->output->writeln('<info>Use "' . $checker . '" check tool.</info>');

            // Override default values
            $this->fileNamePatern = $checkToolFromYaml->getDefaultFileNamePatern();
            $this->doTestSuite($checkToolFromYaml);
        }

        return $this->postExecuteHook();
    }


    /**
     * Load YAML configuration from file
     *
     * @return boolean True if all is good.
     */
    private function loadYamlConfig()
    {
        // Load the configuration file if existe.
        foreach (['phpct.yml', '.phpct.yml', '.phpctrc'] as $file) {
            if (file_exists($file)) {
                $this->yamlConfigFile = $file;
                break;
            }
        }

        // Load YAML data from file
        if ($this->yamlConfigFile === false) {
            $this->output->writeln('<error>No configuration found !</error>');

            return 2;
        }

        // Load YAML config
        try {
            $this->yamlConfig = Yaml::parse(file_get_contents($this->yamlConfigFile));
        } catch (ParseException $e) {
            $this->output->writeln('<error>YAML error in ' . $this->yamlConfigFile . '.</error>');
            $this->getApplication()->renderException($e, $this->output->getErrorOutput());

            return 3;
        }

        // Use schema to validate the YAML config
        try {
            $schema = new MetaYaml(
                Yaml::parse(file_get_contents(__DIR__ . '/../Resources/schema/phpct.yml')),
                true
            );
            $schema->validate($this->yamlConfig);
        } catch (NodeValidatorException $e) {
            $this->output->writeln('<error>Wrong file ' . $this->yamlConfigFile .
                ' that does not validate schema.</error>');
            $this->getApplication()->renderException($e, $this->output->getErrorOutput());

            return 4;
        }

        return true;
    }


    /**
     * Override configuration from command line arguments or options.
     *
     * @param string $propertyName      Property name to store data.
     * @param string $optionName        Command line option or argument name.
     * @param string $configKey         Config key.
     * @param string $isArgument        The command line information is an argument ?
     * @param string $configSection     Config section.
     */
    private function overrideConfigFromCmdLine(
        $propertyName,
        $optionName,
        $configKey,
        $isArgument = false,
        $configSection = 'global_config'
    ) {
        $cmdValue = (($isArgument) ? $this->input->getArgument($optionName) : $this->input->getOption($optionName));
        if (isset($cmdValue)) {
            $this->$propertyName = $cmdValue;
        } elseif (array_key_exists($configSection, $this->yamlConfig)
                && array_key_exists($configKey, $this->yamlConfig[$configSection])) {
            $this->$propertyName = $this->yamlConfig[$configSection][$configKey];
        }
    }
}
