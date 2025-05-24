<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Symfony\Component\HttpFoundation\Request;

class CookieJwtAuthenticator extends JWTAuthenticator
{
    public function supports(Request $request): ?bool
    {
        return str_starts_with($request->getPathInfo(), '/api') &&
            $request->cookies->has('access_token');
    }

    protected function getToken(Request $request): ?string
    {
        return $request->cookies->get('access_token');
    }
}
