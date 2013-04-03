<?php
/**
 * File containing the qatTestYml class.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

use Symfony\Component\Yaml\Yaml;
/**
 * The qatTestYml class.
 *
 * Provide tests for php file.
 *
 * @package QATools
 * @version //autogentag//
 */
class qatTestYml extends qatTest
{

    /**
     * Check if YML is valid
     *
     * @param qatJunitXMLTestSuite $testSuite
     * @param string $file
     * @param string $yml
     */
    public static function checkValidity( qatJunitXMLTestSuite &$testSuite, $file , $yml )
    {
        $ct = qatConsoleTools::getInstance();

        $checkValidity = $testSuite->addTest();
        $checkValidity->setName( 'Check if YML is valid' );
        $checkValidity->setFile( $file );
        $checkValidity->setAssertions( 1 );
        
        try 
        {
            Yaml::parse( $yml );
        }
        catch (Exception $e) 
        {
            $message = $e->getMessage();
            $checkValidity->addFaillure( 'YML', $message );
            $ct->output->outputLine( $message, 'error' );
        }
        
        $checkValidity->finish();
    }
}

?>