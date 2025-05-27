<?php

namespace App\DTO\Response;

class ErrorResponse
{
    public bool $success = false;
    public bool $error = false;

    public function __construct(bool $error, bool $success = false)
    {
        $this->success = $success;
        $this->error = $error;
    }
}