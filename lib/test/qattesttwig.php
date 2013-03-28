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
 * The qatTestEztwig class.
 *
 * Provide tests for Symfony templates file.
 *
 * @package QATools
 * @version //autogentag//
 */
 
/*
 *Module Console pour chargement Twig 
 * 
 */
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class qatTestTwig extends qatTest 
{

    /**
     * Check if is any error in Symfony template
     *
     * @param qatJunitXMLTestSuite $testSuite
     * @param string $file
     * @param string $contentFile
     * @param Symfony template $twig
     */
    public static function checkTemplateSyntaxe( qatJunitXMLTestSuite &$testSuite, $file, &$contentFile )
    {
        $checkTemplateSyntaxe = $testSuite->addTest();
        $checkTemplateSyntaxe->setName( 'Check Symfony templates syntaxe' );
        $checkTemplateSyntaxe->setFile( $file );
        $checkTemplateSyntaxe->setAssertions( 1 );
        $stdout = fopen('php://stdout','w');
        
        

        $console = new Application();
        fwrite($stdout,"valeur de loader : ".print_r($console));
        
        $commands[] = new LintCommand();
        $console->run();
        
        
        fclose($stdout);
        
        
        /*
        if (  !$tpl->validateTemplateFile( $file ) )
        {
            foreach ( $tpl->errorLog() as $error )
            {
                $message = $error['text'];
                $skip = false;

                // Skip bad error
                foreach ( $toSkip as $skiped )
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
        }*/

        $checkTemplateSyntaxe->finish();
    }
}

?>