<?php namespace LaravelLam\Lam\Commands;

use Illuminate\Console\Command;
use LaravelLam\Lam\Files\ThemesJsonFileReader;
use LaravelLam\Lam\Processes\InstallProcess;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * User: joadr
 * Date: 16-10-14
 * Time: 12:17
 */
class InstallCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lam:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Lam.';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $process = new InstallProcess();
        $process->run($this);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(

        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(

        );
    }

}
