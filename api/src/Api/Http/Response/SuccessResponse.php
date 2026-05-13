<?php

namespace App\Api\Http\Response;

final readonly class SuccessResponse implements \JsonSerializable
{
    public function __construct(
        private ?string $message = null,
        private mixed $data = null,
    ) {
    }

    public function jsonSerialize(): array
    {
        $response = [
            'success' => true,
        ];

        if ($this->message !== null) {
            $response['message'] = $this->message;
        }

        if ($this->data !== null) {
            $response['data'] = $this->data;
        }

        return $response;
    }
}