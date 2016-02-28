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

use Llaumgui\CheckToolsFramework\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;
use org\bovigo\vfs\vfsStream;

/**
 * Description of PhpUnitHelper
 */
class PhpUnitHelper extends \PHPUnit_Framework_TestCase
{
    /**
     * @var org\bovigo\vfs\vfsStreamDirectory
     */
    protected $mockedFileSystem;
    /**
     * @var Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected $container;
    /**
     *
     * @var Symfony\Component\DependencyInjection\Extension\ExtensionInterface
     */
    protected $extension;


    /**
     * Build a mock file system.
     *
     * @param   string  $rootDirName  name of root directory
     * @param   int     $permissions  file permissions of root directory
     */
    public function mockFileSystem($rootDirName = 'root', $permissions = null)
    {
        $this->mockedFileSystem = vfsStream::setup($rootDirName, $permissions);
    }


    /**
     * Build a container for test.
     */
    public function buildContainer()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new Extension();
        $this->container->registerExtension($this->extension);
        $this->container->loadFromExtension($this->extension->getAlias());
        $this->container->compile();

    }


    /**
     * Load a YAML file.
     *
     * @param string $yamlFile Name of the YAML file?
     * @return array Configuration parsed from YAML.
     */
    public function yamlLoader($yamlFile)
    {
        return $this->yamlConfig = Yaml::parse(file_get_contents(__DIR__ . '/../src/Resources/config/' . $yamlFile));
    }


    /**
     * Load a XML result file for test.
     *
     * @param string $xmlFile Name of the XML file?
     * @return string String content of XML.
     */
    public function xmlResultLoader($xmlFile)
    {
        return file_get_contents(PATH_TESING_XML . '/' . $xmlFile);
    }


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
