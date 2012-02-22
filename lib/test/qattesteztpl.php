<?php
/**
 * File containing the qatTestEztpl class.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/**
 * The qatTestEztpl class.
 *
 * Provide tests for eZ Publish templates file.
 *
 * @package QATools
 * @version //autogentag//
 */
class qatTestEztpl extends qatTest
{

    private static $toSkip = array(
        'this is most likely a bug in the template parser'
    );





    /**
     * Check if is there a line after the final PHP close tag
     *
     * @param qatJunitXMLTestSuite $testSuite
     * @param string $file
     * @param string $contentFile
     * @param eZTemplate $tpl
     */
    public static function checkTemplateSyntaxe( qatJunitXMLTestSuite &$testSuite, $file, &$contentFile, eZTemplate &$tpl )
    {
        $checkTemplateSyntaxe = $testSuite->addTest();
        $checkTemplateSyntaxe->setName( 'Check eZ Publish templates syntaxe' );
        $checkTemplateSyntaxe->setFile( $file );
        $checkTemplateSyntaxe->setAssertions( 1 );

        if (  !$tpl->validateTemplateFile( $file ) )
        {
            foreach ( $tpl->errorLog() as $error )
            {
                $message = $error['text'];
                $skip = false;

                // Skip bad error
                foreach ( self::$toSkip as $skiped )
                {
                    if( strpos( $message, $skiped ) )
                    {
                        $skip = true;
                        break;
                    }
                }

                if ( !$skip )
                {
                    $checkTemplateSyntaxe->addFaillure( 'Template syntaxe: ' . $error['name'], $message );
                    $ct->output->outputLine( $message, 'error' );
                }
            }
        }

        $checkTemplateSyntaxe->finish();
    }
}

?>