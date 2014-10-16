<?php namespace LaravelLam\Lam\Files; 
/**
 * User: nicolaslopezj
 * Date: 16-10-14
 * Time: 12:21
 */
class FolderRemover {

    protected $path;

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Removes the folder recursively
     */
    public function remove() {
        $this->removeFolder($this->path);
    }

    /**
     * Removes the folder and all its contents
     * @param $dir
     * @return bool
     */
    protected function removeFolder($dir) {
        if (!file_exists($dir)) {
            return;
        }
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            if (is_dir("$dir/$file")) {
                $this->removeFolder("$dir/$file");
            } else {
                unlink("$dir/$file");
            }
        }
        return rmdir($dir);
    }
}