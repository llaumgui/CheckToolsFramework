<?php
/*
 * This file is part of the CheckToolsFramework package.
 *
 * (c) Guillaume Kulakowski <guillaume@kulakowski.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Llaumgui\CheckToolsFramework\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * Check class with all default configuration shared between each CheckClass.
 * Expose also some check helpers.
 */
class CheckHelper extends Command
{
    /**
     * @var array
     */
    protected $path;
    /**
     * @var string
     */
    protected $pathPaternExclusion;
    /**
     * @var string
     */
    protected $fileNamePatern = "*";
    /**
     * @var string
     */
    protected $fileNamePaternExclusion;
    /**
     * @var boolean
     */
    protected $ignoreVcs = true;
    /**
     * @var Symfony\Component\Finder\Finder
     */
    protected $finder;


    /**
     * Configures the child command.
     */
    protected function configure()
    {
        $this
            ->addArgument(
                'path',
                InputArgument::REQUIRED | InputArgument::IS_ARRAY,
                'Path where to find files.' . "\n"
                . 'Can have mutliple values and can use string or regular expression.'
            )
            ->addOption(
                '--filename',
                '-f',
                InputOption::VALUE_REQUIRED,
                'File name pattern to check (can use regular expression)',
                $this->fileNamePatern
            )
            ->addOption(
                '--filename-exclusion',
                null,
                InputOption::VALUE_OPTIONAL,
                'File name pattern extension (can use regular expression)'
            )
            ->addOption(
                '--path-exclusion',
                null,
                InputOption::VALUE_OPTIONAL,
                'Directory name pattern extension (can use regular expression)'
            )
            ->addOption(
                '--noignore-vcs',
                null,
                InputOption::VALUE_NONE,
                'By default the finder ignore VCS files and directories'
            )
        ;
    }


    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract method is not implemented
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->path = $input->getArgument('path');
        $this->pathPaternExclusion = $input->getOption('path-exclusion');
        $this->fileNamePatern = $input->getOption('filename');
        $this->fileNamePaternExclusion = $input->getOption('filename-exclusion');
        $this->ignoreVcs = ($input->getOption('noignore-vcs') ? false : true);
    }

    
    /**
     * Get list of files.
     *
     * @return Symfony\Component\Finder\Finder The finder with the list of files.
     */
    protected function getFinder()
    {
        // Set finder
        $finder = new Finder();
        $finder
            ->in($this->path)
            ->files()->name($this->fileNamePatern)
            ->ignoreVCS($this->ignoreVcs);

        if (!empty($this->fileNamePaternExclusion)) {
            $finder->files()->notName($this->fileNamePaternExclusion);
        }
        if (!empty($this->pathPaternExclusion)) {
            $finder->files()->notPath($this->pathPaternExclusion);
        }

        return $finder;
    }
}
