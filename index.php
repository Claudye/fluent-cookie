<?php
use Ladis\Cookies\Cookies;
require_once __DIR__.'/vendor/autoload.php';
$hello= "<h1>Hello word !</h1> <p>Vous venez de télécharger Fluent-cookie votre gestionnaire de cookie, fluide et simple</p>";
/**
 * Cette méthode permet de créer un cookie
 */
$cookies = Cookies::create("test")
            ->content('Mon super fluent cookie')
            ->expires(time() + 600)
            ->path('/')
            ->secured()
            ->httpOnly()
            ->domain('')
            ->write();
/**
 * Cette méthode renvoie un onjet Cookie Metatada 
 * qui permettra d'avoir des informations par rapport à un cookie
 */
$cookie = Cookies::get('test');
/**
 * Renvoie la date d'expiration du cookie
 */
$cookie->getExpires();
/**
 * La date d'expiration du cookie
 */
dd($cookie);