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

/**
 * The BomCommand class.
 */
class YamlCommand extends CheckToolsCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setDescription('Test if YAML files are valides')
            // @codingStandardsIgnoreStart
            ->setHelp('For more information about regular expression, see <fg=blue>http://symfony.com/doc/current/components/finder.html</fg=blue>.' . "\n\n"
                . 'Example: Check all ".json" in "vendor/Llaumgui", exculding "Tests" directory:' . "\n"
                . 'php bin/phpct yaml --path-exclusion="/Tests/" vendor/Llaumgui')
            ->setAliases(['yml'])
            // @codingStandardsIgnoreEnd
        ;

        parent::configure();
    }
}
