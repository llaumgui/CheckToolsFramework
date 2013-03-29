<?php
/**
 * File containing the qatTestXml class.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/**
 * The qatTestXml class.
 *
 * Provide tests for xml file.
 *
 * @package QATools
 * @version //autogentag//
 */
class qatTestXml extends qatTest
{

    /**
     * Check if XML is valid
     *
     * @param qatJunitXMLTestSuite $testSuite
     * @param string $file
     * @param string $contentFile
     */
    public static function checkValidity( qatJunitXMLTestSuite &$testSuite, $file, &$contentFile )
    {
        $ct = qatConsoleTools::getInstance();

        $checkValidity = $testSuite->addTest();
        $checkValidity->setName( 'Check if XML is valid' );
        $checkValidity->setFile( $file );
        $checkValidity->setAssertions( 1 );

        if ( !@simplexml_load_string( $output ) )
        {
            $message = 'The file "' . $file . '" has a invalid XML syntaxe".';
            $checkValidity->addFaillure( 'XML syntaxe', $message );
            $ct->output->outputLine( $message, 'error' );
        }

        $checkValidity->finish();
    }
}

?>