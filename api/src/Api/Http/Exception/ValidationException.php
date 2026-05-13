<?php

namespace App\Api\Http\Exception;

final class ValidationException extends \RuntimeException
{
    public function __construct(
        private readonly array $errors,
    ) {
        parent::__construct('Validation failed');
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}