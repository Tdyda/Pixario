<?php

// src/Security/JwtLoginSuccessHandler.php
namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Cookie;

class JwtLoginSuccessHandler
{
    #[AsEventListener(event: 'lexik_jwt_authentication.on_authentication_success')]
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $token = $event->getData()['token'];
        $response = $event->getResponse();

        $cookie = Cookie::create('access_token')
            ->withValue($token)
            ->withHttpOnly(true)
            ->withSecure(true) // wymagane dla HTTPS
            ->withSameSite('Strict')
            ->withPath('/');

        $response->headers->setCookie($cookie);

        $event->setData(['success' => true]);
    }
}
