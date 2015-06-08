<?php
/*
 * This file is part of the CheckToolsFramework package.
 *
 * (c) Guillaume Kulakowski <guillaume@kulakowski.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Llaumgui\CheckToolsFramework\CheckTool;

use Symfony\Component\Finder\SplFileInfo;

/**
 * Definition of CheckToolInterface.
 */
interface CheckToolInterface
{
    /**
     * Check content.
     *
     * @param string $content The content to check.
     * @return CheckToolTest Return a CheckToolsResult object.
     */
    public function doCheck(SplFileInfo $content);
}
