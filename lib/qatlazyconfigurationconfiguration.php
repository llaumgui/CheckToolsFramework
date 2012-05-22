<?php
/**
 * File containing the qatLazyConfigurationConfiguration  class.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/**
 * The qatLazyConfigurationConfiguration class.
 *
 * Lazy initialization for Configuration component.
 *
 * @package QATools
 * @version //autogentag//
 */
class qatLazyConfigurationConfiguration implements ezcBaseConfigurationInitializer
{
    /**
     * Lazy init
     *
     * @param unknown_type $cfg
     */
    public static function configureObject( $cfg )
    {
        $cfg->init( 'ezcConfigurationIniReader', QAT_CONF_PATH );
    }
}

?>