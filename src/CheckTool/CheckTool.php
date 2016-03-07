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

/**
 * The service CheckTool parent class.
 */
class CheckTool
{
    /**
     * Configuration from check_tools_framework.yml
     * @var array
     */
    protected $config;


    /**
     * Service constructor inject configuration.
     *
     * @param array $checkToolConfigArray Array with configuration from check_tools_framework.
     */
    public function __construct(array $checkToolConfigArray)
    {
        $this->config = $checkToolConfigArray;
    }

    /**
     * Return testSuites description from config.
     *
     * @return string
     */
    public function getTestSuitesDescription()
    {
        return $this->config['test_suites_description'];
    }


    /**
     * Return testSuite description from config.
     *
     * @return string
     */
    public function getTestSuiteDescription()
    {
        return $this->config['test_suite_description'];
    }


    /**
     * Return default FileNamePatern from config.
     *
     * @return string
     */
    public function getDefaultFileNamePatern()
    {
        return $this->config['default_file_name_patern'];
    }
}
