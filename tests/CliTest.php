<?php

/*
 * This file is part of the CheckToolsFramework package.
 *
 * Copyright (C) 2015-2016 Guillaume Kulakowski <guillaume@kulakowski.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Llaumgui\CheckToolsFramework;

use Tests\Llaumgui\CheckToolsFramework\PhpUnitHelper;
use Llaumgui\CheckToolsFramework\Cli;

/**
 * The Command class.
 *
 * Build a Symfony\Component\Console\ object.
 */
class CliTest extends PhpUnitHelper
{
    /**
     * @var Llaumgui\CheckToolsFramework\Cli
     */
    protected $cli;

    /**
     * @var Llaumgui\CheckToolsFramework\Console\Application
     */
    protected $consoleStub;

    /**
     * @var array
     */
    protected $commandsToLoad = [
        'Llaumgui\CheckToolsFramework\Command\BomCommand'
    ];


    /**
     * Setup mock for test.
     */
    protected function setUp()
    {
        $this->consoleStub = $this
            ->getMockBuilder('Llaumgui\CheckToolsFramework\Console\Application')
            ->getMock();
        $this->cli = new Cli($this->consoleStub, $this->commandsToLoad);
    }


    /**
     * Test the constructor
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            'Llaumgui\CheckToolsFramework\Console\Application',
            $this->getPrivateProperty($this->cli, 'console')->getValue($this->cli)
        );
        $this->assertEquals(
            $this->commandsToLoad,
            $this->getPrivateProperty($this->cli, 'commandsToLoad')->getValue($this->cli)
        );
    }


    public function testGetDefinition()
    {
        $this->assertInstanceOf(
            'Symfony\Component\Console\Input\InputDefinition',
            $this->getPrivateMethod($this->cli, 'getDefinition')
                ->invokeArgs($this->cli, [])
        );
    }
}
