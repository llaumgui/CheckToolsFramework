<?php
/**
 * File containing the qatTestJson class.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/**
 * The qatTestJSON class.
 *
 * Provide tests for json file.
 *
 * @package QATools
 * @version //autogentag//
 */
class qatTestJson extends qatTest
{

    /**
     * Check if JSON is valid
     *
     * @param qatJunitXMLTestSuite $testSuite
     * @param string $file
     * @param string $contentFile
     *
     *  @SuppressWarnings(PHPMD.UnusedLocalVariable) Use json_decode() only to get error.
     */
    public static function checkLastError( qatJunitXMLTestSuite &$testSuite, $file, &$contentFile )
    {
        $ct = qatConsoleTools::getInstance();

        $checkValidity = $testSuite->addTest();
        $checkValidity->setName( 'Check if JSON is valid' );
        $checkValidity->setFile( $file );
        $checkValidity->setAssertions( 1 );
        $json = json_decode( $contentFile );
        $output = '';

        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                $output = 'Get the maximum stack depth exceeded';
            break;

            case JSON_ERROR_STATE_MISMATCH:
                $output = 'Underflow or the modes mismatch';
            break;

            case JSON_ERROR_CTRL_CHAR:
                $output = 'Has an unexpected control character found';
            break;

            case JSON_ERROR_SYNTAX:
                $output = 'Has a syntax error';
            break;

            case JSON_ERROR_UTF8:
                $output = 'none';
            break;

            case JSON_ERROR_NONE:
                return true;
            break;

            default:
                $output = 'has an unknown error';
            break;
        }

        $message = 'The file "' . $file . '" '. $output;
        $checkValidity->addFaillure( 'JSON', $message );
        $ct->output->outputLine( $message, 'error' );

        $checkValidity->finish();
    }

}

?>