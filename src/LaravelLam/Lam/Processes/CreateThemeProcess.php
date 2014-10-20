<?php namespace LaravelLam\Lam\Processes;

use LaravelLam\Lam\Bower\BowerUpdater;
use LaravelLam\Lam\Communication\Api\ListOfThemesToDownloadRequest;
use LaravelLam\Lam\Files\FileDownloader;
use LaravelLam\Lam\Files\ThemeInstaller;
use LaravelLam\Lam\Files\ThemeRemover;
use LaravelLam\Lam\Files\ThemeScanner;
use LaravelLam\Lam\Files\LamJsonFileReader;
use LaravelLam\Lam\Files\LamLockFileReader;
use LaravelLam\Lam\Helpers\ArrayHelper;
use LaravelLam\Lam\Output\TerminalColor;
use LaravelLam\Lam\Output\TerminalOutput;

/**
 * User: joadr
 * Date: 17-10-14
 * Time: 12:24
 */
class CreateThemeProcess {
    protected $command;

    /**
     * Executes the process
     * @param $command
     */
    public function run($command) {
        TerminalOutput::say("Creating new project", 'green');
        $theme_name = $command->argument('name');
        if($this->isCreated($theme_name)){
            return TerminalOutput::say("Error: A project with the name {$theme_name} already exists", 'red');
        }

        // Create folders.
        $create = $this->createFolders($theme_name);

        // Create json file
        $json = $this->createJson($create, $theme_name);

        TerminalOutput::say("Project {$theme_name} created successfully", 'green');

    }

    /**
     * Checks if lam's new project already exists.
     * @param $theme_name
     * @return bool
     */
    public function isCreated($theme_name) {
        $dir = base_path() . '/lam/views/' . $theme_name;
        if (!file_exists($dir) && !is_dir($dir)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Creates the new project's folders.
     * @param $theme_name
     * @return string $theme_path_part -> the full path to the project's folder.
     */
    public function createFolders($theme_name){
        $lam_path = base_path() . '/lam/views/';
        $themePath = explode('/', $theme_name);
        $theme_path_part = $lam_path;
        foreach ($themePath as $folder) {
            var_dump($theme_path_part . '/' . $folder);
            if (!file_exists($theme_path_part . '/' . $folder) && !is_dir($theme_path_part.'/'.$folder)) {
                $theme_path_part = $theme_path_part . '/' . $folder;
                mkdir($theme_path_part, 0777, true);
            } else {
                $theme_path_part = $theme_path_part . '/' . $folder;
            }
        }
        mkdir($theme_path_part.'/public', 0777, true);
        return $theme_path_part;
    }

    /**
     * Create's the json file of the project
     * @param $path
     * @param $theme_name
     */
    public function createJson($path, $theme_name){
        $json = fopen($path.'/lam.json', 'w');
        $name = \Config::get('workbench.name');
        $email = \Config::get('workbench.email');
        $arr = array(
            "name" => $theme_name,
            "description" => "",
            "keywords" => array(),
            "license" => "",
            "authors" => array(
                "name" => $name,
                "email" => $email
            ),
            "bower" => array()
        );
        fwrite($json, utf8_decode(json_encode($arr, 64 | 128 | 256)));
        fclose($json);
    }

}