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
 * CheckToolTest to be returned by each CheckTool.
 */
class CheckToolTest
{
    /**
     * @var boolean
     */
    private $result;
    /**
     * @var string
     */
    private $message;
    /**
     * @var string
     */
    private $description;


    /**
     * Constructor
     *
     * @param type $result  The boolean value of the result.
     */
    public function __construct($result)
    {
        $this->result = $result;
    }


    /**
     * Set result.
     *
     * @param boolean $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }


    /**
     * Get result.
     *
     * @return boolean The boolean value of the result.
     */
    public function getResult()
    {
        return $this->result;
    }


    /**
     * Set message.
     *
     * @param boolean $message Message to return.
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }


    /**
     * Get message.
     *
     * @return string The return message.
     */
    public function getMessage()
    {
        return $this->message;
    }


    /**
     * Set description.
     *
     * @param string $description Description of the tests.
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


    /**
     * Get message.
     *
     * @return string The return message.
     */
    public function getDescription()
    {
        return $this->description;
    }
}
