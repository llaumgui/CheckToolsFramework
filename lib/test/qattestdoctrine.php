<?php
/**
 * File containing the qatTestDoctrine class.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaValidator;

/**
 * The qatTestDoctrine class.
 *
 * Provide tests for php Doctrine annotations.
 *
 * @package QATools
 * @version //autogentag//
 */
class qatTestDoctrine extends qatTest
{

    /**
     * Check if is there a line after the final PHP close tag
     *
     * @param qatJunitXMLTestSuite $testSuite
     * @param string $file
     * @param string $contentFile
     */
    public static function checkLineAfterFinalTag( qatJunitXMLTestSuite &$testSuite, $file, &$contentFile )
    {
        $ct = qatConsoleTools::getInstance();
    
        if ( $ct->options['allowLineAfterTag']->value == false )
        {
            $checkLineAfterFinalTag = $testSuite->addTest();
            $checkLineAfterFinalTag->setName( 'Check line after the final ?> tag' );
            $checkLineAfterFinalTag->setFile( $file );
            $checkLineAfterFinalTag->setAssertions( 1 );
    
            if ( substr( $contentFile, -2 ) !== "?>" )
            {
                $message = 'The file "' . $file . '" has a line after php close tag "?>".';
                $checkLineAfterFinalTag->addFaillure( 'Doctrine annotations  : PHP', $message );
                $ct->output->outputLine( $message, 'error' );
            }
    
            $checkLineAfterFinalTag->finish();
        }
    }

    /**
     * Check if is there namespace and required ORM mapping
     * namespace Acme\StoreBundle\Entity;
     * use Doctrine\ORM\Mapping as ORM;
     *
     * @param qatJunitXMLTestSuite $testSuite
     * @param string $file
     * @param string $contentFile
     */
    public static function checkNamespace( qatJunitXMLTestSuite &$testSuite, $file, &$contentFile )
    {
        $ct = qatConsoleTools::getInstance();

        $checkNamespace = $testSuite->addTest();
        $checkNamespace->setName( 'Check inclusion' );
        $checkNamespace->setFile( $file );
        $checkNamespace->setAssertions( 1 );

        if( !preg_match( '/use Doctrine\\\ORM\\\Mapping as ORM;/', $contentFile ) )
        {
            $message = 'The file "' . $file . '" need to include "use Doctrine\ORM\Mapping as ORM;".';
            $checkNamespace->addFaillure( 'Doctrine annotations  : PHP', $message );
            $ct->output->outputLine( $message, 'error' );
        }
        
        if( !preg_match( '/namespace.*?Entity;/', $contentFile ) )
        {
            $message = 'Notice : Maybe the file "' . $file . '"isn\'t in good repository ".';
            $checkNamespace->addFaillure( 'Doctrine annotations  : PHP', $message );
            $ct->output->outputLine( $message, 'notice' );
        }
    
        $checkNamespace->finish();
    
    }
    
    /**
     * Check annotation for mapping object to database
     * 1st : - @ORM\Entity
     *       - @ORM\Table(name="[name_of_class]")
     * 
     * @param qatJunitXMLTestSuite $testSuite
     * @param string $file
     * @param string $contentFile
     */
    public static function checkValidity( qatJunitXMLTestSuite &$testSuite, $file, &$contentFile )
    {
        $ct = qatConsoleTools::getInstance();
    
        $checkValidity = $testSuite->addTest();
        $checkValidity->setName( 'Check annotation' );
        $checkValidity->setFile( $file );
        $checkValidity->setAssertions( 1 );

        
        $paths =$file;
        $isDevMode = false;
        
        // the connection configuration
        $dbParams = array(
                'driver'   => 'pdo_mysql',
                'user'     => 'root',
                'password' => '',
                'dbname'   => 'foo',
        );
        $config = Setup::createAnnotationMetadataConfiguration( $paths, $isDevMode );
        $entityManager = EntityManager::create( $dbParams, $config );
        
        $validator = new SchemaValidator($entityManager);
        $errors = $validator->validateMapping();
        
        if ( count( $errors ) > 0 )
        {
            // Lots of errors!
            $message.= implode( "\n\n", $errors );
        }
                
        
    /*    if(!preg_match('/namespace.*?Entity;/', $contentFile))
        {
            $message = 'Notice : Maybe the file "' . $file . '"isn\'t in good repository ".';
            $checkNamespace->addFaillure( 'Doctrine annotations  : PHP', $message );
            $ct->output->outputLine( $message, 'notice' );
        }
    */
        //$message = 'Notice : Maybe the file "' . $file . '"isn\'t in good repository ".';
        $checkValidity->addFaillure( 'Doctrine annotations  : PHP', $message );
        $ct->output->outputLine( $message, 'notice' );
        
        $checkValidity->finish();
    
    }
}

?>