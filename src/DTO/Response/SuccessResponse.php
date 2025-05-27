<?php

namespace App\DTO\Response;

class SuccessResponse
{
    public bool $success = true;
    public string $message;

    public function __construct(string $message, bool $success = true)
    {
        $this->success = $success;
        $this->message = $message;
    }
}