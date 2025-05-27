<?php

namespace App\DTO\Response;

class ErrorResponse
{
    public bool $success = false;
    public string $error;

    public function __construct(string $error, bool $success = false)
    {
        $this->success = $success;
        $this->error = $error;
    }
}