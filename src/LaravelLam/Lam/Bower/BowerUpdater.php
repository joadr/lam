<?php namespace LaravelLam\Lam\Bower;

use LaravelLam\Lam\Helpers\ArrayHelper;
use LaravelLam\Lam\Output\TerminalColor;
use LaravelLam\Lam\Output\TerminalOutput;

/**
 * User: nicolaslopezj
 * Date: 16-10-14
 * Time: 12:16
 */
class BowerUpdater {

    protected $list;
    protected $installed;
    protected $directory;


    /**
     * @param mixed $list The list of bower dependencies
     */
    public function setList($list) {
        $this->list = $list;
    }

    /**
     * @param mixed $installed The list of bower installed bower dependencies
     */
    public function setInstalled($installed) {
        $this->installed = $installed;
    }

    /**
     * @param mixed $directory The final directory of the bower files
     */
    public function setDirectory($directory)
    {
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        $this->directory = $directory;
    }

    /**
     * Executes the process, installs each theme
     */
    public function update() {
        $to_remove = $this->getToRemove();
        $to_install = $this->getToInstall();
        $to_update = $this->getToUpdate();

        $command = 'cd ' . $this->directory . ';';
        foreach ($to_remove as $dependency) {
            TerminalOutput::say(' - ' . TerminalColor::set($dependency, 'bold') . TerminalColor::set(' [removing]', 'bold+red'));
            $this->execute($command . $this->getRemoveCommandFor($dependency));
        }

        foreach ($to_install as $dependency) {
            TerminalOutput::say(' - ' . TerminalColor::set($dependency, 'bold') . TerminalColor::set(' [installing]', 'bold+green'));
            $this->execute($command . $this->getInstallCommandFor($dependency));
        }

        foreach ($to_update as $dependency) {
            TerminalOutput::say(' - ' . TerminalColor::set($dependency, 'bold') . TerminalColor::set(' [updating]', 'bold+green'));
            $this->execute($command . $this->getUpdateCommandFor($dependency));
        }
    }

    /**
     * Returns the themes that have to be installed
     * @return array
     */
    protected function getToInstall() {
        return ArrayHelper::yesHereNotThere($this->list, $this->installed);
    }

    /**
     * Returns the themes that have to be removed
     * @return array
     */
    protected function getToRemove() {
        return ArrayHelper::yesHereNotThere($this->installed, $this->list);
    }

    /**
     * Returns the themes that have to be updated
     * @return array
     */
    protected function getToUpdate() {
        return ArrayHelper::intersect($this->list, $this->installed);
    }

    /**
     * Returns the command to remove the theme
     * @param $dependency
     * @return string
     */
    protected function getRemoveCommandFor($dependency) {
        return 'bower uninstall ' . $dependency . '';
    }

    /**
     * Returns the command to install the theme
     * @param $dependency
     * @return string
     */
    protected function getInstallCommandFor($dependency) {
        return 'bower install ' . $dependency . '';
    }

    /**
     * Returns the command to update the theme
     * @param $dependency
     * @return string
     */
    protected function getUpdateCommandFor($dependency) {
        return 'bower update ' . $dependency . '';
    }

    /**
     * Executes the command in the cmd
     * @param $command
     * @return string
     */
    protected function execute($command) {
        exec($command, $response);
        foreach ($response as $message) {
            TerminalOutput::say($this->getOutputForMessage($message));
        }
        TerminalOutput::say('');
        return $response;
    }

    /**
     * Returns the parsed message to output in the console
     * @param $message
     * @return string
     */
    protected function getOutputForMessage($message) {
        $re = "/bower .+?  /";
        preg_match($re, $message, $matches);

        if (count($matches) == 1) {
            $replace = $matches[0];
            $message = str_replace($replace, '', $message);
        }

        $message = trim($message);

        $message = '   ' . $message;

        if (starts_with($message, 'validate')) {
            return TerminalColor::set($message, 'white+bold');
        }

        if (starts_with($message, 'cached')) {
            return TerminalColor::set($message, 'white+bold');
        }

        return TerminalColor::set($message, 'green');
    }

}