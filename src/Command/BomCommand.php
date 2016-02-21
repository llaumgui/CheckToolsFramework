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

use Llaumgui\CheckToolsFramework\Command\CheckToolsCommandAware;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Llaumgui\JunitXml\JunitXmlTestSuites;

/**
 * The BomCommand class.
 */
class BomCommand extends CheckToolsCommandAware
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('bom')
            ->setDescription('Test if files have BOM (Byte Order Mark).')
            // @codingStandardsIgnoreStart
            ->setHelp('For more informations about BOM, see '
                . '<fg=blue>http://en.wikipedia.org/wiki/Byte_order_mark</fg=blue>.' . "\n"
                . 'For more information about regular expression, see <fg=blue>http://symfony.com/doc/current/components/finder.html</fg=blue>.' . "\n\n"
                . 'Example: Find all ".md" and ".php" in "vendor/Llaumgui", exculding "*Tests.php" and "tests" directory:' . "\n"
                . 'php bin/phpct bom --filename="/.(php|md)$/" --filename-exclusion="/Test.php$/" --path-exclusion="/tests/" vendor/Llaumgui')
            // @codingStandardsIgnoreEnd
        ;
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
        parent::execute($input, $output);

        $checkTool = $this->getApplication()->getContainer()->get('ctf.checktool_bom');

        // Init Junit log
        $testSuites = new JunitXmlTestSuites('Check BOM.');
        $testSuite = $testSuites->addTestSuite('Check BOM in files.');

        // Find BOM in files in Finder
        foreach ($this->getFinder() as $file) {
            $check = $checkTool->doCheck($file);

            // Create TestCase
            $testCase = $testSuite->addTest($check->getDescription());
            $testCase->setClassName($file->getRelativePathname());
            $testCase->incAssertions();

            if (!$check->getResult()) {
                $this->output->writeln($check->getDescription() . ': <error>Failed</error>');
                $testCase->addError($check->getMessage());
            } elseif ($check->getResult() && $this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                $this->output->writeln($check->getDescription() . ': <info>Succeeded</info>');
            }
            $testCase->finish();
        }
        $testSuite->finish();

        $this->writeOutput($testSuites->getXml());
    }
}
