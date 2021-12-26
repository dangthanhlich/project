<?php

use App\Libs\{
    ValueUtil,
    ConfigUtil,
    DateUtil,
};
use Illuminate\Support\Facades\{
    Config,
    Log,
    Storage,
    Route,
};

/**
 * get value from constant
 *
 * @param string $key
 * @return int|string|null
 */
function getConstValue($key) {
    return ValueUtil::constToValue($key);
}

/**
 * get value for select/checkbox/radio option from key
 *
 * @param string $key
 * @return array|string|null
 */
function getList($key) {
    return ValueUtil::get($key);
}

/**
 * get message from key
 *
 * @param $messId
 * @param array $options
 * @return mixed|string|null
 */
function getMessage($messId, $options = []) {
    return ConfigUtil::getMessage($messId, $options);
}

/**
 * get text from value
 *
 * @param $value
 * @param $listKey
 * @return mixed|null
 */
function valueToText($value, $listKey) {
    if(!isset($value) || !isset($listKey)){
        return null;
    }
    $list = ValueUtil::get($listKey);
    if (empty($list)) {
        $list = ValueUtil::get($listKey, ['getList' => true]);
    }
    if(is_array($list) && isset($list[$value])){
        return $list[$value];
    }
    return null;
}

/**
 * get value of config
 * 
 * @param string $key
 */
function getValue($key) {
    $key = 'Constant.Values.'.$key;
    return Config::get($key);
}

/**
 * Get S3 File URL
 * 
 * @param string $filePath
 */
function getS3FileUrl($filePath) {
    try {
        if (empty($filePath)) {
            return null;
        }
        return Storage::disk('s3')->temporaryUrl($filePath, now()->addMinutes(10));
    } catch (\Exception $e) {
        Log::error($e);
        return null;
    }
}

/**
 * get key value list
 * 
 * @param string $key
 */
function getKeyValueList($key) {
    return ValueUtil::getKeyValueList($key);
}

/**
 * format date
 * 
 * @param string $date
 * @param string $format
 */
function formatDate($date, $format) {
    return DateUtil::formatDate($date, $format);
}

function checkFormatDate($date) {
    if (preg_match("/^[0-9]{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
        return true;
    } else {
        return false;
    }
}

/**
 * get date modify
 * 
 * @param string $date
 * @param int $number
 * @param string $character
 * @param string $format
 */
function getDateModify($date, $number, $character, $format = 'Y-m-d') {
    return DateUtil::getDateModify($date, $number, $character, $format);
}

/**
 * check screen no need login
 */
function checkScreenNoNeedLogin() {
    return in_array(Route::currentRouteName(), ValueUtil::get('Common.screenNoNeedLogin', ['get_value' => true]));
}

function diff2Date($date1, $date2) {
    return DateUtil::diff2Date($date1, $date2);
}

/**
 * show area name
 */
function getAreaName($areaCode) {
    $areList = getList('Common.area');
    if (array_key_exists($areaCode, $areList)) {
        return $areList[$areaCode];
    }
    return '';
}

/**
 * Collection $mismatchs
 */
function getMismatchByType($mismatchs, $type)
{
    return $mismatchs->where('mismatch_type', $type)->first();
}