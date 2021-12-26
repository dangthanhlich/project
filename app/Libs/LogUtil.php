<?php

namespace App\Libs;

use App\Logging\CustomizeStreamHandler;
use App\Notifications\BatchLogToSlack;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Monolog\Formatter\LineFormatter;
use App\Libs\ValueUtil;

class LogUtil {

    /**
     * Log file.
     * @param $messages
     * @param bool $hasError
     * @return string
     * @throws \Exception
     */
    public static function log($messages, $hasError = true) {
        $path = storage_path('logs/' . date('Y-m-d') . '_' . ($hasError ? 'batch_error' : 'batch_success') . '.log');
        $handler = new CustomizeStreamHandler($path);
        $formatter = new LineFormatter("%message%\n");
        $handler->setFormatter($formatter);
        Log::setHandlers([$handler]);
        // Writer message to log file
        if ($hasError) {
            if (is_array($messages)) {
                foreach ($messages as $message) {
                    Log::error($message);
                }
            }
            if (is_string($messages)) {
                Log::error($messages);
            }
        } else {
            if (is_array($messages)) {
                foreach ($messages as $message) {
                    Log::error($message);
                }
            }
            if (is_string($messages)) {
                Log::error($messages);
            }
        }
        return $path;
    }

    /**
     * send log to slack channel
     * @param array $errors
     */
    public static function sendLogToSlack(Array $errors) {
        $batchNm = !empty($errors['batch_name']) ? $errors['batch_name'] . ' error' : '';
        $fileName = !empty($errors['file_name']) ? $errors['file_name'] : '';
        $message = 'File: ' . $fileName . "\n";
        $message .= is_string($errors['messages']) ? $errors['messages'] : implode("\n", $errors['messages']);
        // send log to slack
        Notification::route('slack', env('SLACK_WEBHOOK_URL'))
                    ->notify(new BatchLogToSlack($batchNm, $message));
    }

}
