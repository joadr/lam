<?php namespace LaravelLam\Lam;

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
 * User: nicolaslopezj
 * Date: 16-10-14
 * Time: 12:14
 */
class UpdateProcess {
    protected $command;

    /**
     * Executes the process
     * @param $command
     */
    public function run($command) {
        $this->command = $command;

        TerminalOutput::say("\nUpdating themes:", 'bold');
        $check = $this->getThemesToCheck();

        TerminalOutput::say("Getting the list of new themes updates...\n", 'green');
        $list = $this->getListsOfThemesToDownload($check);
        $this->updateThemes($list);

        TerminalOutput::say("Updating bower dependencies:\n", 'bold');
        $bower = $this->getBowerDependencies();
        $installed = $this->getBowerInstalled();
        $this->updateBower($bower, $installed);
    }

    /**
     * Returns the array of the required themes and the version installed
     * @return array
     */
    protected function getThemesToCheck() {
        $json = with(new LamJsonFileReader())->getRequire();
        $lock = with(new LamLockFileReader())->getInstalled();
        $query = [];
        foreach ($json as $name => $version) {
            if (array_key_exists($name, $lock)) {
                $query[$name] = $version . ',' . $lock[$name];
            } else {
                $query[$name] = $version;
            }
        }
        return $query;
    }

    /**
     * Saves the installed theme dependencies
     * @param $installed
     */
    protected function setInstalledThemes($installed) {
        with(new LamLockFileReader())->setInstalled($installed);
    }

    /**
     * Returns the bower dependencies in the lam.json file
     * @return mixed
     */
    protected function getBowerDependencies() {
        $lam = with(new LamJsonFileReader)->getBower();
        $scanned = with(new ThemeScanner)->getBowerDependencies();
        return ArrayHelper::sumNoRepeat($lam, $scanned);
    }

    /**
     * Returns the bower dependencies in the lam.json file
     * @return mixed
     */
    protected function getBowerInstalled() {
        $lam = with(new LamLockFileReader())->getBower();
        return $lam;
    }

    /**
     * Saves the installed bower dependencies
     * @param $installed
     */
    protected function saveBowerInstalled($installed) {
        with(new LamLockFileReader())->setBower($installed);
    }

    /**
     * Updates the bower dependencies
     * @param $list
     * @param $installed
     */
    protected function updateBower($list, $installed) {
        $updater = new BowerUpdater;
        $updater->setDirectory(public_path() . '/lam');
        $updater->setInstalled($installed);
        $updater->setList($list);
        $updater->update();

        $this->saveBowerInstalled($list);
    }

    /**
     * Returns the list of themes that has to be updated
     * It connects to the web services to check
     * @param $list_of_required the list of the required themes
     * @return mixed
     */
    protected function getListsOfThemesToDownload($list_of_required) {
        $request = new ListOfThemesToDownloadRequest;
        return $request->run($list_of_required);
    }

    /**
     * Updates the themes
     * @param $list list of themes to update
     */
    protected function updateThemes($list) {
        $updated = [];
        foreach ($list as $theme) {
            if ($theme['updates']) {
                TerminalOutput::say(" - " . TerminalColor::set($theme['name'], 'bold'));
                $this->updateTheme($theme);
            } else {
                TerminalOutput::say(" - " . TerminalColor::set($theme['name'], 'bold'));
                TerminalOutput::say("   Up to date\n", 'bold+white');
            }
            $updated[$theme['name']] = $theme['tag'];
        }

        $this->setInstalledThemes($updated);
    }

    /**
     * Updates the specific theme.
     * It removes the old version, download the new one and install it
     * @param $data information of the theme
     */
    protected function updateTheme($data) {
        TerminalOutput::say('   Removing old version', 'green');
        $remover = new ThemeRemover;
        $remover->setThemeName($data['name']);
        $remover->remove();

        TerminalOutput::say('   Downloading the new version (' . $data['tag'] . ') ...', 'green');
        $downloader = new FileDownloader;
        $downloader->setUrl($data['download']);
        $downloader->setFilename('temp.zip');
        $zip_path = $downloader->download();

        TerminalOutput::say("   Installing theme\n", 'green');
        $installer = new ThemeInstaller;
        $installer->setZipPath($zip_path);
        $installer->setThemeName($data['name']);
        $installer->install();
    }

} 