<?php
/*
 * This file is part of the CheckToolsFramework package.
 *
 * (c) Guillaume Kulakowski <guillaume@kulakowski.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Llaumgui\CheckToolsFramework;

use Llaumgui\CheckToolsFramework\Console\Application;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * The Command class.
 *
 * Build a Symfony\Component\Console\ object.
 */
class Cli
{
    /***
     * @var Llaumgui\CheckToolsFramework\Console\Application
     */
    private $console;
    /***
     * @var array
     */
    private $commandsToLoad;


    /**
     * Service constructor.
     *
     * @param Application $console        Application instance.
     * @param array       $commandsToLoad List of commands to load.
     */
    public function __construct(Application $console, array $commandsToLoad)
    {
        $this->console = $console;
        $this->commandsToLoad = $commandsToLoad;

        // Set the Application definition
        $this->console->setDefinition($this->getDefinition());
        $this->loadCommands();

        $this->console->run();
    }


    /**
     * Gets the default input definition.
     *
     * @return InputDefinition An InputDefinition instance
     */
    protected function getDefinition()
    {
        return new InputDefinition(array(
            // @codingStandardsIgnoreStart
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),
            new InputOption('--help', '-h', InputOption::VALUE_NONE, 'Display this help message'),
            new InputOption('--verbose', '-v|vv|vvv', InputOption::VALUE_NONE, 'Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug'),
            //new InputOption('--quiet', '-q', InputOption::VALUE_NONE, 'Do not output any message'),
            new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Display this application version'),
            //new InputOption('--ansi', '', InputOption::VALUE_NONE, 'Force ANSI output'),
            //new InputOption('--no-ansi', '', InputOption::VALUE_NONE, 'Disable ANSI output'),
            //new InputOption('--no-interaction', '-n', InputOption::VALUE_NONE, 'Do not ask any interactive question'),
            // @codingStandardsIgnoreEnd
        ));
    }


    /**
     * Load commands from configuration.
     */
    private function loadCommands()
    {
        foreach ($this->commandsToLoad as $command) {
            $this->console->add(new $command());
        }
    }
}
