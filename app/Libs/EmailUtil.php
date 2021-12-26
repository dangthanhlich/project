<?php

namespace App\Libs;

use App\Mail\B_702;
use App\Mail\BatchNotification;
use Illuminate\Support\Facades\Mail;

class EmailUtil {

    /**
     * send mail when run batch has error
     * @param $messages
     */
    public static function sendMail($data) {
        return Mail::to(ValueUtil::get('Common.batch_mail_to'))
            ->send(new BatchNotification($data));
    }

    /**
     * send mail when run batch has error
     * @param $messages
     */
    public static function sendMail_B_702($email, $data) {
        return Mail::to($email)
            ->send(new B_702($data));
    }

}
