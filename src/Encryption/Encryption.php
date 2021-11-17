<?php
namespace Simplecode\Encryption;

use Simplecode\Encryption\OpensslEncryption;
/**
 * Façade pour l'encryption des données
 */
class Encryption{
    

    /**
     * Encypte les données
     *
     * @param string $text
     * @return  string|null
     */
    public static function encrypt(string $text):?string{
        $openssl = static::openssl();
        $openssl->data($text);
        $openssl->encrypt();
        return $encryptText= $openssl->getEncrypt();
        
        
    }

    /**
     * Décrupte un texte encrypté
     *
     * @param string $encryptText
     * @return string|false
     */
    public static function decrypt(string $encryptText){
        $openssl = static::openssl();
        return $openssl->decrypt($encryptText);
    }

    private static function openssl():OpensslEncryption{
        return new OpensslEncryption;
    }

}