<?php
/**
 * File containing the qatJunitXMLTestCase class.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/**
 * The qatJunitXMLTestCase class.
 *
 * JUnit Test case.
 *
 * @package QATools
 * @version //autogentag//
 */
class qatJunitXMLTestCase extends qatJunitXML
{
    /**
     * @var DOMElement
     */
    private $xmlTestCase;
    /**
     * @var qatJUnitXMLTestSuite
     */
    private $testSuite;
    /**
    * @var string
    */
    private $name = '';
    /**
     * @var string
     */
    private $class = '';
    /**
     * @var string
     */
    private $file = '';
    /**
     * @var string
     */
    private $line = '';
    /**
     * @var string
     */
    private $assertions = 0;
    /**
     * @var float
     */
    private $beginimeTime;
    /**
     * @var float
     */
    private $time;



    /**
     * Constructor
     *
     * @param qatJUnitXMLTestSuite $ts
     */
    public function qatJUnitXMLTestCase( qatJUnitXMLTestSuite $ts )
    {
        $this->testSuite = $ts;
        $this->beginimeTime = microtime( true );
        $this->xmlTestCase = new DOMElement( 'testcase' );
    }



    /**
     * Finish test suite
     */
    public function finish()
    {
        $this->time = microtime( true ) - $this->beginimeTime;

        $this->xmlTestCase->setAttribute( 'time', $this->time );
    }



    /**
     * Add an error
     *
     * @param string $type
     * @param string $message
     */
    public function addError( $type, $message )
    {
        $this->testSuite->incError();

        $errorXML = new DOMElement( 'error' );
        $this->xmlTestCase->appendChild( $errorXML );
        $errorXML->setAttribute( "type", $type );
        $errorXML->nodeValue = $message;
    }



    /**
     * Add an failures
     *
     * @param string $type
     * @param string $message
     */
    public function addFaillure( $type, $message )
    {
        $this->testSuite->incFailures();

        $failureXML = new DOMElement( 'failure' );
        $this->xmlTestCase->appendChild( $failureXML );
        $failureXML->setAttribute( "type", $type );
        $failureXML->nodeValue = $message;
    }



/* __________________________________________________________________ Setters */

    /**
     * Set Name
     *
     * @param string $name
     */
    public function setName( $name )
    {
        $this->name = $name;

        $this->xmlTestCase->setAttribute( 'name', $this->name );
    }



    /**
     * Set class
     *
     * @param string $class
     */
    public function setClass( $class )
    {
        $this->class = $class;

        $this->xmlTestCase->setAttribute( 'class', $this->class );
    }



    /**
     * Set file
     *
     * @param string $file
     */
    public function setFile( $file )
    {
        $this->file = $file;

        $this->xmlTestCase->setAttribute( 'file', $this->file );
    }



    /**
     * Set line
     *
     * @param string $line
     */
    public function setLine( $line )
    {
        $this->line = $line;

        $this->xmlTestCase->setAttribute( 'line', $this->line );
    }



    /**
     * Set assertions
     *
     * @param string $assertions
     */
    public function setAssertions( $assertions )
    {
        $this->assertions = $assertions;
        $this->testSuite->incAssertions( $assertions );

        $this->xmlTestCase->setAttribute( 'assertions', $this->assertions );
    }





/* __________________________________________________________________ Getters */

    /**
     * Get $xmlTestSuite
     *
     * @return DOMElement
     */
    public function getXMLTestCase()
    {
        return $this->xmlTestCase;
    }
}

?>