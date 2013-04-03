#!/usr/bin/env php
<?php
/**
 * File containing the qatools-xml file.
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
$ct->addOptionIncludeFilters( array( '@\.xml$@' ) );
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
    $testSuite->setName( "Check XML files: {$file}" );
    $testSuite->setFile( $file );

    $contentFile = file_get_contents( $file );

    qatTestXml::checkCRLF( $testSuite, $file, $contentFile );
    qatTestXml::checkEncoding( $testSuite, $file, $contentFile );
    qatTestXml::checkBOM( $testSuite, $file, $contentFile );
    qatTestXml::checkValidity( $testSuite, $file, $contentFile );

    $contentFile = null;
    $testSuite->finish();
}
$mainTestSuites->finish();

$ct->output->outputLine( "\n" . 'Checked.' );

file_put_contents( $ct->options['output']->value, $testSuites->getXML() );

?>