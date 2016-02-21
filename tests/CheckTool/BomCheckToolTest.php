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
use Llaumgui\CheckToolsFramework\CheckTool\BomCheckTool;
use Symfony\Component\Finder\Finder;

class BomCheckToolTest extends PhpUnitHelper
{

    /**
     * Check if content has BOM.
     */
    public function testDoCheck()
    {
        $bomCheckTool = new BomCheckTool();

        // Get testing files
        $finder = new Finder();
        $finder->files()->in(__DIR__ . '/../files');

        foreach ($finder as $file) {
            $check = $bomCheckTool->doCheck($file);
            if (strpos($file->getFileName(), "bom_ko") !== false) {
                $this->assertFalse($check->getResult());
            } else {
                $this->assertTrue($check->getResult());
            }

            $this->assertInstanceOf('Llaumgui\CheckToolsFramework\CheckTool\CheckToolTest', $check);
        }
    }
}
