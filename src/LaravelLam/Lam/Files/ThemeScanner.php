<?php namespace LaravelLam\Lam\Files;

use LaravelLam\Lam\Helpers\ArrayHelper;

/**
 * User: nicolaslopezj
 * Date: 16-10-14
 * Time: 12:25
 */
class ThemeScanner {

    public function getBowerDependencies() {
        $lams = $this->getAllLamJson();
        $bowers = [];
        foreach($lams as $lam) {
            $bowers = ArrayHelper::sumNoRepeat($bowers, $lam->bower);
        }
        return $bowers;
    }

    protected function getAllLamJson() {
        $lams = $this->getLamsInSubdir(base_path() . '/lam/views');
        return $lams;
    }

    protected function getLamsInSubdir($dir) {
        $lams = [];

        if (!file_exists($dir)) {
            return $lams;
        }

        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            if (is_dir("$dir/$file")) {
                $new_lams = $this->getLamsInSubdir("$dir/$file");
                foreach ($new_lams as $new_lam) {
                    $lams[] = $new_lam;
                }
            } elseif ($file == 'lam.json') {
                $lams[] = json_decode(file_get_contents("$dir/$file"));
            }
        }

        return $lams;
    }

}