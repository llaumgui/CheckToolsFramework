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
 * Definition of CheckToolInterface for all CheckTool services.
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


    /**
     * Return description about textSuites.
     *
     * @return string
     */
    public function getTestSuitesDescription();


    /**
     * Return description about textSuite.
     *
     * @return string
     */
    public function getTestSuiteDescription();


    /**
     * Return default fileNamePatern argment..
     *
     * @return string
     */
    public function getDefaultFileNamePatern();
}
