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
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * The service YamlCheckTool.
 */
class YamlCheckTool extends CheckTool implements CheckToolInterface
{
    /**
     * The key with configuration about this service in the
     * check_tools_framework.check_tools array.
     * @var string
     */
    protected $checkToolsConfigKey = 'yaml';


    /**
     * Check if content is valid.
     *
     * @param SplFileInfo $file The $finder to check.
     *
     * @return CheckToolTest Return a CheckToolsResult object.
     */
    public function doCheck(SplFileInfo $file)
    {
        // Try to parse YAML
        $result = true;
        $message = '';
        try {
            Yaml::parse($file->getContents());
        } catch (ParseException $e) {
            $result = false;
            $message = $e->getMessage();
        }

        // Inject result in CheckToolTest
        $checkToolTest = new CheckToolTest($result);
        $checkToolTest->setDescription('Check the YAML syntax of ' . $file->getRelativePathname());

        if (!$result) {
            $checkToolTest->setMessage(
                'The file "' . $file->getRelativePathname() . '" has an YAML error: ' . $message
            );
        }

        return $checkToolTest;
    }
}
