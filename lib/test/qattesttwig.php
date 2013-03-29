<?php
/**
 * File containing the qatTesttwigl class.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/**
 * The qatTesttwig class.
 *
 * Provide tests for Symfony templates file.
 *
 * @package QATools
 * @version //autogentag//
 */
 
/*
 *Module Console pour test template Twig 
 * 
 */

class qatTestTwig extends qatTest 
{

    /**
     * Check if is any error in Symfony template
     *
     * @param qatJunitXMLTestSuite $testSuite
     * @param string $file
     * @param string $contentFile
     * 
     */
    public static function checkTemplateSyntaxe( qatJunitXMLTestSuite &$testSuite, $file, &$contentFile )
    {
    	$ct = qatConsoleTools::getInstance();
    	
        $checkTemplateSyntaxe = $testSuite->addTest();
        $checkTemplateSyntaxe->setName( 'Check Symfony templates syntaxe' );
        $checkTemplateSyntaxe->setFile( $file );
        $checkTemplateSyntaxe->setAssertions( 1 );
        $message='';  
            
        Twig_Autoloader::register();
        $loader1 = new Twig_Loader_Array(array(
        		$file.'html' => $contentFile,
        ));
        $twig = new Twig_Environment($loader1);
        try {
        	$template = $twig->loadTemplate($file.'html');

       }
       catch (Twig_Error_Syntax $e) {
	        $message = $e->getMessage();
        	$checkTemplateSyntaxe->addFaillure( 'TWIG Template', $message );
            $ct->output->outputLine( $message, 'error' );
       }	

       $checkTemplateSyntaxe->finish();
    }
}

?>