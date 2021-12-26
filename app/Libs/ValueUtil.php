<?php

namespace App\Libs;

class ValueUtil
{

    /**
     * Get value list from yml config file
     * @param $keys
     * @param array $options
     * @return array|string|null
     */
    public static function get($keys, $options = array()) {
        return ConfigUtil::getValueList($keys, $options);
    }

    /**
     * Get value from const (in Yml config file)
     * @param $keys
     * @return int|null|string
     */
    public static function constToValue($keys) {
        return ConfigUtil::getValue($keys);
    }

    /**
     * Get text from const (in Yml config file)
     * @param $keys
     * @return int|null|string
     */
    public static function constToText($keys) {
        return ConfigUtil::getValue($keys, TRUE);
    }

    /**
     * Get value from test i
     * @param $searchText
     * @param $keys
     * @return int|null|string
     */
    public static function textToValue($searchText, $keys) {
        $valueList = ValueUtil::get($keys);
        foreach($valueList as $key => $text){
            if($searchText == $text){
                return $key;
            }
        }

        return NULL;
    }

    /**
     * Get const list from yml config file
     * @param $keys
     * @param array $options
     * @return array|string|null
     */
    public static function getConstList($keys, $options = []) {
        $options['get_const'] = true;
        return ConfigUtil::getValueList($keys, $options);
    }

    /**
     * Convert full width kana to half width kana or half width kana to full width kana
     * @param string $str
     * @param boolean $halfWidth
     * @return array
     */
    public static function convertKana($str, $halfWidth = true) {
        $result = "";
        $formatType = $halfWidth ? 'k' : 'K';
        if(!empty($str)){
            $result = mb_convert_kana($str, $formatType, 'UTF-8');
        }

        return $result;
    }

    /**
     * get time from environment variable setting
     * @param $key
     * @return mixed|string|string[]
     */
    public static function getTimeFromEnvironmentVariable($key) {
        return str_replace('-', ':', env($key));
    }

    /**
     * Get key value list
     * @param $keys
     * @param array $options
     * @return array|string|null
     */
    public static function getKeyValueList($keys, $options = []) {
        $options['get_key'] = true;
        return ConfigUtil::getValueList($keys, $options);
    }
}
