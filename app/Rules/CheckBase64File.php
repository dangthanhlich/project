<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckBase64File implements Rule
{
    private $maxSize;
    private $checkJpeg;

    /**
     * Create a new rule instance.
     *
     * @param int $maxSize
     * @param bool $checkJpeg
     * @return void
     */
    public function __construct($maxSize, $checkJpeg = false) {
        $this->maxSize = $maxSize;
        $this->checkJpeg = $checkJpeg;
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
        // check image type
        if (
            $this->checkJpeg &&
            exif_imagetype($tmpFilePath) !== IMAGETYPE_JPEG
        ) {
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
        return 'File not valid.';
    }
}
