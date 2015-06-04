<?php
/*
 * This file is part of the CheckToolsFramework package.
 *
 * (c) Guillaume Kulakowski <guillaume@kulakowski.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Llaumgui\CheckToolsFramework\Helper;

use Symfony\Component\Finder\Finder;

/**
 * The service Bom.
 */
class Bom
{
    public function check(Finder $finder)
    {
        foreach ($finder as $file) {
            print $file->getRealpath()."\n";
            print $file->getRelativePath()."\n";
            print $file->getRelativePathname()."\n";
        }
    }
}
