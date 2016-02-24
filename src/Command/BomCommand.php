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
        $this->setCheckTool($this->getApplication()->getContainer()->get('ctf.checktool_bom'));

        // Return exit status
        return parent::execute($input, $output);
    }
}
