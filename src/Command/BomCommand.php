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

/**
 * The BomCommand class.
 */
class BomCommand extends CheckToolsCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setDescription('Test if files have BOM (Byte Order Mark)')
            // @codingStandardsIgnoreStart
            ->setHelp('For more informations about BOM, see '
                . '<fg=blue>http://en.wikipedia.org/wiki/Byte_order_mark</fg=blue>.' . "\n"
                . 'For more information about regular expression, see <fg=blue>http://symfony.com/doc/current/components/finder.html</fg=blue>.' . "\n\n"
                . 'Example: Check all ".md" and ".php" in "vendor/Llaumgui", exculding "*Tests.php" and "tests" directory:' . "\n"
                . 'php bin/phpct bom --filename="/.(php|md)$/" --filename-exclusion="/Test.php$/" --path-exclusion="/tests/" vendor/Llaumgui')
            // @codingStandardsIgnoreEnd
        ;

        parent::configure();
    }
}
