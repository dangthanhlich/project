<?php

namespace App\Libs;

use App\Libs\{
    ValueUtil,
};
use Carbon\Carbon;
use Illuminate\Support\Facades\{
    Storage,
    Log
};
use ZipArchive;

class FileUtil {

    /**
     * get file content from s3
     * @param $path
     */
    public static function getFileFromS3($path) {
        // files in s3
        if (Storage::disk('s3-import')->exists($path)) {
            $s3Files = Storage::files($path);
            if (empty($s3Files)) {
                return false;
            }
            // create temporary file
            $tempFiles = [];
            $x = 0;
            foreach ($s3Files as $file) {
                // get country
                $language = explode('_', str_replace($path, '', $file))[1];
                $localFileName = $language . '_' . Carbon::now()->format('Ymd_His') . rand(5, 15) . '.csv';
                Storage::disk('local')->put(
                    $localFileName,
                    Storage::disk('s3-import')->get($file)
                );
                $tempFiles[$x] = [
                    'lang' => $language,
                    'name' => $localFileName,
                    'originalName' => basename($file),
                    'path' => Storage::disk('local')->path($localFileName)
                ];
                $x++;
            }
            return $tempFiles;
        }
        return false;
    }

    /**
     * delete temporary csv file in localhost
     * @param $files
     */
    public static function deleteTemporaryFile($files) {
        if (is_array($files)) {
            foreach ($files as $file) {
                if (Storage::disk('local')->exists($file['name'])) {
                    Storage::disk('local')->delete($file['name']);
                }
            }
        }
    }

    /**
     * Move file to folder when complete/error
     * @param string $path
     * @param bool $errorFlg
     */
    public static function moveFiles($path, bool $errorFlg = true) {
        if (!empty($path) && Storage::disk('s3-import')->exists($path)) {
            $s3Files = Storage::files($path);
            foreach ($s3Files as $file) {
                $filePath = Storage::disk('s3-import')->path($file);
                if ($errorFlg) {
                    Storage::move($filePath, $path . 'error/' . basename($filePath));
                } else {
                    Storage::move($filePath, $path . 'complete/' . basename($filePath));
                }
            }
        }
    }

    /**
     * Export csv
     * @param array $header
     * @param array $lstData
     * @param string $fileName
     * @param array $option
     * @return mixed
     */
    public static function exportCsv($header, $lstData, $fileName, $options = []) {
        array_unshift($lstData, $header);
        $rowContent = 0;
        $contents = null;
        foreach ($lstData as $index => $data) {
            foreach ($data as &$row) {
                $row = '"' . preg_replace('/"/','""', $row) . '"';
            }
            if ($rowContent > 0) {
                $contents .= "\n";
            }
            if (isset($options['encoding_from'])) {
                $encodingTo = isset($options['encoding_to']) ? $options['encoding_to'] : 'UTF-8';
                $contents .= mb_convert_encoding(implode(',', $data), $options['encoding_from'], $encodingTo);
            } else {
                $contents .= implode(',', $data);
            }
            $rowContent++;
        }
        $path = storage_path('app/public');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $fullPatchName = $path . $fileName;
        file_put_contents($fullPatchName, $contents);
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=$fileName");
        readfile($fullPatchName);
        unlink($fullPatchName);
        exit;
    }

    /**
     * Download file from s3
     * @param string|array $filePath
     * @param string $zipName
     * @param boolean
     */
    public static function downloadFileOrZipFromS3($filePath, $zipName = null) {
        $s3Storage = Storage::disk('s3');
        if (is_array($filePath)) {
            $arrFile = [];
            foreach ($filePath as $file) {
                if ($s3Storage->exists($file)) {
                    $arrFile[] = $file;
                }
            }
            if (empty($arrFile)) {
                return false;
            } else {
                self::downloadZipFileS3($arrFile, $zipName);
            }
        } else {
            self::downloadFileS3($filePath);
        }
    }

    /**
     * Upload base64 to S3
     *
     * @param string $base64File
     * @param string $fileName
     * @param string $folder Folder to save on S3
     * @param array $option
     * @return bool
     */
    public static function uploadBase64ToS3($base64File, $fileName, $folder = '', $option = []) {
        try {
            $base64File = preg_replace('#^data:image/\w+;base64,#i', '', $base64File);
            // detect folder to save on S3
            if (isset($option['detect_folder'])) {
                $fileTypeToS3Folder = ValueUtil::get('File.fileTypeToS3Folder');
                $fileType = explode('_', $fileName);
                $fileType = $fileType[2];
                // check valid file_type
                if (
                    isset($option['valid_file_type']) &&
                    !in_array($fileType, $option['valid_file_type'])
                ) {
                    return false;
                }
                $folder = $fileTypeToS3Folder[$fileType] ?? $fileTypeToS3Folder['null'];
            }
            // upload to S3
            return Storage::disk('s3')->put($folder. '/'. $fileName, base64_decode($base64File));
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }

    /**
     * Download file s3
     * @param string $filePath
     * @return boolean
     */
    public static function downloadFileS3($filePath) {
        try {
            $s3Storage = Storage::disk('s3');
            $path = storage_path('app/public');
            $fileExplode = explode('/', $filePath);
            $fileName = $fileExplode[1];
            $extension = explode('.', $fileName)[1];
            $fullPatchName = $path . '/' . $fileName;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            if ($s3Storage->exists($filePath)) {
                $fileContent = $s3Storage->get($filePath);
                if (empty($fileContent)) {
                    return false;
                }
                file_put_contents($fullPatchName, $fileContent);
                header("Content-Type: image/${extension}");
                header("Content-Disposition: attachment; filename=$fileName");
                readfile($fullPatchName);
                unlink($fullPatchName);
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            Log::error($e);
            abort(400);
        }
    }

    /**
     * Download zip file s3
     * @param array $filePath
     * @param string $fileName
     * @return mixed
     */
    public static function downloadZipFileS3($filePath, $fileName) {
        try {
            $s3Storage = Storage::disk('s3');
            $zip = new ZipArchive;
            $fileName = !empty($fileName) ? $fileName : 'zip_file.zip';
            $tempPath = ValueUtil::get('Common.public_temp_path');
            $path = storage_path('app/public');
            $fullPatchName = $path . $fileName;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            if (count($filePath) == 0) {
                return false;
            }
            if ($zip->open($fullPatchName, ZipArchive::CREATE) === true) {
                $arrFileUnlink = [];
                foreach ($filePath as $file) {
                    $fileContent = $s3Storage->get($file);
                    if (!empty($fileContent)) {
                        $fileExplode = explode('/', $file);
                        $fullFilePath = $path . '/' . $fileExplode[1];
                        file_put_contents($fullFilePath, $fileContent);
                        $zip->addFile($fullFilePath, basename($fileExplode[1]));
                        $arrFileUnlink[] = $fullFilePath;
                    }
                }
                $zip->close();
                // unlink all file after archive zip
                foreach ($arrFileUnlink as $file) {
                    unlink($file);
                }
            } else {
                abort(400);
            }
            header('Content-Type: application/zip');
            header("Content-Disposition: attachment; filename=$fileName");
            readfile($fullPatchName);
            unlink($fullPatchName);
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            abort(400);
        }
    }

    /**
     * Copy S3 file to other path
     *
     * @param string $fromFile
     * @param string $toFile
     * @return bool
     */
    public static function copyS3File($fromFile, $toFile) {
        try {
            return Storage::disk('s3')->copy($fromFile, $toFile);
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }

}
