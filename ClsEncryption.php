<?php
//This class was made to simplify encrypting and decrypting of data in PHP.
//Be advised, you should use hashing instead of encryption in most use cases!
//If you really must use encryption, this is (for now) a safe way to do it.
//Keys and iv's are randomly generated, which you should store separately!
//
//Usage Encrypt:
//include('ClsEncryption.php');
//
//Either (Preferred)
//$TextToEncrypt = "Hello World!";
//$Encrypted = ClsEncryption::Encrypt($TextToEncrypt);
//$CipherText = $Encrypted[0]; //Save somewhere safe
//$Key = $Encrypted[1]; //Save somewhere even safer
//
//Or
//$TextToEncrypt = "Hello World!";
//$Key = "Private-Key"; //Save somewhere even safer
//$Encrypted = ClsEncryption::Encrypt($TextToEncrypt, $Key);
//$CipherText = $Encrypted[0]; //Save somewhere safe
//
//Usage Decrypt:
//include('ClsEncryption.php');
//
//$CipherText = "HJUNifnsJFNJFNSJknFJ"; //Some string from database probably
//$Key = "Private-Key"; //Probably some string from database as well
//$ClearText = ClsEncryption::Decrypt($CipherText, $Key);
//
//~ Jeremy V.

/**
 * Class: ClsEncryption
 * @author Jeremy Vorrink
 * Description: Encrypt en Decrypt opgegeven data
 */
class ClsEncryption {
    public static function Encrypt($string, $key = ""){
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        if($key == ""){
            $key = openssl_random_pseudo_bytes(32);
        }
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($string, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
        $ReturnArray = array();
        array_push($ReturnArray, base64_encode( $iv.$hmac.$ciphertext_raw ));
        array_push($ReturnArray, $key);
        return $ReturnArray;
    }
    
    public static function Decrypt($ciphertext, $key = ""){
        $c = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
        if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
        {
            return $original_plaintext;
        }
    }
}
?>