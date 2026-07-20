<?php

/*
 * This class allows you to:
 * - Encrypt filepaths and expiration dates into signatures
 * - Decrypt signatures into filepaths and expiration dates
 *
 *
 * Note: String encryption functions were found here:
 * http://stackoverflow.com/a/1289114/1913729
 */

class VideoSignature
{
    // Time before a URL expires, in seconds
    private $expires = 8;
    // Key used to sign your URLs
    private $encryption_key = 'soMeStr0ngP455W0rD!!';
    // Public URL used to serve the content

    // Encrypts a string
    private function encryptStr($str){
        $iv = mcrypt_create_iv(
            mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC),
            MCRYPT_DEV_URANDOM
        );

        $encrypted = base64_encode(
            $iv .
            mcrypt_encrypt(
                MCRYPT_RIJNDAEL_128,
                hash('sha256', $this->encryption_key, true),
                $str,
                MCRYPT_MODE_CBC,
                $iv
            )
        );

        return $encrypted;
    }

    // Decrypts a String
    private function decryptStr($str){
        $data = base64_decode($str);
        $iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));

        $decrypted = rtrim(
            mcrypt_decrypt(
                MCRYPT_RIJNDAEL_128,
                hash('sha256', $this->encryption_key, true),
                substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC)),
                MCRYPT_MODE_CBC,
                $iv
            ),
            "\0"
        );

        return $decrypted;
    }

    // Returns a temporary URL
    public function getSignedURL($filepath){
        $data = json_encode(
                array(
                    "filepath" => $filepath,
                    "expires" => time() + $this->expires
                )
            );

        $signature = $this->encryptStr($data);

        return  "?s=" . urlencode($signature);
    }

    // Returns a filepath from a signature if it did not expire
    public function getFilepath($signature){
        $data = json_decode( $this->decryptStr($signature), true);

        if($data !== null && $data['expires'] > time() && file_exists($data['filepath'])){
            return $data['filepath'];
        }

        return false;
    }
}