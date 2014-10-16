<?php namespace LaravelLam\Lam\Commands;

use Illuminate\Console\Command;
use LaravelLam\Lam\Files\ThemesJsonFileReader;
use LaravelLam\Lam\UpdateProcess;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * User: nicolaslopezj
 * Date: 16-10-14
 * Time: 12:17
 */
class UpdateCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lam:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the themes.';

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
        $process = new UpdateProcess();
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
