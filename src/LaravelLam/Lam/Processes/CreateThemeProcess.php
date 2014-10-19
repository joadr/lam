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
        $themename = $command->argument('name');
        if($this->isCreated($themename)){
            return TerminalOutput::say("Error: A project with the name {$themename} already exists", 'red');
        }

        // Create folders.
        $create = $this->createFolders($themename);

        // Create json file
        $json = $this->createJson($create, $themename);

        TerminalOutput::say("Project {$themename} created successfully", 'green');

    }

    /**
     * Checks if lam's new project already exists.
     */
    public function isCreated($themename) {
        $dir = app_path().'/views/lam/'.$themename;
        if (!file_exists($dir) && !is_dir($dir)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Creates the new project's folders.
     * @return $theme_path_part -> the full path to the project's folder.
     */
    public function createFolders($themename){
        $lam_path = app_path().'/views/lam/';
        $themePath = explode('/', $themename);
        $theme_path_part = $lam_path;
        foreach($themePath as $folder){
            var_dump($theme_path_part.'/'.$folder);
            if (!file_exists($theme_path_part.'/'.$folder) && !is_dir($theme_path_part.'/'.$folder)) {
                $theme_path_part = $theme_path_part.'/'.$folder;
                mkdir($theme_path_part, 0777, true);
            } else {
                $theme_path_part = $theme_path_part.'/'.$folder;
            }
        }
        mkdir($theme_path_part.'/public', 0777, true);
        return $theme_path_part;
    }

    /**
     * Create's the json file of the project
     */
    public function createJson($path, $themename){
        $json = fopen($path.'/lam.json', 'w');
        $name = \Config::get('workbench.name');
        $email = \Config::get('workbench.email');
        $arr = array(
            "name" => $themename,
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