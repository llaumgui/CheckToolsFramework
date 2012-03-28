<?php
/**
 * File containing the qatConsoleTools class.
 *
 * @version //autogentag//
 * @package QATools
 * @copyright Copyright (C) 2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/**
 * The qatConsoleTools class.
 *
 * Toolbox for ConsoleTools
 *
 * @package QATools
 * @version //autogentag//
 */
class qatConsoleTools
{
    /**
     * instance for singleton
     * @var spwUserProfile $instance
     */
    static private $instance = null;
    /**
     * @var ezcConsoleOutput
     */
    public $output;
    /**
     * @var ezcConsoleInput
     */
    public $input;
    /**
     * @var array
     */
    public $options = array();
    /*
     * @var array
     */
    public $defaultIncludeFilters = array();
    /*
     * @var array
     */
    public $defaultExcludeFilters = array( '@.svn@' );



    /**
     * Constructor
     */
    private function qatConsoleTools()
    {
        /*
         * Setup output
         */
        $this->output = new ezcConsoleOutput();

        $this->output->formats->error->color = 'red';
        $this->output->formats->info->color = 'blue';

        /*
         * Setup input
         */
        $this->input = new ezcConsoleInput();

        $this->options['help'] = $this->input->registerOption(
            new ezcConsoleOption( 'h', 'help' )
        );
    }



    /**
     * Returns an instance of the class qatConsoleTools.
     *
     * @return qatConsoleTools Instance of qatConsoleTools
     */
    public static function getInstance()
    {
        if ( is_null( self::$instance ) )
        {
            self::$instance = new qatConsoleTools();
        }
        return self::$instance;
    }



    /**
     * Display help message
     */
    public function showHelp( )
    {
        $this->output->outputText( $this->input->getSynopsis() . "\n" );
        foreach ( $this->input->getOptions() as $option )
        {
            $helpMessage = $option->shorthelp;
            if ( !empty( $option->longhelp ) )
            {
                $helpMessage = $option->longhelp;

            }

            $cmd = '';
            if ( !empty( $option->short ) )
            {
                $cmd .= "-{$option->short}";

            }
            if ( !empty( $option->long ) )
            {
                if ( !empty( $option->short ) )
                {
                    $cmd .= '/';
                }
                $cmd .= "--{$option->long}";
            }

            $this->output->outputText( "\t" . "{$cmd}: {$helpMessage}\n" );
        }
    }



    /**
     * Process
     *
     */
    public function process()
    {
        try {
            $this->input->process();
        }
        catch( ezcConsoleArgumentMandatoryViolationException $e ) {
            $this->showHelp();
            exit(0);
        }
        catch ( ezcConsoleOptionException $e ) {
            die( $e->getMessage() );
        }

        $this->check();
    }



    /**
     * Check options validities
     *
     */
    private function check()
    {
        if ( $this->options['help']->value !== false )
        {
            $this->showHelp();
            exit(0);
        }

        if ( array_key_exists( 'output', $this->options ) && empty( $this->options['output']->value ) )
        {
            $this->output->outputLine( 'Option with long name "output" is mandatory but not submitted' . "\n", 'error' );
            $this->showHelp();
            exit(0);
        }
    }



    /**
     * Use options and arg for findRecursive
     */
    public function findRecursiveFromArg()
    {
        $exclude = array_merge(
            $this->options['excludeFilters']->value,
            $this->defaultExcludeFilters
        );
        ( $this->options['includeFilters']->value == false )
            ? $include = $this->defaultIncludeFilters : $include = $this->options['includeFilters']->value;

        try {
            $files = ezcBaseFile::findRecursive(
                $this->input->argumentDefinition["source"]->value,
                $this->options['includeFilters']->value,
                $exclude
            );
            return $files;
        } catch (ezcBaseFileNotFoundException $e) {
            $this->output->outputLine( 'Source "' . $this->input->argumentDefinition["source"]->value . '" not found' . "\n", 'error' );
            return array();
        } catch (Exception $e) {
            die( $e );
        }


    }





/* ______________________________________________________________ Add options */

    /**
     * Add output option
     */
    public function addOptionOutput()
    {
        $this->options['output'] = $this->input->registerOption( new ezcConsoleOption(
            'o', // short
            'output', // long
            ezcConsoleInput::TYPE_STRING, // type
            null, // default
            false, // multiple
                'Output file.', // shorthelp
                'Output file for result.' // longhelp
        ) );
    }



    /**
     * Add includeFilters option
     *
     * @param array $default
     */
    public function addOptionIncludeFilters( $default = array() )
    {
        $this->defaultIncludeFilters = array_merge( $this->defaultIncludeFilters, $default );

        $this->options['includeFilters'] = $this->input->registerOption( new ezcConsoleOption(
            'i', // short
            'include', // long
            ezcConsoleInput::TYPE_STRING, // type
            $this->defaultIncludeFilters, // default
            true, // multiple
            'Include filter.', // shorthelp
            'Include filter, use regular expressions like \'@\.php$@\'.' // longhelp
        ) );
    }



    /**
     * Add excludeFilters option
     */
    public function addOptionExcludeFilters()
    {
        $this->options['excludeFilters'] = $this->input->registerOption( new ezcConsoleOption(
            'e', // short
            'exclude', // long
            ezcConsoleInput::TYPE_STRING, // type
            array(), // default
            true, // multiple
            'Exclude filter.', // shorthelp
            'Exclude filter, use regular expressions like \'@ezc@\'.' // longhelp
        ) );
    }



    /**
     * Add allowCRLF option
     */
    public function addOptionAllowCRLF()
    {
        $this->options['allowCRLF'] = $this->input->registerOption( new ezcConsoleOption(
            'c', // short
            'allowCRLF', // long
            ezcConsoleInput::TYPE_NONE, // type
            false, // default
            false, // multiple
            'Allow CRLF  in file.', // shorthelp
            'Dont\'t check CRLF and allow it.' // longhelp
        ) );
    }


    /**
     * Add allowNotUTF option
     */
    public function addOptionAllowNotUTF8()
    {
        $this->options['allowNotUTF'] = $this->input->registerOption( new ezcConsoleOption(
                'u', // short
                'allowNotUTF', // long
        ezcConsoleInput::TYPE_NONE, // type
        false, // default
        false, // multiple
                'Allow files not in UTF8.', // shorthelp
                'Dont\'t check if file is encoded in UTF-8, allow all charsets.' // longhelp
        ) );
    }


    /**
     * Add allowBOM option
     */
    public function addOptionAllowBOM()
    {
        $this->options['allowBOM'] = $this->input->registerOption( new ezcConsoleOption(
                    'b', // short
                    'allowBOM', // long
        ezcConsoleInput::TYPE_NONE, // type
        false, // default
        false, // multiple
                    'Allow BOM in files.', // shorthelp
                    'Allow BOM in files.' // longhelp
        ) );
    }


    /**
     * Add allowLineAfterTag option
     */
    public function addOptionAllowLineAfterTag()
    {
        $this->options['allowLineAfterTag'] = $this->input->registerOption( new ezcConsoleOption(
            'l', // short
            'allowLineAfterTag', // long
            ezcConsoleInput::TYPE_NONE, // type
            false, // default
            false, // multiple
            'Allow line after tag.', // shorthelp
            'Allow new line after final tag ?>.' // longhelp
        ) );
    }



    /**
     * Add argument source
     */
    public function addArgSource()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "source" );
        $this->input->argumentDefinition[0]->shorthelp = "The source directory.";
    }

}

?>