#!/usr/bin/env php
<?php
/**
 * File containing the qatools-doctrine file.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */
define( "LOAD_TWIG", true );

require dirname( __FILE__ ) . '/../lib/bootstrap.php'; // Packagers "sed" it !


// Init ConsoleTools
$ct = qatConsoleTools::getInstance();
$ct->addOptionOutput();
$ct->addOptionIncludeFilters( array( '@\.php$@' ) );
$ct->addOptionExcludeFilters();
$ct->addOptionAllowCRLF();
$ct->addOptionAllowNotUTF8();
$ct->addOptionAllowBOM();
$ct->addOptionAllowLineAfterTag();
$ct->addArgSource();

// Process
$ct->process();
$ct->output->outputLine();


// Go tests
$testSuites = qatJunitXMLTestSuites::getInstance();
$mainTestSuites = $testSuites->addTestSuite();

foreach ( $ct->findRecursiveFromArg() as $file )
{
    $ct->output->outputLine( $file );

    $testSuite = $mainTestSuites->addTestSuite();
    $testSuite->setName( "PHP Check Doctrine annotations : {$file}" );
    $testSuite->setFile( $file );

    $contentFile = file_get_contents( $file );

    qatTestDoctrine::checkCRLF( $testSuite, $file, $contentFile );
    qatTestDoctrine::checkEncoding( $testSuite, $file, $contentFile );
    qatTestDoctrine::checkBOM( $testSuite, $file, $contentFile );
    qatTestDoctrine::checkLineAfterFinalTag( $testSuite, $file, $contentFile );
    qatTestDoctrine::checkNamespace( $testSuite, $file, $contentFile );
    qatTestDoctrine::checkValidity( $testSuite, $file, $contentFile );
    
    $contentFile = null;
    $testSuite->finish();
}
$mainTestSuites->finish();

$ct->output->outputLine( "\n" . 'Checked.' );

file_put_contents( $ct->options['output']->value, $testSuites->getXML() );

?>