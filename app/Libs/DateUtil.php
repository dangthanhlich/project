<?php

namespace App\Libs;

use Carbon\Carbon;

class DateUtil
{
    /**
     * Get timestamp
     * @return string
     */
    public static function getTimestamp() {
        $microtime = floatval(substr((string)microtime(), 1, 8));
        $rounded = round($microtime, 3);
        $milisecond = substr((string)$rounded, 2, strlen($rounded));
        return date('YmdHis') . $milisecond;
    }

    /**
     * Get full date time
     * @return string
     */
    public static function parseStringFullDateTime() {
        return Carbon::now()->format('YmdHis');
    }

    /**
     * Get date modify
     * @param string $date
     * @param int $number
     * @param string $character
     * @param string $format
     * @return string
     */
    public static function getDateModify($date, $number, $character, $format = 'Y-m-d') {
        $result = null;
        if (!empty($date)) {
            if (is_string($date)) {
                $carbon = new Carbon($date);
            } else {
                $carbon = null;
            }
        } else {
            // get current date
            $carbon = Carbon::now();
        }
        if (!empty($carbon)) {
            $result = $carbon->add($number, $character)->format($format);
        }
        return $result;
    }

    /**
     * Format date
     * @param string $date
     * @param string $format
     * @return string;
     */
    public static function formatDate($date, $format = 'Y-m-d') {
        $result = null;
        if (is_string($date)) {
            $carbon = new Carbon($date);
            $result = $carbon->format($format);
        }
        return $result;
    }

    /**
     * Diff between 2 date
     * @param string $date1
     * @param string $date2
     * @return mixed
     */
    public static function diff2Date($date1, $date2) {
        if (empty($date1) && empty($date2)) {
            return null;
        }
        if (!is_string($date1) && !is_string($date2)) {
            return null;
        }
        $date1 = new Carbon($date1);
        $date2 = new Carbon($date2);
        $result = $date1->diff($date2);
        return $result;
    }
}
