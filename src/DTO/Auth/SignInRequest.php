<?php

namespace App\DTO\Auth;

class SignInRequest extends AuthRequest
{
    public bool $rememberMe = false;
}