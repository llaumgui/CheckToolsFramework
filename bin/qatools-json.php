#!/usr/bin/env php
<?php
/**
 * File containing the qatools-json file.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

require dirname( __FILE__ ) . '/../lib/bootstrap.php'; // Packagers "sed" it !


// Init ConsoleTools
$ct = qatConsoleTools::getInstance();
$ct->addOptionOutput();
$ct->addOptionIncludeFilters( array( '@\.json$@' ) );
$ct->addOptionExcludeFilters();
$ct->addOptionAllowCRLF();
$ct->addOptionAllowNotUTF8();
$ct->addOptionAllowBOM();
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
    $testSuite->setName( "Check JSON files: {$file}" );
    $testSuite->setFile( $file );

    $contentFile = file_get_contents( $file );

    qatTestJson::checkCRLF( $testSuite, $file, $contentFile );
    qatTestJson::checkEncoding( $testSuite, $file, $contentFile );
    qatTestJson::checkBOM( $testSuite, $file, $contentFile );
    qatTestJson::checkLastError( $testSuite, $file, $contentFile );
    
    $contentFile = null;
    $testSuite->finish();
}
$mainTestSuites->finish();

$ct->output->outputLine( "\n" . 'Checked.' );

file_put_contents( $ct->options['output']->value, $testSuites->getXML() );

?>