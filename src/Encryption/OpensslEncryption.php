<?php

namespace Ladis\Encryption;
/**
 * Encrypte les données
 */
class OpensslEncryption
{

    /**
     * encrypt
     * 
     * @var string
     */
    protected $encrypt;
    /**
     * Le texte à chiffrer
     * @var string
     */
    protected $data;

    /**
     * La méthode de cipher
     * @var string
     */
    protected $algo = "AES-128-CBC";

    /**
     * Le token
     * @var Token
     */
    protected $token;

    /**
     * Disjonction au niveau des bits des drapeaux OPENSSL_RAW_DATA et OPENSSL_ZERO_PADDING.
     * @var int
     */
    protected $options = OPENSSL_RAW_DATA;

    /**
     *  Vecteur d'initialisation non-nul.
     * @var string
     */
    protected $inivect;

    /**
     * Le tag d'authentification passé par référence
     * @var string
     */
    protected $tag = null;

    /**
     * Données additionnelles d'authentification.
     * @var string
     */
    protected $aad;


    /**
     * La longueur du tag d'authentification
     * @var string
     */
    protected $tag_length = 16;

    /**
     *  Liste des méthodes de chiffrements disponibles.
     * @var array
     */
    protected $cipher_algo = [];

    public function __construct()
    {
        $this->cipher_algo = openssl_get_cipher_methods();

        $this->token = new Token(__DIR__ . '/../Cookies/token.json');

        $this->inivect($this->algo);
        $this->generateToken();
    }


    /**
     * Accepte le texte à chiffrer
     *
     * @param  string  $data  Le texte à chiffrer
     *
     * @return  $this
     */
    public function data(string $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Encrypte les données
     *
     * @return string
     */
    public function encrypt()
    {
            $ciphertext_raw = openssl_encrypt($this->data, $this->algo, (string)$this->token, $this->options, $this->inivect);
            $hmac = hash_hmac('sha256', $ciphertext_raw, (string)$this->token, $as_binary = true);
            $ciphertext = base64_encode($this->inivect . $hmac . $ciphertext_raw);
            $this->encrypt = $ciphertext;
            return $this->encrypt;
    }

    /**
     * Décrypte les données
     *
     * @param string $ciphertext
     * @return string!false
     */
    public function decrypt(string $ciphertext = null)
    {
       
        $c = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length($this->algo);
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($c, $ivlen + $sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $this->algo, $this->token, $this->options, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $this->token, $as_binary = true);
        if (hash_equals($hmac, $calcmac)) //PHP 5.6+ timing attack safe comparison
        {
            return $original_plaintext;
        }
        return false;
        //return openssl_decrypt($ciphertext ?? $this->encrypt, $this->algo, (string)$this->token, $this->options, $iv, $this->tag);
    }

    /**
     * Génère le token et l'enregistre
     *
     * @return void
     */
    public function generateToken()
    {
        if (false == $this->token->hasToken()) {
            $this->token->generate();
            $this->token->storeToken();
        }
        return;
    }


    /**
     * Set vecteur d'initialisation non-nul.
     *
     * @param  string  $inivect  Vecteur d'initialisation non-nul.
     *
     * @return  self
     */
    public function inivect(string $inivect)
    {

        $ivlen = openssl_cipher_iv_length($inivect ?? $this->algo);
        $this->inivect = openssl_random_pseudo_bytes($ivlen);

        return $this;
    }

    /**
     * Get the value of encrypt
     * @var string
     */
    public function getEncrypt()
    {
        return $this->encrypt;
    }

    /**
     * Set la méthode de cipher
     *
     * @param  string  $algo  La méthode de cipher
     *
     * @return  self
     */
    public function algo(string $algo)
    {
        $this->algo = $algo;

        return $this;
    }

}
