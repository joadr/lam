<?php namespace LaravelLam\Lam\Helpers; 
/**
 * User: nicolaslopezj
 * Date: 16-10-14
 * Time: 12:27
 */
class ArrayHelper {

    /**
     * Merge 2 array and remove the repeated items
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public static function sumNoRepeat(array $array1, array $array2) {
        return array_unique(array_merge($array1, $array2));
    }

    /**
     * Returns the items that are in the first array but not in the second
     * @param array $here
     * @param array $there
     * @return array
     */
    public static function yesHereNotThere(array $here, array $there) {
        $new = [];
        foreach ($here as $item) {
            if (!in_array($item, $there)) {
                $new[] = $item;
            }
        }
        return $new;
    }

    /**
     * Returns the items that are in both arrays
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public static function intersect(array $array1, array $array2) {
        return array_intersect($array1, $array2);
    }

}