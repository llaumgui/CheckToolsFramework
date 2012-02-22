#!/usr/bin/env php
<?php
/**
 * File containing the qatools-eztpl file.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

// Load eZ Publish
if ( !@include 'autoload.php' )
{
    die ( 'Launch this script from eZ Publish root directory like : "../../check-tpl-files.php -o ../../output.xml extension"' );
}

require dirname( __FILE__ ) . '/../lib/bootstrap.php'; // Packagers "sed" it !

// Init ConsoleTools
$ct = qatConsoleTools::getInstance();
$ct->addOptionOutput();
$ct->addOptionIncludeFilters( array( '@\.tpl$@' ) );
$ct->addOptionExcludeFilters();
$ct->addOptionAllowCRLF();
$ct->addArgSource();

// Process
$ct->process();
$ct->output->outputLine();


// Go tests
$tpl = eZTemplate::factory();
$testSuites = qatJunitXMLTestSuites::getInstance();

foreach ( $ct->findRecursiveFromArg() as $file )
{
    $ct->output->outputLine( $file );

    $testSuite = $testSuites->addTestSuite();
    $testSuite->setName( 'Check eZ Publish templates' );
    $testSuite->setFile( __FILE__ );

    $contentFile = file_get_contents( $file );

    qatTestEztpl::checkCRLF( $testSuite, $file, $contentFile );
    qatTestEztpl::checkEncoding( $testSuite, $file, $contentFile );
    qatTestEztpl::checkBOM( $testSuite, $file, $contentFile );
    qatTestEztpl::checkTemplateSyntaxe( $testSuite, $file, $contentFile, $tpl );

    $contentFile = null;
    $testSuite->finish();
}

$ct->output->outputLine( "\n" . 'Checked.' );

file_put_contents( $ct->options['output']->value, $testSuites->getXML() );

?>
