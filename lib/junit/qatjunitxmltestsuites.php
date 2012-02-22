<?php
/**
 * File containing the qatJunitXMLTestSuites class.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/**
 * The qatJUnitXMLTestSuites class.
 *
 * JUnit Test suites
 *
 * @package QATools
 * @version //autogentag//
 */
class qatJunitXMLTestSuites extends qatJunitXML
{
    /**
     * instance for singleton
     * @var spwUserProfile $instance
     */
    static private $instance = null;

    /**
     * @var DOMDocument
     */
    private $xml;
    /**
     * @var DOMElement
     */
    private $xmlTestSuites;

    /**
     * Constructor
     *
     */
    private function qatJUnitXMLTestSuites()
    {
        $this->xml = new DOMDocument();
        $this->xmlTestSuites = new DOMElement( 'testsuites' );
        $this->xml->appendChild( $this->xmlTestSuites );
    }



    /**
     * Returns an instance of the class qatJUnitXMLTestSuites.
     *
     * @return qatJUnitXMLTestSuites Instance of qatJUnitXMLTestSuites
     */
    public static function getInstance()
    {
        if ( is_null( self::$instance ) )
        {
            self::$instance = new qatJUnitXMLTestSuites();
        }
        return self::$instance;
    }



    /**
     * Add a test suite
     *
     */
    public function addTestSuite()
    {
        $ts = new qatJunitXMLTestSuite();
        $this->xmlTestSuites->appendChild( $ts->getXMLTestSuite() );

        return $ts;
    }





/* _________________________________________________________________ Getters */

    /**
     * Get XML output
     *
     */
    public function getXML()
    {
        return $this->xml->saveXML();
    }

}

?>