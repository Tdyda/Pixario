<?php

namespace App\Api\Http\Response;

use JsonSerializable;

final readonly class ErrorResponse implements JsonSerializable
{
    public function __construct(
        private string $message,
        private string $code,
        private int $status,
        private array $details = [],
    ) {
    }

    public static function validation(array $details): self
    {
        return new self(
            message: 'Validation failed',
            code: 'VALIDATION_ERROR',
            status: 422,
            details: $details
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'success' => false,
            'error' => [
                'message' => $this->message,
                'code' => $this->code,
                'status' => $this->status,
                'details' => $this->details,
            ],
        ];
    }
}