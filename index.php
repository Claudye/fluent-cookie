<?php
use Ladis\Cookies\Cookies;
require_once __DIR__.'/vendor/autoload.php';

/**
 * Crée un cookie et chiffre son contenu
 */
$cookies = Cookies::create("test")
            ->content('Mon super fluent cookie')
            ->expires(time() + 3600)
            ->path('/')
            ->secured()
            ->httpOnly()
            ->domain('');
            /*->write();*/
/**
 * 
 */
$cookie = Cookies::get('test');
/**
 * Renvoie la date d'expiration du cookie
 */
$cookie->getExpires();
/**
 * La date d'expiration du cookie
 */
dd($cookie->getDateExpires());