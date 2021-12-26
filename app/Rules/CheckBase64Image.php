<?php

namespace App\Rules;

use App\Libs\ConfigUtil;
use Illuminate\Contracts\Validation\Rule;

class CheckBase64Image implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct() {}

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
        // check image type
        try {
            if (exif_imagetype($tmpFilePath) !== IMAGETYPE_JPEG) {
                $isValid = false;
            }
        } catch (\Exception $e) {
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
        return ConfigUtil::getMessage('c-015', ['jpeg/jpg']);
    }
}
