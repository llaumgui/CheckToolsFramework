<?php
/**
 * File containing the qatTest class.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/**
 * The qatTest class.
 *
 * Provides common tests for all specifics tests.
 *
 * @package QATools
 * @version //autogentag//
 */
abstract class qatTest
{

    const ENCODING_LIST = 'UTF-8, ISO-8859-1';





    /**
     * Check if file have a CRLF line delimiter.
     *
     * @param qatJunitXMLTestSuite $testSuite
     * @param string $file
     * @param string $contentFile
     */
    public static function checkCRLF( qatJunitXMLTestSuite &$testSuite, $file, &$contentFile )
    {
        $ct = qatConsoleTools::getInstance();

        if ( $ct->options['allowCRLF']->value == false )
        {
            $checkCRLF = $testSuite->addTest();
            $checkCRLF->setName( 'Check file line delimiter (CRLF)' );
            $checkCRLF->setFile( $file );
            $checkCRLF->setAssertions( 1 );

            if ( preg_match( '/'."\r\n".'/', $contentFile ) )
            {
                $message = 'The file "' . $file . '" has a Windows line delimiter (CRLF). Use Unix line delimiter (LF).';
                $checkCRLF->addFaillure( 'CRLF', $message );
                $ct->output->outputLine( $message, 'error' );
            }

            $checkCRLF->finish();
        }
    }



    /**
     * Check if file have a no UTF-8 encoding.
     *
     * @param qatJunitXMLTestSuite $testSuite
     * @param string $file
     * @param string $contentFile
     */
    public static function checkEncoding( qatJunitXMLTestSuite &$testSuite, $file, &$contentFile )
    {
        $ct = qatConsoleTools::getInstance();

        $checkEncoding = $testSuite->addTest();
        $checkEncoding->setName( 'Check file encoding' );
        $checkEncoding->setFile( $file );
        $checkEncoding->setAssertions( 1 );

        $encoding = mb_detect_encoding( $contentFile, self::ENCODING_LIST );
        if ( $encoding !== 'UTF-8' )
        {
            $message = 'The file "' . $file . '" has a non UTF-8 encoding (' . $encoding . ').';
            $checkEncoding->addFaillure( 'Charset', $message );
            $ct->output->outputLine( $message, 'error' );
        }

        $checkEncoding->finish();
    }



    /**
     * Check if file have a BOM.
     *
     * @param qatJunitXMLTestSuite $testSuite
     * @param string $file
     * @param string $contentFile
     */
    public static function checkBOM( qatJunitXMLTestSuite &$testSuite, $file, &$contentFile )
    {
        $ct = qatConsoleTools::getInstance();

        $checkBOM = $testSuite->addTest();
        $checkBOM->setName( 'Check file BOM' );
        $checkBOM->setFile( $file );
        $checkBOM->setAssertions( 1 );

        $bom = pack( 'CCC', 0xef, 0xbb, 0xbf );
        if ( strncmp( $contentFile, $bom, 3 ) == 0 )
        {
            $message = 'The file "' . $file . '" has a BOM.';
            $checkBOM->addFaillure( 'BOM', $message );
            $ct->output->outputLine( $message, 'error' );
        }

        $checkBOM->finish();
    }
}

?>