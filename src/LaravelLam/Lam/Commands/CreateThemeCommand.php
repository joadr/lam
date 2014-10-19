<?php namespace LaravelLam\Lam\Commands;

use Illuminate\Console\Command;
use LaravelLam\Lam\Files\ThemesJsonFileReader;
use LaravelLam\Lam\Processes\CreateThemeProcess;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * User: joadr
 * Date: 16-10-14
 * Time: 12:17
 */
class CreateThemeCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lam:create-theme';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Lam Theme.';

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
        $process = new CreateThemeProcess();
        $process->run($this);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return array(
            array("name", InputArgument::OPTIONAL, "Create new theme command", "new-theme")
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
