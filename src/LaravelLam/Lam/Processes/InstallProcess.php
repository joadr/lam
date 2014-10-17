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
class InstallProcess {
    protected $command;

    /**
     * Executes the process
     * @param $command
     */
    public function run($command) {

        // we check if already installed first
        if($this->isInstalled()){
            TerminalOutput::say("Lam already installed!", 'red');
            return;
        }
        TerminalOutput::say("Installing Lam...", 'bold');
        TerminalOutput::say("Creating Json File", 'green');
        $this->createJsonFile();

        TerminalOutput::say("Creating Lock File", 'green');
        $this->createLockFile();

        TerminalOutput::say("Installation successful!", 'green');
    }

    /**
     * Executes the creation of the file
     */
    public function createJsonFile() {
        $createJson = with(new LamJsonFileReader())->createJson();
    }

    /**
     * Executes the lock generation
     */
    public function createLockFile() {
        $createLock = with(new LamLockFileReader())->createLock();
    }

    /**
     * Checks if lam is already installed.
     */
    public function isInstalled() {
        if (file_exists(with(new LamJsonFileReader())->getFilePath())) {
            return true;
        } else {
            return false;
        }
    }

}