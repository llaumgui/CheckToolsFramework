<?php
/**
 * File bootstrap.php.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/*
 * Load eZ Components
 */
if( !defined( 'EZCBASE_ENABLED' ) OR EZCBASE_ENABLED !== true )
{
    $baseEnabled = @include 'ezc/Base/base.php';
    if ( !$baseEnabled )
    {
        $baseEnabled = @include 'Base/src/base.php';
        if ( !$baseEnabled )
        {
            $baseEnabled = @include dirname( __FILE__ ) . '/ezc/Base/src/base.php';
        }
    }
    define( 'EZCBASE_LOADED', $baseEnabled );

    if ( !EZCBASE_LOADED )
    {
        trigger_error( 'eZ Components is not avalaible on your system. Please read README file first !', E_USER_ERROR );
    }
}

spl_autoload_register( array( 'ezcBase', 'autoload' ) );


/*
 * Add the class repository containing our application's classes. We store
 * those in the /lib directory and the classes have the "hello" prefix.
 */
ezcBase::addClassRepository( dirname( __FILE__ ), null );

?>