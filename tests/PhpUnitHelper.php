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

/**
 * Description of PhpUnitHelper
 */
class PhpUnitHelper extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $filesToTestPath = ;

    /**
     * getPrivateMethod
     *
     * @param   object $instance    Instance of the class to reflect.
     * @param   string $methodName  Name of the method.
     * @return  ReflectionMethod
     */
    public function getPrivateMethod($instance, $methodName)
    {
        $reflector = new \ReflectionClass(get_class($instance));
        $method = $reflector->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }


    /**
     * getPrivateProperty
     *
     * @param   object $instance    Instance of the class to reflect.
     * @param   string $methodName  Name of the method.
     * @return  ReflectionProperty
     */
    public function getPrivateProperty($instance, $propertyName)
    {
        $reflector = new \ReflectionClass(get_class($instance));
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);

        return $property;
    }
}
