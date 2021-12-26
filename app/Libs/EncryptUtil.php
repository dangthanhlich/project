<?php 
namespace App\Libs;

class EncryptUtil
{

    /**
     * Decrypt Blowfish-CBC-PKCS5Padding with key 256 bits
     *
     * @param string $key
     * @return string: plain text
     */
    private static function makeCompatibleKey($key) {
        //If key is null, return null
        if("$key" === ''){
            return $key;
        }
        //If key length < 72, add characters to key
        $mxLen = 72;  // cycled key itself over until 72 bytes
        while(strlen($key) < $mxLen) {
            $key .= $key;
        }
        //If key length > 72, split key with max length 72
        $key = substr($key, 0, $mxLen);
        return $key;
    }

    /**
     * Encrypt string use AES 256
     *
     * @param string $str
     * @return string
     */
    public static function encryptAes256($str) {
        //Get constant value
        $key_len = ValueUtil::get('Common.aes_256_key');
        $method = ValueUtil::get('Common.method_aes_256');
        $hashMethod = ValueUtil::get('Common.method_hash');
        //Encrypt string
        $key = hash($hashMethod, $key_len, true);
        $iv = base64_decode(ValueUtil::get('Common.aes_256_iv'));
        $ciphertext = openssl_encrypt($str, $method, $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($ciphertext);
    }

    /**
     * Decrypt string use AES 256
     *
     * @param string $str
     * @return string
     */
    public static function decryptAes256($str) {
        //Get constant value
        $decrypt_str = base64_decode($str);
        $key_len = ValueUtil::get('Common.aes_256_key');
        $method = ValueUtil::get('Common.method_aes_256');
        $hashMethod = ValueUtil::get('Common.method_hash');
        $iv = base64_decode(ValueUtil::get('Common.aes_256_iv'));
        //Parse key to decrypt
        $key = hash($hashMethod, $key_len, true);
        return openssl_decrypt($decrypt_str, $method, $key, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * encrypt string use SHA 256
     */
    public static function encryptSha256($str) {
        return hash('sha256', $str);
    }

}