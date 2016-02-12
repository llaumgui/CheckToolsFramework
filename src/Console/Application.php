<?php

/*
 * This file is part of the CheckToolsFramework package.
 *
 * Copyright (C) 2015-2016 Guillaume Kulakowski <guillaume@kulakowski.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Llaumgui\CheckToolsFramework\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\DependencyInjection\ContainerBuilder as Container;

/**
 * Application.
 */
class Application extends BaseApplication
{
    /**
     * @var ContainerInterface
     */
    protected $container;


    /**
     * Gets the Container associated with this Controller.
     *
     * @return ContainerInterface $container A ContainerInterface instance
     */
    public function getContainer()
    {
        return $this->container;
    }


    /**
     * Sets the Container associated with this Controller.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function setContainer(Container $container = null)
    {
        $this->container = $container;
    }
}
