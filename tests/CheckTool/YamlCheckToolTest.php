<?php

/*
 * This file is part of the CheckToolsFramework package.
 *
 * Copyright (C) 2015-2016 Guillaume Kulakowski <guillaume@kulakowski.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Llaumgui\CheckToolsFramework\CheckTool;

use Tests\Llaumgui\CheckToolsFramework\PhpUnitHelper;
use Llaumgui\CheckToolsFramework\CheckTool\YamlCheckTool;
use Symfony\Component\Finder\Finder;

class YamlheckToolTest extends PhpUnitHelper
{

    /**
     * Check if YAML is valide
     */
    public function testDoCheck()
    {
        // Load CheckTool
        $config = $this->yamlLoader('check_tools_framework.yml');
        $yamlCheckTool = new YamlCheckTool($config['check_tools_framework']['check_tools']['yaml']);

        // Get testing files
        $finder = new Finder();
        $finder->files()
            ->in(PATH_TESING_FILES)
            ->name('/\.yml/')
            ->depth(0);

        $count = 0;
        foreach ($finder as $file) {
            $check = $yamlCheckTool->doCheck($file);
            if (strpos($file->getFileName(), "yml_ko") !== false
                    || strpos($file->getFileName(), "encoding_ko") !== false) {
                $this->assertFalse($check->getResult());
                $count++;
            } else {
                $this->assertTrue($check->getResult());
                $count++;
            }

            $this->assertInstanceOf('Llaumgui\CheckToolsFramework\CheckTool\CheckToolTest', $check);
        }
        $this->assertTrue($count>=2);
    }
}
