<?php
/**
 * File containing the qatJunitXMLTestSuite class.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/**
 * The qatJunitXMLTestSuite class.
 *
 * JUnit Test suite.
 *
 * @package QATools
 * @version //autogentag//
 */
class qatJunitXMLTestSuite extends qatJunitXML
{
    /**
    * @var DOMElement
    */
    private $xmlTestSuite;
    /**
     * @var string
     */
    private $name = '';
    /**
     * @var string
     */
    private $file = '';
    /**
     * @var int
     */
    private $tests = 0;
    /**
     * @var int
     */
    private $errors = 0;
    /**
     * @var int
     */
    private $failures = 0;
    /**
     * @var int
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
     */
    public function qatJUnitXMLTestSuite()
    {
        $this->beginimeTime = microtime( true );
        $this->xmlTestSuite = new DOMElement( 'testsuite' );
    }



    /**
     * Add test
     */
    public function addTest()
    {
        $this->tests++;

        $test = new qatJunitXMLTestCase( $this );
        $this->xmlTestSuite->appendChild( $test->getXMLTestCase() );

        return $test;
    }



    /**
     * Finish test suite
     */
    public function finish()
    {
        $this->time = microtime( true ) - $this->beginimeTime;

        $this->xmlTestSuite->setAttribute( 'tests', $this->tests );
        $this->xmlTestSuite->setAttribute( 'errors', $this->errors );
        $this->xmlTestSuite->setAttribute( 'failures', $this->failures );
        $this->xmlTestSuite->setAttribute( 'time', $this->time );
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

        $this->xmlTestSuite->setAttribute( 'name', $this->name );
    }



    /**
     * Set file
     *
     * @param string $file
     */
    public function setFile( $file )
    {
        $this->file = $file;

        $this->xmlTestSuite->setAttribute( 'file', $this->file );
    }



    /**
     * Increment errors
     *
     * @param int $inc
     */
    public function incError( $inc = 1 )
    {
        $this->errors += $inc;
    }



    /**
     * Increment failures
     *
     * @param int $inc
     */
    public function incFailures( $inc = 1 )
    {
        $this->failures += $inc;
    }



    /**
     * Increment failures
     *
     * @param int $inc
     */
    public function incAssertions( $inc = 1 )
    {
        $this->assertions += $inc;
    }





/* __________________________________________________________________ Getters */
    /**
     * Get $xmlTestSuite
     *
     * @return DOMElement
     */
    public function getXMLTestSuite()
    {
        return $this->xmlTestSuite;
    }
}

?>