<?php

namespace App\Libs;

use Illuminate\Support\Facades\Config;

class ConfigUtil {

    /**
     * Get message from message_file, params is optional
     * @param $key
     * @param array $paramArray
     * @return mixed|null
     */
    public static function getMessage($key, $paramArray = array()) {
        $messages = Config::get('Constant.Messages.messages');
        // if key is not exists in array

        if (!array_key_exists($key, $messages)) {
            return '';
        }

        $message = $messages[$key];

        if ($message && is_string($message)) {
            foreach ($paramArray as $param => $value) {
                $message = str_replace(sprintf('<%d>', $param), $value, $message);
            }
        }

        return $message;
    }

    /**
     * Get $key value from value_list_file
     * @param $keys
     * @param array $options
     * @return array|null
     */
    public static function getValueList($keys, $options = array()) {
        $keys = explode('.', $keys);
        if (!is_array($keys) || count($keys) != 2) {
            return NULL;
        }
        list($fileName, $key) = $keys;
        $keyName = 'Constant.Values.' . $fileName;
        $valueList = Config::get($keyName);
        if (!array_key_exists($key, $valueList)) {
            return '';
        }
        $valueList = $valueList[$key];
        if ($valueList && is_array($valueList)) {
            $resultList = array();
            foreach ($valueList as $keyName => $value) {
                if (is_string($value)) {
                    $value = explode('|', $value);
                    if(!isset($value[0])){
                        $resultList[$keyName] = '';
                    } else {
                        $resultList[$keyName] = $value[0];
                    }
                    // get const list
                    if (isset($options['get_const']) && $options['get_const']) {
                        if (!isset($value[1])) {
                            $resultList[$keyName] = '';
                        } else {
                            $resultList[$keyName] = $value[1];
                        }
                    }
                    // get key value
                    if (isset($options['get_key']) && $options['get_key']) {
                        if (isset($value[1])) {
                            $resultList[$value[1]] = $keyName;
                        }
                    }
                } else if (isset($options['get_value']) && $options['get_value'] && is_array($value)) {
                    $resultList[$keyName] = $value;
                }
            }
            return $resultList;
        }
        return $valueList;
    }

    /**
     * @param $keys
     * @param bool $getText
     * @return int|null|string
     */
    public static function getValue($keys, $getText = FALSE) {
        $keys = explode('.', $keys);
        if (!is_array($keys) || count($keys) != 3) {
            return NULL;
        }
        list($fileName, $key, $const) = $keys;
        $keyName = 'Constant.Values.' . $fileName;
        $valueList = Config::get($keyName);
        $valueList = $valueList[$key];

        if ($valueList && is_array($valueList)) {
            foreach ($valueList as $key => $value) {
                $value = explode('|', $value);
                if (isset($value[1]) && $value[1] == $const) {
                    if($getText){
                        return $value[0];
                    }
                    return $key;
                }
            }
        }
        return NULL;
    }

    /**
     * check empty but not zero
     * @param $data
     * @return bool
     */
    public static function checkEmpty($data) {
        if (isset($data) && $data !== '' && $data !== null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get value list csv
     * @param string $keys
     * @return array|null
     */
    public static function getCsv($keys) {
        $keys = explode('.', $keys);
        if (!is_array($keys) || count($keys) != 3) {
            return null;
        }
        list($folderName, $fileName, $key) = $keys;
        $keyName = 'Constant.Csv.' . $folderName  . '.' . $fileName;
        $valueList = Config::get($keyName);
        if (!array_key_exists($key, $valueList)) {
            return null;
        }
        $valueList = $valueList[$key];
        return $valueList;
    }
}
