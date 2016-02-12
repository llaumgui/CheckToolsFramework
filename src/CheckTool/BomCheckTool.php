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

use Symfony\Component\Finder\SplFileInfo;

/**
 * The service BomCheckTool.
 */
class BomCheckTool implements CheckToolInterface
{
    /**
     * @var string
     */
    private $bom;


    /**
     * Construct a BomCheckTool.
     */
    public function __construct()
    {
        $this->bom = pack('CCC', 0xef, 0xbb, 0xbf);
    }


    /**
     * Check if content has BOM.
     *
     * @param SplFileInfo $file The $finder to check.
     *
     * @return CheckToolTest Return a CheckToolsResult object.
     */
    public function doCheck(SplFileInfo $file)
    {
        $result = (strncmp($file->getContents(), $this->bom, 3) == 0) ? false : true;

        $checkToolTest = new CheckToolTest($result);
        $checkToolTest->setDescription('Check BOM on ' . $file->getRelativePathname());

        if (!$result) {
            $checkToolTest->setMessage('The file "' . $file->getRelativePathname() . '" has BOM.');
        }

        return $checkToolTest;
    }
}
