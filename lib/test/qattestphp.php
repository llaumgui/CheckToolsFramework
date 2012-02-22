<?php
/**
 * File containing the qatTestPhp class.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/**
 * The qatTestPhp class.
 *
 * Provide tests for php file.
 *
 * @package QATools
 * @version //autogentag//
 */
class qatTestPhp extends qatTest
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
                $checkLineAfterFinalTag->addFaillure( 'PHP tag', $message );
                $ct->output->outputLine( $message, 'error' );
            }

            $checkLineAfterFinalTag->finish();
        }
    }
}

?>