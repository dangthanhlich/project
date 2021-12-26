<?php

namespace App\Rules;

use App\Libs\ConfigUtil;
use Illuminate\Contracts\Validation\Rule;

class CheckBase64MaxSize implements Rule
{
    private $maxSize;

    /**
     * Create a new rule instance.
     *
     * @param int $maxSize
     * @return void
     */
    public function __construct($maxSize) {
        $this->maxSize = $maxSize;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value) {
        $base64File = preg_replace('#^data:image/\w+;base64,#i', '', $value);
        // convert base64 to binary file
        $tmpFilePath = tempnam(storage_path('app/tmp'), 'ars_');
        $handle = fopen($tmpFilePath, 'wb');
        fwrite($handle, base64_decode($base64File));
        fclose($handle);
        $isValid = true;
        // check file size
        if (filesize($tmpFilePath) > $this->maxSize * 1024 * 1024) {
            $isValid = false;
        }
        // remove temp file
        unlink($tmpFilePath);
        return $isValid;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return ConfigUtil::getMessage('c-016', [$this->maxSize. 'MB']);
    }
}
