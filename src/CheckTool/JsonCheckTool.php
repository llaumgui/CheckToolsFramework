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
 * The service JsonCheckTool.
 */
class JsonCheckTool implements CheckToolInterface
{
    /**
     * testSuites description.
     * @SuppressWarnings(PHPMD.LongVariable)
     * @var string
     */
    private $testSuitesDescription = 'Check JSON.';
    /**
     * testSuite description..
     * @SuppressWarnings(PHPMD.LongVariable)
     * @var string
     */
    private $testSuiteDescription = 'Check JSON syntax.';


    /**
     * textSuitesDescription getter.
     *
     * @return string
     */
    public function getTestSuitesDescription()
    {
        return $this->testSuitesDescription;
    }


    /**
     * textSuiteDescription getter.
     *
     * @return string
     */
    public function getTestSuiteDescription()
    {
        return $this->testSuiteDescription;
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
        json_decode($file->getContents());
        list($result, $message) = $this->getJsonError(json_last_error());

        $checkToolTest = new CheckToolTest($result);
        $checkToolTest->setDescription('Check the JSON syntax of ' . $file->getRelativePathname());

        if (!$result) {
            $checkToolTest->setMessage('The file "' . $file->getRelativePathname() . '" has an JSO error: ' . $message);
        }

        return $checkToolTest;
    }


    /**
     * Get human error from json_last_error error code.
     *
     * @param integer $errorCode The error code.
     *
     * @return array Return a array.
     */
    public function getJsonError($errorCode)
    {
        switch ($errorCode) {
            case JSON_ERROR_NONE:
                $result = true;
                $message = '';
                break;
            case JSON_ERROR_SYNTAX:
                $result = false;
                $message = 'Syntax error';
                break;
            // @codeCoverageIgnoreStart
            // Because it's PHP native
            case JSON_ERROR_DEPTH:
                $result = false;
                $message = 'The maximum stack depth has been exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $result = false;
                $message = 'Invalid or malformed JSON';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $result = false;
                $message = 'Control character error, possibly incorrectly encoded';
                break;
            case JSON_ERROR_UTF8:
                $result = false;
                $message = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            case JSON_ERROR_RECURSION:
                $result = false;
                $message = 'One or more recursive references in the value to be encoded';
                break;
            case JSON_ERROR_INF_OR_NAN:
                $result = false;
                $message = 'One or more NAN or INF values in the value to be encoded';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $result = false;
                $message = 'A value of a type that cannot be encoded was given';
                break;
            default:
                $result = false;
                $message = 'has an unknown error';
                break;
        }// @codeCoverageIgnoreEnd

        return array($result, $message);
    }
}
