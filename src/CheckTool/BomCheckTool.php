<?php

/*
 * This file is part of the CheckToolsFramework package.
 *
 * Copyright (C) 2015-2016 Guillaume Kulakowski <guillaume@kulakowski.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Llaumgui\CheckToolsFramework\CheckTool;

use Llaumgui\CheckToolsFramework\CheckTool\CheckTool;
use Symfony\Component\Finder\SplFileInfo;

/**
 * The service BomCheckTool which check BOM in files provided by SplFileInfo.
 */
class BomCheckTool extends CheckTool implements CheckToolInterface
{
    /**
     * Check if content has BOM.
     *
     * @param SplFileInfo $file The $finder to check.
     * @return CheckToolTest Return a CheckToolTest object.
     */
    public function doCheck(SplFileInfo $file)
    {
        // Search BOM in file
        $bomString = pack('CCC', 0xef, 0xbb, 0xbf);
        $result = (strncmp($file->getContents(), $bomString, 3) == 0) ? false : true;

        // Inject result in CheckToolTest
        $checkToolTest = new CheckToolTest($result);
        $checkToolTest->setDescription('Check BOM on ' . $file->getRelativePathname());

        if (!$result) {
            $checkToolTest->setMessage('The file "' . $file->getRelativePathname() . '" has BOM.');
        }

        return $checkToolTest;
    }
}
